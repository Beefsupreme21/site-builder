<?php

namespace App\Support\Ai;

use RuntimeException;

class LocalSkill
{
    /**
     * @return array{instructions: string, picker: string, identifier: string}
     */
    public static function load(string $vendor, string $skill): array
    {
        $basePath = resource_path("ai/skills/{$vendor}/{$skill}");

        $instructionsPath = "{$basePath}/SKILL.md";
        $pickerPath = "{$basePath}/PICKER.md";

        if (! is_file($instructionsPath)) {
            throw new RuntimeException("Skill instructions not found at [{$instructionsPath}].");
        }

        return [
            'identifier' => "{$vendor}/{$skill}",
            'instructions' => (string) file_get_contents($instructionsPath),
            'picker' => is_file($pickerPath) ? (string) file_get_contents($pickerPath) : '',
        ];
    }
}
