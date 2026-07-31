import Link from 'next/link';
import { VocabularyWord } from '@/types/vocabulary';

interface VocabularyListItemProps {
  word: VocabularyWord;
}

export function VocabularyListItem({ word }: VocabularyListItemProps) {
  return (
    <Link href={`/vocabulary/${word.id}`}>
      <div className="bg-slate-800 border border-slate-700 rounded-lg p-4 hover:border-blue-500 transition-colors cursor-pointer group">
        <div className="flex justify-between items-start mb-2">
          <div className="flex items-center gap-3">
            <h3 className="text-xl font-bold text-white group-hover:text-blue-400 transition-colors">
              {word.word}
            </h3>
            <span className="px-2 py-1 text-xs font-medium bg-slate-700 text-slate-300 rounded-md">
              {word.part_of_speech}
            </span>
          </div>
          {word.user_state?.is_learned ? (
            <span className="text-emerald-400 text-sm font-medium flex items-center gap-1">
              <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"></path></svg>
              Learned
            </span>
          ) : (
            <span className="text-slate-500 text-sm font-medium">
              Unlearned
            </span>
          )}
        </div>
        <p className="text-slate-400 text-sm line-clamp-1">{word.definition}</p>
        <div className="mt-3 flex gap-2">
          <span className="text-xs text-slate-500 bg-slate-900 px-2 py-1 rounded">
            {word.category.name}
          </span>
          <span className="text-xs text-slate-500 bg-slate-900 px-2 py-1 rounded">
            {word.difficulty_level.display_name}
          </span>
        </div>
      </div>
    </Link>
  );
}
