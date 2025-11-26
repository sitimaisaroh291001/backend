<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\RecommendationService;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    protected $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    public function getRecommendations($userId)
    {
        $recommendations = $this->recommendationService->getRecommendations($userId);

        return response()->json([
            'status' => 'success',
            'data' => $recommendations
        ]);
    }
}
