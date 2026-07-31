# BrainForge — API Specification

> **Document Type:** Technical Documentation
> **Location:** `docs/technical/API_SPEC.md`
> **Version:** 0.1.1
> **Last Updated:** 2026-07-31 (Amended & Frozen)
> **Status:** Frozen
> **Derived From:** `PRODUCT_REQUIREMENTS.md`, `FEATURES.md`, `DATABASE.md`

## 1. Purpose
This document serves as the definitive API contract between the Laravel 12 backend and the React frontend. It outlines RESTful standards, UUID constraints, pagination, authentication, and comprehensive endpoint specifications.

## 2. API Design Principles
1. **RESTful Adherence**: Resources are represented as nouns. HTTP verbs dictate the action.
2. **UUID-First**: All resource identifiers exposed to the frontend are UUIDv7. No internal integer IDs are exposed.
3. **Stateless (mostly)**: Uses Laravel Sanctum SPA cookie authentication.
4. **Predictable Envelopes**: Consistent success and error response formats.
5. **Event-Driven Traceability**: API actions trigger domain events asynchronously where defined.

## 3. Versioning Strategy
*   URL-based versioning: `/api/v1/...`
*   Breaking changes require a new version namespace (`/api/v2/...`).

## 4. Authentication & Authorization
*   **Authentication**: Laravel Sanctum via HTTP-only, secure cookies (CSRF token exchange).
*   **Authorization**: Handled via Laravel Policies/Gates. Endpoints are documented as `Public`, `Authenticated`, or `Owner Only`.

## 5. Request Lifecycle
`React Client -> Sanctum CSRF -> API Route -> FormRequest Validation -> Controller -> Service Action -> Repository -> DB -> API Resource -> React Client`

## 6. Standard Headers
*   **Request**: `Accept: application/json`, `Content-Type: application/json`, `X-XSRF-TOKEN` (for mutative requests).
*   **Response**: `Content-Type: application/json`.

## 7. URL Conventions
*   Lowercase, kebab-case (e.g., `/api/v1/daily-goals`).
*   Nested routes strictly for child relationships (e.g., `/api/v1/quizzes/{quiz_id}/questions`).

## 8. HTTP Method Standards
*   `GET`: Retrieve resources.
*   `POST`: Create resources or submit actions (e.g., auth).
*   `PUT`/`PATCH`: Update resources (PATCH preferred for partials and state changes like marking complete).
*   `DELETE`: Soft-delete resources.

## 9. Status Code Standards
*   `200 OK`: Successful GET, PUT, PATCH, DELETE (if returning data).
*   `201 Created`: Successful POST.
*   `204 No Content`: Successful DELETE (if returning nothing).
*   `401 Unauthorized`: Unauthenticated.
*   `403 Forbidden`: Authenticated but unauthorized (wrong owner).
*   `404 Not Found`: UUID does not exist or is soft-deleted.
*   `422 Unprocessable Entity`: Validation failure.
*   `429 Too Many Requests`: Rate limit exceeded.
*   `500 Internal Server Error`: Backend exception.

## 10. Standard Success Response Envelope (NFR-API-001)
```json
{
  "data": { ... },
  "meta": { "timestamp": "2026-07-31T00:00:00Z" },
  "links": { "self": "/api/v1/resource/uuid" }
}
```

## 11. Standard Error Response Envelope (NFR-API-002)
```json
{
  "message": "The given data was invalid.",
  "errors": {},
  "status": 422
}
```

## 12. Validation Error Format
```json
{
  "message": "Validation failed.",
  "errors": {
    "email": ["The email has already been taken."],
    "password": ["The password must be at least 8 characters."]
  },
  "status": 422
}
```

## 13. Pagination Standard (NFR-SCALE-005)
All list endpoints use this envelope in `meta` and `links`.
*   **Query**: `?page=1&per_page=20` (max 100).
*   **Response**:
```json
{
  "data": [ ... ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 20,
    "total": 95,
    "has_more": true
  },
  "links": { "next": "/api/v1/resource?page=2", "prev": null }
}
```

## 14. Filtering Standard
Query parameters correspond exactly to resource attributes or known scopes. E.g., `?difficulty_id=uuid`.

## 15. Sorting Standard
`?sort=created_at` (Ascending) | `?sort=-created_at` (Descending).

## 16. Searching Standard
`?q=search-term`. Applies to indexed text columns (e.g., vocabulary word, quiz title).

## 17. UUID Standards
100% UUIDv7 resource identifiers. BIGINT configuration keys are strictly internal.

## 18. Rate Limiting Strategy
*   Global: 60 requests per minute per IP.
*   Auth: 5 attempts per minute per IP.
*   Quiz Session Submissions: 120 requests per minute per User.

## 19. Idempotency Rules
*   Submitting the same Quiz Session Complete request twice returns `200 OK` with the existing score (BR-QUIZ-009).
*   Marking vocabulary as learned twice returns `200 OK` (no-op).

## 20. File Upload Standards
Avatar uploads via `POST /api/v1/profile/avatar`. `multipart/form-data`. Max 2MB, JPG/PNG/WEBP.

## 21. Soft Delete Behavior
`DELETE` endpoints set `deleted_at`. `GET` endpoints hide them automatically.

## 22. API Security Guidelines
*   All routes under `/api/v1` except `/auth/login` and `/auth/register` enforce the `auth:sanctum` middleware.
*   Resource ownership validated via Laravel Policies (e.g., User cannot update another User's Quiz Builder quiz).

---

## 23. Endpoint Inventory

**Auth & Core (M-01, M-02, M-19, M-03)**
*   `POST /api/v1/auth/register`
*   `POST /api/v1/auth/login`
*   `POST /api/v1/auth/logout`
*   `GET /api/v1/profile`
*   `PATCH /api/v1/profile`
*   `GET /api/v1/settings`
*   `PATCH /api/v1/settings`
*   `PUT /api/v1/settings/password`
*   `GET /api/v1/dashboard/summary`

**Gamification (M-04, M-05, M-06, M-15, M-16)**
*(Note: Streak Freeze consumption is handled synchronously via backend scheduled tasks/events; no frontend endpoint is required).*
*   `GET /api/v1/daily-goals`
*   `GET /api/v1/streaks`
*   `GET /api/v1/achievements`
*   `GET /api/v1/progress`
*   `GET /api/v1/xp/history`

**Learning Modules (M-07, M-08, M-09, M-10, M-11)**
*   `GET /api/v1/vocabulary`
*   `GET /api/v1/vocabulary/categories`
*   `GET /api/v1/vocabulary/progress`
*   `GET /api/v1/vocabulary/reviews`
*   `GET /api/v1/vocabulary/{id}`
*   `POST /api/v1/vocabulary/{id}/learn`
*   `POST /api/v1/vocabulary/{id}/reviews`
*   `POST /api/v1/vocabulary/study-sessions`
*   `PATCH /api/v1/vocabulary/study-sessions/{id}`
*   `GET /api/v1/grammar`
*   `POST /api/v1/grammar/{id}/completions`
*   `GET /api/v1/reading`
*   `POST /api/v1/reading/{id}/completions`
*   `GET /api/v1/writing/prompts`
*   `GET /api/v1/writing/submissions`
*   `POST /api/v1/writing/prompts/{id}/submissions`

**Quiz Engine & Library (M-12, M-13, M-14, M-17, M-18)**
*   `GET /api/v1/quizzes`
*   `POST /api/v1/quizzes` (Quiz Builder)
*   `PATCH /api/v1/quizzes/{id}`
*   `DELETE /api/v1/quizzes/{id}`
*   `POST /api/v1/quiz-sessions`
*   `GET /api/v1/quiz-sessions/{id}/questions`
*   `POST /api/v1/quiz-sessions/{id}/answers`
*   `PATCH /api/v1/quiz-sessions/{id}` (Finalize session)
*   `GET /api/v1/iq-exercises`
*   `GET /api/v1/logic-exercises`

---

## 24-33. Detailed Endpoint Specifications
*(Grouping sections 24 through 33 for each critical endpoint to avoid redundancy)*

### 1. Auth & Core

#### `POST /api/v1/auth/register`
*   **Purpose**: Register a new user account.
*   **Auth**: Public
*   **Request Body**: `email` (string, required, email, max:255), `password` (string, required, min:8), `display_name` (string, required, max:255)
*   **Validation**: Unique email in `users` table.
*   **Success**: `201 Created` with User DTO.
*   **Error**: `422 Unprocessable Entity`
*   **DB Tables**: `users`, `daily_goal_tracking` (creates defaults).
*   **Events**: `UserRegistered`.

#### `GET /api/v1/dashboard/summary`
*   **Purpose**: Aggregate data for the Dashboard.
*   **Auth**: Authenticated
*   **Response**: `200 OK` returning User DTO + nested XP, Level, Current Streak, Freeze Balance, and Today's Daily Goals progress.
*   **DB Tables**: `users`, `daily_goal_tracking`, `streak_freeze_log`.

### 2. Gamification

#### `GET /api/v1/daily-goals`
*   **Purpose**: Get current day's goal targets and progress (FR-GOAL-003).
*   **Auth**: Authenticated
*   **Response**: `200 OK` returning DailyGoalTracking DTO (includes `target_vocab`, `current_vocab`, etc.).

### 3. Vocabulary (M-07)

#### `GET /api/v1/vocabulary`
*   **Purpose**: Fetch paginated vocabulary words.
*   **Auth**: Authenticated
*   **Query Params**: `?q=`, `?difficulty_id=`, `?category_id=`, `?review_due=true|false`
*   **Response**: `200 OK` (Paginated Word DTOs + User SM-2 state if known).

#### `GET /api/v1/vocabulary/categories`
*   **Purpose**: List all active vocabulary categories.
*   **Auth**: Authenticated
*   **Response**: `200 OK` Collection of `VocabularyCategoryResource`

#### `GET /api/v1/vocabulary/progress`
*   **Purpose**: Get the authenticated user's vocabulary progress summary.
*   **Auth**: Authenticated
*   **Response**: `200 OK` `VocabularyProgressResource` (total, learned, due_for_review, new)

#### `GET /api/v1/vocabulary/reviews`
*   **Purpose**: Get the user's SM-2 review queue.
*   **Auth**: Authenticated
*   **Query Params**: `limit` (default 20, max 100)
*   **Response**: `200 OK` Collection of `UserVocabularyResource` ordered by `next_review_at`

#### `GET /api/v1/vocabulary/{id}`
*   **Purpose**: Get a single word's full details.
*   **Auth**: Authenticated
*   **Path Params**: `id` (UUIDv7 of word)
*   **Response**: `200 OK` `VocabularyWordResource` with `user_vocabulary` state

#### `POST /api/v1/vocabulary/{id}/learn`
*   **Purpose**: Mark a word as learned for the first time.
*   **Auth**: Authenticated
*   **Path Params**: `id` (UUIDv7 of word)
*   **Response**: `200 OK` (idempotent repeat) or `201 Created` (first time) `UserVocabularyResource`
*   **Side Effects**: Emits `UserActivityCompleted` (vocab_learned) on first learn

#### `POST /api/v1/vocabulary/{id}/reviews`
*   **Purpose**: Submit SM-2 grade for a word (BR-SR-001). Creates a review resource.
*   **Auth**: Authenticated
*   **Path Params**: `id` (UUIDv7 of word).
*   **Request Body**: `idempotency_key` (UUID, required), `quality_score` (integer, 0-5, required).
*   **Success**: `200 OK` (Returns updated SM-2 ease factor, interval, next review date).
*   **DB Tables**: `user_vocabulary`, `vocabulary_review_logs`.
*   **Events**: `UserActivityCompleted(vocab_review_passed)` on quality >= 3.

#### `POST /api/v1/vocabulary/study-sessions`
*   **Purpose**: Initialize an active study session.
*   **Auth**: Authenticated
*   **Response**: `201 Created` with UUIDv7 identifier.
*   **DB Tables**: `vocabulary_study_sessions`.

#### `PATCH /api/v1/vocabulary/study-sessions/{id}`
*   **Purpose**: Complete a study session.
*   **Auth**: Authenticated, Owner Only
*   **Path Params**: `id` (UUIDv7 of study session)
*   **Request Body**: `status` (string, must be "completed"), `word_ids` (array of UUIDs)
*   **Success**: `200 OK`
*   **DB Tables**: `vocabulary_study_sessions`, `vocabulary_study_session_words`
*   **Events**: Emits `UserActivityCompleted(vocab_study_session_completed)`

### 4. Writing & IELTS (M-10, M-11)

#### `POST /api/v1/writing/prompts/{id}/submissions`
*   **Purpose**: Submit an essay for a general or IELTS prompt (BR-WRITE-001).
*   **Auth**: Authenticated
*   **Request Body**: `content` (string, required, min:1 char).
*   **Success**: `201 Created` (Submission DTO).
*   **Events**: `WritingSubmitted` (Awards XP only if first submission for this prompt).

### 5. Quiz Engine (M-14) (FR-QENG-001)

#### `POST /api/v1/quiz-sessions`
*   **Purpose**: Initialize a stateful quiz attempt.
*   **Auth**: Authenticated
*   **Request Body**: `module_source` (enum: 'vocabulary', 'grammar', 'quiz_library', 'quiz_builder', 'iq', 'logic'), `reference_id` (UUID, optional).
*   **Success**: `201 Created` (QuizSession DTO).
*   **DB Tables**: `quiz_sessions`.

#### `POST /api/v1/quiz-sessions/{id}/answers`
*   **Purpose**: Record an answer (locked in).
*   **Auth**: Authenticated, Owner Only.
*   **Request Body**: `questionable_id` (UUID), `user_answer` (string).
*   **Success**: `200 OK`.
*   **DB Tables**: `quiz_session_answers`.

#### `PATCH /api/v1/quiz-sessions/{id}`
*   **Purpose**: Finalize quiz, calculate score, award XP.
*   **Auth**: Authenticated, Owner Only.
*   **Request Body**: `status` (string, must be "completed").
*   **Success**: `200 OK` (Returns final score, XP awarded).
*   **Edge Case**: Idempotent. If status == 'completed', returns existing score without firing events (BR-QUIZ-009).
*   **Events**: `QuizCompleted` (Triggers XP, Daily Goals, Streak).

---

## 34. Requirement Traceability Matrix

| API Endpoint | Relates To FR / BR | Fulfills |
|---|---|---|
| `POST /auth/register` | FR-AUTH-001 | User Account Creation |
| `GET /dashboard/summary` | FR-DASH-002, 003, 004 | Dashboard Data Aggregation |
| `PATCH /settings` | FR-SETTINGS-009, FR-GOAL-006 | Timezone & Goal configuration |
| `POST /vocabulary/{id}/reviews` | BR-SR-001 | SM-2 Algorithm Updates |
| `POST /writing/prompts/{id}/submissions`| BR-WRITE-001, BR-WRITE-003 | IELTS / Writing First-Submit XP |
| `POST /quiz-sessions` | FR-QENG-001 | Unified Quiz Engine Start |
| `PATCH /quiz-sessions/{id}` | BR-QUIZ-009 | Idempotent Session Finalization |
| `GET /*` (All lists) | NFR-SCALE-005 | Standardized API Pagination |
| `*` (All Envelopes) | NFR-API-001, 002 | Success/Error JSON formatting |
