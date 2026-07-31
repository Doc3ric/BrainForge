import { VocabularyWord } from '@/types/vocabulary';
import { useState } from 'react';

interface Props {
  word: VocabularyWord;
  onNext: () => void;
  isSubmitting?: boolean;
}

export function StudyCard({ word, onNext, isSubmitting }: Props) {
  const [flipped, setFlipped] = useState(false);

  return (
    <div className="w-full max-w-2xl mx-auto flex flex-col h-[500px]">
      <div 
        className={`flex-1 relative cursor-pointer transition-all duration-500 [transform-style:preserve-3d] ${flipped ? '[transform:rotateY(180deg)]' : ''}`}
        onClick={() => setFlipped(!flipped)}
      >
        {/* Front */}
        <div className="absolute inset-0 bg-slate-800 border border-slate-700 rounded-2xl p-8 flex flex-col items-center justify-center [backface-visibility:hidden] shadow-lg hover:border-blue-500 transition-colors">
          <span className="text-slate-400 mb-4">{word.category.name}</span>
          <h2 className="text-5xl font-bold text-white mb-4 text-center">{word.word}</h2>
          <span className="px-4 py-1.5 bg-slate-700 text-slate-300 rounded-full text-sm font-medium">
            {word.part_of_speech}
          </span>
          <p className="absolute bottom-6 text-sm text-slate-500">Click to flip</p>
        </div>

        {/* Back */}
        <div className="absolute inset-0 bg-slate-800 border border-slate-700 rounded-2xl p-8 flex flex-col items-center justify-center [backface-visibility:hidden] [transform:rotateY(180deg)] shadow-lg overflow-y-auto">
          <h3 className="text-2xl font-bold text-blue-400 mb-6 text-center">{word.definition}</h3>
          
          {word.examples.length > 0 && (
            <div className="w-full max-w-md">
              <h4 className="text-sm font-bold text-slate-500 uppercase tracking-wider mb-3">Examples</h4>
              <ul className="space-y-3">
                {word.examples.map((ex) => (
                  <li key={ex.id} className="text-slate-300 border-l-2 border-slate-600 pl-4 italic">
                    &quot;{ex.example_sentence}&quot;
                  </li>
                ))}
              </ul>
            </div>
          )}
        </div>
      </div>

      <div className="mt-8 flex justify-center opacity-0 transition-opacity duration-300" style={{ opacity: flipped ? 1 : 0, pointerEvents: flipped ? 'auto' : 'none' }}>
        <button
          onClick={(e) => {
            e.stopPropagation();
            onNext();
            setFlipped(false);
          }}
          disabled={isSubmitting}
          className="px-8 py-3 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white rounded-lg font-bold text-lg transition-colors shadow-lg shadow-blue-900/20"
        >
          {isSubmitting ? 'Saving...' : 'Got it! Next'}
        </button>
      </div>
    </div>
  );
}
