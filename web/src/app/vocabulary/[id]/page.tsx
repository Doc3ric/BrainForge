'use client';

import { useWordDetail } from '@/hooks/useVocabulary';
import { WordDetailCard } from '@/components/features/vocabulary/WordDetailCard';
import { VocabularyErrorState } from '@/components/features/vocabulary/VocabularyErrorState';
import { useAuth } from '@/hooks/useAuth';
import { useRouter } from 'next/navigation';
import Link from 'next/link';

export default function WordDetailPage({ params }: { params: { id: string } }) {
  const { user, isUserLoading: authLoading } = useAuth();
  const router = useRouter();
  const { data, isLoading, isError, error } = useWordDetail(params.id);

  if (authLoading) return null;
  if (!user) {
    router.push('/login');
    return null;
  }

  return (
    <div className="container mx-auto px-4 py-8 max-w-4xl">
      <Link href="/vocabulary" className="text-slate-400 hover:text-white inline-flex items-center gap-2 mb-6 transition-colors">
        <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Back to Vocabulary
      </Link>
      
      {isLoading ? (
        <div className="bg-slate-800 h-[600px] rounded-xl border border-slate-700 animate-pulse"></div>
      ) : isError ? (
        <VocabularyErrorState error={error} />
      ) : data?.data ? (
        <WordDetailCard word={data.data} />
      ) : (
        <div className="text-center py-12 text-slate-400">Word not found.</div>
      )}
    </div>
  );
}
