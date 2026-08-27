<?php

namespace App\Http\Controllers;

use App\Actions\Block\GenerateBlockVariantWithAi;
use App\Models\Block;
use App\Models\Layout;
use App\Models\SitePage;
use App\Support\Ai\SkillRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class BlockVariantAiController extends Controller
{
    public function storePage(SitePage $page, Block $block, string $skill, Request $request): JsonResponse
    {
        $page->loadMissing('site');

        abort_if($block->isSlot(), 404);
        abort_unless(SkillRegistry::isBlockSkill($skill), 404);

        return $this->runVariant($page->site, $block, $skill, $request);
    }

    public function storeLayout(Layout $layout, Block $block, string $skill, Request $request): JsonResponse
    {
        $layout->loadMissing('site');

        abort_if($block->isSlot(), 404);
        abort_unless(SkillRegistry::isBlockSkill($skill), 404);

        return $this->runVariant($layout->site, $block, $skill, $request);
    }

    private function runVariant($site, Block $block, string $skill, Request $request): JsonResponse
    {
        try {
            return response()->json(
                (new GenerateBlockVariantWithAi)->handle($site, $block, $skill, $request->all()),
            );
        } catch (ValidationException $exception) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $exception->errors(),
            ], 422);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'Variant generation failed. Check ai_request_logs for details.',
                'error' => config('app.debug') ? $exception->getMessage() : null,
            ], 500);
        }
    }
}
