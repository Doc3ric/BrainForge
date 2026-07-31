'use client';

import { useEffect, useState } from 'react';
import { useReviewQueue } from '@/hooks/useVocabulary';
import { ReviewSession } from '@/components/features/vocabulary/ReviewSession';
import { VocabularyEmptyState } from '@/components/features/vocabulary/VocabularyEmptyState';
import { VocabularyErrorState } from '@/components/features/vocabulary/VocabularyErrorState';
import { useAuth } from '@/hooks/useAuth';
import { useRouter } from 'next/navigation';
import { useVocabularyStore } from '@/stores/vocabulary.store';

export default function ReviewPage() {
  const { user, isUserLoading: authLoading } = useAuth();
  const router = useRouter();
  
  const [started, setStarted] = useState(false);
  const { data, isLoading, isError, error } = useReviewQueue();
  const { initReviewSession, resetReviewSession } = useVocabularyStore();

  useEffect(() => {
    return () => resetReviewSession();
  }, [resetReviewSession]);

  if (authLoading) return null;
  if (!user) {
    router.push('/login');
    return null;
  }

  const words = data?.data || [];

  const handleStart = () => {
    if (words.length === 0) return;
    initReviewSession();
    setStarted(true);
  };

  return (
    <div className="container mx-auto px-4 py-8 min-h-screen">
      {!started ? (
        <div className="max-w-xl mx-auto text-center mt-20">
          <div className="w-24 h-24 bg-emerald-900/50 text-emerald-400 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg className="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          </div>
          <h1 className="text-4xl font-bold text-white mb-4">Review Time</h1>
          <p className="text-xl text-slate-400 mb-8">
            Keep your memory sharp. You have {words.length} words due for review.
          </p>
          
          {isLoading ? (
            <div className="h-12 w-32 bg-slate-800 animate-pulse rounded-lg mx-auto"></div>
          ) : isError ? (
            <VocabularyErrorState error={error} />
          ) : words.length === 0 ? (
            <VocabularyEmptyState message="All caught up! No reviews due right now." />
          ) : (
            <button
              onClick={handleStart}
              className="px-10 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xl transition-all hover:scale-105 active:scale-95 shadow-xl shadow-emerald-900/20"
            >
              Start Review
            </button>
          )}
        </div>
      ) : (
        <ReviewSession words={words} />
      )}
    </div>
  );
}
