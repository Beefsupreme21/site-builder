<?php

namespace App\Enums;

enum BlockDirection: string
{
    case Up = 'up';
    case Down = 'down';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
