<?php

declare(strict_types = 1);

namespace Support\Logging\Database;


use Domain\Site\Models\Log;
use Illuminate\Support\Facades\DB;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

final class DatabaseLoggerHandler extends AbstractProcessingHandler
{
    protected function write(LogRecord $record): void
    {
        DB::table('logs')->insert([
            'level'      => $record->level->name,
            'message'    => $record->message,
            'context'    => json_encode($record->context, JSON_UNESCAPED_UNICODE),
            'created_at' => now(),
        ]);
    }
}
