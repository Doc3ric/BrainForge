import { VocabularyWord } from '@/types/vocabulary';
import { useState } from 'react';

interface Props {
  word: VocabularyWord;
  onSubmit: (score: number) => void;
  isSubmitting?: boolean;
}

export function ReviewCard({ word, onSubmit, isSubmitting }: Props) {
  const [flipped, setFlipped] = useState(false);

  return (
    <div className="w-full max-w-2xl mx-auto flex flex-col h-[500px]">
      <div 
        className={`flex-1 relative cursor-pointer transition-all duration-500 [transform-style:preserve-3d] ${flipped ? '[transform:rotateY(180deg)]' : ''}`}
        onClick={() => !flipped && setFlipped(true)}
      >
        {/* Front */}
        <div className="absolute inset-0 bg-slate-800 border border-slate-700 rounded-2xl p-8 flex flex-col items-center justify-center [backface-visibility:hidden] shadow-lg hover:border-emerald-500 transition-colors">
          <span className="text-slate-400 mb-2">Review</span>
          <h2 className="text-5xl font-bold text-white mb-4 text-center">{word.word}</h2>
          <p className="absolute bottom-6 text-sm text-slate-500">Think of the definition, then click to flip</p>
        </div>

        {/* Back */}
        <div className="absolute inset-0 bg-slate-800 border border-slate-700 rounded-2xl p-8 flex flex-col items-center justify-center [backface-visibility:hidden] [transform:rotateY(180deg)] shadow-lg overflow-y-auto">
          <h3 className="text-2xl font-bold text-emerald-400 mb-4 text-center">{word.definition}</h3>
          <span className="px-3 py-1 bg-slate-700 text-slate-300 rounded-full text-sm font-medium mb-6">
            {word.part_of_speech}
          </span>
          
          {word.examples.length > 0 && (
            <div className="w-full max-w-md">
              <h4 className="text-sm font-bold text-slate-500 uppercase tracking-wider mb-2">Example</h4>
              <p className="text-slate-300 border-l-2 border-slate-600 pl-4 italic">
                &quot;{word.examples[0].example_sentence}&quot;
              </p>
            </div>
          )}
        </div>
      </div>

      <div className="mt-8 transition-opacity duration-300" style={{ opacity: flipped ? 1 : 0, pointerEvents: flipped ? 'auto' : 'none' }}>
        <h4 className="text-center text-slate-300 mb-4 font-medium">How well did you remember this?</h4>
        <div className="grid grid-cols-2 md:grid-cols-6 gap-2">
          {[
            { score: 0, label: 'Complete Blackout', color: 'bg-red-900/50 hover:bg-red-600 border-red-700 text-red-200' },
            { score: 1, label: 'Incorrect (Familiar)', color: 'bg-orange-900/50 hover:bg-orange-600 border-orange-700 text-orange-200' },
            { score: 2, label: 'Incorrect (Easy)', color: 'bg-amber-900/50 hover:bg-amber-600 border-amber-700 text-amber-200' },
            { score: 3, label: 'Hard Recall', color: 'bg-blue-900/50 hover:bg-blue-600 border-blue-700 text-blue-200' },
            { score: 4, label: 'Good Recall', color: 'bg-emerald-900/50 hover:bg-emerald-600 border-emerald-700 text-emerald-200' },
            { score: 5, label: 'Perfect', color: 'bg-teal-900/50 hover:bg-teal-600 border-teal-700 text-teal-200' },
          ].map(({ score, label, color }) => (
            <button
              key={score}
              onClick={(e) => {
                e.stopPropagation();
                onSubmit(score);
                setFlipped(false);
              }}
              disabled={isSubmitting}
              className={`p-3 rounded-lg border flex flex-col items-center justify-center gap-1 transition-all disabled:opacity-50 ${color}`}
            >
              <span className="font-bold text-xl">{score}</span>
              <span className="text-[10px] leading-tight text-center opacity-80">{label}</span>
            </button>
          ))}
        </div>
      </div>
    </div>
  );
}
