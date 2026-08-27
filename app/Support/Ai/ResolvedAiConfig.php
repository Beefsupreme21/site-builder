<?php

namespace App\Support\Ai;

use InvalidArgumentException;

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

    /**
     * @return list<array{model: string, name: string, description: string}>
     */
    public static function blockModels(): array
    {
        /** @var list<array{model: string, name: string, description: string}> */
        return config('ai.block_models', []);
    }

    /**
     * @return list<string>
     */
    public static function blockModelIds(): array
    {
        return array_column(self::blockModels(), 'model');
    }

    public static function isBlockModel(string $model): bool
    {
        return in_array($model, self::blockModelIds(), true);
    }

    /**
     * @return array{provider: string, model: string|null}
     */
    public static function resolveText(?string $model = null): array
    {
        $configured = self::defaultText();

        if ($model === null) {
            return $configured;
        }

        if (! self::isBlockModel($model)) {
            throw new InvalidArgumentException("Model [{$model}] is not available for block generation.");
        }

        return [
            'provider' => $configured['provider'],
            'model' => $model,
        ];
    }
}
