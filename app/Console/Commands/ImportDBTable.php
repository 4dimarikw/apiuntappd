<?php

namespace App\Console\Commands;

use Domain\Entity\Actions\BackUpTable;
use Illuminate\Console\Command;
use Throwable;

class ImportDBTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:import-db-table {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     * @throws Throwable
     */
    public function handle(BackUpTable $backUpTable): int
    {
        $table = $this->argument('table');

        try {
            if (!$this->confirm("Вы действительно хотите выполнить импорт таблицы '{$table}'? [y/n]")) {
                $this->warn('импорт таблицы отменен');
                return self::FAILURE;
            }

            $backUpTable->import($table);

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}
