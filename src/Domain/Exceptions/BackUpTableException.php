<?php

declare(strict_types = 1);

namespace Domain\Exceptions;


final class BackUpTableException extends ProjectException
{

    public static function tableNameNotValid(): self
    {
        $message = 'Имя таблицы не указано';
        return new self(
            $message,
            [
                'code'         => '404',
                'exception'    => __CLASS__,
            ],
            404
        );
    }

    public static function importFileNotFound($filePath): self
    {
        $message = "Файл для импорта ($filePath) не найден.";
        return new self(
            $message,
            [
                'code'         => '404',
                'exception'    => __CLASS__,
            ],
            404
        );
    }

    public static function tableNotFound($table): self
    {
        $message = "Таблица ($table) не найдена.";
        return new self(
            $message,
            [
                'code'         => '404',
                'exception'    => __CLASS__,
            ],
            404
        );
    }

    public static function tableEmpty($table): self
    {
        $message = "Таблица ($table) пуста.";
        return new self(
            $message,
            [
                'code'         => '404',
                'exception'    => __CLASS__,
            ],
            404
        );
    }

}
