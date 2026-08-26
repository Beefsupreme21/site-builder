<?php

namespace App\Support\Ai;

class ResolvedAiConfig
{
    /**
     * @return array{provider: string, model: string|null}
     */
    public static function defaultText(): array
    {
        $provider = (string) config('ai.default');

        return [
            'provider' => $provider,
            'model' => config("ai.providers.{$provider}.models.text.default"),
        ];
    }
}
