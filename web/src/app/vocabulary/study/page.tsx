'use client';

import { useEffect, useState } from 'react';
import { useStartStudySession, useVocabularyList } from '@/hooks/useVocabulary';
import { StudySession } from '@/components/features/vocabulary/StudySession';
import { VocabularyEmptyState } from '@/components/features/vocabulary/VocabularyEmptyState';
import { VocabularyErrorState } from '@/components/features/vocabulary/VocabularyErrorState';
import { useAuth } from '@/hooks/useAuth';
import { useRouter } from 'next/navigation';
import { useVocabularyStore } from '@/stores/vocabulary.store';

export default function StudyPage() {
  const { user, isUserLoading: authLoading } = useAuth();
  const router = useRouter();
  
  const [sessionId, setSessionId] = useState<string | null>(null);
  const { mutateAsync: startSession, isPending: isStarting } = useStartStudySession();
  const { data, isLoading, isError, error } = useVocabularyList({ status: 'unlearned' });
  const { setStudySession, resetStudySession } = useVocabularyStore();

  useEffect(() => {
    return () => resetStudySession();
  }, [resetStudySession]);

  if (authLoading) return null;
  if (!user) {
    router.push('/login');
    return null;
  }

  const words = data?.pages[0]?.data.slice(0, 5) || []; // Just take 5 words for a session

  const handleStart = async () => {
    if (words.length === 0) return;
    try {
      const response = await startSession();
      setSessionId(response.data.id);
      setStudySession(words.map(w => w.id));
    } catch (e) {
      console.error(e);
    }
  };

  return (
    <div className="container mx-auto px-4 py-8 min-h-screen">
      {!sessionId ? (
        <div className="max-w-xl mx-auto text-center mt-20">
          <div className="w-24 h-24 bg-blue-900/50 text-blue-400 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg className="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
          </div>
          <h1 className="text-4xl font-bold text-white mb-4">Ready to learn?</h1>
          <p className="text-xl text-slate-400 mb-8">
            Start a new session to learn up to 5 new words today.
          </p>
          
          {isLoading ? (
            <div className="h-12 w-32 bg-slate-800 animate-pulse rounded-lg mx-auto"></div>
          ) : isError ? (
            <VocabularyErrorState error={error} />
          ) : words.length === 0 ? (
            <VocabularyEmptyState message="You've learned all available words! Check back later for more." />
          ) : (
            <button
              onClick={handleStart}
              disabled={isStarting}
              className="px-10 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xl transition-all hover:scale-105 active:scale-95 shadow-xl shadow-blue-900/20 disabled:opacity-50 disabled:hover:scale-100"
            >
              {isStarting ? 'Preparing...' : 'Start Session'}
            </button>
          )}
        </div>
      ) : (
        <StudySession sessionId={sessionId} words={words} />
      )}
    </div>
  );
}
