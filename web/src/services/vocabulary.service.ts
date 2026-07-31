import { apiClient as api } from '@/lib/api-client';
import { 
  VocabularyFilters, 
  PaginatedVocabularyResponse,
  VocabularyCategory,
  VocabularyWord,
  UserVocabulary,
  VocabularyProgress,
  ReviewSubmitPayload
} from '@/types/vocabulary';

// We need to define StudySession types if not in vocabulary.ts
export interface StudySession {
  id: string;
  user_id: string;
  status: 'active' | 'completed' | 'abandoned';
  started_at: string;
  completed_at: string | null;
  word_count: number;
}

export const vocabularyKeys = {
  all: ['vocabulary'] as const,
  lists: () => [...vocabularyKeys.all, 'list'] as const,
  list: (filters: VocabularyFilters) => [...vocabularyKeys.lists(), filters] as const,
  categories: () => [...vocabularyKeys.all, 'categories'] as const,
  detail: (id: string) => [...vocabularyKeys.all, 'detail', id] as const,
  reviews: () => [...vocabularyKeys.all, 'reviews'] as const,
  progress: () => [...vocabularyKeys.all, 'progress'] as const,
};

export const vocabularyService = {
  async getVocabularyList(filters: VocabularyFilters): Promise<PaginatedVocabularyResponse> {
    const params = new URLSearchParams();
    if (filters.category_id) params.append('category_id', filters.category_id);
    if (filters.difficulty_id) params.append('difficulty_id', filters.difficulty_id);
    if (filters.search) params.append('search', filters.search);
    if (filters.status) params.append('status', filters.status);
    if (filters.page) params.append('page', filters.page.toString());
    
    const response = await api.get<PaginatedVocabularyResponse>(`/api/v1/vocabulary?${params.toString()}`);
    return response.data;
  },

  async getVocabularyCategories(): Promise<{ data: VocabularyCategory[] }> {
    const response = await api.get<{ data: VocabularyCategory[] }>('/api/v1/vocabulary/categories');
    return response.data;
  },

  async getWordDetail(id: string): Promise<{ data: VocabularyWord }> {
    const response = await api.get<{ data: VocabularyWord }>(`/api/v1/vocabulary/${id}`);
    return response.data;
  },

  async markWordLearned(id: string): Promise<{ data: UserVocabulary }> {
    const response = await api.post<{ data: UserVocabulary }>(`/api/v1/vocabulary/${id}/learn`);
    return response.data;
  },

  async getReviewQueue(): Promise<{ data: VocabularyWord[] }> {
    const response = await api.get<{ data: VocabularyWord[] }>('/api/v1/vocabulary/reviews');
    return response.data;
  },

  async submitReview(wordId: string, payload: ReviewSubmitPayload & { idempotencyKey: string }): Promise<void> {
    await api.post(`/api/v1/vocabulary/${wordId}/reviews`, {
      quality_score: payload.quality_score,
      idempotency_key: payload.idempotencyKey,
    });
  },

  async startStudySession(): Promise<{ data: StudySession }> {
    const response = await api.post<{ data: StudySession }>('/api/v1/vocabulary/study-sessions');
    return response.data;
  },

  async completeStudySession(sessionId: string, wordIds: string[]): Promise<void> {
    await api.patch(`/api/v1/vocabulary/study-sessions/${sessionId}`, {
      status: 'completed',
      word_ids: wordIds,
    });
  },

  async getVocabularyProgress(): Promise<{ data: VocabularyProgress }> {
    const response = await api.get<{ data: VocabularyProgress }>('/api/v1/vocabulary/progress');
    return response.data;
  },
};
