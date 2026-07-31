import { useVocabularyProgress } from '@/hooks/useVocabulary';

export function VocabularyProgressSummary() {
  const { data, isLoading, error } = useVocabularyProgress();

  if (isLoading) {
    return (
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6 animate-pulse">
        {[1, 2, 3].map(i => (
          <div key={i} className="bg-slate-800 h-32 rounded-xl border border-slate-700"></div>
        ))}
      </div>
    );
  }

  if (error || !data) {
    return null; // Silent fail for this widget or show error
  }

  const { total_words, learned_words, reviews_due } = data.data;
  const progressPercent = total_words > 0 ? Math.round((learned_words / total_words) * 100) : 0;

  return (
    <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div className="bg-slate-800 border border-slate-700 rounded-xl p-6 shadow-lg">
        <div className="flex items-center gap-4 mb-2">
          <div className="p-3 bg-blue-900/30 text-blue-400 rounded-lg">
            <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <h3 className="text-slate-400 font-medium">Total Words</h3>
        </div>
        <div className="text-4xl font-bold text-white">{total_words}</div>
      </div>

      <div className="bg-slate-800 border border-slate-700 rounded-xl p-6 shadow-lg">
        <div className="flex items-center gap-4 mb-2">
          <div className="p-3 bg-emerald-900/30 text-emerald-400 rounded-lg">
            <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h3 className="text-slate-400 font-medium">Learned</h3>
        </div>
        <div className="flex items-end justify-between">
          <div className="text-4xl font-bold text-white">{learned_words}</div>
          <div className="text-emerald-400 font-medium bg-emerald-900/30 px-2 py-1 rounded mb-1">{progressPercent}%</div>
        </div>
      </div>

      <div className="bg-slate-800 border border-slate-700 rounded-xl p-6 shadow-lg">
        <div className="flex items-center gap-4 mb-2">
          <div className="p-3 bg-orange-900/30 text-orange-400 rounded-lg">
            <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h3 className="text-slate-400 font-medium">Reviews Due</h3>
        </div>
        <div className="text-4xl font-bold text-white">{reviews_due}</div>
      </div>
    </div>
  );
}
