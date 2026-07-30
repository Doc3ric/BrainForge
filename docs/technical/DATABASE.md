# BrainForge — Database Schema Specification

> **Document Type:** Technical Documentation
> **Location:** `docs/technical/DATABASE.md`
> **Version:** 0.1.2
> **Last Updated:** 2026-07-31 (Amended & Frozen)
> **Status:** Frozen
> **Derived From:** `PRODUCT_REQUIREMENTS.md`, `FEATURES.md`, `PROJECT_OVERVIEW.md`

## 1. Purpose and Scope
This document specifies the PostgreSQL database architecture for BrainForge Version 1. It defines the physical schema, constraints, relationships, indexing strategies, and traceability back to the approved product requirements.

## 2. Database Design Principles
1. **Third Normal Form (3NF)**: Strictly enforced. No data duplication.
2. **Universal UUIDv7 Primary Keys**: 100% of tables (including lookup and pivot tables) use PostgreSQL native `UUID` columns generated as UUIDv7. No table mixes BIGINT foreign keys with UUID primary keys. This ensures absolute consistency across Eloquent relationships, API resource identifiers, and offline-first mobile synchronization.
3. **Polymorphic Relationship Strategy**: All polymorphic relationships strictly use an enforced Laravel Morph Map (e.g., `'vocabulary'`, `'grammar'`, `'quiz'`). Storing PHP class names (FQCN) in the database is strictly prohibited to prevent structural coupling.
4. **Data Integrity at the DB Level**: Enforcing business rules via Foreign Keys (FK), Unique constraints, and Check constraints.
5. **Soft Deletion**: Applied exclusively to learnable content entities (BR-CONTENT-004) via a `deleted_at` timestamp.
6. **UTC Standardization**: All `TIMESTAMP` columns use `TIMESTAMPTZ` and store data in UTC.

## 3. Naming Conventions
*   **Tables**: `snake_case`, plural (e.g., `writing_prompts`).
*   **Pivot/Junction Tables**: `snake_case`, singular entity names joined (e.g., `user_achievements`).
*   **Columns**: `snake_case`, descriptive.
*   **Primary Keys**: `id` (type: `UUID`).
*   **Foreign Keys**: `[singular_table_name]_id` (type: `UUID`).
*   **Booleans**: Prefixed with `is_` or `has_` (e.g., `is_completed`).

## 4. PostgreSQL Version Assumptions
*   **Version**: PostgreSQL 16.x or higher.
*   **Features utilized**: `JSONB`, `TIMESTAMPTZ`, `UUID`, constraints, partial indexes.

## 5. High-Level ERD Overview
The database follows a star-like schema radiating from the `users` table. 
*   **User Meta**: `users` (1) → (N) `xp_logs`, `user_achievements`, `daily_goal_tracking`, `streak_freeze_log`.
*   **Content**: `difficulty_levels` (1) → (N) all content tables.
*   **Progress**: `users` (1) → (N) `user_vocabulary`, `user_grammar_progress`, `quiz_sessions`, etc.
*   **Polymorphism**: `quiz_sessions` acts as a polymorphic bridge for all quiz-compatible domains (FR-QENG-001).

## 6. Domain Boundaries
1.  **Identity & Gamification**: Users, achievements, XP logging, daily goals, streak freezes.
2.  **Shared Engine**: Quizzes, sessions, answers.
3.  **Module Content**: Specific tables for Vocabulary, Grammar, Reading, Writing, IQ, Logic.

## 7. Complete Entity Inventory
`users`, `difficulty_levels`, `xp_activity_types`, `xp_logs`, `achievements`, `user_achievements`, `streak_freeze_log`, `daily_goal_tracking`, `vocabulary_categories`, `vocabulary_words`, `vocabulary_examples`, `user_vocabulary`, `grammar_topics`, `grammar_exercises`, `user_grammar_progress`, `reading_passages`, `reading_questions`, `user_reading_progress`, `writing_prompts`, `writing_submissions`, `quizzes`, `quiz_questions`, `quiz_sessions`, `quiz_session_answers`, `iq_exercises`, `logic_exercises`. Total: 26 tables.

## 8. Detailed Table Specifications
*The detailed specifications are categorized by domain in sections 11 through 19 below. Every table includes PK/FK, types, nullability, constraints, and indexes.*

## 9. Relationship Definitions
*   **1:N (One-to-Many)**: e.g., `users` to `xp_logs`, `difficulty_levels` to `vocabulary_words`.
*   **N:N (Many-to-Many)**: Resolved via junction tables (e.g., `users` and `vocabulary_words` via `user_vocabulary`).
*   **Polymorphic**: `quiz_sessions` uses `module_source` and `reference_id` to dynamically link to `quizzes` or ad-hoc sources. `quiz_session_answers` uses `questionable_type` and `questionable_id`.

## 10. Junction (Pivot) Tables
*All junction tables use UUIDs for both their Primary Key and all Foreign Keys.*
*   `user_achievements` (users ↔ achievements)
*   `user_vocabulary` (users ↔ vocabulary_words)
*   `user_grammar_progress` (users ↔ grammar_topics)
*   `user_reading_progress` (users ↔ reading_passages)

---

## 11. Lookup / Configuration Tables

### `difficulty_levels`
*Purpose: Centralized difficulty taxonomy (BR-CONTENT-005).*
| Column | Type | Constraints / Modifiers |
|---|---|---|
| `id` | UUID | PK (UUIDv7) |
| `level_key` | VARCHAR(50) | UNIQUE, NOT NULL (e.g., 'beginner') |
| `display_name` | VARCHAR(100) | NOT NULL |
| `order_index` | INTEGER | NOT NULL |

### `xp_activity_types`
*Purpose: Hardcoded XP event source definitions (BR-XP-006).*
| Column | Type | Constraints / Modifiers |
|---|---|---|
| `id` | UUID | PK (UUIDv7) |
| `type_key` | VARCHAR(100) | UNIQUE, NOT NULL |
| `display_name` | VARCHAR(100) | NOT NULL |
| `default_xp_amount` | INTEGER | NOT NULL, CHECK (> 0) |

---

## 12. Audit & Log Tables

### `xp_logs`
*Purpose: Immutable ledger of all XP awarded.*
| Column | Type | Constraints / Modifiers |
|---|---|---|
| `id` | UUID | PK (UUIDv7) |
| `user_id` | UUID | FK (users), NOT NULL |
| `activity_type_id` | UUID | FK (xp_activity_types), NOT NULL |
| `amount` | INTEGER | NOT NULL |
| `source_id` | UUID | NULLABLE (polymorphic tracking ID) |
| `created_at` | TIMESTAMPTZ | NOT NULL, DEFAULT NOW() |
*Indexes*: `idx_xp_logs_user_id`

### `streak_freeze_log`
*Purpose: Tracks grants and consumptions of streak freezes (BR-STREAK-008).*
| Column | Type | Constraints / Modifiers |
|---|---|---|
| `id` | UUID | PK (UUIDv7) |
| `user_id` | UUID | FK (users), NOT NULL |
| `action_type` | VARCHAR(20) | NOT NULL, CHECK (action_type IN ('grant', 'consume')) |
| `reason` | VARCHAR(255) | NULLABLE |
| `created_at` | TIMESTAMPTZ | NOT NULL, DEFAULT NOW() |
*Indexes*: `idx_freeze_log_user_id`

---

## 13. User Progress Tables

### `user_grammar_progress`
*Purpose: Tracks completion of grammar topics.*
| Column | Type | Constraints / Modifiers |
|---|---|---|
| `id` | UUID | PK (UUIDv7) |
| `user_id` | UUID | FK (users), NOT NULL |
| `grammar_topic_id` | UUID | FK (grammar_topics), NOT NULL |
| `is_completed` | BOOLEAN | NOT NULL, DEFAULT FALSE |
| `completed_at` | TIMESTAMPTZ | NULLABLE |
*Constraints*: UNIQUE (`user_id`, `grammar_topic_id`)

### `user_reading_progress`
*Purpose: Tracks reading passage completion and scores.*
| Column | Type | Constraints / Modifiers |
|---|---|---|
| `id` | UUID | PK (UUIDv7) |
| `user_id` | UUID | FK (users), NOT NULL |
| `reading_passage_id`| UUID | FK (reading_passages), NOT NULL |
| `score` | NUMERIC(5,2) | NULLABLE |
| `is_completed` | BOOLEAN | NOT NULL, DEFAULT FALSE |
| `completed_at` | TIMESTAMPTZ | NULLABLE |
*Constraints*: UNIQUE (`user_id`, `reading_passage_id`)

---

## 14. Gamification Tables

### `users`
*Purpose: Authentication and global user state.*
| Column | Type | Constraints / Modifiers |
|---|---|---|
| `id` | UUID | PK (UUIDv7) |
| `display_name` | VARCHAR(255) | NOT NULL |
| `email` | VARCHAR(255) | UNIQUE, NOT NULL |
| `password` | VARCHAR(255) | NOT NULL |
| `timezone` | VARCHAR(100) | NULLABLE (IANA TZ) |
| `theme` | VARCHAR(20) | DEFAULT 'system' |
| `total_xp` | BIGINT | NOT NULL, DEFAULT 0 |
| `level` | INTEGER | NOT NULL, DEFAULT 1 |
| `current_streak` | INTEGER | NOT NULL, DEFAULT 0 |
| `longest_streak` | INTEGER | NOT NULL, DEFAULT 0 |
| `daily_target_vocab`| INTEGER | NOT NULL, DEFAULT 10 |
| `daily_target_quizzes`| INTEGER| NOT NULL, DEFAULT 2 |
| `daily_target_xp` | INTEGER | NOT NULL, DEFAULT 50 |
| `created_at` | TIMESTAMPTZ | NOT NULL |
| `updated_at` | TIMESTAMPTZ | NOT NULL |

### `achievements`
*Purpose: Definitions for unlockable badges (BR-ACHIEVE-006).*
| Column | Type | Constraints / Modifiers |
|---|---|---|
| `id` | UUID | PK (UUIDv7) |
| `key` | VARCHAR(100) | UNIQUE, NOT NULL |
| `name` | VARCHAR(100) | NOT NULL |
| `description` | TEXT | NOT NULL |
| `condition_type` | VARCHAR(50) | NOT NULL (e.g., 'xp_total_reached') |
| `condition_value` | INTEGER | NOT NULL |
| `xp_reward` | INTEGER | NOT NULL, DEFAULT 0 |
| `icon_path` | VARCHAR(255) | NULLABLE |

### `user_achievements`
*Purpose: Links users to unlocked achievements.*
| Column | Type | Constraints / Modifiers |
|---|---|---|
| `id` | UUID | PK (UUIDv7) |
| `user_id` | UUID | FK (users), NOT NULL |
| `achievement_id` | UUID | FK (achievements), NOT NULL |
| `unlocked_at` | TIMESTAMPTZ | NOT NULL, DEFAULT NOW() |
*Constraints*: UNIQUE (`user_id`, `achievement_id`)

### `daily_goal_tracking`
*Purpose: Immutable snapshots of daily progress against targets (FR-GOAL-005).*
| Column | Type | Constraints / Modifiers |
|---|---|---|
| `id` | UUID | PK (UUIDv7) |
| `user_id` | UUID | FK (users), NOT NULL |
| `goal_date` | DATE | NOT NULL (calculated in user's TZ) |
| `target_vocab` | INTEGER | NOT NULL (Snapshot from users table) |
| `target_quizzes` | INTEGER | NOT NULL (Snapshot from users table) |
| `target_xp` | INTEGER | NOT NULL (Snapshot from users table) |
| `current_vocab` | INTEGER | NOT NULL, DEFAULT 0 |
| `current_quizzes` | INTEGER | NOT NULL, DEFAULT 0 |
| `current_xp` | INTEGER | NOT NULL, DEFAULT 0 |
| `is_completed` | BOOLEAN | NOT NULL, DEFAULT FALSE |
| `created_at` | TIMESTAMPTZ | NOT NULL |
| `updated_at` | TIMESTAMPTZ | NOT NULL |
*Constraints*: UNIQUE (`user_id`, `goal_date`)

---

## 15. Quiz System Tables (FR-QENG-001)

### `quizzes`
*Purpose: Library and Custom quiz headers (BR-QUIZ-007).*
| Column | Type | Constraints / Modifiers |
|---|---|---|
| `id` | UUID | PK (UUIDv7) |
| `source_type` | VARCHAR(50) | NOT NULL, CHECK (IN ('curated', 'user_created')) |
| `created_by` | UUID | FK (users), NULLABLE (null if curated) |
| `title` | VARCHAR(255) | NOT NULL |
| `description` | TEXT | NULLABLE |
| `deleted_at` | TIMESTAMPTZ | NULLABLE (BR-CONTENT-004) |
| `created_at` | TIMESTAMPTZ | NOT NULL |
| `updated_at` | TIMESTAMPTZ | NOT NULL |

### `quiz_questions`
*Purpose: Questions belonging to specific quizzes.*
| Column | Type | Constraints / Modifiers |
|---|---|---|
| `id` | UUID | PK (UUIDv7) |
| `quiz_id` | UUID | FK (quizzes), NOT NULL |
| `question_text` | TEXT | NOT NULL |
| `options_json` | JSONB | NOT NULL |
| `correct_answer` | VARCHAR(255) | NOT NULL |
| `explanation` | TEXT | NULLABLE |
| `order_index` | INTEGER | NOT NULL, DEFAULT 0 |
| `deleted_at` | TIMESTAMPTZ | NULLABLE |

### `quiz_sessions`
*Purpose: The active/completed state of a quiz attempt (BR-QUIZ-009).*
| Column | Type | Constraints / Modifiers |
|---|---|---|
| `id` | UUID | PK (UUIDv7) |
| `user_id` | UUID | FK (users), NOT NULL |
| `module_source` | VARCHAR(50) | NOT NULL (e.g., 'quiz_library', 'vocabulary', 'iq') |
| `reference_id` | UUID | NULLABLE (e.g., quiz_id if library quiz) |
| `status` | VARCHAR(20) | NOT NULL, CHECK (IN ('pending', 'completed', 'abandoned')) |
| `score` | NUMERIC(5,2) | NULLABLE |
| `started_at` | TIMESTAMPTZ | NOT NULL, DEFAULT NOW() |
| `completed_at` | TIMESTAMPTZ | NULLABLE |

### `quiz_session_answers`
*Purpose: Polymorphic table storing answers given during a session.*
| Column | Type | Constraints / Modifiers |
|---|---|---|
| `id` | UUID | PK (UUIDv7) |
| `quiz_session_id` | UUID | FK (quiz_sessions), NOT NULL |
| `questionable_type`| VARCHAR(100) | NOT NULL (e.g., 'vocabulary', 'iq', 'grammar' via Morph Map) |
| `questionable_id` | UUID | NOT NULL (ID of the original question) |
| `user_answer` | VARCHAR(255) | NOT NULL |
| `is_correct` | BOOLEAN | NOT NULL |
| `created_at` | TIMESTAMPTZ | NOT NULL |

---

## 16. Vocabulary SM-2 Tables

### `vocabulary_categories`
| Column | Type | Constraints / Modifiers |
|---|---|---|
| `id` | UUID | PK (UUIDv7) |
| `name` | VARCHAR(100) | UNIQUE, NOT NULL |
| `description` | TEXT | NULLABLE |

### `vocabulary_words`
| Column | Type | Constraints / Modifiers |
|---|---|---|
| `id` | UUID | PK (UUIDv7) |
| `category_id` | UUID | FK (vocabulary_categories), NOT NULL |
| `difficulty_id` | UUID | FK (difficulty_levels), NOT NULL |
| `word` | VARCHAR(100) | NOT NULL |
| `part_of_speech` | VARCHAR(50) | NOT NULL |
| `definition` | TEXT | NOT NULL |
| `deleted_at` | TIMESTAMPTZ | NULLABLE (BR-CONTENT-004) |

### `vocabulary_examples`
| Column | Type | Constraints / Modifiers |
|---|---|---|
| `id` | UUID | PK (UUIDv7) |
| `vocabulary_word_id`| UUID | FK (vocabulary_words), NOT NULL |
| `example_sentence` | TEXT | NOT NULL |

### `user_vocabulary` (SM-2 State)
*Purpose: Tracking spaced repetition per user per word (BR-SR-002).*
| Column | Type | Constraints / Modifiers |
|---|---|---|
| `id` | UUID | PK (UUIDv7) |
| `user_id` | UUID | FK (users), NOT NULL |
| `vocabulary_word_id`| UUID | FK (vocabulary_words), NOT NULL |
| `is_learned` | BOOLEAN | NOT NULL, DEFAULT FALSE |
| `ease_factor` | NUMERIC(4,2) | NOT NULL, DEFAULT 2.50 |
| `interval_days` | INTEGER | NOT NULL, DEFAULT 1 |
| `repetition_count` | INTEGER | NOT NULL, DEFAULT 0 |
| `next_review_at` | TIMESTAMPTZ | NULLABLE |
| `last_interacted_at`| TIMESTAMPTZ | NULLABLE |
*Constraints*: UNIQUE (`user_id`, `vocabulary_word_id`)

---

## 17. Writing & IELTS Tables

### `writing_prompts`
*Purpose: Shared prompts for general and IELTS tasks (BR-WRITE-003).*
| Column | Type | Constraints / Modifiers |
|---|---|---|
| `id` | UUID | PK (UUIDv7) |
| `module_type` | VARCHAR(50) | NOT NULL, CHECK (IN ('general', 'ielts_task1', 'ielts_task2')) |
| `difficulty_id` | UUID | FK (difficulty_levels), NOT NULL |
| `title` | VARCHAR(255) | NOT NULL |
| `instructions` | TEXT | NOT NULL |
| `image_url` | VARCHAR(255) | NULLABLE |
| `minimum_words` | INTEGER | NOT NULL |
| `deleted_at` | TIMESTAMPTZ | NULLABLE |

### `writing_submissions`
*Purpose: Tracking user essays and ensuring XP on first submission (BR-WRITE-001).*
| Column | Type | Constraints / Modifiers |
|---|---|---|
| `id` | UUID | PK (UUIDv7) |
| `user_id` | UUID | FK (users), NOT NULL |
| `writing_prompt_id`| UUID | FK (writing_prompts), NOT NULL |
| `content` | TEXT | NOT NULL |
| `word_count` | INTEGER | NOT NULL |
| `created_at` | TIMESTAMPTZ | NOT NULL |

---

## 18. Reading Tables

### `reading_passages`
| Column | Type | Constraints / Modifiers |
|---|---|---|
| `id` | UUID | PK (UUIDv7) |
| `difficulty_id` | UUID | FK (difficulty_levels), NOT NULL |
| `title` | VARCHAR(255) | NOT NULL |
| `content` | TEXT | NOT NULL |
| `reading_time_minutes`| INTEGER | NOT NULL |
| `deleted_at` | TIMESTAMPTZ | NULLABLE |

### `reading_questions`
| Column | Type | Constraints / Modifiers |
|---|---|---|
| `id` | UUID | PK (UUIDv7) |
| `reading_passage_id`| UUID | FK (reading_passages), NOT NULL |
| `question_type` | VARCHAR(50) | NOT NULL, CHECK (IN ('multiple_choice', 'true_false')) |
| `question_text` | TEXT | NOT NULL |
| `options_json` | JSONB | NULLABLE |
| `correct_answer` | VARCHAR(255) | NOT NULL |
| `explanation` | TEXT | NULLABLE |

---

## 19. IQ & Logic Tables

### `iq_exercises`
| Column | Type | Constraints / Modifiers |
|---|---|---|
| `id` | UUID | PK (UUIDv7) |
| `category` | VARCHAR(100) | NOT NULL |
| `difficulty_id` | UUID | FK (difficulty_levels), NOT NULL |
| `question_text` | TEXT | NOT NULL |
| `image_url` | VARCHAR(255) | NULLABLE |
| `options_json` | JSONB | NOT NULL |
| `correct_answer` | VARCHAR(255) | NOT NULL |
| `explanation` | TEXT | NOT NULL |
| `deleted_at` | TIMESTAMPTZ | NULLABLE |

### `logic_exercises`
| Column | Type | Constraints / Modifiers |
|---|---|---|
| `id` | UUID | PK (UUIDv7) |
| `category` | VARCHAR(100) | NOT NULL |
| `difficulty_id` | UUID | FK (difficulty_levels), NOT NULL |
| `question_text` | TEXT | NOT NULL |
| `options_json` | JSONB | NOT NULL |
| `correct_answer` | VARCHAR(255) | NOT NULL |
| `explanation` | TEXT | NOT NULL |
| `deleted_at` | TIMESTAMPTZ | NULLABLE |

---

## 20. Soft Delete Strategy
*   Implementation: `deleted_at` (TIMESTAMPTZ).
*   Rule: Content is never physically deleted. Queries for lists (e.g., `WHERE deleted_at IS NULL`) hide them. Foreign keys remain intact to preserve `user_reading_progress` or `quiz_sessions` data.

## 21. Cascading Delete / Update Rules
*   **ON DELETE CASCADE**: Junction tables (`user_achievements`, `user_vocabulary`) cascade if a `user_id` is deleted.
*   **ON DELETE RESTRICT**: Lookups (`difficulty_levels`, `xp_activity_types`) cannot be deleted if referenced.
*   **Content Deletion**: Handled via Soft Delete. No cascading physical deletes on content tables.

## 22. Transaction Boundaries
*   `Quiz Session Finalization`: Recording answers, calculating score, inserting `quiz_attempts` state, inserting `xp_logs`, and emitting events must occur within a single DB transaction.
*   `Daily Goal / Streak / Achievement Sync`: Occurs synchronously post-transaction in the application layer via events, wrapped in their own isolated transactions to prevent module failure if gamification logic fails.

## 23. Indexing Strategy
*   **Primary Keys**: Auto-indexed for UUID lookups.
*   **Foreign Keys**: Explicit non-unique B-tree indexes added to all `user_id`, `difficulty_id`, `quiz_id`, and `category_id` columns to prevent sequential scans during Eloquent relationship loading.
*   **Query-Optimized**: 
    *   `idx_user_vocab_review` (`user_id`, `next_review_at`)
    *   `idx_daily_goal_date` (`user_id`, `goal_date`)
    *   `idx_quiz_sessions_user` (`user_id`, `status`)
    *   `idx_write_prompts_type` (`module_type`)

## 24. Performance Considerations
*   `JSONB` used for options to avoid excessive 1:N table joins (e.g., `quiz_question_options` table is unnecessary).
*   Pagination (NFR-SCALE-005) dictates that `LIMIT` / `OFFSET` or cursor-based querying will be standard.
*   `xp_logs` volume will grow extremely fast. An index on `user_id, created_at` is mandatory.

## 25. Seed Data Requirements
*   `difficulty_levels`: Beginner, Intermediate, Advanced.
*   `xp_activity_types`: `vocabulary_study_session`, `quiz_attempt`, `writing_submission`, etc. (13 types from BR-XP-006).
*   `achievements`: At least 10 baseline achievements mapped to `condition_type` enum.
*   `content`: Baselines as defined in Assumption A-10.

## 26. Future Expansion Considerations
*   Polymorphic `quiz_session_answers` easily extends to support audio questions or drag-and-drop by altering the frontend, without DB schema changes.
*   Leaderboards can be introduced easily via the `total_xp` column.

## 27. Requirement Traceability Matrix

| Requirement ID | Relates To | Fulfilled By (Table/Column) |
|---|---|---|
| BR-XP-006 | Valid XP source types | `xp_activity_types` table |
| BR-STREAK-008 | Streak freeze math | `streak_freeze_log` (sum of grants - consumes) |
| BR-ACHIEVE-006 | Typed unlock conditions | `achievements.condition_type`, `condition_value` |
| FR-GOAL-005 | Daily goal isolation | `daily_goal_tracking` table |
| FR-GOAL-006 | User target configuration | `daily_goal_tracking.target_*` snapshot columns |
| BR-QUIZ-007 | Unified quiz entity | `quizzes.source_type` |
| BR-QUIZ-009 | Idempotent completions | `quiz_sessions.status` |
| BR-CONTENT-004 | Soft Deletion | `deleted_at` on 8 content tables |
| BR-CONTENT-005 | Centralized difficulty | `difficulty_levels` table |
| BR-CONTENT-006 | Multi-user progress | `user_vocabulary`, `user_grammar_progress`, `user_reading_progress` |
| BR-SR-002 | SM-2 variables | `user_vocabulary.ease_factor`, `interval_days`, etc. |
| BR-WRITE-003 | Shared writing structure | `writing_prompts.module_type` |
| FR-QENG-001 | Content-agnostic engine | `quiz_sessions`, polymorphic `quiz_session_answers` |
