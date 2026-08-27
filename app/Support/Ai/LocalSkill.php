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
        return self::loadFromPath("{$vendor}/{$skill}", "{$vendor}/{$skill}");
    }

    /**
     * @return array{instructions: string, picker: string, identifier: string}
     */
    public static function loadByKey(string $key): array
    {
        $entry = SkillRegistry::entry($key);

        if (isset($entry['path'])) {
            return self::loadFromPath($entry['path'], $key);
        }

        return self::loadFromPath("{$entry['vendor']}/{$entry['skill']}", SkillRegistry::identifier($key));
    }

    /**
     * Load a skill and any bundled reference skills (e.g. animation-vocabulary with animate).
     *
     * @return array{instructions: string, picker: string, identifier: string, bundled_identifiers: list<string>}
     */
    public static function loadWithBundles(string $key): array
    {
        $primary = self::loadByKey($key);
        $instructions = [$primary['instructions']];
        $bundledIdentifiers = [];

        foreach (SkillRegistry::bundledSkillKeys($key) as $bundledKey) {
            $bundled = self::loadByKey($bundledKey);
            $bundledIdentifiers[] = $bundled['identifier'];
            $instructions[] = "--- Reference: {$bundled['identifier']} ---\n\n{$bundled['instructions']}";
        }

        return [
            'identifier' => $primary['identifier'],
            'instructions' => implode("\n\n", $instructions),
            'picker' => $primary['picker'],
            'bundled_identifiers' => $bundledIdentifiers,
        ];
    }

    /**
     * @return array{instructions: string, picker: string, identifier: string}
     */
    private static function loadFromPath(string $relativePath, string $identifier): array
    {
        $basePath = resource_path("ai/skills/{$relativePath}");

        $instructionsPath = "{$basePath}/SKILL.md";
        $pickerPath = "{$basePath}/PICKER.md";

        if (! is_file($instructionsPath)) {
            throw new RuntimeException("Skill instructions not found at [{$instructionsPath}].");
        }

        return [
            'identifier' => $identifier,
            'instructions' => (string) file_get_contents($instructionsPath),
            'picker' => is_file($pickerPath) ? (string) file_get_contents($pickerPath) : '',
        ];
    }
}
