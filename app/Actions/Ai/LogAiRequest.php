<?php

namespace App\Actions\Ai;

use App\Models\AiRequestLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LogAiRequest
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function handle(array $input): AiRequestLog
    {
        $validated = Validator::make($input, [
            'site_id' => ['required', 'integer', 'exists:sites,id'],
            'block_id' => ['nullable', 'integer', 'exists:blocks,id'],
            'agent' => ['required', 'string', 'max:255'],
            'provider' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'conversation_id' => ['nullable', 'string', 'size:36'],
            'prompt' => ['required', 'string'],
            'reply' => ['nullable', 'string'],
            'prompt_tokens' => ['nullable', 'integer', 'min:0'],
            'completion_tokens' => ['nullable', 'integer', 'min:0'],
            'reasoning_tokens' => ['nullable', 'integer', 'min:0'],
            'skills' => ['nullable', 'array'],
            'skills.*' => ['string', 'max:255'],
            'status' => ['nullable', 'string', 'in:succeeded,failed'],
            'error_message' => ['nullable', 'string'],
        ])->validate();

        return DB::transaction(function () use ($validated): AiRequestLog {
            return AiRequestLog::query()->create($validated);
        });
    }
}
