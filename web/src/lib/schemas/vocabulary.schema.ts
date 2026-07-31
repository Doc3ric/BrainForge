import { z } from 'zod';

export const VocabularyCategorySchema = z.object({
  id: z.string().uuid(),
  name: z.string(),
  description: z.string().nullable(),
});

export const UserVocabularySchema = z.object({
  id: z.string().uuid(),
  is_learned: z.boolean(),
  ease_factor: z.number(),
  interval_days: z.number(),
  repetition_count: z.number(),
  next_review_at: z.string().nullable(),
  last_interacted_at: z.string().nullable(),
});

export const VocabularyExampleSchema = z.object({
  id: z.string().uuid(),
  example_sentence: z.string(),
});

export const VocabularyWordSchema = z.object({
  id: z.string().uuid(),
  word: z.string(),
  part_of_speech: z.string(),
  definition: z.string(),
  category: VocabularyCategorySchema,
  difficulty_level: z.object({
    id: z.string().uuid(),
    display_name: z.string(),
  }),
  examples: z.array(VocabularyExampleSchema),
  user_state: UserVocabularySchema.nullable(),
});

export const VocabularyProgressSchema = z.object({
  total_words: z.number(),
  learned_words: z.number(),
  reviews_due: z.number(),
});

export const ReviewSubmitSchema = z.object({
  quality_score: z.number().int().min(0).max(5),
});

export const PaginatedVocabularySchema = z.object({
  data: z.array(VocabularyWordSchema),
  meta: z.object({
    current_page: z.number(),
    last_page: z.number(),
    per_page: z.number(),
    total: z.number(),
  }),
});
