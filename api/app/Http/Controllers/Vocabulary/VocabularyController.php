<?php

namespace App\Http\Controllers\Vocabulary;

use App\Http\Controllers\Controller;
use App\Services\Vocabulary\VocabularyService;
use App\Http\Resources\Vocabulary\VocabularyWordResource;
use App\Http\Resources\Vocabulary\VocabularyCategoryResource;
use Illuminate\Http\Request;

class VocabularyController extends Controller
{
    private VocabularyService $service;

    public function __construct(VocabularyService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['category_id', 'difficulty_id', 'search', 'status']);
        $words = $this->service->getPaginatedList($request->user()->id, $filters);
        
        return VocabularyWordResource::collection($words);
    }

    public function categories()
    {
        $categories = $this->service->getCategories();
        return VocabularyCategoryResource::collection($categories);
    }

    public function show(Request $request, string $id)
    {
        $word = $this->service->getDetail($id, $request->user()->id);
        
        if (!$word) {
            return response()->json(['message' => 'Not found'], 404);
        }
        
        return new VocabularyWordResource($word);
    }
}
