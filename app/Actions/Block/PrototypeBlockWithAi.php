<?php

namespace App\Actions\Block;

use App\Actions\Ai\LogAiRequest;
use App\Ai\Agents\BlockPrototypeAgent;
use App\Models\Block;
use App\Models\Site;
use App\Support\Ai\AiExecutionTime;
use App\Support\Ai\ResolvedAiConfig;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Ai\Responses\StructuredAgentResponse;
use Throwable;

class PrototypeBlockWithAi
{
    /**
     * @param  array<string, mixed>  $input
     * @return array{variants: list<array{name: string, axis: string, html: string}>, meta: array{provider: string, model: string|null}}
     */
    public function handle(Site $site, Block $block, array $input): array
    {
        AiExecutionTime::extend();

        $validated = Validator::make($input, [
            'prompt' => ['required', 'string', 'min:3', 'max:5000'],
            'content' => ['required', 'string'],
        ])->validate();

        $configured = ResolvedAiConfig::defaultText();

        $agent = BlockPrototypeAgent::make(
            site: $site,
            block: $block,
            currentContent: $validated['content'],
        );

        try {
            /** @var StructuredAgentResponse $response */
            $response = $agent->prompt($validated['prompt']);

            $variants = $response['variants'] ?? [];

            if (count($variants) !== 4) {
                throw ValidationException::withMessages([
                    'prompt' => ['The AI returned '.count($variants).' variants instead of 4. Try again.'],
                ]);
            }

            $provider = $response->meta->provider ?? $configured['provider'];
            $model = $response->meta->model ?? $configured['model'];

            (new LogAiRequest)->handle([
                'site_id' => $site->id,
                'block_id' => $block->id,
                'agent' => BlockPrototypeAgent::class,
                'provider' => $provider,
                'model' => $model,
                'prompt' => $validated['prompt'],
                'reply' => json_encode($variants, JSON_THROW_ON_ERROR),
                'prompt_tokens' => $response->usage->promptTokens,
                'completion_tokens' => $response->usage->completionTokens,
                'reasoning_tokens' => $response->usage->reasoningTokens,
                'skills' => $agent->skillNames(),
                'status' => 'succeeded',
            ]);

            return [
                'variants' => $variants,
                'meta' => [
                    'provider' => $provider,
                    'model' => $model,
                ],
            ];
        } catch (Throwable $exception) {
            (new LogAiRequest)->handle([
                'site_id' => $site->id,
                'block_id' => $block->id,
                'agent' => BlockPrototypeAgent::class,
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
}
