<?php

namespace App\Http\Controllers\Vocabulary;

use App\Http\Controllers\Controller;
use App\Services\Vocabulary\StudyService;
use App\Http\Requests\Vocabulary\CompleteStudySessionRequest;
use App\Http\Resources\Vocabulary\UserVocabularyResource;
use App\Http\Resources\Vocabulary\StudySessionResource;
use Illuminate\Http\Request;

class StudyController extends Controller
{
    private StudyService $service;

    public function __construct(StudyService $service)
    {
        $this->service = $service;
    }

    public function learn(Request $request, string $id)
    {
        $userVocab = $this->service->markLearned($id, $request->user()->id);
        return new UserVocabularyResource($userVocab);
    }

    public function startSession(Request $request)
    {
        $session = $this->service->startSession($request->user()->id);
        return (new StudySessionResource($session))
            ->response()
            ->setStatusCode(201);
    }

    public function completeSession(CompleteStudySessionRequest $request, string $id)
    {
        $this->service->completeSession($id, $request->user()->id, $request->validated('word_ids'));
        return response()->json(['message' => 'Session completed successfully']);
    }
}
