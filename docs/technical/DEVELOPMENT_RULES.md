# BrainForge — Development Rules & Engineering Standards

> **Document Type:** Technical Documentation
> **Location:** `docs/technical/DEVELOPMENT_RULES.md`
> **Version:** 0.1.1
> **Last Updated:** 2026-07-31 (Frozen)
> **Status:** Frozen
> **Derived From:** `PROJECT_OVERVIEW.md`, `PRODUCT_REQUIREMENTS.md`, `FEATURES.md`, `DATABASE.md`, `API_SPEC.md`

## 1. Purpose
This document serves as the authoritative engineering handbook for BrainForge. It defines the coding conventions, architectural rules, and implementation practices that every developer and AI agent must strictly follow. It ensures the codebase remains modular, testable, and aligned with the frozen architecture.

## 2. Guiding Engineering Principles
1. **Database-First & API-First**: The database schema (`DATABASE.md`) and API contract (`API_SPEC.md`) are the undisputed sources of truth.
2. **Modularity**: Domains (e.g., Vocabulary, Gamification, Auth) remain independent and communicate via events, never through tight coupling.
3. **Consistency**: Code must look as if it was written by a single Senior Engineer. Adhere to PSR-12 for PHP and standard ESLint rules for React.
4. **No Magic Strings**: All constants, configuration keys, and repeated strings must be defined in constants, enums, or configuration files.
5. **Dependency Injection**: Dependencies must be injected, not instantiated inline.

## 3. Project Folder Structure
*   **Backend (`/api`)**: Standard Laravel 12 structure, heavily utilizing `/app/Services`, `/app/Events`, and `/app/Listeners`.
*   **Frontend (`/web`)**: Next.js 15 App Router structure. `src/components`, `src/hooks`, `src/services`, `src/types`.

## 4. Laravel Architecture Rules
*   **Fat Controllers are strictly prohibited.**
*   **Controllers orchestrate only:** They receive requests, call Form Requests for validation, pass DTOs to Services, and return API Resources.
*   **Strong Typing:** Use PHP 8.4+ strict types (`declare(strict_types=1);`), return type declarations, and typed properties throughout the codebase.

## 5. React Architecture Rules
*   Use Functional Components and React Hooks exclusively. No class components.
*   Strict TypeScript enforcement.
*   Presentational components must be decoupled from data fetching.

## 6. Domain-Driven Organization
Organize logic by Domain (e.g., `App\Services\Vocabulary`, `App\Events\Gamification`) rather than purely by technical function when the application scales. Keep related Events, Listeners, and Services cohesive.

## 7. Naming Conventions
*   **PHP Classes/Interfaces**: `PascalCase`.
*   **PHP Methods/Variables**: `camelCase`.
*   **DB Tables/Columns**: `snake_case`.
*   **React Components**: `PascalCase`.
*   **TypeScript Types/Interfaces**: `PascalCase`, prefixed with `I` for interfaces (optional, prefer simple nouns like `QuizSession`).

## 8. File Naming Standards
*   **PHP**: Matches class name exactly (`QuizService.php`).
*   **React**: Matches component name exactly (`QuizCard.tsx`).
*   **CSS**: Vanilla CSS modules (`QuizCard.module.css`).

## 9. Database Rules
*   **UUIDv7 used everywhere.** No mixing of BIGINT and UUID.
*   Soft Deletes used **only** where explicitly specified in `DATABASE.md`.
*   Use Enforced Laravel Morph Maps for all polymorphic relationships. No FQCN (Fully Qualified Class Names) in the database.

## 10. API Rules
*   Strict adherence to REST.
*   Consistent error and success envelopes matching `API_SPEC.md`.
*   Do not introduce endpoints that bypass domain business logic.

## 11. UUID Standards
*   Use PostgreSQL native `UUID` types.
*   Configure Laravel Models to use `HasUuids` (generating UUIDv7).
*   All frontend API calls must reference UUIDs.

## 12. Event-Driven Architecture Rules
*   Domains must communicate via events to avoid tight coupling.
*   Example: `VocabularyService` fires `VocabularyReviewed`. `GamificationListener` catches it and awards XP. `GamificationService` should not be injected into `VocabularyService`.
*   Events must be queued (async) unless synchronous execution is strictly required by the transaction boundary.

## 13. Service Layer Rules
*   **Business logic belongs exclusively in Services.**
*   Services should be single-responsibility.
*   Services can depend on Repositories or other Services via Dependency Injection.

## 14. Repository Usage Policy
*   Use the Repository pattern **only when it provides clear value** (e.g., complex querying, abstracting caching layers). Avoid unnecessary abstraction for simple CRUD where Eloquent suffices within the Service.

## 15. Eloquent Model Rules
*   **Models contain relationships, scopes, and lightweight mutators/accessors only.**
*   No complex business logic or external API calls inside Models.
*   Define `$casts` clearly.

## 16. Validation Rules
*   Use strict Form Requests for all incoming data validation.
*   Validation logic must never leak into Controllers or Services.
*   Map request payloads into Data Transfer Objects (DTOs) before passing to Services.

## 17. Authorization Rules
*   Use Laravel Policies and Gates.
*   Authorize via Form Requests (`authorize()` method) or immediately in the Controller before any business logic executes.

## 18. Error Handling Standards
*   Throw custom Exceptions for domain errors (e.g., `QuizAlreadyCompletedException`).
*   Catch exceptions globally in Laravel's Exception Handler and map them to standard API error envelopes.
*   Never expose stack traces to the frontend in production.

## 19. Logging Standards
*   Log all critical domain events (XP awarded, Auth failures, Goal completions).
*   Include Context (User ID, IP, Action) in logs.
*   Do not log PII (Personally Identifiable Information) or passwords.

## 20. Transaction Rules
*   **Transactions are strictly required for multi-table writes.**
*   Wrap DB writes in `DB::transaction()` inside Service classes to ensure atomicity (e.g., finalizing a quiz session and recording answers).

## 21. Performance Guidelines
*   Eager load Eloquent relationships to prevent N+1 query problems.
*   Index foreign keys and commonly queried columns (enforced by `DATABASE.md`).

## 22. Frontend Component Rules
*   Keep components small and single-purpose.
*   Extract complex logic into custom hooks.
*   Props must be strictly typed.

## 23. State Management Standards (TanStack Query)
*   Use TanStack Query for all asynchronous data fetching and caching.
*   Define standard query keys (e.g., `['vocabulary', { difficulty: 'beginner' }]`).
*   Use mutations for POST/PUT/PATCH/DELETE actions.
*   Avoid global state (Zustand/Redux) unless absolutely necessary for UI state (e.g., Theme, Sidebar toggle). Server state belongs in TanStack Query.

## 24. Form Handling Standards
*   Use `react-hook-form` paired with `zod` for frontend validation.
*   Zod schemas must mirror the Laravel backend validation rules exactly.

## 25. Styling Standards
*   Vanilla CSS Modules. Avoid global CSS pollution.
*   Follow the established Design System tokens (variables for colors, typography, spacing).
*   No inline styles.

## 26. Accessibility Standards
*   Semantic HTML (`<nav>`, `<main>`, `<article>`).
*   Ensure keyboard navigability and focus states.
*   ARIA labels on interactive elements lacking text.

## 27. Testing Standards
*   **Backend**: PestPHP or PHPUnit. Focus on Feature tests for API endpoints and Unit tests for Service logic.
*   **Frontend**: Vitest / React Testing Library for critical UI components.

## 28. Security Best Practices
*   Never trust user input. Validate everything.
*   Use CSRF protection (Sanctum).
*   Avoid SQL injection (always use Eloquent/Query Builder, never raw untyped queries).
*   Escape output (React handles XSS by default, ensure `dangerouslySetInnerHTML` is avoided).

## 29. Code Review Checklist
*   Does it follow `API_SPEC.md`?
*   Are transactions used for multi-writes?
*   Is business logic isolated in a Service?
*   Are there any N+1 query issues?
*   Is it strongly typed?

## 30. Git Workflow
*   Main branch (`main`) is always deployable.
*   Feature branches (`feat/module-name`).
*   Meaningful commit messages.

## 31. Documentation Requirements
*   Update `API_SPEC.md` if an endpoint is altered.
*   Update `DATABASE.md` if a schema changes.
*   Docblocks on complex Service methods.

## 32. AI Coding Rules
*   **Never bypass service classes.**
*   **Never write SQL in controllers.**
*   **Never duplicate validation rules.**
*   Reuse existing domain events instead of firing new ones for the same action.
*   Reuse existing services before creating new ones.
*   Prefer composition over duplication.
*   Keep methods small and single-purpose.
*   Preserve backward compatibility when modifying APIs.
*   Update documentation whenever architecture changes.

## 33. Prohibited Practices
*   No inline SQL.
*   No duplicated business logic (DRY principle).
*   No magic strings (use Enums/Constants).
*   No fat controllers.
*   No business logic in blade templates (not applicable, React used, but no business logic in React components either).

## 34. Future Expansion Guidelines
*   Code must be written with the assumption that a Mobile App will consume the same API.
*   Code must be written assuming horizontal scaling (stateless services, external queues).
