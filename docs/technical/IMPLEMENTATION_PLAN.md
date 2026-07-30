# BrainForge — Master Implementation Plan

> **Document Type:** Technical Documentation
> **Location:** `docs/technical/IMPLEMENTATION_PLAN.md`
> **Version:** 0.1.0
> **Status:** Draft (Pending Review)
> **Derived From:** `PROJECT_OVERVIEW.md`, `PRODUCT_REQUIREMENTS.md`, `FEATURES.md`, `DATABASE.md`, `API_SPEC.md`, `DEVELOPMENT_RULES.md`

## 1. Development Philosophy
The execution of the BrainForge platform will follow a strict **Dependency-First Strategy**. Development must prioritize backend infrastructure, database schemas, and shared engines (like the Quiz Engine and Gamification Engine) before building the user interfaces that consume them. Under no circumstances should development prioritize UI order over architectural dependency order.

## 2. Repository Initialization

**Backend Stack (API)**
*   **Framework**: Laravel 12 (PHP 8.4)
*   **Database**: PostgreSQL
*   **Authentication**: Laravel Sanctum (SPA Cookie Auth)
*   **Identity**: UUIDv7 via `HasUuids`
*   **Testing**: PestPHP
*   **Quality Tools**: Laravel Pint (styling), Larastan (static analysis)
*   **Environment**: Docker (Laravel Sail recommended for local dev)

**Frontend Stack (Web)**
*   **Framework**: Next.js 16 (App Router)
*   **Library**: React 19
*   **Language**: TypeScript (Strict Mode)
*   **Styling**: Tailwind CSS
*   **Data Fetching**: TanStack Query (React Query)
*   **State Management**: Zustand (for UI state only)
*   **Forms & Validation**: React Hook Form + Zod

## 3. Folder Architecture

### Backend (`/api`)
```text
/app
  /Console        # Scheduled Tasks
  /DTOs           # Data Transfer Objects
  /Events         # Domain Events (QuizCompleted, VocabularyReviewed)
  /Http
    /Controllers  # API Controllers
    /Requests     # Form Requests (Validation)
    /Resources    # API JSON Resources
  /Listeners      # Gamification, XP, Streaks
  /Models         # Eloquent Models
  /Policies       # Authorization Rules
  /Repositories   # Data Access Layer
  /Services       # Core Business Logic
/database
  /migrations     # PostgreSQL schema
  /seeders        # Default data
/tests
  /Feature        # API Endpoint tests
  /Unit           # Service & Repository tests
```

### Frontend (`/web`)
```text
/src
  /app            # Next.js App Router (Pages & Layouts)
  /components
    /ui           # Reusable UI components (Buttons, Inputs)
    /features     # Domain-specific components
  /hooks          # Custom React Hooks
  /lib            # Utility functions & API clients
  /services       # TanStack Query logic
  /stores         # Zustand UI stores
  /types          # TypeScript interfaces
```

## 4. Development Milestones

### Phase 1: Project Initialization
*   **Goal**: Setup repositories, CI/CD, database connection, and basic scaffolding.
*   **Dependencies**: None.
*   **Deliverables**: Empty Laravel 12 and Next.js 16 apps running via Docker.
*   **Exit Criteria**: Both apps compile, test suites pass, database is reachable.
*   **Complexity**: Low
*   **Risks**: Environment mismatches across developer machines.

### Phase 2: Authentication
*   **Goal**: Implement User registration, login, logout via Sanctum.
*   **Dependencies**: Phase 1.
*   **Deliverables**: `/auth/*` API endpoints, Frontend Auth Context.
*   **Exit Criteria**: A user can register, log in, and view a protected dummy page.

### Phase 3: User Profile
*   **Goal**: User metadata management and avatar upload.
*   **Dependencies**: Phase 2.
*   **Deliverables**: `PATCH /profile`, UI for profile editing.
*   **Exit Criteria**: Profile updates correctly reflect in the database.

### Phase 4: Settings
*   **Goal**: Timezone, theme, and goal configurations.
*   **Dependencies**: Phase 2.
*   **Deliverables**: `PATCH /settings`, UI for configuration.
*   **Exit Criteria**: Target configurations successfully save and apply to the UI.

### Phase 5: Shared Infrastructure (Gamification)
*   **Goal**: Build the core gamification engines that other modules will trigger.
*   **Dependencies**: Phase 2, 3, 4.
*   **Deliverables**:
    *   Events: `XPAwarded`, `StreakIncremented`, `GoalUpdated`, `AchievementUnlocked`.
    *   Engines: XP Engine, Achievement Engine, Daily Goals, Streak Engine.
*   **Exit Criteria**: Emitting a test event correctly calculates XP, increments streaks, and updates daily goals synchronously via Listeners.
*   **Complexity**: High
*   **Risks**: Transaction boundary failures during multi-table writes.

### Phase 6: Quiz Session Engine
*   **Goal**: Build the polymorphic Quiz Engine (`FR-QENG-001`).
*   **Dependencies**: Phase 5.
*   **Deliverables**: `QuizSessionService`, polymorphic `quiz_sessions` endpoints.
*   **Exit Criteria**: A simulated quiz session can be started, answers recorded, and finalized, properly triggering Gamification events.
*   **Complexity**: Very High
*   **Risks**: Polymorphic relationship mapping errors.

### Phase 7: Vocabulary
*   **Goal**: Implement SM-2 algorithm and vocabulary library.
*   **Dependencies**: Phase 6.
*   **Deliverables**: SM-2 Service, Vocab Endpoints, Vocab UI.
*   **Exit Criteria**: Words can be marked learned and scheduled correctly based on SM-2 inputs.

### Phase 8: Grammar
*   **Goal**: Grammar topics and exercises.
*   **Dependencies**: Phase 5.
*   **Deliverables**: Grammar endpoints, Progress tracking.
*   **Exit Criteria**: Completing a grammar exercise marks it complete and awards XP.

### Phase 9: Reading
*   **Goal**: Reading comprehension passages and questions.
*   **Dependencies**: Phase 5.
*   **Deliverables**: Reading endpoints, Progress tracking.
*   **Exit Criteria**: Score calculation is accurate upon passage completion.

### Phase 10: Writing
*   **Goal**: General writing prompts and submission tracking.
*   **Dependencies**: Phase 5.
*   **Deliverables**: Writing endpoints, Submission Service.
*   **Exit Criteria**: First submission awards XP, subsequent submissions for the same prompt do not.

### Phase 11: IELTS
*   **Goal**: Specialized IELTS writing prompts (Task 1 & 2).
*   **Dependencies**: Phase 10 (extends Writing infrastructure).
*   **Deliverables**: IELTS specific UI and filtering.
*   **Exit Criteria**: IELTS prompts are cleanly separated from general prompts in the UI.

### Phase 12: Quiz Library
*   **Goal**: Curated static quizzes.
*   **Dependencies**: Phase 6.
*   **Deliverables**: Quiz catalog endpoints, UI catalog.
*   **Exit Criteria**: A user can browse curated quizzes and launch the Quiz Engine.

### Phase 13: Quiz Builder
*   **Goal**: Custom user-generated quizzes.
*   **Dependencies**: Phase 12.
*   **Deliverables**: `POST /quizzes`, Quiz creation UI.
*   **Exit Criteria**: A user can create a quiz, and it only appears in their personal library.

### Phase 14: IQ Training
*   **Goal**: Visual and pattern IQ exercises.
*   **Dependencies**: Phase 6 (Quiz Engine).
*   **Deliverables**: IQ endpoints, Image-based question UI.
*   **Exit Criteria**: IQ questions render correctly in the Quiz Engine.

### Phase 15: Logic Training
*   **Goal**: Text-based logic puzzles.
*   **Dependencies**: Phase 6.
*   **Deliverables**: Logic endpoints.
*   **Exit Criteria**: Logic puzzles integrate seamlessly with the Quiz Engine.

### Phase 16: Dashboard
*   **Goal**: Central aggregation of user progress.
*   **Dependencies**: All previous phases.
*   **Deliverables**: `/dashboard/summary` endpoint, main UI dashboard.
*   **Exit Criteria**: Dashboard displays real-time, accurate gamification data and dynamic targets.

### Phase 17: Progress
*   **Goal**: Historical data visualization.
*   **Dependencies**: Phase 16.
*   **Deliverables**: Historical XP/Goal charts.
*   **Exit Criteria**: Charts accurately reflect soft-deleted and historical data.

### Phase 18: Performance Optimization
*   **Goal**: N+1 query elimination, caching, indexing.
*   **Exit Criteria**: All API endpoints return within 200ms under load.

### Phase 19: Testing
*   **Goal**: Comprehensive test coverage.
*   **Exit Criteria**: 80%+ Backend Feature test coverage. Core UI component tests passing.

### Phase 20: Production Deployment
*   **Goal**: Go-live preparation.
*   **Exit Criteria**: CI/CD pipelines green, environment variables secured, deployed to staging.

---

## 5. Database Implementation Order
To prevent foreign key constraint violations, the database must be constructed in the following order:
1.  **Migrations**: Lookups (`difficulty_levels`, `xp_activity_types`) -> Users -> Gamification tables -> Content tables -> Polymorphic junction tables.
2.  **Seeders**: Lookups must be seeded first.
3.  **Factories**: Define factories for every model to facilitate testing.
4.  **Relationship Validation**: Verify all UUIDv7 FKs and Morph Maps in Tinker before proceeding to the Service layer.

---

## 6. Backend Implementation Order (Per Domain)
For every domain (e.g., Vocabulary, Gamification), follow this strict bottom-up order:
1.  **Models**: Define fields, casts, relationships, UUID config.
2.  **Repositories**: Data access and complex queries (if necessary).
3.  **Services**: Core business logic and transaction boundaries.
4.  **Policies**: Define authorization rules.
5.  **Events & Listeners**: Connect domain actions to gamification.
6.  **DTOs & Form Requests**: Define data structures and Zod-equivalent validation.
7.  **Controllers**: Orchestrate Requests -> DTOs -> Services.
8.  **API Resources**: Format the JSON output (envelopes).
9.  **Routes**: Expose the endpoints.
10. **Tests**: Write Pest Feature tests for the endpoints.

---

## 7. Frontend Implementation Order
1.  **Layouts**: App shell, Sidebar navigation, routing structure.
2.  **Authentication**: Login/Register forms, protected route wrappers.
3.  **Shared Components**: Buttons, Modals, Loaders, Form inputs.
4.  **Hooks & API Client**: Axios/Fetch wrappers with CSRF interceptors.
5.  **Feature Modules**: Building out Vocabulary, Quizzes, etc.
6.  **Dashboard & Charts**: Data visualization components.
7.  **Settings**: User configuration forms.
8.  **Accessibility (A11y)**: Focus states, ARIA audits.
9.  **Testing**: Vitest integration.

---

## 8. Testing Strategy
*   **Unit Tests (Pest)**: For complex Services (e.g., SM-2 calculation, XP calculations).
*   **Feature Tests (Pest)**: For all API endpoints. Must assert status codes, JSON structures, and database state changes.
*   **Integration Tests**: Validating Event/Listener cascades (e.g., ensuring a Quiz completion triggers XP and Goals).
*   **API Tests**: Automated Postman/Insomnia collections for manual verification.
*   **Frontend Tests (Vitest)**: Testing critical UI logic (e.g., Quiz timer, Score calculations).
*   **Manual QA & Regression**: Final visual and functional checks across devices.

---

## 9. Definition of Done
A module or phase is NOT complete until:
1.  Code is written and conforms to `DEVELOPMENT_RULES.md`.
2.  Database migrations and seeders are present.
3.  API endpoints strictly match `API_SPEC.md` envelopes.
4.  Feature tests are written and passing.
5.  Frontend components are visually complete and responsive.
6.  Zod frontend validation mirrors Laravel backend validation.
7.  Events successfully trigger cross-domain logic (e.g., Gamification).
8.  No N+1 query issues exist.

---

## 10. AI Development Rules
When future AI coding agents (or developers) implement this plan, they must:
*   **Never violate** `DEVELOPMENT_RULES.md` (e.g., no Fat Controllers).
*   **Never contradict** `DATABASE.md` schemas or UUID definitions.
*   **Never invent** endpoints outside of `API_SPEC.md`.
*   **Never change** the UUIDv7 strategy or mix with BIGINTs.
*   **Never bypass** Services for business logic.
*   **Never bypass** Repositories (if utilized in a domain).
*   **Never duplicate** business logic across Controllers.
*   **Never modify** frozen documentation without explicit human approval.

---

## 11. Risk Register

| Risk | Impact | Mitigation Strategy |
|---|---|---|
| **Transaction Boundary Failures** | High | Wrap multi-table Gamification logic (XP, Streaks) in `DB::transaction()`. Write Integration tests specifically for failure rollbacks. |
| **Polymorphic Coupling** | Medium | Strictly enforce Morph Maps in Laravel (`Relation::enforceMorphMap()`) on application boot to prevent FQCN database pollution. |
| **N+1 Query Explosions** | High | Use Laravel's `Model::preventLazyLoading()` in local/testing environments to catch missing eager loads immediately. |
| **UUID Performance** | Low | UUIDv7 natively prevents B-tree fragmentation, mitigating traditional UUID scalability concerns. |
| **SPA CSRF Auth Issues** | Medium | Standardize Axios interceptors early (Phase 2) to handle Sanctum cookie exchanges transparently before feature work begins. |

---

## 12. Freeze Summary
This Implementation Plan translates the frozen architecture (Database, APIs, Development Rules) into a sequential, actionable roadmap. By strictly enforcing a backend-first, dependency-driven order (Phases 1-6), the project mitigates the risk of UI features out-pacing infrastructure capabilities.

This document is pending Final Architectural Review. Once frozen, the repositories can be initialized and coding can commence at Phase 1.
