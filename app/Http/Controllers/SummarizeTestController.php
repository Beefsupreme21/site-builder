<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Response;

class SummarizeTestController extends Controller
{
    public function index(Request $request): JsonResponse|Response
    {
        if ($request->wantsJson()) {
            $article = $request->string('article')->toString() ?: self::defaultArticle();

            return response()->json([
                'article' => $article,
                'summary' => Str::of($article)->summarize(),
            ]);
        }

        return inertia('test/summarize', [
            'defaultArticle' => self::defaultArticle(),
            'article' => null,
            'summary' => null,
            'provider' => config('ai.default'),
            'model' => self::cheapestTextModel(),
        ]);
    }

    public function store(Request $request): Response
    {
        $validated = $request->validate([
            'article' => ['required', 'string', 'min:10'],
        ]);

        $summary = Str::of($validated['article'])->summarize();

        return inertia('test/summarize', [
            'defaultArticle' => self::defaultArticle(),
            'article' => $validated['article'],
            'summary' => $summary,
            'provider' => config('ai.default'),
            'model' => self::cheapestTextModel(),
        ]);
    }

    private static function cheapestTextModel(): ?string
    {
        $provider = config('ai.default');

        return config("ai.providers.{$provider}.models.text.cheapest");
    }

    private static function defaultArticle(): string
    {
        return <<<'TEXT'
            Laravel is a web application framework with expressive, elegant syntax.
            We believe development must be an enjoyable and creative experience
            to be truly fulfilling. Laravel attempts to take the pain out of
            development by easing common tasks used in most web projects.
            TEXT;
    }
}
