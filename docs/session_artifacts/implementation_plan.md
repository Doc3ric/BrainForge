# Phase 4 — Vocabulary Module: Implementation Plan

> **Phase:** 4
> **Module:** Vocabulary (M-07)
> **Status:** 📋 Awaiting Final Approval
> **Version:** 1.1.0
> **Based on verified:** Phase 3 v3.1.0 Verification Certificate, Post-Architecture Review Amendments

---

## 1. Architecture Review — Pre-Phase 4 Carryover Items

These items were identified as non-blocking in the Phase 3 Verification Certificate and must be resolved at the start of Phase 4 execution.

### 1.1 DailyGoalController Inline Query (Carry-Forward)

**Current state:** `DailyGoalController::index()` performs a direct `DailyGoalTracking::where(...)->first()` query inline.

**Required action:** Extract the query into `DailyGoalRepository::findForDate(string $userId, string $date): ?DailyGoalTracking`.

**Files to modify:**
- `[MODIFY] app/Http/Controllers/Gamification/DailyGoalController.php` — inject `DailyGoalRepository`, remove direct query
- `[MODIFY] app/Repositories/Gamification/DailyGoalRepository.php` — add `findForDate()` method

**Impact:** Zero behavior change. Pure DEVELOPMENT_RULES.md compliance.

### 1.2 Morph Map — Vocabulary Model Registration

**Current state:** `AppServiceProvider` registers `'vocabulary' => \App\Models\Vocabulary::class` but `Vocabulary.php` does not exist. The morph map value is currently an unresolved FQCN string.

> [!IMPORTANT]
> Creating `App\Models\VocabularyWord.php` as the Vocabulary model in Phase 4 requires updating the morph map value from `\App\Models\Vocabulary::class` to `\App\Models\VocabularyWord::class`. The morph map **key** (`'vocabulary'`) remains unchanged. This is the stable alias that gets stored in the database.

**Files to modify:**
- `[MODIFY] app/Providers/AppServiceProvider.php` — update `vocabulary` alias to point to `VocabularyWord::class`

### 1.3 ProgressService Stub

**Current state:** `ProgressService` is an empty class.

**Required action:** Expand only with `getVocabularyProgress(string $userId): array`. No scope creep beyond vocabulary in Phase 4.

---

## 2. Database Layer

### 2.1 DATABASE.md Schema Compliance

The frozen `DATABASE.md` (Section 16) defines the following vocabulary tables. These are the **only** tables we create:

| Table | Type | Soft Delete |
|---|---|---|
| `vocabulary_categories` | Content | ✅ Yes |
| `vocabulary_words` | Content | ✅ Yes |
| `vocabulary_examples` | Content | No (child of words) |
| `user_vocabulary` | Junction / SM-2 State | No |
| `vocabulary_study_sessions` | Study Session State | No |
| `vocabulary_study_session_words` | Junction | No |
| `vocabulary_review_logs` | Review History | No |

> [!IMPORTANT]
> The DATABASE.md `user_vocabulary` spec uses `vocabulary_word_id` (not `word_id`) and `is_learned` (not `status`). Column names must match the spec exactly.

### 2.2 Migration Files (New)

#### `[NEW] create_vocabulary_categories_table.php`
```
vocabulary_categories
  id               UUID     PK, UUIDv7
  name             VARCHAR(100)  UNIQUE, NOT NULL
  description      TEXT          NULLABLE
  deleted_at       TIMESTAMPTZ   NULLABLE (soft delete)
  created_at       TIMESTAMPTZ
  updated_at       TIMESTAMPTZ

Indexes:
  unq_vocab_categories_name (name)
```

#### `[NEW] create_vocabulary_words_table.php`
```
vocabulary_words
  id               UUID      PK, UUIDv7
  category_id      UUID      FK → vocabulary_categories, NOT NULL, CASCADE
  difficulty_id    UUID      FK → difficulty_levels, NOT NULL, RESTRICT
  word             VARCHAR(100)  NOT NULL
  part_of_speech   VARCHAR(50)   NOT NULL
  definition       TEXT          NOT NULL
  deleted_at       TIMESTAMPTZ   NULLABLE (soft delete)
  created_at       TIMESTAMPTZ
  updated_at       TIMESTAMPTZ

Indexes (explicit — FK compliance):
  idx_vocab_words_category_id   (category_id)
  idx_vocab_words_difficulty_id (difficulty_id)
  idx_vocab_words_word          (word)           — for search queries
```

#### `[NEW] create_vocabulary_examples_table.php`
```
vocabulary_examples
  id                   UUID    PK, UUIDv7
  vocabulary_word_id   UUID    FK → vocabulary_words, NOT NULL, CASCADE
  example_sentence     TEXT    NOT NULL
  created_at           TIMESTAMPTZ
  updated_at           TIMESTAMPTZ

Indexes:
  idx_vocab_examples_word_id (vocabulary_word_id)
```

#### `[NEW] create_user_vocabulary_table.php`
```
user_vocabulary
  id                   UUID          PK, UUIDv7
  user_id              UUID          FK → users, NOT NULL, CASCADE
  vocabulary_word_id   UUID          FK → vocabulary_words, NOT NULL, RESTRICT
  is_learned           BOOLEAN       NOT NULL, DEFAULT FALSE
  ease_factor          DECIMAL(4,2)  NOT NULL, DEFAULT 2.50
  interval_days        INTEGER       NOT NULL, DEFAULT 1
  repetition_count     INTEGER       NOT NULL, DEFAULT 0
  next_review_at       TIMESTAMPTZ   NULLABLE
  last_interacted_at   TIMESTAMPTZ   NULLABLE
  created_at           TIMESTAMPTZ
  updated_at           TIMESTAMPTZ

Constraints:
  UNIQUE (user_id, vocabulary_word_id)  → unq_user_vocabulary

Indexes:
  idx_user_vocab_user_id              (user_id)
  idx_user_vocab_word_id              (vocabulary_word_id)
  idx_user_vocab_next_review          (user_id, next_review_at)   — SM-2 queue query
```

#### `[NEW] create_vocabulary_study_sessions_table.php`
```
vocabulary_study_sessions
  id               UUID     PK, UUIDv7
  user_id          UUID     FK → users, NOT NULL, CASCADE
  status           VARCHAR(20) NOT NULL, CHECK IN ('active', 'completed', 'abandoned')
  started_at       TIMESTAMPTZ NOT NULL, DEFAULT NOW()
  completed_at     TIMESTAMPTZ NULLABLE
  word_count       INTEGER  NOT NULL, DEFAULT 0
  created_at       TIMESTAMPTZ
  updated_at       TIMESTAMPTZ
```

#### `[NEW] create_vocabulary_study_session_words_table.php`
```
vocabulary_study_session_words
  id               UUID     PK, UUIDv7
  study_session_id UUID     FK → vocabulary_study_sessions, NOT NULL, CASCADE
  vocabulary_word_id UUID   FK → vocabulary_words, NOT NULL, RESTRICT
  studied_at       TIMESTAMPTZ NOT NULL, DEFAULT NOW()
```

#### `[NEW] create_vocabulary_review_logs_table.php`
```
vocabulary_review_logs
  id                 UUID     PK, UUIDv7
  user_vocabulary_id UUID     FK → user_vocabulary, NOT NULL, CASCADE
  idempotency_key    UUID     UNIQUE, NOT NULL
  quality_score      INTEGER  NOT NULL, CHECK (0-5)
  reviewed_at        TIMESTAMPTZ NOT NULL, DEFAULT NOW()
```

### 2.3 Model Files (New)

#### `[NEW] app/Models/VocabularyCategory.php`
- `HasUuids` + UUIDv7 override
- `SoftDeletes`
- Relationships: `hasMany(VocabularyWord::class)`
- Fillable: `name`, `description`

#### `[NEW] app/Models/VocabularyWord.php`
- `HasUuids` + UUIDv7 override
- `SoftDeletes`
- Relationships: `belongsTo(VocabularyCategory::class)`, `belongsTo(DifficultyLevel::class)`, `hasMany(VocabularyExample::class)`, `hasMany(UserVocabulary::class)`
- Fillable: `category_id`, `difficulty_id`, `word`, `part_of_speech`, `definition`

#### `[NEW] app/Models/VocabularyExample.php`
- `HasUuids` + UUIDv7 override
- Relationship: `belongsTo(VocabularyWord::class)`
- Fillable: `vocabulary_word_id`, `example_sentence`

#### `[NEW] app/Models/UserVocabulary.php`
- `HasUuids` + UUIDv7 override
- Relationships: `belongsTo(User::class)`, `belongsTo(VocabularyWord::class)`
- Fillable: `user_id`, `vocabulary_word_id`, `is_learned`, `ease_factor`, `interval_days`, `repetition_count`, `next_review_at`, `last_interacted_at`
- Casts: `is_learned` → `boolean`, `ease_factor` → `decimal:2`, `next_review_at` → `datetime`, `last_interacted_at` → `datetime`

#### `[NEW] app/Models/VocabularyStudySession.php`
- `HasUuids` + UUIDv7 override
- Relationships: `belongsTo(User::class)`, `belongsToMany(VocabularyWord::class, 'vocabulary_study_session_words')`
- Fillable: `user_id`, `status`, `started_at`, `completed_at`, `word_count`

#### `[NEW] app/Models/VocabularyReviewLog.php`
- `HasUuids` + UUIDv7 override
- Relationships: `belongsTo(UserVocabulary::class)`
- Fillable: `user_vocabulary_id`, `idempotency_key`, `quality_score`, `reviewed_at`

### 2.4 Morph Map Update

```php
// AppServiceProvider.php — update vocabulary alias
'vocabulary' => \App\Models\VocabularyWord::class,
```

---

## 3. SM-2 Algorithm Design

### 3.1 Algorithm Overview

The SM-2 algorithm governs spaced repetition. **The backend owns all calculations.** The frontend never computes intervals, ease factors, or review dates.

### 3.2 Input Parameters

| Parameter | Column | Type |
|---|---|---|
| Quality score (user-submitted) | Request body `quality_score` | Integer 0–5 |
| Current ease factor | `user_vocabulary.ease_factor` | DECIMAL(4,2) |
| Current interval days | `user_vocabulary.interval_days` | INTEGER |
| Current repetition count | `user_vocabulary.repetition_count` | INTEGER |

### 3.3 SM-2 Calculation Rules

```
IF quality_score < 3:
    reset:
      repetition_count = 0
      interval_days    = 1
      ease_factor      = unchanged (no penalty on ease factor for failed recall)
    next_review_at = now() + 1 day

IF quality_score == 3 (borderline recall):
    interval_days unchanged
    ease_factor unchanged
    next_review_at = now() + interval_days

IF quality_score >= 4 (good recall):
    IF repetition_count == 0:
        interval_days = 1
    ELSE IF repetition_count == 1:
        interval_days = 6
    ELSE:
        interval_days = round(interval_days * ease_factor)
    
    ease_factor = ease_factor + (0.1 - (5 - quality_score) * (0.08 + (5 - quality_score) * 0.02))
    ease_factor = max(1.30, ease_factor)
    
    repetition_count += 1
    next_review_at = now() + interval_days
```

### 3.4 First Review Behavior

When a word is first marked learned (via `POST /api/v1/vocabulary/{id}/learn`):
- A `user_vocabulary` record is created with `is_learned = true`, `interval_days = 1`, `ease_factor = 2.50`, `repetition_count = 0`
- `next_review_at` is set to `now() + 1 day`

### 3.5 Review Queue Ordering

`GET /api/v1/vocabulary/reviews` query:
```sql
SELECT * FROM user_vocabulary
WHERE user_id = ? AND is_learned = true AND next_review_at <= NOW()
ORDER BY next_review_at ASC
LIMIT ?
```

### 3.6 Idempotency

- Idempotency is strictly enforced via the `idempotency_key` (UUID) column on the `vocabulary_review_logs` table. This guarantees a single review interaction cannot be recorded twice, independent of time windows.
- The unique index on `(user_id, vocabulary_word_id)` prevents duplicate `user_vocabulary` records.
- The `last_interacted_at` column tracks when the last review occurred.

### 3.7 Transaction Boundaries

The SM-2 update is wrapped in a `DB::transaction()`.
**CRITICAL**: The gamification event emission (`UserActivityCompleted`) MUST be dispatched **after** the transaction is committed, ensuring that gamification listeners do not execute if the transaction fails or roll back.

---

## 4. Backend Files — Complete Inventory

### 4.1 Migrations (New)

| File | Responsibility |
|---|---|
| `[NEW] create_vocabulary_categories_table.php` | Creates `vocabulary_categories` |
| `[NEW] create_vocabulary_words_table.php` | Creates `vocabulary_words` |
| `[NEW] create_vocabulary_examples_table.php` | Creates `vocabulary_examples` |
| `[NEW] create_user_vocabulary_table.php` | Creates `user_vocabulary` (SM-2 state) |
| `[NEW] create_vocabulary_study_sessions_table.php` | Creates `vocabulary_study_sessions` |
| `[NEW] create_vocabulary_study_session_words_table.php` | Creates `vocabulary_study_session_words` |
| `[NEW] create_vocabulary_review_logs_table.php` | Creates `vocabulary_review_logs` |

### 4.2 Models (New)

| File | Responsibility |
|---|---|
| `[NEW] app/Models/VocabularyCategory.php` | Category entity, soft delete |
| `[NEW] app/Models/VocabularyWord.php` | Word entity, soft delete, morph alias `vocabulary` |
| `[NEW] app/Models/VocabularyExample.php` | Example sentences, child of VocabularyWord |
| `[NEW] app/Models/UserVocabulary.php` | SM-2 pivot, per-user word state |
| `[NEW] app/Models/VocabularyStudySession.php` | Study session aggregate root |
| `[NEW] app/Models/VocabularyStudySessionWord.php` | Study session word tracking |
| `[NEW] app/Models/VocabularyReviewLog.php` | Immutable review history log |

### 4.3 Repositories (New)

| File | Responsibility |
|---|---|
| `[NEW] app/Repositories/Vocabulary/VocabularyRepository.php` | Paginated listing with filters (category, difficulty, search); word detail fetch |
| `[NEW] app/Repositories/Vocabulary/UserVocabularyRepository.php` | `findOrCreate`, `lockForUpdate`, `getReviewQueue`, `save` |
| `[NEW] app/Repositories/Vocabulary/StudySessionRepository.php` | Manage `vocabulary_study_sessions` and `vocabulary_study_session_words` |
| `[MODIFY] app/Repositories/Gamification/DailyGoalRepository.php` | Add `findForDate(userId, date)` method |

### 4.4 Services (New / Modified)

| File | Responsibility |
|---|---|
| `[NEW] app/Services/Vocabulary/VocabularyService.php` | Browse, search, filter words |
| `[NEW] app/Services/Vocabulary/StudyService.php` | Handle session start/complete and `markLearned()` |
| `[NEW] app/Services/Vocabulary/ReviewService.php` | Handle `submitReview(userId, userVocabId, qualityScore, idempotencyKey)` |
| `[NEW] app/Services/Vocabulary/SM2Service.php` | Pure calculation service. |
| `[MODIFY] app/Services/Gamification/ProgressService.php` | Add `getVocabularyProgress(userId): array` |

### 4.5 DTOs (New)

| File | Responsibility |
|---|---|
| `[NEW] app/DTOs/Vocabulary/ReviewRequestDTO.php` | Immutable DTO: `readonly int $qualityScore, readonly string $idempotencyKey` |
| `[NEW] app/DTOs/Vocabulary/SM2ResultDTO.php` | Immutable DTO for SM-2 outputs |

### 4.6 Form Requests (New)

| File | Responsibility |
|---|---|
| `[NEW] app/Http/Requests/Vocabulary/SubmitReviewRequest.php` | Validates `quality_score` (0-5) and `idempotency_key` |
| `[NEW] app/Http/Requests/Vocabulary/CompleteStudySessionRequest.php` | Validates `status` and `word_ids` |

### 4.7 API Resources (New)

| File | Responsibility |
|---|---|
| `[NEW] app/Http/Resources/Vocabulary/VocabularyWordResource.php` | Word data + user SM-2 state |
| `[NEW] app/Http/Resources/Vocabulary/VocabularyCategoryResource.php` | Category list |
| `[NEW] app/Http/Resources/Vocabulary/UserVocabularyResource.php` | SM-2 state |
| `[NEW] app/Http/Resources/Vocabulary/VocabularyProgressResource.php` | Summary counts |
| `[NEW] app/Http/Resources/Vocabulary/StudySessionResource.php` | Session details |

### 4.8 Policies (New)

| File | Responsibility |
|---|---|
| `[NEW] app/Policies/Vocabulary/UserVocabularyPolicy.php` | Restrict updates to owner |
| `[NEW] app/Policies/Vocabulary/StudySessionPolicy.php` | Restrict updates to owner |

### 4.9 Controllers (New)

| File | Responsibility |
|---|---|
| `[NEW] app/Http/Controllers/Vocabulary/VocabularyController.php` | `index`, `categories`, `show` |
| `[NEW] app/Http/Controllers/Vocabulary/StudyController.php` | `learn`, `startSession`, `completeSession` |
| `[NEW] app/Http/Controllers/Vocabulary/ReviewController.php` | `index`, `store` |
| `[NEW] app/Http/Controllers/Vocabulary/VocabularyProgressController.php` | `index` |
| `[MODIFY] app/Http/Controllers/Gamification/DailyGoalController.php` | Repository refactor |

### 4.10 Events (New)

No new events are created. Vocabulary emits the existing `UserActivityCompleted` event with vocabulary-specific metadata.

### 4.11 Routes (Modified)

```php
// [MODIFY] routes/api.php — add inside auth:sanctum group

Route::prefix('vocabulary')->group(function () {
    Route::get('/', [VocabularyController::class, 'index']);
    Route::get('/categories', [VocabularyController::class, 'categories']);
    Route::get('/progress', [VocabularyProgressController::class, 'index']);
    Route::get('/reviews', [ReviewController::class, 'index']);
    Route::get('/{id}', [VocabularyController::class, 'show']);
    
    Route::post('/{id}/learn', [StudyController::class, 'learn']);
    Route::post('/{id}/reviews', [ReviewController::class, 'store']);
    
    Route::post('/study-sessions', [StudyController::class, 'startSession']);
    Route::patch('/study-sessions/{id}', [StudyController::class, 'completeSession']);
});
```

---

## 5. API Contract

### 5.1 `POST /api/v1/vocabulary/study-sessions`

| Property | Value |
|---|---|
| Method | POST |
| Auth | Authenticated |
| Response | `201 Created` returning `StudySessionResource` (with UUID ID) |

### 5.2 `PATCH /api/v1/vocabulary/study-sessions/{id}`

| Property | Value |
|---|---|
| Method | PATCH |
| Auth | Authenticated (Owner Only) |
| Path Param | `id` (UUID of the study session) |
| Body | `status: 'completed', word_ids: array` |
| Response | `200 OK` |
| Side Effects | Validates >= 5 words. Emits `UserActivityCompleted` for `vocab_study_session_completed`. |

*(Other endpoints as defined in `API_SPEC.md` match this phase plan exactly)*

---

## 6. Gamification Integration

### 6.1 Integration Rule

Vocabulary services must **never** directly call `XpService`, `StreakService`, `DailyGoalService`, or `AchievementService`. The gamification engine is accessed exclusively by emitting `UserActivityCompleted`.

### 6.2 When Vocabulary Emits `UserActivityCompleted`

| Trigger | `activityType` key | `sourceType` | `sourceId` | `metadata.goal_metric` |
|---|---|---|---|---|
| First time a word is marked learned | `vocab_learned` | `vocabulary` | `user_vocabulary.id` | `vocab` |
| Review submission with quality >= 3 | `vocab_review_passed` | `vocabulary` | `user_vocabulary.id` | (none) |
| Complete study session | `vocab_study_session_completed` | `vocabulary` | `vocabulary_study_sessions.id` | `vocab` |

> [!NOTE]
> Streak qualification requires completing a study session of >= 5 words. 
> The `vocab_study_session_completed` activity is emitted when a session is finalized. The orchestrator checks if `metadata.word_count >= 5` to decide if the activity qualifies for a streak increment. The DailyGoal target is incremented using `metadata.goal_metric = 'vocab'`.

### 6.3 XP Idempotency for Vocabulary

The existing `unq_xp_logs_idempotency` constraint on `xp_logs` uses `(user_id, activity_type_id, source_type, source_id)`. Since `source_id = user_vocabulary.id` and there is a unique `user_vocabulary` record per `(user_id, word_id)`, a word that has already been learned cannot produce a second `vocab_learned` XP award. This is guaranteed at the DB constraint level.

---

## 7. Vocabulary Quiz Integration

*(Skipping detailed quiz definitions for Phase 4; only the preparation service stub `VocabularyQuizPreparationService` will be added without exposing tables or endpoints)*

---

## 8. Frontend Files — Complete Inventory

### 8.1 TypeScript Types (New)

```
[NEW] src/types/vocabulary.ts
  - VocabularyCategory
  - VocabularyWord
  - UserVocabulary (SM-2 state)
  - VocabularyProgress
  - ReviewSubmitPayload
  - PaginatedVocabularyResponse
```

### 8.2 Zod Schemas (New)

```
[NEW] src/lib/schemas/vocabulary.schema.ts
  - VocabularyCategorySchema
  - VocabularyWordSchema
  - UserVocabularySchema
  - VocabularyProgressSchema
  - ReviewSubmitSchema (validates quality_score 0-5 on the frontend before API call)
```

### 8.3 API Service (New)

```
[NEW] src/services/vocabulary.service.ts
  - vocabularyKeys (query key factory — hierarchical)
  - getVocabularyList(filters)
  - getVocabularyCategories()
  - getWordDetail(id)
  - markWordLearned(id)
  - getReviewQueue(limit)
  - submitReview(wordId, qualityScore)
  - getVocabularyProgress()
  - invalidateVocabulary(queryClient)
```

### 8.4 Query Keys

```typescript
export const vocabularyKeys = {
  all: ['vocabulary'] as const,
  lists: () => [...vocabularyKeys.all, 'list'] as const,
  list: (filters: VocabularyFilters) => [...vocabularyKeys.lists(), filters] as const,
  categories: () => [...vocabularyKeys.all, 'categories'] as const,
  detail: (id: string) => [...vocabularyKeys.all, 'detail', id] as const,
  reviews: () => [...vocabularyKeys.all, 'reviews'] as const,
  progress: () => [...vocabularyKeys.all, 'progress'] as const,
};
```

### 8.5 React Query Hooks (New)

```
[NEW] src/hooks/useVocabulary.ts
  - useVocabularyList(filters) — infinite query with Load More
  - useVocabularyCategories()
  - useWordDetail(id)
  - useMarkLearned() — mutation
  - useReviewQueue(limit)
  - useSubmitReview() — mutation, invalidates progress + gamification keys on success
  - useVocabularyProgress()
```

### 8.6 Route Pages (New — Next.js App Router)

```
[NEW] src/app/vocabulary/page.tsx               — Vocabulary Home / List
[NEW] src/app/vocabulary/[id]/page.tsx          — Word Detail
[NEW] src/app/vocabulary/study/page.tsx         — Study Mode (new words)
[NEW] src/app/vocabulary/review/page.tsx        — Review Session
[NEW] src/app/vocabulary/progress/page.tsx      — Progress Summary
```

### 8.7 Feature Components (New)

```
[NEW] src/components/features/vocabulary/
  VocabularyListItem.tsx        — single word row in list view
  VocabularyFilters.tsx         — category + difficulty dropdowns + search input
  WordDetailCard.tsx            — full word breakdown (definition, examples, SM-2 state)
  StudyCard.tsx                 — flashcard component (front: word, back: definition)
  StudySession.tsx              — manages study card progression, emits bulk learn calls
  ReviewCard.tsx                — review flashcard with 0-5 quality selector
  ReviewSession.tsx             — manages review queue progression
  VocabularyProgressSummary.tsx — charts/stats for progress page
  LoadMoreButton.tsx            — shared pagination trigger
  VocabularyEmptyState.tsx      — no words, no reviews, etc.
  VocabularyErrorState.tsx      — error boundary fallback
  WordSkeleton.tsx              — loading skeleton for word cards
```

### 8.8 Zustand Store (New — UI State Only)

```
[NEW] src/stores/vocabulary.store.ts
  - activeStudyCardIndex: number        — current card position in study session
  - studySessionWordIds: string[]       — UUIDs of words in current session
  - activeReviewCardIndex: number       — current position in review queue
  - reviewQualityScores: Record<string, number>  — unsaved scores during session
  - (no server data, no word objects)
```

### 8.9 Cache Invalidation Strategy

| Action | Keys Invalidated |
|---|---|
| Mark word as learned | `vocabularyKeys.detail(id)`, `vocabularyKeys.reviews()`, `vocabularyKeys.progress()`, `gamificationKeys.all` |
| Submit review (pass) | `vocabularyKeys.reviews()`, `vocabularyKeys.progress()`, `vocabularyKeys.detail(id)`, `gamificationKeys.all` |
| Submit review (fail) | `vocabularyKeys.reviews()`, `vocabularyKeys.detail(id)` — no gamification invalidation |
| Load more list | No invalidation, new page appended to cache |

> [!NOTE]
> Do not use blanket `queryClient.invalidateQueries({ queryKey: vocabularyKeys.all })` for review score submission — gamification keys only invalidate on passing reviews where XP may have been awarded.

---

## 9. Seed Data

### 9.1 Difficulty Levels (database-driven)

| level_key | display_name | order_index |
|---|---|---|
| beginner | Beginner | 1 |
| elementary | Elementary | 2 |
| intermediate | Intermediate | 3 |
| upper_intermediate | Upper Intermediate | 4 |
| advanced | Advanced | 5 |
| proficient | Proficient | 6 |

### 9.2 Vocabulary Categories (minimum 6)

| name | description |
|---|---|
| Academic | Words used in academic and scholarly contexts |
| Business | Professional and corporate vocabulary |
| Travel | Words for travel, tourism, and geography |
| Science | Scientific terms and concepts |
| Culture & Arts | Cultural expressions, idioms, and arts |
| Technology | Modern technology and digital vocabulary |

### 9.3 Vocabulary Words (minimum 500)

- Distribution across all 6 difficulty levels
- At least 2 example sentences per word (stored in `vocabulary_examples`)
- Parts of speech: noun, verb, adjective, adverb, phrasal verb
- All definitions in clear English
- Seeded via a database-driven factory (no JSON files, no hardcoded frontend arrays)

### 9.4 XP Activity Types (seeded with Phase 4)

| type_key | display_name | default_xp_amount |
|---|---|---|
| vocab_learned | Vocabulary Word Learned | 10 |
| vocab_review_passed | Vocabulary Review Passed | 5 |
| vocab_study_session_completed | Study Session Completed | 20 |
| quiz_completed | Quiz Completed | 30 |

---

## 10. Implementation Order

The following sequence ensures no foreign-key or dependency violations:

```
Step 1:  Carry-forward fixes (DailyGoalController + DailyGoalRepository)
Step 2:  Migrations (categories → words → examples → user_vocabulary → sessions → session_words → review_logs)
Step 3:  Models (VocabularyCategory, VocabularyWord, VocabularyExample, UserVocabulary, VocabularyStudySession, VocabularyStudySessionWord, VocabularyReviewLog)
Step 4:  Update AppServiceProvider morph map (vocabulary alias → VocabularyWord)
Step 5:  Factories (for testing)
Step 6:  Seeders (DifficultyLevel → XpActivityType → Category → Words)
Step 7:  SM2Service (pure calculation, zero DB access)
Step 8:  Repositories (VocabularyRepository, UserVocabularyRepository, StudySessionRepository)
Step 9:  DTOs (ReviewRequestDTO, SM2ResultDTO)
Step 10: Services (VocabularyService, StudyService, ReviewService, ProgressService partial)
Step 11: Policies (UserVocabularyPolicy, StudySessionPolicy)
Step 12: Form Requests (SubmitReviewRequest, CompleteStudySessionRequest)
Step 13: API Resources (VocabularyWordResource, UserVocabularyResource, StudySessionResource, etc.)
Step 14: Controllers (VocabularyController, StudyController, ReviewController, ProgressController)
Step 15: Routes (register in api.php under auth:sanctum)
Step 16: Unit Tests (SM2ServiceTest)
Step 17: Feature Tests (all vocabulary feature tests)
Step 18: Frontend Types + Zod Schemas
Step 19: Frontend Service (vocabulary.service.ts)
Step 20: Frontend Hooks (useVocabulary.ts)
Step 21: Zustand Store (vocabulary.store.ts)
Step 22: UI Components (skeleton → word card → study → review → progress)
Step 23: Route Pages (list → detail → study → review → progress)
Step 24: Frontend TypeScript + lint verification
```

---

## 11. Risks & Mitigations

| Risk | Severity | Mitigation |
|---|---|---|
| SM-2 floating point rounding in PHP vs test expectations | Medium | Use `round()` explicitly; assert within delta in tests |
| `vocabulary/reviews` and `vocabulary/progress` routes conflict with `vocabulary/{id}` | High | Register static routes (`/reviews`, `/progress`, `/categories`) before the `/{id}` wildcard |
| Seeding 500 words causes timeout | Low | Use chunked inserts in seeder (100 at a time) |
| Vocabulary session quiz (Phase 8) dependency | Low | `VocabularyQuizPreparationService` prepared but not exposed; no quiz tables created |
| Zustand vocabulary store holding stale data | Medium | Store holds only index/position primitives, never word objects |
| N+1 queries on word list (user_vocabulary join) | High | Eager load `userVocabulary` with a WHERE clause scoped to `auth()->id()` using a custom scope or subquery |

---

## 12. Verification Checklist

### Backend
- [ ] All 7 migrations execute in correct dependency order
- [ ] UUIDv7 generated for all vocabulary records
- [ ] Morph map alias `vocabulary` points to `VocabularyWord`
- [ ] Idempotency: `vocabulary_review_logs.idempotency_key` prevents duplicate review XP
- [ ] `UserActivityCompleted` is dispatched *after* DB transactions commit
- [ ] Study session must contain >= 5 words for streak increment
- [ ] API routes match `API_SPEC.md`
- [ ] SM-2 algorithm operates exactly as defined
- [ ] Tests cover SM-2 logic and event emission

### Frontend
- [ ] `npm run build` succeeds without errors
- [ ] TypeScript strict mode passes
- [ ] ESLint passes
- [ ] Loading skeleton shown during word list fetch
- [ ] Empty state shown when no words match filters
- [ ] Error state shown on API failure with retry affordance
- [ ] Review session completes and navigates to result state
- [ ] Cache invalidation fires after learn mutation
- [ ] Gamification keys invalidated only on passing review
- [ ] Vocabulary store holds no server data (only indices)
- [ ] All route pages are protected (redirect unauthenticated users)

---

## 13. Definition of Done

Phase 4 is complete when:
1. All constraints are honored
2. No generic tools or duplicate modules are written
3. All code is tested and complies with frozen docs.
4. Study Sessions are successfully tracked and provide idempotency protection.
