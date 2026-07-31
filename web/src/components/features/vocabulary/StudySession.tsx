import { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import { VocabularyWord } from '@/types/vocabulary';
import { StudyCard } from './StudyCard';
import { useVocabularyStore } from '@/stores/vocabulary.store';
import { useCompleteStudySession, useMarkLearned } from '@/hooks/useVocabulary';
import { toast } from 'react-hot-toast';

interface Props {
  sessionId: string;
  words: VocabularyWord[];
}

export function StudySession({ sessionId, words }: Props) {
  const router = useRouter();
  const { activeStudyCardIndex, nextStudyCard, resetStudySession } = useVocabularyStore();
  const { mutateAsync: completeSession, isPending: isCompleting } = useCompleteStudySession();
  const { mutateAsync: markLearned, isPending: isMarking } = useMarkLearned();
  
  const [isFinished, setIsFinished] = useState(false);

  useEffect(() => {
    return () => resetStudySession();
  }, [resetStudySession]);

  const activeWord = words[activeStudyCardIndex];
  
  const handleNext = async () => {
    try {
      if (activeWord) {
        await markLearned(activeWord.id);
      }
      
      if (activeStudyCardIndex < words.length - 1) {
        nextStudyCard();
      } else {
        setIsFinished(true);
        await completeSession({ sessionId, wordIds: words.map(w => w.id) });
        toast.success('Study session completed!');
      }
    } catch {
      toast.error('Failed to save progress.');
    }
  };

  if (isFinished) {
    return (
      <div className="bg-slate-800 border border-slate-700 rounded-xl p-12 text-center max-w-2xl mx-auto shadow-xl">
        <div className="w-20 h-20 bg-emerald-900/50 text-emerald-400 rounded-full flex items-center justify-center mx-auto mb-6">
          <svg className="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <h2 className="text-3xl font-bold text-white mb-4">Session Complete!</h2>
        <p className="text-slate-300 text-lg mb-8">You&apos;ve successfully studied {words.length} words.</p>
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
        <h1 className="text-2xl font-bold text-white">Study Session</h1>
        <div className="text-slate-400 font-medium bg-slate-800 px-4 py-2 rounded-lg border border-slate-700">
          Card {activeStudyCardIndex + 1} of {words.length}
        </div>
      </div>
      
      <div className="w-full bg-slate-800 rounded-full h-2 mb-8 overflow-hidden">
        <div 
          className="bg-blue-500 h-2 rounded-full transition-all duration-300"
          style={{ width: `${((activeStudyCardIndex) / words.length) * 100}%` }}
        ></div>
      </div>

      <StudyCard 
        word={activeWord} 
        onNext={handleNext} 
        isSubmitting={isCompleting || isMarking}
      />
    </div>
  );
}
