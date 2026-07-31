export function WordSkeleton() {
  return (
    <div className="bg-slate-800 border border-slate-700 rounded-lg p-4 animate-pulse">
      <div className="flex justify-between items-start mb-2">
        <div className="flex items-center gap-3">
          <div className="h-6 w-32 bg-slate-700 rounded"></div>
          <div className="h-5 w-16 bg-slate-700 rounded"></div>
        </div>
        <div className="h-4 w-20 bg-slate-700 rounded"></div>
      </div>
      <div className="h-4 w-full bg-slate-700 rounded mt-3"></div>
      <div className="mt-3 flex gap-2">
        <div className="h-6 w-24 bg-slate-700 rounded"></div>
        <div className="h-6 w-24 bg-slate-700 rounded"></div>
      </div>
    </div>
  );
}
