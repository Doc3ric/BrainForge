interface Props {
  error: Error | null;
  reset?: () => void;
}

export function VocabularyErrorState({ error, reset }: Props) {
  return (
    <div className="p-6 bg-red-900/20 border border-red-500/50 rounded-lg text-center">
      <h3 className="text-lg font-medium text-red-400 mb-2">Failed to load vocabulary</h3>
      <p className="text-red-300/70 text-sm mb-4">{error?.message || 'An unknown error occurred.'}</p>
      {reset && (
        <button 
          onClick={reset}
          className="px-4 py-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded transition-colors"
        >
          Try Again
        </button>
      )}
    </div>
  );
}
