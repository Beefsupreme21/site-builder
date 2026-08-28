<?php

namespace App\Actions\Block;

use App\Actions\Ai\LogAiRequest;
use App\Ai\Agents\BlockHtmlAgent;
use App\Models\Block;
use App\Models\Site;
use App\Support\Ai\AiExecutionTime;
use App\Support\Ai\ResolvedAiConfig;
use Illuminate\Support\Facades\Validator;
use Laravel\Ai\Responses\StructuredAgentResponse;
use RuntimeException;
use Throwable;

class GenerateBlockHtmlWithAi
{
    /**
     * @param  array<string, mixed>  $input
     * @return array{html: string, meta: array{provider: string, model: string|null}}
     */
    public function handle(Site $site, Block $block, array $input): array
    {
        AiExecutionTime::extend();

        $validated = Validator::make($input, [
            'prompt' => ['required', 'string', 'min:3', 'max:5000'],
            'content' => ['required', 'string'],
        ])->validate();

        $configured = ResolvedAiConfig::defaultText();

        $agent = BlockHtmlAgent::make(
            site: $site,
            block: $block,
            currentContent: $validated['content'],
        );

        try {
            $result = $this->promptWithRetry($agent, $validated['prompt'], $configured);

            (new LogAiRequest)->handle([
                'site_id' => $site->id,
                'block_id' => $block->id,
                'agent' => BlockHtmlAgent::class,
                'provider' => $result['provider'] ?? $configured['provider'],
                'model' => $result['model'] ?? $configured['model'],
                'prompt' => $validated['prompt'],
                'reply' => json_encode(['html' => $result['html']], JSON_THROW_ON_ERROR),
                'prompt_tokens' => $result['prompt_tokens'],
                'completion_tokens' => $result['completion_tokens'],
                'reasoning_tokens' => $result['reasoning_tokens'],
                'skills' => [$agent->skillIdentifier()],
                'status' => 'succeeded',
            ]);

            return [
                'html' => $result['html'],
                'meta' => [
                    'provider' => $result['provider'] ?? $configured['provider'],
                    'model' => $result['model'] ?? $configured['model'],
                ],
            ];
        } catch (Throwable $exception) {
            (new LogAiRequest)->handle([
                'site_id' => $site->id,
                'block_id' => $block->id,
                'agent' => BlockHtmlAgent::class,
                'provider' => $configured['provider'],
                'model' => $configured['model'],
                'prompt' => $validated['prompt'],
                'reply' => null,
                'skills' => [$agent->skillIdentifier()],
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    /**
     * @param  array{provider: string, model: string|null}  $configured
     * @return array{
     *     html: string,
     *     provider: string|null,
     *     model: string|null,
     *     prompt_tokens: int,
     *     completion_tokens: int,
     *     reasoning_tokens: int,
     * }
     */
    private function promptWithRetry(BlockHtmlAgent $agent, string $prompt, array $configured): array
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
                    throw new RuntimeException('Model returned empty HTML.');
                }

                return [
                    'html' => $html,
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
            "Block HTML generation failed: {$lastException?->getMessage()}",
            0,
            $lastException,
        );
    }
}
