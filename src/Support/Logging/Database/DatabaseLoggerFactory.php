<?php

declare(strict_types = 1);

namespace Support\Logging\Database;


use Monolog\Logger;

final class DatabaseLoggerFactory
{
    public function __invoke(array $config): Logger
    {
        $logger = new Logger('database');

        $logger->pushHandler(new DatabaseLoggerHandler());

        return $logger;
    }

}
