# BrainForge — Project Overview

> **Document Type:** Product Documentation
> **Location:** `docs/product/PROJECT_OVERVIEW.md`
> **Version:** 0.1.0
> **Last Updated:** 2026-07-30
> **Status:** Active — Single Source of Truth

---

## Table of Contents

1. [Vision](#1-vision)
2. [Mission](#2-mission)
3. [Purpose](#3-purpose)
4. [Goals](#4-goals)
5. [Target Users](#5-target-users)
6. [Project Scope — Version 1](#6-project-scope--version-1)
7. [Non-Goals — Explicitly Out of Scope](#7-non-goals--explicitly-out-of-scope)
8. [Architecture Overview](#8-architecture-overview)
9. [Success Metrics](#9-success-metrics)
10. [Guiding Principles](#10-guiding-principles)
11. [Future Vision](#11-future-vision)
12. [Cross-References](#12-cross-references)

---

## 1. Vision

**BrainForge is the platform where disciplined learners forge their intellect — one structured session at a time.**

The long-term vision of BrainForge is to become a comprehensive, modular learning platform that serves learners across a wide spectrum of cognitive disciplines. While the first version focuses on English proficiency and critical thinking, the platform is architected from the ground up to eventually accommodate:

- Language learning beyond English
- Mathematics and formal reasoning
- Programming and computer science fundamentals
- Civil service exam preparation
- Professional certifications
- Science and academic subjects

BrainForge is not a content library. It is a **learning engine** — a structured system that guides users through material, tests their understanding, measures their growth, and rewards their consistency.

The platform's identity is grounded in three beliefs:

1. **Mastery requires repetition.** Deep learning does not happen in one sitting. Spaced repetition, daily goals, and streak mechanics exist because they work.
2. **Measurement drives improvement.** What gets tracked gets improved. XP, progress charts, achievement systems, and quiz analytics are not decoration — they are feedback loops.
3. **Structure enables freedom.** A well-organized learning environment removes the friction of decision fatigue. Users arrive, know what to do next, and leave having made progress.

---

## 2. Mission

**To build a learning platform that is rigorous enough to produce real results and engaging enough to make returning feel natural.**

BrainForge's mission is to deliver:

- **Structured curriculum paths** that guide the user from foundational to advanced levels
- **Assessment systems** that surface genuine weaknesses without discouraging the learner
- **Gamification mechanics** that reinforce consistency rather than merely reward time spent
- **A clean, distraction-free interface** that respects the user's cognitive load
- **A database-driven content engine** that can be expanded without changing application architecture

BrainForge does not gamble on passive consumption. Every session should result in a measurable outcome: a word learned, a grammar rule understood, an IELTS task completed, or a quiz passed.

---

## 3. Purpose

### 3.1 Why BrainForge Exists

Existing learning platforms suffer from one or more of the following problems:

| Problem | Common Example |
|---|---|
| Content is too shallow | Vocabulary apps that only show flashcards without context |
| Progress is not meaningful | Platforms where XP is awarded for merely opening the app |
| Modules are siloed | IELTS apps that ignore general English fluency |
| Interface is cluttered | Feature overload that increases cognitive friction |
| No personalization | Everyone gets the same lesson regardless of their level |
| No long-term retention design | Users finish a course and forget everything |

BrainForge is purpose-built to address each of these. It connects vocabulary, grammar, reading, writing, and critical thinking into a unified learning environment where modules reinforce each other rather than compete for attention.

### 3.2 What Makes BrainForge Different

- **Unified XP Economy:** Progress in any module contributes to a single XP and leveling system. Learning grammar earns the same currency as solving an IQ puzzle.
- **Reusable Quiz Engine:** The same quiz infrastructure powers vocabulary drills, grammar exercises, reading comprehension questions, IELTS practice, logic puzzles, and general knowledge quizzes. No module is treated as special.
- **Everything from the Database:** No content is hardcoded. Every question, passage, vocabulary word, grammar rule, and achievement is stored in PostgreSQL and served dynamically.
- **Designed to Scale:** The architecture treats the platform as if it will serve thousands of users — even though it starts as a personal tool. This ensures no structural rewrite is needed for commercial growth.

---

## 4. Goals

### 4.1 Primary Goals (Version 1)

| # | Goal | Description |
|---|---|---|
| G-01 | Deliver a complete learning environment | All Version 1 modules are functional, connected, and consistent |
| G-02 | Enable structured vocabulary acquisition | Users can learn, review, and be tested on vocabulary with spaced repetition logic |
| G-03 | Enable grammar study and practice | Grammar rules are organized by topic and level, with exercises and explanations |
| G-04 | Support reading comprehension development | Passages of varying difficulty with associated comprehension questions |
| G-05 | Provide IELTS preparation structure | Task 1, Task 2 writing; reading and general comprehension tasks aligned with IELTS format |
| G-06 | Train logic and IQ skills | Sequential logic puzzles, pattern recognition, and abstract reasoning exercises |
| G-07 | Track user progress meaningfully | XP, level, streak, quiz scores, and module completion rates are all tracked and visualized |
| G-08 | Reward consistent behavior | Achievement system recognizes milestones, streaks, and learning volume |
| G-09 | Support user-created quizzes | The Quiz Builder allows users to create custom question sets from any module |
| G-10 | Deliver a high-quality, consistent UI | Every page follows the design system defined in `UI_GUIDELINES.md` |

### 4.2 Architectural Goals

| # | Goal | Description |
|---|---|---|
| A-01 | Database-driven content | Zero hardcoded content in the application layer |
| A-02 | RESTful API contract | All data flows through a versioned Laravel API |
| A-03 | Modular backend | Each learning domain is a separate Laravel module with its own controllers, services, and resources |
| A-04 | Type-safe frontend | No untyped data anywhere in the Next.js codebase |
| A-05 | Reusable quiz engine | One quiz infrastructure serves all modules |
| A-06 | Scalable schema design | The database schema is prepared for multi-user, multi-subject, and multi-language expansion |

---

## 5. Target Users

### 5.1 Primary User (Version 1)

BrainForge Version 1 is designed for **a single personal user** — a disciplined, self-directed learner who wants a professional-grade tool for their own development.

**Profile:**

| Attribute | Detail |
|---|---|
| Learning Goals | English proficiency, IELTS preparation, critical thinking |
| Technical Comfort | Comfortable with web applications |
| Motivation Style | Responds well to structure, measurement, and milestone rewards |
| Session Frequency | Daily or near-daily usage expected |
| Content Expectations | High-quality, accurate, contextually rich content |

### 5.2 Future Users (Post Version 1)

Although the architecture is built for scalability, user management and multi-tenancy are not in scope for Version 1. Future user profiles include:

| User Type | Description |
|---|---|
| Self-directed adult learner | Individuals studying English for career, travel, or exam purposes |
| IELTS candidate | Learners preparing for the IELTS Academic or General Training exam |
| Exam preparer | Users studying for civil service, professional certifications, or academic entrance exams |
| Corporate learner | Employees upskilling through employer-sponsored access |
| Educator | Teachers using BrainForge to assign content and monitor student progress |

> **Architecture Note:** Even though Version 1 serves one user, the `users` table, all foreign key relationships, and the API authentication layer are built as if the system is multi-user. This is a deliberate architectural decision to eliminate migration debt in future versions.

---

## 6. Project Scope — Version 1

The following modules and features are **in scope** for Version 1. No implementation should deviate from this list without a formal update to the roadmap and this document.

### 6.1 Modules

| # | Module | Description |
|---|---|---|
| M-01 | Authentication | Registration, login, logout, password management using Laravel Sanctum |
| M-02 | User Profile | Avatar, display name, bio, level, XP, join date, statistics summary |
| M-03 | Dashboard | Learning summary, daily goals widget, streak counter, quick-access cards, recent activity |
| M-04 | Daily Goals | Configurable daily targets (vocabulary, quizzes, XP) with progress tracking; resets at midnight in the user's configured local timezone |
| M-05 | XP System | Experience points earned across all modules; level thresholds; level-up events |
| M-06 | Streak System | Consecutive daily activity tracking relative to the user's configured local timezone; streak display; streak freeze mechanics |
| M-07 | Vocabulary | Word learning, definitions, examples, difficulty tiers, spaced repetition review |
| M-08 | Grammar | Topic-based grammar lessons with rules, examples, and exercises |
| M-09 | Reading | Reading passages with comprehension questions; difficulty levels |
| M-10 | Writing | Structured writing prompts with guided templates; response submission and history |
| M-11 | IELTS | Task 1 and Task 2 writing practice; reading comprehension aligned with IELTS format |
| M-12 | Quiz Library | Curated quizzes across all subjects; browsing, filtering, and attempt history |
| M-13 | Quiz Builder | User-created quizzes with custom questions, answers, and settings |
| M-14 | Quiz Attempts | Quiz session engine; answer tracking; result summary; review mode |
| M-15 | Progress | Charts and statistics for XP over time, accuracy rates, completion rates per module |
| M-16 | Achievements | Unlockable badges based on milestones, streaks, and learning volume |
| M-17 | IQ Training | Abstract reasoning, pattern recognition, and spatial thinking exercises |
| M-18 | Logic Training | Deductive reasoning, logical sequences, and critical thinking puzzles |
| M-19 | Settings | Account settings, password change, theme preferences, daily goal configuration, timezone preference |

### 6.2 Platform Scope

| Attribute | Value |
|---|---|
| Platform | Web only |
| Environment | Local development only |
| Users | Single user (multi-user architecture) |
| Languages | English (UI and content) |
| Deployment | Not in scope |

---

## 7. Non-Goals — Explicitly Out of Scope

The following are **deliberately excluded** from Version 1. They must not be implemented, referenced in the database schema, or anticipated in the API design beyond what is absolutely necessary for future compatibility.

| # | Feature | Reason for Exclusion |
|---|---|---|
| NG-01 | AI Chat | Requires LLM integration; out of scope for personal learning engine |
| NG-02 | AI Grammar Correction | Requires NLP pipeline; deferred to future AI integration phase |
| NG-03 | AI Essay Evaluation | Requires LLM scoring; complex and expensive; future feature |
| NG-04 | AI Question Generation | Deferred to future content automation phase |
| NG-05 | Listening Module | Requires audio content pipeline and player infrastructure |
| NG-06 | Speaking Module | Requires speech recognition and audio recording capabilities |
| NG-07 | Multiplayer / Competitive Mode | Requires real-time infrastructure (WebSockets); future phase |
| NG-08 | Marketplace | Requires content seller infrastructure; future commercial phase |
| NG-09 | Payments / Subscriptions | No billing system in scope |
| NG-10 | Notification System | Email, push, and in-app notifications deferred to future version |
| NG-11 | Social Feed / Community | Social features require moderation infrastructure |
| NG-12 | Admin Panel | Content management admin deferred to post-MVP phase |
| NG-13 | Mobile Application | Mobile app (React Native or similar) is planned but not in this version |
| NG-14 | Docker / Deployment | Environment management out of scope |
| NG-15 | CI/CD Pipeline | Automated testing and deployment out of scope |

> **Important:** While these features are excluded from Version 1, the architecture must not make decisions that would make adding them significantly harder later. The database schema in particular must be forward-compatible with multi-user, notification, and social features where possible.

---

## 8. Architecture Overview

### 8.1 System Architecture

BrainForge follows a clean client-server architecture with a clear separation between the presentation layer, API layer, and data layer.

```
┌─────────────────────────────────────────────────────────────┐
│                        FRONTEND                             │
│                      Next.js 15 App                         │
│  ┌─────────────┐  ┌──────────────┐  ┌──────────────────┐   │
│  │  App Router  │  │ TanStack Q.  │  │    Zustand Store │   │
│  │  (Pages/    │  │ (Server State│  │  (Client State)  │   │
│  │   Layouts)  │  │  & Caching)  │  │                  │   │
│  └─────────────┘  └──────────────┘  └──────────────────┘   │
│  ┌─────────────────────────────────────────────────────┐    │
│  │         shadcn/ui + Tailwind CSS v4 Design System   │    │
│  └─────────────────────────────────────────────────────┘    │
└────────────────────────────┬────────────────────────────────┘
                             │ HTTPS / REST API
                             │ Bearer Token (Sanctum)
┌────────────────────────────▼────────────────────────────────┐
│                         BACKEND                             │
│                       Laravel 12 API                        │
│  ┌─────────────┐  ┌──────────────┐  ┌──────────────────┐   │
│  │  Controllers │  │   Services   │  │   Repositories   │   │
│  │ (HTTP Layer) │  │ (Biz Logic)  │  │  (Data Access)   │   │
│  └─────────────┘  └──────────────┘  └──────────────────┘   │
│  ┌─────────────┐  ┌──────────────┐  ┌──────────────────┐   │
│  │Form Requests │  │  Resources   │  │    Policies      │   │
│  │(Validation)  │  │(API Response)│  │ (Authorization)  │   │
│  └─────────────┘  └──────────────┘  └──────────────────┘   │
└────────────────────────────┬────────────────────────────────┘
                             │ PDO / Eloquent ORM
┌────────────────────────────▼────────────────────────────────┐
│                         DATABASE                            │
│                       PostgreSQL 16                         │
│  ┌─────────────────────────────────────────────────────┐    │
│  │  Users │ Vocabulary │ Grammar │ Quizzes │ Progress   │    │
│  │  Achievements │ XP Logs │ Streak │ Daily Goals       │    │
│  └─────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────┘
```

### 8.2 Key Architectural Decisions

| Decision | Rationale |
|---|---|
| **Next.js 15 App Router** | Enables server components for SEO and performance without sacrificing interactivity where needed |
| **Laravel 12 REST API** | Provides a battle-tested, structured backend with opinionated conventions that enforce consistency |
| **PostgreSQL** | Relational integrity, JSON column support, full-text search capability, and proven scalability |
| **Laravel Sanctum** | Lightweight SPA authentication that works without the complexity of OAuth for the current scope |
| **TanStack Query** | Handles server state caching, background refetching, and loading/error states — eliminates manual data management boilerplate |
| **Zustand** | Minimal global client state (user session, UI preferences) without the overhead of Redux |
| **Zod** | Schema-first validation that works both for form validation (with React Hook Form) and API response parsing |
| **Recharts** | Declarative, React-native charting library that integrates cleanly with the component model |

### 8.3 Module Dependency Map

All modules share infrastructure but have **no cross-module business logic dependencies**:

```
Shared Infrastructure
├── Auth Module        (all modules depend on this)
├── XP System          (all learning modules emit XP events)
├── Quiz Engine        (Vocabulary, Grammar, Reading, IELTS, IQ, Logic all use this)
├── Progress Tracker   (all modules write progress data)
└── Achievement System (all modules trigger achievement checks)

Learning Modules (independent from each other)
├── Vocabulary
├── Grammar
├── Reading
├── Writing
├── IELTS
├── IQ Training
└── Logic Training

User Modules
├── Dashboard          (reads from all modules — read-only aggregation)
├── Profile
├── Daily Goals
└── Settings
```

### 8.4 Data Flow Pattern

Every user interaction follows a consistent pattern:

```
User Action
    ↓
Frontend Component
    ↓
Custom Hook (useVocabulary, useQuiz, etc.)
    ↓
TanStack Query (cache check → API call)
    ↓
Laravel Controller (HTTP routing)
    ↓
Form Request (validation)
    ↓
Service Layer (business logic)
    ↓
Repository Layer (database query)
    ↓
Eloquent Resource (response shaping)
    ↓
JSON Response
    ↓
Zod Schema Validation (frontend)
    ↓
UI State Update
    ↓
Component Re-render
```

---

## 9. Success Metrics

Success for BrainForge Version 1 is measured against the following criteria.

### 9.1 Functional Completeness

| Metric | Target |
|---|---|
| All 19 modules are implemented and functional | 100% |
| All API endpoints documented in `API_SPEC.md` are working | 100% |
| All database tables defined in `DATABASE.md` exist and are seeded | 100% |
| No hardcoded quiz questions, vocabulary, or grammar content in application code | 0 exceptions |
| All forms have validation on both frontend and backend | 100% |

### 9.2 Code Quality

| Metric | Target |
|---|---|
| No TypeScript `any` types (undocumented) | Zero |
| All API routes are protected by authentication middleware | 100% |
| No business logic in controllers | 0 exceptions |
| No duplicate UI components | 0 exceptions |
| All React components under 300 lines | Target (soft limit) |

### 9.3 User Experience

| Metric | Target |
|---|---|
| All pages load without visible layout shift | Required |
| All forms provide clear error messages | Required |
| All loading states are handled gracefully | Required |
| The application is usable on mobile screen widths (responsive) | Required |
| All interactive elements are keyboard-accessible | Required |

### 9.4 Documentation Quality

| Metric | Target |
|---|---|
| Every module has corresponding documentation | 100% |
| Every API endpoint is documented before implementation begins | Required |
| Every database table is documented before schema creation | Required |
| Changelog is updated with every meaningful change | Required |

---

## 10. Guiding Principles

These principles are not aspirational suggestions. They are architectural mandates that every implementation decision must respect.

### 10.1 Database is the Source of Truth

The database is not a persistence layer for the application — it **is** the application's content. If it is not in the database, it does not exist in BrainForge. This applies to vocabulary words, grammar explanations, reading passages, quiz questions, answer options, achievement definitions, XP thresholds, and daily goal templates.

### 10.2 The API is the Contract

The frontend and backend are two separate systems that communicate through a defined API contract. The frontend should never assume knowledge about the backend's internal structure, and the backend should never generate HTML or make frontend decisions. The API specification (`API_SPEC.md`) is the binding contract between them.

### 10.3 Modules are Independent

A vocabulary lesson should have no knowledge of a grammar lesson. The IQ training module should have no dependency on the IELTS module. Shared functionality — the quiz engine, XP system, progress tracker — exists as infrastructure that all modules consume, not as business logic baked into individual modules.

### 10.4 Scalability is Not Optional

Every table has timestamps. Every content record belongs to a category. Every question belongs to a pool. Every user action is logged. These are not premature optimizations — they are the minimum requirements for a system that must be able to grow without a foundational rewrite.

### 10.5 Consistency Over Preference

Naming conventions, folder structures, API response shapes, and component patterns are defined once and followed everywhere. Personal preference is not a justification for deviation. When a pattern exists, it is used. When a new pattern is needed, it is documented before it is implemented.

### 10.6 Documentation Precedes Code

No feature is implemented before its specification exists in the documentation. The implementation plan, database schema, and API contract for a feature are written and reviewed before the first line of code is produced. This rule applies regardless of how small or obvious the feature appears.

---

## 11. Future Vision

The following directions represent potential growth paths for BrainForge after Version 1. They are recorded here for architectural awareness — not for implementation.

| Horizon | Potential Addition | Architectural Consideration |
|---|---|---|
| Version 2 | Admin Panel for content management | Requires role-based access control (RBAC) foundation |
| Version 2 | Notification system (email and in-app) | Requires notification preference schema and queue system |
| Version 3 | AI-powered grammar correction and essay feedback | Requires integration with external LLM API; no schema changes needed |
| Version 3 | Listening and speaking modules | Requires audio file storage and CDN infrastructure |
| Version 4 | Multiplayer quiz battles | Requires WebSocket server (Laravel Reverb or Pusher) |
| Version 4 | Social features and leaderboards | Requires user relationship tables and privacy controls |
| Version 5 | Marketplace for community content | Requires content ownership, review, and monetization infrastructure |
| Version 5 | Mobile application (iOS and Android) | API is already mobile-ready; requires React Native frontend |
| Version 6 | Programming and mathematics modules | Modular architecture already supports adding new domains |
| Version 6 | Multi-language platform UI | Requires i18n infrastructure; no backend changes needed |

---

## 12. Cross-References

| Document | Relationship |
|---|---|
| [`PRODUCT_REQUIREMENTS.md`](PRODUCT_REQUIREMENTS.md) | Detailed functional and non-functional requirements derived from this overview |
| [`FEATURES.md`](FEATURES.md) | Feature-by-feature specification for all 19 modules |
| [`USER_FLOW.md`](USER_FLOW.md) | User journey maps for all primary use cases |
| [`DATABASE.md`](../architecture/DATABASE.md) | Complete database schema implementing the data model described here |
| [`API_SPEC.md`](../architecture/API_SPEC.md) | REST API endpoints implementing the API contract described here |
| [`UI_GUIDELINES.md`](../development/UI_GUIDELINES.md) | Design system implementing the UI philosophy described here |
| [`DEVELOPMENT_RULES.md`](../development/DEVELOPMENT_RULES.md) | Coding standards implementing the guiding principles described here |
| [`ROADMAP.md`](ROADMAP.md) | Phased delivery plan aligned with the goals listed in Section 4 |
| [`CHANGELOG.md`](../development/CHANGELOG.md) | Version history tracking progress toward the goals in this document |

---

*BrainForge Project Overview — v0.1.0*
*This document is the authoritative reference for project identity, scope, and direction.*
*All other documentation is derived from and must remain consistent with this file.*
