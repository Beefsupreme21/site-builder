<?php

namespace App\Support\Ai;

use InvalidArgumentException;

class SkillRegistry
{
    /**
     * @return array<string, array{vendor?: string, skill?: string, path?: string, bundle?: list<string>}>
     */
    public static function entries(): array
    {
        /** @var array<string, array{vendor?: string, skill?: string, path?: string, bundle?: list<string>}> */
        return config('ai.skills.registry', []);
    }

    /**
     * @return list<array{skill: string, name: string, axis: string}>
     */
    public static function prototypePalette(): array
    {
        /** @var list<array{skill: string, name: string, axis: string}> */
        return config('ai.skills.prototype_palette', []);
    }

    /**
     * @return array{vendor?: string, skill?: string, path?: string, bundle?: list<string>}
     */
    public static function entry(string $key): array
    {
        $entry = self::entries()[$key] ?? null;

        if ($entry === null) {
            throw new InvalidArgumentException("Unknown skill key [{$key}].");
        }

        return $entry;
    }

    public static function identifier(string $key): string
    {
        $entry = self::entry($key);

        if (isset($entry['path'])) {
            return $key;
        }

        return "{$entry['vendor']}/{$entry['skill']}";
    }

    /**
     * @return list<string>
     */
    public static function bundledSkillKeys(string $key): array
    {
        return self::entry($key)['bundle'] ?? [];
    }

    /**
     * @return list<array{skill: string, name: string, description: string}>
     */
    public static function blockSkills(): array
    {
        /** @var list<array{skill: string, name: string, description: string}> */
        return config('ai.skills.block_skills', []);
    }

    /**
     * @return list<string>
     */
    public static function blockSkillKeys(): array
    {
        return array_column(self::blockSkills(), 'skill');
    }

    public static function isBlockSkill(string $key): bool
    {
        return in_array($key, self::blockSkillKeys(), true);
    }

    /**
     * @return array{skill: string, name: string, description: string}
     */
    public static function blockSkill(string $skillKey): array
    {
        foreach (self::blockSkills() as $skill) {
            if ($skill['skill'] === $skillKey) {
                return $skill;
            }
        }

        throw new InvalidArgumentException("Skill [{$skillKey}] is not available for block generation.");
    }

    /**
     * @return list<string>
     */
    public static function paletteSkillKeys(): array
    {
        return array_column(self::prototypePalette(), 'skill');
    }

    public static function isPaletteSkill(string $key): bool
    {
        return in_array($key, self::paletteSkillKeys(), true);
    }

    /**
     * @return array{skill: string, name: string, axis: string}
     */
    public static function paletteSlot(string $skillKey): array
    {
        foreach (self::prototypePalette() as $index => $slot) {
            if ($slot['skill'] === $skillKey) {
                return $slot;
            }
        }

        throw new InvalidArgumentException("Skill [{$skillKey}] is not in the prototype palette.");
    }

    /**
     * @return list<string>
     */
    public static function skillIdentifiersFor(string $key): array
    {
        $identifiers = [self::identifier($key)];

        foreach (self::bundledSkillKeys($key) as $bundledKey) {
            $identifiers[] = self::identifier($bundledKey);
        }

        return $identifiers;
    }
}
