import { z } from 'zod';
import {
  VocabularyCategorySchema,
  UserVocabularySchema,
  VocabularyExampleSchema,
  VocabularyWordSchema,
  VocabularyProgressSchema,
  ReviewSubmitSchema,
  PaginatedVocabularySchema
} from '@/lib/schemas/vocabulary.schema';

export type VocabularyCategory = z.infer<typeof VocabularyCategorySchema>;
export type UserVocabulary = z.infer<typeof UserVocabularySchema>;
export type VocabularyExample = z.infer<typeof VocabularyExampleSchema>;
export type VocabularyWord = z.infer<typeof VocabularyWordSchema>;
export type VocabularyProgress = z.infer<typeof VocabularyProgressSchema>;
export type ReviewSubmitPayload = z.infer<typeof ReviewSubmitSchema>;
export type PaginatedVocabularyResponse = z.infer<typeof PaginatedVocabularySchema>;

export interface VocabularyFilters {
  category_id?: string;
  difficulty_id?: string;
  search?: string;
  status?: 'learned' | 'unlearned';
  page?: number;
}
