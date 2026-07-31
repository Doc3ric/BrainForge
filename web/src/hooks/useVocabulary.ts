import { useQuery, useMutation, useQueryClient, useInfiniteQuery } from '@tanstack/react-query';
import { vocabularyService, vocabularyKeys } from '@/services/vocabulary.service';
import { gamificationKeys } from '@/services/gamification.service';
import { VocabularyFilters } from '@/types/vocabulary';


export const useVocabularyList = (filters: VocabularyFilters) => {
  return useInfiniteQuery({
    queryKey: vocabularyKeys.list(filters),
    queryFn: ({ pageParam = 1 }) => 
      vocabularyService.getVocabularyList({ ...filters, page: pageParam as number }),
    getNextPageParam: (lastPage) => {
      if (lastPage.meta.current_page < lastPage.meta.last_page) {
        return lastPage.meta.current_page + 1;
      }
      return undefined;
    },
    initialPageParam: 1,
  });
};

export const useVocabularyCategories = () => {
  return useQuery({
    queryKey: vocabularyKeys.categories(),
    queryFn: vocabularyService.getVocabularyCategories,
  });
};

export const useWordDetail = (id: string) => {
  return useQuery({
    queryKey: vocabularyKeys.detail(id),
    queryFn: () => vocabularyService.getWordDetail(id),
    enabled: !!id,
  });
};

export const useMarkLearned = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (id: string) => vocabularyService.markWordLearned(id),
    onSuccess: (_, id) => {
      queryClient.invalidateQueries({ queryKey: vocabularyKeys.detail(id) });
      queryClient.invalidateQueries({ queryKey: vocabularyKeys.reviews() });
      queryClient.invalidateQueries({ queryKey: vocabularyKeys.progress() });
      queryClient.invalidateQueries({ queryKey: gamificationKeys.all });
    },
  });
};

export const useReviewQueue = () => {
  return useQuery({
    queryKey: vocabularyKeys.reviews(),
    queryFn: vocabularyService.getReviewQueue,
  });
};

export const useSubmitReview = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: ({ wordId, qualityScore }: { wordId: string; qualityScore: number }) => 
      vocabularyService.submitReview(wordId, { 
        quality_score: qualityScore, 
        idempotencyKey: crypto.randomUUID()
      }),
    onSuccess: (_, { wordId, qualityScore }) => {
      queryClient.invalidateQueries({ queryKey: vocabularyKeys.reviews() });
      queryClient.invalidateQueries({ queryKey: vocabularyKeys.detail(wordId) });
      
      if (qualityScore >= 3) {
        queryClient.invalidateQueries({ queryKey: vocabularyKeys.progress() });
        queryClient.invalidateQueries({ queryKey: gamificationKeys.all });
      }
    },
  });
};

export const useStartStudySession = () => {
  return useMutation({
    mutationFn: vocabularyService.startStudySession,
  });
};

export const useCompleteStudySession = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: ({ sessionId, wordIds }: { sessionId: string; wordIds: string[] }) => 
      vocabularyService.completeStudySession(sessionId, wordIds),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: gamificationKeys.all });
    },
  });
};

export const useVocabularyProgress = () => {
  return useQuery({
    queryKey: vocabularyKeys.progress(),
    queryFn: vocabularyService.getVocabularyProgress,
  });
};
