<?php

namespace App\Http\Controllers\Vocabulary;

use App\Http\Controllers\Controller;
use App\Services\Vocabulary\ReviewService;
use App\Http\Requests\Vocabulary\SubmitReviewRequest;
use App\DTOs\Vocabulary\ReviewRequestDTO;
use App\Http\Resources\Vocabulary\UserVocabularyResource;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    private ReviewService $service;

    public function __construct(ReviewService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $limit = $request->query('limit', 50);
        $queue = $this->service->getReviewQueue($request->user()->id, $limit);
        
        return UserVocabularyResource::collection($queue);
    }

    public function store(SubmitReviewRequest $request, string $id)
    {
        $dto = new ReviewRequestDTO(
            userId: $request->user()->id,
            userVocabularyId: $id,
            qualityScore: $request->validated('quality_score'),
            idempotencyKey: $request->validated('idempotency_key')
        );

        $this->service->submitReview($dto);
        
        return response()->json(['message' => 'Review submitted successfully']);
    }
}
