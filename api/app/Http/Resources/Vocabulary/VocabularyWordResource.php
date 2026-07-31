<?php

namespace App\Http\Resources\Vocabulary;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VocabularyWordResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'word' => $this->word,
            'part_of_speech' => $this->part_of_speech,
            'definition' => $this->definition,
            'category' => new VocabularyCategoryResource($this->whenLoaded('category')),
            'difficulty' => [
                'id' => $this->difficulty?->id,
                'display_name' => $this->difficulty?->display_name,
            ],
            'examples' => $this->whenLoaded('examples', function () {
                return $this->examples->pluck('example_sentence');
            }),
            'user_state' => $this->userVocabulary && $this->userVocabulary->isNotEmpty()
                ? new UserVocabularyResource($this->userVocabulary->first())
                : null,
        ];
    }
}
