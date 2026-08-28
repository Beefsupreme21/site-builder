<?php

namespace App\Support\Ai;

use RuntimeException;

class LocalSkill
{
    /**
     * @return array{instructions: string, identifier: string}
     */
    public static function forBlockEditor(): array
    {
        /** @var array{path: string, includes?: list<string>} $config */
        $config = config('ai.block_skill');

        $loaded = self::loadFromPath(
            $config['path'],
            $config['path'],
            $config['includes'] ?? [],
        );

        return [
            'identifier' => $loaded['identifier'],
            'instructions' => $loaded['instructions'],
        ];
    }

    /**
     * @param  list<string>  $includes
     * @return array{instructions: string, identifier: string}
     */
    private static function loadFromPath(string $relativePath, string $identifier, array $includes = []): array
    {
        $basePath = resource_path("ai/skills/{$relativePath}");

        $instructionsPath = "{$basePath}/SKILL.md";

        if (! is_file($instructionsPath)) {
            throw new RuntimeException("Skill instructions not found at [{$instructionsPath}].");
        }

        $instructions = [(string) file_get_contents($instructionsPath)];

        foreach ($includes as $include) {
            $includePath = "{$basePath}/{$include}";

            if (! is_file($includePath)) {
                throw new RuntimeException("Skill include not found at [{$includePath}].");
            }

            $instructions[] = "--- Include: {$include} ---\n\n".(string) file_get_contents($includePath);
        }

        return [
            'identifier' => $identifier,
            'instructions' => implode("\n\n", $instructions),
        ];
    }
}
