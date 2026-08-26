<?php

namespace App\Support\Ai;

class AiExecutionTime
{
    public static function extend(?int $seconds = null): void
    {
        set_time_limit($seconds ?? (int) config('ai.execution_time_limit', 180));
    }
}
