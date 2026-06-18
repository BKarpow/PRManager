<?php
// app/Http/Controllers/Api/ExpiryController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DeepSeekVisionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ExpiryController extends Controller
{
    protected DeepSeekVisionService $visionService;

    public function __construct(DeepSeekVisionService $visionService)
    {
        $this->visionService = $visionService;
    }

    public function analyze(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $result = $this->visionService->extractExpiryDate($request->file('image'));

        return response()->json([
            'success' => $result->success(),
            'data' => $result->toArray()
        ]);
    }
}
