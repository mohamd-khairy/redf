<?php

namespace App\Enums;

enum TaskTypeEnum: string
{
    public const TYPE1 = 'type1';
    public const TYPE2 = 'type2';
    public const TYPE3 = 'type3';

    public static function all(): array
    {
        return [
            self::TYPE1,
            self::TYPE2,
            self::TYPE3,
        ];
    }
}
