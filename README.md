# BrainForge

> **A modern, production-quality web learning platform built for English proficiency, IELTS preparation, critical thinking, and general knowledge mastery.**

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Project Status](#2-project-status)
3. [Tech Stack](#3-tech-stack)
4. [Repository Structure](#4-repository-structure)
5. [Getting Started](#5-getting-started)
6. [Documentation Overview](#6-documentation-overview)
7. [Core Principles](#7-core-principles)
8. [Contributing Guidelines](#8-contributing-guidelines)
9. [Versioning](#9-versioning)
10. [License](#10-license)

---

## 1. Introduction

**BrainForge** is a structured, gamified learning platform designed to sharpen the mind across multiple disciplines:

- **English Proficiency** — Vocabulary acquisition, grammar mastery, and reading comprehension
- **IELTS Preparation** — Task-based practice aligned with real exam formats
- **Critical Thinking & Logic** — IQ-style exercises, pattern recognition, and logical reasoning
- **Writing Practice** — Structured prompts with guided feedback scaffolding
- **General Knowledge** — Broad quiz library covering diverse domains

BrainForge is architected as a **professional SaaS application** despite being used for personal learning. The reason is intentional: the codebase must remain scalable, maintainable, and ready for future commercial growth without requiring fundamental rewrites.

The platform operates on a **motivation loop** modeled on proven gamification principles:

```
Learn → Practice → Quiz → Earn XP → Level Up → Unlock Achievements → Streak → Repeat
```

Everything in BrainForge is database-driven. There are no hardcoded questions, static JSON files, or mock data in production. All content — vocabulary, grammar rules, reading passages, quiz questions, achievements, and daily goals — is served from PostgreSQL through a structured REST API.

---

## 2. Project Status

| Version | Status      | Description                              |
|---------|-------------|------------------------------------------|
| v0.1.0  | 🟡 Planning  | Documentation phase — architecture and design |
| v0.2.0  | ⬜ Upcoming  | Backend foundation — database and auth   |
| v0.3.0  | ⬜ Upcoming  | Frontend foundation — layout and routing |
| v1.0.0  | ⬜ Upcoming  | Full MVP with all Phase 1 features       |

> See [`docs/architecture/07_ROADMAP.md`](docs/architecture/07_ROADMAP.md) for the complete phased delivery plan.

---

## 3. Tech Stack

### Frontend

| Technology         | Version  | Purpose                                              |
|--------------------|----------|------------------------------------------------------|
| Next.js            | 15.x     | React framework with App Router and server components |
| TypeScript         | 5.x      | Type safety across all frontend code                 |
| Tailwind CSS       | v4.x     | Utility-first styling system                         |
| shadcn/ui          | Latest   | Accessible, composable UI component library          |
| TanStack Query     | v5.x     | Server state management and data fetching            |
| Zustand            | v5.x     | Lightweight client-side global state management      |
| React Hook Form    | v7.x     | Performant form state and validation                 |
| Zod                | v3.x     | Schema validation for forms and API responses        |
| Recharts           | v2.x     | Declarative chart components for progress visuals    |

### Backend

| Technology         | Version  | Purpose                                              |
|--------------------|----------|------------------------------------------------------|
| Laravel            | 12.x     | PHP framework powering the REST API                  |
| Laravel Sanctum    | Latest   | Token-based SPA authentication                       |
| PostgreSQL         | 16.x     | Primary relational database                          |

### Development Environment

- **Environment:** Localhost only (no cloud deployment in scope)
- **Node Package Manager:** npm or pnpm
- **PHP Package Manager:** Composer
- **API Testing:** Postman or similar REST client

> Docker, CI/CD, and deployment configurations are **out of scope** for the current version.

---

## 4. Repository Structure

```
BrainForge/
│
├── README.md                        # You are here — project overview and entry point
│
├── docs/                            # All project documentation (source of truth)
│   ├── architecture/                # Technical and product architecture documents
│   │   ├── 00_PROJECT_OVERVIEW.md   # Vision, mission, goals, and scope
│   │   ├── 01_PRODUCT_REQUIREMENTS.md  # Functional and non-functional requirements
│   │   ├── 02_FEATURES.md           # Detailed feature specifications
│   │   ├── 03_USER_FLOW.md          # Complete user journey maps
│   │   ├── 04_DATABASE.md           # Full database schema and relationships
│   │   ├── 05_API_SPEC.md           # REST API endpoint specifications
│   │   ├── 06_UI_GUIDELINES.md      # Design system and UI component rules
│   │   ├── 07_ROADMAP.md            # Phased development plan
│   │   ├── 08_DEVELOPMENT_RULES.md  # Mandatory rules for all contributors and AI agents
│   │   └── 09_CHANGELOG.md          # Version history and release notes
│   │
│   ├── meeting-notes/               # Records of design sessions and decisions made
│   ├── decisions/                   # Architecture Decision Records (ADRs)
│   └── assets/                      # Diagrams, wireframes, mockups, and media
│
├── backend/                         # Laravel 12 API application
│   ├── app/
│   ├── database/
│   ├── routes/
│   └── ...
│
├── frontend/                        # Next.js 15 application
│   ├── app/
│   ├── components/
│   ├── lib/
│   └── ...
│
└── mobile/                          # Reserved — mobile app (future version only)
```

> **Note:** The `mobile/` directory exists as a placeholder. No mobile implementation is in scope for Version 1. Do not create files inside this directory until a mobile development phase is formally planned.

---

## 5. Getting Started

> The following is an orientation overview. Detailed setup instructions for each application will live in their respective directories once implementation begins.

### Prerequisites

Ensure the following are installed on your local machine:

- **Node.js** v20 or later
- **npm** v10 or later (or **pnpm** v9 or later)
- **PHP** v8.3 or later
- **Composer** v2.x
- **PostgreSQL** v16.x
- A PostgreSQL client (e.g., pgAdmin, TablePlus, DBeaver)

### Backend Setup (Laravel)

```bash
# 1. Navigate to the backend directory
cd backend

# 2. Install PHP dependencies
composer install

# 3. Copy environment configuration
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Configure your .env with your PostgreSQL credentials
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_PORT=5432
# DB_DATABASE=brainforge
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# 6. Run database migrations
php artisan migrate

# 7. Seed the database with initial content
php artisan db:seed

# 8. Start the development server
php artisan serve
```

> The API will be available at `http://localhost:8000`

### Frontend Setup (Next.js)

```bash
# 1. Navigate to the frontend directory
cd frontend

# 2. Install Node dependencies
npm install

# 3. Copy environment configuration
cp .env.example .env.local

# 4. Configure your .env.local
# NEXT_PUBLIC_API_URL=http://localhost:8000/api

# 5. Start the development server
npm run dev
```

> The frontend will be available at `http://localhost:3000`

---

## 6. Documentation Overview

The `docs/architecture/` directory is the **single source of truth** for this project. Before writing a single line of code, all contributors and AI coding agents must read and understand the relevant documentation.

| Document | Purpose | Key Audience |
|---|---|---|
| [`00_PROJECT_OVERVIEW.md`](docs/architecture/00_PROJECT_OVERVIEW.md) | Vision, goals, scope, and success metrics | Everyone |
| [`01_PRODUCT_REQUIREMENTS.md`](docs/architecture/01_PRODUCT_REQUIREMENTS.md) | Requirements, constraints, and acceptance criteria | PMs, Engineers |
| [`02_FEATURES.md`](docs/architecture/02_FEATURES.md) | Deep-dive into every feature's behavior | Engineers, QA |
| [`03_USER_FLOW.md`](docs/architecture/03_USER_FLOW.md) | User journeys from registration to mastery | UX, Engineers |
| [`04_DATABASE.md`](docs/architecture/04_DATABASE.md) | Tables, columns, relationships, and indexes | Backend Engineers |
| [`05_API_SPEC.md`](docs/architecture/05_API_SPEC.md) | Every REST API endpoint defined in full | Backend + Frontend |
| [`06_UI_GUIDELINES.md`](docs/architecture/06_UI_GUIDELINES.md) | Design system, component rules, and accessibility | Frontend Engineers |
| [`07_ROADMAP.md`](docs/architecture/07_ROADMAP.md) | Phased delivery plan with priorities | PMs, Engineers |
| [`08_DEVELOPMENT_RULES.md`](docs/architecture/08_DEVELOPMENT_RULES.md) | Non-negotiable coding and architecture rules | All Contributors + AI Agents |
| [`09_CHANGELOG.md`](docs/architecture/09_CHANGELOG.md) | Release history using semantic versioning | Everyone |

> **For AI Coding Agents:** Always begin a session by reading `08_DEVELOPMENT_RULES.md`. It contains all mandatory conventions that must be followed regardless of task scope.

---

## 7. Core Principles

These principles guide every decision in BrainForge — from database design to button styling.

### 7.1 Database-First

Every piece of content in BrainForge lives in the database. There are no hardcoded strings, static JSON files, or in-memory mock data used in production. Vocabulary, grammar rules, reading passages, quiz questions, achievements, and XP thresholds are all stored in PostgreSQL and served via the API.

### 7.2 API-First

The backend exposes a clean, versioned REST API. The frontend consumes this API exclusively. No direct database access from the frontend. No server-side rendering of data that bypasses the API contract.

### 7.3 Type Safety Everywhere

TypeScript is enforced on the frontend. All API responses have corresponding Zod schemas for runtime validation. There are no `any` types unless absolutely unavoidable, and those must be explicitly documented.

### 7.4 Separation of Concerns

The frontend handles rendering, user interaction, and state. The backend handles business logic, data access, and validation. Neither side should encroach on the other's responsibilities.

### 7.5 Scalability by Default

Although BrainForge begins as a personal learning tool, the architecture must support multi-user operation without structural changes. Database schemas, API design, and component architecture must not be built with single-user assumptions baked in.

### 7.6 Consistency Over Cleverness

Readable, predictable code is always preferred over clever solutions. Naming conventions, folder structures, and patterns should be consistent enough that any engineer — or AI agent — can navigate the codebase on their first session.

### 7.7 Documentation as a Deliverable

Documentation is not an afterthought. It is created before code, updated alongside code, and reviewed with the same care as code. Stale documentation is treated as a defect.

---

## 8. Contributing Guidelines

As BrainForge is currently a personal project, contributions are self-managed. However, the following process should be followed to maintain professional standards:

1. **Read the documentation first.** Specifically `08_DEVELOPMENT_RULES.md` before writing any code.
2. **Check the roadmap.** Only work on features that are in scope for the current phase.
3. **Follow naming conventions.** As specified in `08_DEVELOPMENT_RULES.md`.
4. **Update documentation.** If your change affects an existing specification, update the relevant `.md` file.
5. **Update the changelog.** Every meaningful change is recorded in `09_CHANGELOG.md`.
6. **Test before committing.** Backend endpoints must be tested. Frontend components must be manually verified.

---

## 9. Versioning

BrainForge follows [Semantic Versioning 2.0.0](https://semver.org/):

```
MAJOR.MINOR.PATCH

MAJOR — Breaking changes or complete feature overhauls
MINOR — New features added in a backward-compatible manner
PATCH — Bug fixes and minor improvements
```

All version history is documented in [`docs/architecture/09_CHANGELOG.md`](docs/architecture/09_CHANGELOG.md).

---

## 10. License

BrainForge is a private project. All rights reserved.

No part of this codebase or documentation may be reproduced, distributed, or used commercially without explicit written permission from the project owner.

---

<div align="center">

**BrainForge** — *Forge your knowledge. One rep at a time.*

</div>
