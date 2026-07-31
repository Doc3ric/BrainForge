import { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import { VocabularyWord } from '@/types/vocabulary';
import { ReviewCard } from './ReviewCard';
import { useVocabularyStore } from '@/stores/vocabulary.store';
import { useSubmitReview } from '@/hooks/useVocabulary';
import { toast } from 'react-hot-toast';

interface Props {
  words: VocabularyWord[];
}

export function ReviewSession({ words }: Props) {
  const router = useRouter();
  const { activeReviewCardIndex, nextReviewCard, resetReviewSession } = useVocabularyStore();
  const { mutateAsync: submitReview, isPending } = useSubmitReview();
  const [isFinished, setIsFinished] = useState(false);

  useEffect(() => {
    return () => resetReviewSession();
  }, [resetReviewSession]);

  const activeWord = words[activeReviewCardIndex];

  const handleSubmit = async (score: number) => {
    try {
      await submitReview({ wordId: activeWord.id, qualityScore: score });
      
      if (activeReviewCardIndex < words.length - 1) {
        nextReviewCard();
      } else {
        setIsFinished(true);
        toast.success('Review queue complete!');
      }
    } catch {
      toast.error('Failed to submit review.');
    }
  };

  if (isFinished) {
    return (
      <div className="bg-slate-800 border border-slate-700 rounded-xl p-12 text-center max-w-2xl mx-auto shadow-xl">
        <div className="w-20 h-20 bg-emerald-900/50 text-emerald-400 rounded-full flex items-center justify-center mx-auto mb-6">
          <svg className="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <h2 className="text-3xl font-bold text-white mb-4">All Caught Up!</h2>
        <p className="text-slate-300 text-lg mb-8">You&apos;ve completed all your due reviews for now.</p>
        <button 
          onClick={() => router.push('/vocabulary')}
          className="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-lg transition-colors"
        >
          Return to Vocabulary
        </button>
      </div>
    );
  }

  if (!activeWord) return null;

  return (
    <div className="max-w-4xl mx-auto">
      <div className="flex justify-between items-center mb-8">
        <h1 className="text-2xl font-bold text-white">Review Queue</h1>
        <div className="text-slate-400 font-medium bg-slate-800 px-4 py-2 rounded-lg border border-slate-700">
          Review {activeReviewCardIndex + 1} of {words.length}
        </div>
      </div>
      
      <div className="w-full bg-slate-800 rounded-full h-2 mb-8 overflow-hidden">
        <div 
          className="bg-emerald-500 h-2 rounded-full transition-all duration-300"
          style={{ width: `${((activeReviewCardIndex) / words.length) * 100}%` }}
        ></div>
      </div>

      <ReviewCard 
        word={activeWord} 
        onSubmit={handleSubmit} 
        isSubmitting={isPending}
      />
    </div>
  );
}
