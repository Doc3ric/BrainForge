import React from 'react';
import { useDailyGoals } from '../../../hooks/useGamification';

export const DailyGoalWidget = () => {
  const { data: goals, isLoading, isError } = useDailyGoals();

  if (isLoading) return <div className="animate-pulse bg-slate-800 h-32 rounded-xl"></div>;
  if (isError) return <div className="text-red-400 p-4">Failed to load goals.</div>;

  const vocabPercent = Math.min(100, ((goals?.current_vocab || 0) / (goals?.target_vocab || 1)) * 100);
  
  return (
    <div className="bg-slate-800 p-4 rounded-xl border border-slate-700">
      <h3 className="text-sm font-semibold text-slate-400 mb-4">Today's Targets</h3>
      
      <div className="space-y-4">
        <div>
          <div className="flex justify-between text-xs mb-1">
            <span className="text-slate-300">Vocabulary</span>
            <span className="text-slate-400">{goals?.current_vocab || 0} / {goals?.target_vocab || 0}</span>
          </div>
          <div className="h-1.5 w-full bg-slate-700 rounded-full overflow-hidden">
            <div className="h-full bg-green-500 transition-all" style={{ width: `${vocabPercent}%` }}></div>
          </div>
        </div>
        {/* Additional goals omitted for brevity */}
      </div>
    </div>
  );
};
