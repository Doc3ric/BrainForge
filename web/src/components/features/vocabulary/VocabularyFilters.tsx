import { VocabularyCategory } from '@/types/vocabulary';

interface Props {
  categories: VocabularyCategory[];
  difficulties: { id: string; display_name: string }[];
  filters: {
    category_id?: string;
    difficulty_id?: string;
    search?: string;
    status?: 'learned' | 'unlearned';
  };
  onFilterChange: (key: string, value: string) => void;
}

export function VocabularyFilters({ categories, difficulties, filters, onFilterChange }: Props) {
  return (
    <div className="flex flex-col md:flex-row gap-4 mb-6">
      <input
        type="text"
        placeholder="Search words..."
        value={filters.search || ''}
        onChange={(e) => onFilterChange('search', e.target.value)}
        className="flex-1 bg-slate-800 border border-slate-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500 transition-colors"
      />
      <select
        value={filters.category_id || ''}
        onChange={(e) => onFilterChange('category_id', e.target.value)}
        className="bg-slate-800 border border-slate-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500"
      >
        <option value="">All Categories</option>
        {categories.map((c) => (
          <option key={c.id} value={c.id}>{c.name}</option>
        ))}
      </select>
      <select
        value={filters.difficulty_id || ''}
        onChange={(e) => onFilterChange('difficulty_id', e.target.value)}
        className="bg-slate-800 border border-slate-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500"
      >
        <option value="">All Difficulties</option>
        {difficulties.map((d) => (
          <option key={d.id} value={d.id}>{d.display_name}</option>
        ))}
      </select>
      <select
        value={filters.status || ''}
        onChange={(e) => onFilterChange('status', e.target.value)}
        className="bg-slate-800 border border-slate-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500"
      >
        <option value="">All Statuses</option>
        <option value="learned">Learned</option>
        <option value="unlearned">Unlearned</option>
      </select>
    </div>
  );
}
