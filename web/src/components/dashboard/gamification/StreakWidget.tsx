import React from 'react';
import { useStreak } from '../../../hooks/useGamification';

export const StreakWidget = () => {
  const { data: streak, isLoading, isError } = useStreak();

  if (isLoading) return <div className="animate-pulse bg-slate-800 h-24 rounded-xl"></div>;
  if (isError) return <div className="text-red-400 p-4">Failed to load streak. <button onClick={() => window.location.reload()}>Retry</button></div>;

  return (
    <div className="bg-slate-800 p-4 rounded-xl border border-slate-700 flex items-center justify-between">
      <div className="flex items-center gap-4">
        <div className="w-12 h-12 bg-orange-500/20 text-orange-500 rounded-full flex items-center justify-center text-2xl">
          🔥
        </div>
        <div>
          <div className="text-sm text-slate-400">Current Streak</div>
          <div className="text-2xl font-bold text-white">{streak?.current_streak || 0} days</div>
        </div>
      </div>
      {streak?.freeze_balance > 0 && (
        <div className="text-xs font-semibold bg-blue-500/20 text-blue-400 px-2 py-1 rounded-md">
          ❄️ {streak.freeze_balance} Freezes
        </div>
      )}
    </div>
  );
};
