<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChatMessageRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Ai\Messages\Message;
use Throwable;

use function Laravel\Ai\agent;

class ChatController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('chat/index', [
            'messages' => session('chat_messages', []),
        ]);
    }

    public function store(StoreChatMessageRequest $request): RedirectResponse|Response
    {
        $name = config('ai.default', 'openai');

        if (blank(config("ai.providers.{$name}.key"))) {
            return back()->withErrors([
                'content' => "Add an API key for [{$name}] in .env.",
            ]);
        }

        $prior = session('chat_messages', []);
        $content = $request->validated('content');

        $messages = array_map(
            static fn (array $m): Message => Message::tryFrom($m),
            $prior
        );

        try {
            $reply = (string) agent(
                instructions: 'You are a helpful assistant.',
                messages: $messages,
            )->prompt($content);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return back()->withErrors([
                'content' => 'Could not get a reply. Try again shortly.',
            ]);
        }

        $updated = [
            ...$prior,
            ['role' => 'user', 'content' => $content],
            ['role' => 'assistant', 'content' => $reply],
        ];

        session(['chat_messages' => $updated]);

        return Inertia::render('chat/index', [
            'messages' => $updated,
        ]);
    }

    public function clear(): RedirectResponse
    {
        session()->forget('chat_messages');

        return redirect()->route('chat.index');
    }
}
