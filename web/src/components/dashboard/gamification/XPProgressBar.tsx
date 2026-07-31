import React from 'react';

export const XPProgressBar = ({ levelData }: { levelData?: {
    current_level: number;
    current_xp: number;
    xp_for_next_level: number;
    xp_progress_percent: number;
  } | null; }) => {
  const percent = levelData?.xp_progress_percent || 0;
  
  return (
    <div className="bg-slate-800 p-4 rounded-xl border border-slate-700">
      <div className="flex justify-between items-end mb-2">
        <div>
          <span className="text-slate-400 text-xs font-semibold uppercase tracking-wider">Level</span>
          <div className="text-2xl font-bold text-white">{levelData?.current_level || 1}</div>
        </div>
        <div className="text-right">
          <span className="text-blue-400 text-sm font-semibold">{levelData?.current_xp || 0} XP</span>
        </div>
      </div>
      <div className="h-2 w-full bg-slate-700 rounded-full overflow-hidden">
        <div 
          className="h-full bg-gradient-to-r from-blue-500 to-indigo-500 transition-all duration-500" 
          style={{ width: `${percent}%` }}
        ></div>
      </div>
    </div>
  );
};
