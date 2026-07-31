interface Props {
  hasNextPage: boolean;
  isFetchingNextPage: boolean;
  fetchNextPage: () => void;
}

export function LoadMoreButton({ hasNextPage, isFetchingNextPage, fetchNextPage }: Props) {
  if (!hasNextPage) return null;
  
  return (
    <div className="flex justify-center mt-6">
      <button
        onClick={() => fetchNextPage()}
        disabled={isFetchingNextPage}
        className="px-6 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-full font-medium transition-colors disabled:opacity-50 border border-slate-700"
      >
        {isFetchingNextPage ? 'Loading...' : 'Load More'}
      </button>
    </div>
  );
}
