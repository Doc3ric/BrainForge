import { VocabularyWord } from '@/types/vocabulary';

interface Props {
  word: VocabularyWord;
}

export function WordDetailCard({ word }: Props) {
  return (
    <div className="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden shadow-xl">
      <div className="p-6 md:p-8 border-b border-slate-700">
        <div className="flex justify-between items-start mb-4">
          <div>
            <h1 className="text-3xl md:text-4xl font-bold text-white mb-2">{word.word}</h1>
            <span className="inline-block px-3 py-1 bg-blue-900/50 text-blue-400 font-medium rounded-full text-sm">
              {word.part_of_speech}
            </span>
          </div>
          {word.user_state?.is_learned && (
            <div className="bg-emerald-900/30 text-emerald-400 px-4 py-2 rounded-lg flex flex-col items-end">
              <span className="text-xs uppercase tracking-wider font-bold opacity-70">Learned</span>
              <span className="font-medium">Ease: {word.user_state.ease_factor}</span>
            </div>
          )}
        </div>
        
        <p className="text-xl text-slate-300 leading-relaxed mb-6">
          {word.definition}
        </p>

        <div className="flex gap-3">
          <span className="bg-slate-700 text-slate-300 px-3 py-1 rounded text-sm">
            {word.category.name}
          </span>
          <span className="bg-slate-700 text-slate-300 px-3 py-1 rounded text-sm">
            {word.difficulty_level.display_name}
          </span>
        </div>
      </div>

      <div className="p-6 md:p-8 bg-slate-800/50">
        <h3 className="text-lg font-bold text-slate-200 mb-4 uppercase tracking-wider text-sm">Examples</h3>
        {word.examples.length > 0 ? (
          <ul className="space-y-4">
            {word.examples.map((ex) => (
              <li key={ex.id} className="text-slate-300 relative pl-6 border-l-2 border-slate-600">
                &quot;{ex.example_sentence}&quot;
              </li>
            ))}
          </ul>
        ) : (
          <p className="text-slate-500">No examples available.</p>
        )}
      </div>
      
      {word.user_state && (
        <div className="p-6 border-t border-slate-700 bg-slate-900/50">
          <h3 className="text-sm font-bold text-slate-400 uppercase tracking-wider mb-3">Study Stats</h3>
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div className="bg-slate-800 p-3 rounded-lg border border-slate-700">
              <div className="text-xs text-slate-500 mb-1">Repetitions</div>
              <div className="text-lg font-medium text-slate-300">{word.user_state.repetition_count}</div>
            </div>
            <div className="bg-slate-800 p-3 rounded-lg border border-slate-700">
              <div className="text-xs text-slate-500 mb-1">Interval</div>
              <div className="text-lg font-medium text-slate-300">{word.user_state.interval_days} days</div>
            </div>
            <div className="bg-slate-800 p-3 rounded-lg border border-slate-700 col-span-2">
              <div className="text-xs text-slate-500 mb-1">Next Review</div>
              <div className="text-lg font-medium text-slate-300">
                {word.user_state.next_review_at ? new Date(word.user_state.next_review_at).toLocaleDateString() : 'N/A'}
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
