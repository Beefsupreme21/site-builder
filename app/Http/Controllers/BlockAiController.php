<?php

namespace App\Http\Controllers;

use App\Actions\Block\PrototypeBlockWithAi;
use App\Models\Block;
use App\Models\Layout;
use App\Models\SitePage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class BlockAiController extends Controller
{
    public function prototypePage(SitePage $page, Block $block, Request $request): JsonResponse
    {
        $page->loadMissing('site');

        abort_if($block->isSlot(), 404);

        return $this->runPrototype($page->site, $block, $request);
    }

    public function prototypeLayout(Layout $layout, Block $block, Request $request): JsonResponse
    {
        $layout->loadMissing('site');

        abort_if($block->isSlot(), 404);

        return $this->runPrototype($layout->site, $block, $request);
    }

    private function runPrototype($site, Block $block, Request $request): JsonResponse
    {
        try {
            return response()->json(
                (new PrototypeBlockWithAi)->handle($site, $block, $request->all()),
            );
        } catch (ValidationException $exception) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $exception->errors(),
            ], 422);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'Prototype generation failed. Check ai_request_logs for details.',
                'error' => config('app.debug') ? $exception->getMessage() : null,
            ], 500);
        }
    }
}
