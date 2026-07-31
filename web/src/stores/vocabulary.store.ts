import { create } from 'zustand';

interface VocabularyState {
  activeStudyCardIndex: number;
  studySessionWordIds: string[];
  activeReviewCardIndex: number;
  reviewQualityScores: Record<string, number>;
  
  // Actions
  setStudySession: (wordIds: string[]) => void;
  nextStudyCard: () => void;
  resetStudySession: () => void;
  
  initReviewSession: () => void;
  setReviewScore: (wordId: string, score: number) => void;
  nextReviewCard: () => void;
  resetReviewSession: () => void;
}

export const useVocabularyStore = create<VocabularyState>((set) => ({
  activeStudyCardIndex: 0,
  studySessionWordIds: [],
  activeReviewCardIndex: 0,
  reviewQualityScores: {},

  setStudySession: (wordIds: string[]) => set({ 
    studySessionWordIds: wordIds,
    activeStudyCardIndex: 0 
  }),
  
  nextStudyCard: () => set((state) => ({ 
    activeStudyCardIndex: state.activeStudyCardIndex + 1 
  })),
  
  resetStudySession: () => set({ 
    activeStudyCardIndex: 0, 
    studySessionWordIds: [] 
  }),

  initReviewSession: () => set({
    activeReviewCardIndex: 0,
    reviewQualityScores: {},
    // We can also store the wordIds if needed for review, but the query holds them.
    // However, it's safe to store IDs if we want strict queue management.
  }),

  setReviewScore: (wordId: string, score: number) => set((state) => ({
    reviewQualityScores: {
      ...state.reviewQualityScores,
      [wordId]: score,
    }
  })),

  nextReviewCard: () => set((state) => ({
    activeReviewCardIndex: state.activeReviewCardIndex + 1
  })),

  resetReviewSession: () => set({
    activeReviewCardIndex: 0,
    reviewQualityScores: {},
  }),
}));
