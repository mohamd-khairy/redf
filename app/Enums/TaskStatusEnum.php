<?php

namespace App\Enums;

enum TaskStatusEnum: string
{
    public const STATUS1 = 'status1';
    public const STATUS2 = 'status2';
    public const STATUS3 = 'status3';

    public static function all(): array
    {
        return [
            self::STATUS1,
            self::STATUSE2,
            self::STATUS3,
        ];
    }
}
