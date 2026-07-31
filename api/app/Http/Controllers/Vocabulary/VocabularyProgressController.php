<?php

namespace App\Http\Controllers\Vocabulary;

use App\Http\Controllers\Controller;
use App\Services\Gamification\ProgressService;
use App\Http\Resources\Vocabulary\VocabularyProgressResource;
use Illuminate\Http\Request;

class VocabularyProgressController extends Controller
{
    private ProgressService $service;

    public function __construct(ProgressService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $progress = $this->service->getVocabularyProgress($request->user()->id);
        
        return new VocabularyProgressResource($progress);
    }
}
