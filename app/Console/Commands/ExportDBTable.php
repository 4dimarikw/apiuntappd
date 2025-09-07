<?php

namespace App\Console\Commands;

use Domain\Entity\Actions\BackUpTable;
use Illuminate\Console\Command;
use Throwable;

class ExportDBTable extends Command
{

    protected $signature = 'project:export-db-table {table}';

    protected $description = 'Command description';

    public function handle(BackUpTable $backUpTable): int
    {
        $table = $this->argument('table');

        try {
            $backUpTable->export($table);

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}
