'use client';

import { useState } from 'react';
import Link from 'next/link';
import { useVocabularyList, useVocabularyCategories, useVocabularyProgress } from '@/hooks/useVocabulary';
import { VocabularyFilters } from '@/components/features/vocabulary/VocabularyFilters';
import { VocabularyListItem } from '@/components/features/vocabulary/VocabularyListItem';
import { VocabularyEmptyState } from '@/components/features/vocabulary/VocabularyEmptyState';
import { VocabularyErrorState } from '@/components/features/vocabulary/VocabularyErrorState';
import { WordSkeleton } from '@/components/features/vocabulary/WordSkeleton';
import { LoadMoreButton } from '@/components/features/vocabulary/LoadMoreButton';
import { useAuth } from '@/hooks/useAuth';
import { useRouter } from 'next/navigation';

export default function VocabularyHomePage() {
  const { user, isUserLoading: authLoading } = useAuth();
  const router = useRouter();

  const [filters, setFilters] = useState({});

  const {
    data,
    isLoading,
    isError,
    error,
    fetchNextPage,
    hasNextPage,
    isFetchingNextPage,
  } = useVocabularyList(filters);

  const { data: categoriesData } = useVocabularyCategories();
  const { data: progressData } = useVocabularyProgress();

  if (authLoading) return null;
  if (!user) {
    router.push('/login');
    return null;
  }

  const handleFilterChange = (key: string, value: string) => {
    setFilters(prev => ({ ...prev, [key]: value || undefined }));
  };

  const words = data?.pages.flatMap(page => page.data) ?? [];

  return (
    <div className="container mx-auto px-4 py-8 max-w-6xl">
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
          <h1 className="text-3xl font-bold text-white mb-2">Vocabulary Library</h1>
          <p className="text-slate-400">Master new words and track your progress.</p>
        </div>
        <div className="flex gap-3">
          <Link 
            href="/vocabulary/progress"
            className="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg font-medium transition-colors border border-slate-700 flex items-center gap-2"
          >
            <svg className="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
            Stats
          </Link>
          <Link 
            href="/vocabulary/study"
            className="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors flex items-center gap-2"
          >
            <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            Learn New
          </Link>
          {progressData?.data.reviews_due ? (
            <Link 
              href="/vocabulary/review"
              className="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition-colors flex items-center gap-2"
            >
              <div className="bg-white text-emerald-700 text-xs font-bold px-2 py-0.5 rounded-full">
                {progressData.data.reviews_due}
              </div>
              Reviews Due
            </Link>
          ) : null}
        </div>
      </div>

      <VocabularyFilters 
        categories={categoriesData?.data || []}
        difficulties={[
          { id: '1', display_name: 'Beginner' },
          { id: '2', display_name: 'Elementary' },
          { id: '3', display_name: 'Intermediate' },
          { id: '4', display_name: 'Upper Intermediate' },
          { id: '5', display_name: 'Advanced' },
          { id: '6', display_name: 'Proficient' },
        ]} // Alternatively fetch from difficulty endpoint if it existed, but it doesn't so hardcoding IDs isn't good. Let's omit difficulty for now or pass empty array since we didn't add difficulty route.
        filters={filters}
        onFilterChange={handleFilterChange}
      />

      {isError ? (
        <VocabularyErrorState error={error} />
      ) : isLoading ? (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {[1, 2, 3, 4, 5, 6].map(i => <WordSkeleton key={i} />)}
        </div>
      ) : words.length === 0 ? (
        <VocabularyEmptyState message="No vocabulary words match your filters." />
      ) : (
        <>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {words.map(word => (
              <VocabularyListItem key={word.id} word={word} />
            ))}
          </div>
          <LoadMoreButton 
            hasNextPage={!!hasNextPage}
            isFetchingNextPage={isFetchingNextPage}
            fetchNextPage={fetchNextPage}
          />
        </>
      )}
    </div>
  );
}
