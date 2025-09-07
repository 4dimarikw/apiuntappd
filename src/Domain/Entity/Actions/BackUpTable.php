<?php

declare(strict_types=1);

namespace Domain\Entity\Actions;


use Domain\Exceptions\BackUpTableException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

final class BackUpTable
{

    private string $basePath = 'exports';

    private array $validTables = ['beers', 'brewery'];

    /**
     * @throws Throwable
     */
    public function import($table = null): void
    {
        if (blank($table)) {
            throw BackUpTableException::tableNameNotValid();
        }

        $filePath = storage_path("app/private/$this->basePath/$table.json");


        if (blank(!file_exists($filePath))) {
            throw BackUpTableException::importFileNotFound($filePath);
        }

        $data_array = json_decode(file_get_contents($filePath), true);

        foreach ($data_array as $data) {
            DB::table($table)->updateOrInsert(['id' => $data['id']], $data);
        }
    }

    /**
     * @throws Throwable
     */
    public function export($table = null): void
    {
        $this->tableValidate($table);

        $data = DB::table($table)->get();

        if ($data->isEmpty()) {
            throw BackUpTableException::tableEmpty($table);
        }

        if (!Storage::disk('local')->exists($this->basePath)) {
            Storage::disk('local')->makeDirectory($this->basePath);
        }

        $filePath = storage_path("app/private/$this->basePath/$table.json");

        file_put_contents($filePath, $data->toJson());
    }

    /**
     * @throws BackUpTableException
     */
    public function tableValidate($table): void
    {
        if (Arr::exists($this->validTables, $table)) {
            throw BackUpTableException::tableNotFound($table);
        }
    }

}
