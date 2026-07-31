'use client';

import { useAuth } from '@/hooks/useAuth';
import { useRouter } from 'next/navigation';
import { VocabularyProgressSummary } from '@/components/features/vocabulary/VocabularyProgressSummary';
import Link from 'next/link';

export default function VocabularyProgressPage() {
  const { user, isUserLoading: authLoading } = useAuth();
  const router = useRouter();

  if (authLoading) return null;
  if (!user) {
    router.push('/login');
    return null;
  }

  return (
    <div className="container mx-auto px-4 py-8 max-w-4xl">
      <Link href="/vocabulary" className="text-slate-400 hover:text-white inline-flex items-center gap-2 mb-8 transition-colors">
        <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Back to Vocabulary
      </Link>
      
      <h1 className="text-3xl font-bold text-white mb-8">Your Vocabulary Progress</h1>
      
      <VocabularyProgressSummary />

      <div className="mt-12 bg-slate-800 border border-slate-700 rounded-xl p-8">
        <h2 className="text-xl font-bold text-white mb-4">How it works</h2>
        <div className="space-y-4 text-slate-300">
          <p>
            BrainForge uses the <strong>SM-2 Spaced Repetition Algorithm</strong> to optimize your learning. 
            When you learn a new word, it enters your review queue. 
          </p>
          <p>
            Each time you review a word, you rate how well you remembered it from 0 (forgot completely) to 5 (perfect recall).
            The algorithm uses this score to calculate when you should review the word again.
          </p>
          <p>
            Words you find difficult will appear more frequently, while words you know well will be spaced further apart, maximizing your long-term retention while minimizing study time.
          </p>
        </div>
      </div>
    </div>
  );
}
