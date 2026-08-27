<?php

namespace App\Actions\Block;

use App\Actions\Ai\LogAiRequest;
use App\Ai\Agents\BlockVariantAgent;
use App\Models\Block;
use App\Models\Site;
use App\Support\Ai\AiExecutionTime;
use App\Support\Ai\ResolvedAiConfig;
use App\Support\Ai\SkillRegistry;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Ai\Responses\StructuredAgentResponse;
use RuntimeException;
use Throwable;

class GenerateBlockVariantWithAi
{
    /**
     * @param  array<string, mixed>  $input
     * @return array{variant: array{name: string, axis: string, skill: string, html: string}, meta: array{provider: string, model: string|null}}
     */
    public function handle(Site $site, Block $block, string $skillKey, array $input): array
    {
        AiExecutionTime::extend();

        $validated = Validator::make($input, [
            'prompt' => ['required', 'string', 'min:3', 'max:5000'],
            'content' => ['required', 'string'],
            'model' => ['nullable', 'string', Rule::in(ResolvedAiConfig::blockModelIds())],
        ])->validate();

        if (! SkillRegistry::isBlockSkill($skillKey)) {
            throw ValidationException::withMessages([
                'skill' => ["Skill [{$skillKey}] is not available."],
            ]);
        }

        $skill = SkillRegistry::blockSkill($skillKey);
        $configured = ResolvedAiConfig::resolveText($validated['model'] ?? null);

        $agent = BlockVariantAgent::make(
            site: $site,
            block: $block,
            currentContent: $validated['content'],
            skillKey: $skillKey,
            variantName: $skill['name'],
            variantAxis: $skill['description'],
            variantIndex: 0,
            variantCount: 1,
        );

        try {
            $result = $this->promptWithRetry($agent, $validated['prompt'], $skill, $configured);

            (new LogAiRequest)->handle([
                'site_id' => $site->id,
                'block_id' => $block->id,
                'agent' => BlockVariantAgent::class,
                'provider' => $result['provider'] ?? $configured['provider'],
                'model' => $result['model'] ?? $configured['model'],
                'prompt' => $validated['prompt'],
                'reply' => json_encode($result['variant'], JSON_THROW_ON_ERROR),
                'prompt_tokens' => $result['prompt_tokens'],
                'completion_tokens' => $result['completion_tokens'],
                'reasoning_tokens' => $result['reasoning_tokens'],
                'skills' => $agent->skillNames(),
                'status' => 'succeeded',
            ]);

            return [
                'variant' => $result['variant'],
                'meta' => [
                    'provider' => $result['provider'] ?? $configured['provider'],
                    'model' => $result['model'] ?? $configured['model'],
                ],
            ];
        } catch (Throwable $exception) {
            (new LogAiRequest)->handle([
                'site_id' => $site->id,
                'block_id' => $block->id,
                'agent' => BlockVariantAgent::class,
                'provider' => $configured['provider'],
                'model' => $configured['model'],
                'prompt' => $validated['prompt'],
                'reply' => null,
                'skills' => $agent->skillNames(),
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    /**
     * @param  array{skill: string, name: string, description: string}  $skill
     * @param  array{provider: string, model: string|null}  $configured
     * @return array{
     *     variant: array{name: string, axis: string, skill: string, html: string},
     *     provider: string|null,
     *     model: string|null,
     *     prompt_tokens: int,
     *     completion_tokens: int,
     *     reasoning_tokens: int,
     * }
     */
    private function promptWithRetry(BlockVariantAgent $agent, string $prompt, array $skill, array $configured): array
    {
        $lastException = null;

        for ($attempt = 1; $attempt <= 2; $attempt++) {
            try {
                /** @var StructuredAgentResponse $response */
                $response = $agent->prompt(
                    $prompt,
                    provider: $configured['provider'],
                    model: $configured['model'],
                    timeout: 120,
                );

                $html = trim($response['html'] ?? '');

                if ($html === '') {
                    throw new RuntimeException("Skill [{$skill['skill']}] returned empty HTML.");
                }

                return [
                    'variant' => [
                        'name' => $skill['name'],
                        'axis' => $skill['description'],
                        'skill' => $skill['skill'],
                        'html' => $html,
                    ],
                    'provider' => $response->meta->provider ?? null,
                    'model' => $response->meta->model ?? null,
                    'prompt_tokens' => $response->usage->promptTokens,
                    'completion_tokens' => $response->usage->completionTokens,
                    'reasoning_tokens' => $response->usage->reasoningTokens,
                ];
            } catch (Throwable $exception) {
                $lastException = $exception;
            }
        }

        throw new RuntimeException(
            "Failed using skill [{$skill['skill']}]: {$lastException?->getMessage()}",
            0,
            $lastException,
        );
    }
}
