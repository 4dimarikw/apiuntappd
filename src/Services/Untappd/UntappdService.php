<?php

namespace Services\Untappd;


use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Services\Untappd\Exceptions\UntappdServiceException;
use Throwable;

class UntappdService
{
    protected string $apiUrl;
    protected int $rateLimit;
    protected ?string $accessToken;

    /**
     * @param int|null $rateLimit
     * @throws UntappdServiceException
     */
    public function __construct(?int $rateLimit = null)
    {
        $this->apiUrl = config('services.untappd.api_url');
        $this->rateLimit = $rateLimit ?? config('services.untappd.rate_limit', 100);
        $this->accessToken = config('services.untappd.access_token');

        if (!$this->accessToken) {
            throw UntappdServiceException::invalidToken();
        }
    }

    /**
     * Получение информации о пиве по ID.
     *
     * @param int $beerId
     * @return PromiseInterface|Response
     * @throws UntappdServiceException
     */
    public function getBeerInfo(int $beerId): PromiseInterface|Response
    {
        return $this->makeRequest("beer/info/{$beerId}");
    }

    /**
     * Получение информации о пивоварне по ID.
     *
     * @param int $breweryId
     * @return PromiseInterface|Response
     * @throws UntappdServiceException
     */
    public function getBreweryInfo(int $breweryId): PromiseInterface|Response
    {
        return $this->makeRequest("brewery/info/{$breweryId}");
    }

    /**
     * Выполнение HTTP-запроса к Untappd API.
     *
     * @param string $endpoint
     * @param array $query
     * @return PromiseInterface|Response
     * @throws UntappdServiceException
     */
    protected function makeRequest(string $endpoint, array $query = []): PromiseInterface|Response
    {
        $query = array_merge($query, [
            'access_token' => $this->accessToken,
            'compact' => 'true',
        ]);

        try {
            $response = Http::timeout(30)
                ->retry(3, 100)
                ->withHeaders(['User-Agent' => 'LaravelUntappd/1.0'])
                ->get("{$this->apiUrl}/{$endpoint}", $query);

            // Проверка заголовков лимита
            $remaining = (int)$response->header('X-Ratelimit-Remaining');
            $limit = (int)$response->header('X-Ratelimit-Limit');

            if ($remaining <= 0 || $response->status() === 429) {
                throw UntappdServiceException::rateLimitExceeded($response->json());
            }

            if ($response->failed()) {
                throw UntappdServiceException::apiError($response->json('meta.error_detail', 'Unknown error'), $response->json());
            }

//            $data = $response->json('response');
//
//            if (!$data) {
//                throw UntappdServiceException::invalidResponse("{$this->apiUrl}/{$endpoint}");
//            }

            return $response;
        } catch (Throwable $e) {
            if ($e instanceof RequestException) {
                $context = $e->response->json();
            } else {
                $context = [];
            }

            throw UntappdServiceException::apiError($e->getMessage(), $context);
        }
    }
}
