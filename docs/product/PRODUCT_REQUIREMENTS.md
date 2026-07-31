# BrainForge — Product Requirements

> **Document Type:** Product Documentation
> **Location:** `docs/product/PRODUCT_REQUIREMENTS.md`
> **Version:** 0.1.1
> **Last Updated:** 2026-07-31 (Amended: Phase 4 Vocabulary)
> **Status:** Active
> **Derived From:** [`PROJECT_OVERVIEW.md`](PROJECT_OVERVIEW.md)

---

## Table of Contents

1. [Purpose](#1-purpose)
2. [Product Objectives](#2-product-objectives)
3. [Functional Requirements](#3-functional-requirements)
   - 3.1 Authentication
   - 3.2 User Profile
   - 3.3 Dashboard
   - 3.4 Daily Goals
   - 3.5 XP System
   - 3.6 Streak System
   - 3.7 Vocabulary
   - 3.8 Grammar
   - 3.9 Reading
   - 3.10 Writing
   - 3.11 IELTS Preparation
   - 3.12 Quiz Library
   - 3.13 Quiz Builder
   - 3.14 Quiz Attempts
   - 3.15 Progress Tracking
   - 3.16 Achievements
   - 3.17 IQ Training
   - 3.18 Logic Training
   - 3.19 Settings
4. [Non-Functional Requirements](#4-non-functional-requirements)
5. [Business Rules](#5-business-rules)
6. [User Stories](#6-user-stories)
7. [Acceptance Criteria](#7-acceptance-criteria)
8. [Constraints](#8-constraints)
9. [Assumptions](#9-assumptions)
10. [Risks](#10-risks)
11. [Future Considerations](#11-future-considerations)
12. [Cross-References](#12-cross-references)

---

## 1. Purpose

This document translates the approved `PROJECT_OVERVIEW.md` into measurable, traceable software requirements for BrainForge Version 1.

It defines **what the system must do** — not how it should be implemented. Implementation decisions belong to `DATABASE.md`, `API_SPEC.md`, `UI_GUIDELINES.md`, and `DEVELOPMENT_RULES.md`.

This document serves the following purposes:

- Provides a shared understanding of expected system behavior between all contributors and AI coding agents
- Establishes a traceable requirement hierarchy using unique requirement IDs
- Defines acceptance criteria that can be used to verify whether a feature is correctly implemented
- Records business rules that govern the platform's logic and data integrity
- Documents constraints, assumptions, and risks that shape the project

Every requirement in this document must be:

- **Specific** — clearly stated without ambiguity
- **Measurable** — able to be verified through testing or inspection
- **Traceable** — linked to the goals and modules defined in `PROJECT_OVERVIEW.md`
- **Realistic** — achievable within the Version 1 scope
- **Complete** — sufficient to understand expected behavior without implementation guidance

---

## 2. Product Objectives

The following objectives are derived directly from the goals defined in `PROJECT_OVERVIEW.md` (Section 4). They represent the expected outcomes BrainForge Version 1 must deliver.

| Objective ID | Goal Ref | Description | Measurable Outcome |
|---|---|---|---|
| OBJ-01 | G-01 | Deliver a complete, functional learning environment | All 19 modules are implemented, accessible, and operational |
| OBJ-02 | G-02 | Enable structured vocabulary acquisition | Users can learn new words, review previously studied words, and be tested through quizzes |
| OBJ-03 | G-03 | Enable grammar study and practice | Grammar topics are organized by level; each topic contains rules, examples, and exercises |
| OBJ-04 | G-04 | Support reading comprehension development | Reading passages are available with comprehension questions at multiple difficulty levels |
| OBJ-05 | G-05 | Provide structured IELTS preparation | Task 1 and Task 2 writing practice and IELTS-format reading comprehension are available |
| OBJ-06 | G-06 | Train logic and IQ skills | Exercises covering abstract reasoning, pattern recognition, and logical deduction are available |
| OBJ-07 | G-07 | Track user progress meaningfully | XP, level, accuracy, streak, and module completion data are displayed in the Progress module |
| OBJ-08 | G-08 | Reward consistent behavior | Achievements are unlocked automatically when defined milestone conditions are met |
| OBJ-09 | G-09 | Support user-created quizzes | Users can create, organize, and attempt custom question sets through the Quiz Builder |
| OBJ-10 | G-10 | Deliver a high-quality, consistent UI | Every page adheres to the design system defined in `UI_GUIDELINES.md` |

---

## 3. Functional Requirements

> **Priority Scale:**
> - **P1 — Critical:** Must be present for the system to function at its most basic level
> - **P2 — High:** Required for Version 1 release; directly enables core user value
> - **P3 — Medium:** Important but can follow core feature release
> - **P4 — Low:** Enhances the experience; acceptable to defer within Version 1

---

### 3.1 Authentication (M-01)

| ID | Title | Description | Priority | Acceptance Criteria | Dependencies |
|---|---|---|---|---|---|
| FR-AUTH-001 | User Registration | The system must allow a new user to create an account using an email address and password | P1 | A user can submit a registration form; on success, their account is created and they are redirected to the Dashboard | None |
| FR-AUTH-002 | Email Uniqueness | The system must prevent registration using an email address that is already associated with an existing account | P1 | If a duplicate email is submitted, the system returns a specific validation error identifying the email field | FR-AUTH-001 |
| FR-AUTH-003 | Password Requirements | The system must enforce a minimum password strength at registration | P1 | Registration fails if the password is fewer than 8 characters; an error is shown | FR-AUTH-001 |
| FR-AUTH-004 | User Login | The system must allow a registered user to log in using their email and password | P1 | A registered user can successfully authenticate and receive access to the platform | FR-AUTH-001 |
| FR-AUTH-005 | Invalid Credential Handling | The system must display a clear error when login credentials are incorrect | P1 | An invalid email or password results in a user-facing error message without revealing which field is incorrect | FR-AUTH-004 |
| FR-AUTH-006 | User Logout | The system must allow an authenticated user to log out | P1 | After logging out, the user's session is invalidated and they are redirected to the login page | FR-AUTH-004 |
| FR-AUTH-007 | Session Persistence | The system must keep a user authenticated across browser sessions until they explicitly log out or their token expires | P2 | A user who closes and reopens the browser remains logged in without re-entering credentials | FR-AUTH-004 |
| FR-AUTH-008 | Protected Routes | All platform pages except Login and Register must require authentication | P1 | An unauthenticated user accessing any protected route is redirected to the login page | FR-AUTH-004 |
| FR-AUTH-009 | Password Change | An authenticated user must be able to change their password from within the application | P2 | A user who submits their current password along with a new password successfully updates their credentials. *Canonical UI entry point: FR-SETTINGS-004 (Settings page). This requirement defines the underlying capability only.* | FR-AUTH-004, FR-SETTINGS-004 |
| FR-AUTH-010 | Display Name at Registration | The system must allow users to provide a display name during registration | P2 | The registration form includes a display name field; the name is saved and displayed throughout the platform | FR-AUTH-001 |

---

### 3.2 User Profile (M-02)

| ID | Title | Description | Priority | Acceptance Criteria | Dependencies |
|---|---|---|---|---|---|
| FR-PROF-001 | Profile Page | The system must display a dedicated profile page for the authenticated user | P2 | The profile page is accessible from the navigation and displays the user's information | FR-AUTH-004 |
| FR-PROF-002 | Display Name | The profile must display the user's display name | P2 | The display name is visible on the profile page | FR-AUTH-010 |
| FR-PROF-003 | User Avatar | The system must display a user avatar on the profile page | P3 | An avatar image is displayed; if no custom avatar is uploaded, a generated placeholder is shown | FR-PROF-001 |
| FR-PROF-004 | Avatar Upload | The user must be able to upload a custom avatar image | P3 | A user can select and upload an image file; the new avatar replaces the previous one on save | FR-PROF-003 |
| FR-PROF-005 | Bio Field | The user must be able to write a short personal bio | P4 | A text field allows the user to save up to 300 characters as their bio | FR-PROF-001 |
| FR-PROF-006 | Join Date Display | The profile must display the date the user registered | P3 | The registration date is shown in a human-readable format on the profile page | FR-AUTH-001 |
| FR-PROF-007 | Level Display | The profile must display the user's current level | P2 | The user's level number and level name are visible on the profile page | FR-XP-001 |
| FR-PROF-008 | XP Display | The profile must display the user's total accumulated XP | P2 | Total XP is shown with visual context of progress toward the next level | FR-XP-001 |
| FR-PROF-009 | Streak Display | The profile must display the user's current streak | P2 | The current streak count and longest streak are shown on the profile | FR-STREAK-001 |
| FR-PROF-010 | Statistics Summary | The profile must display a summary of learning statistics | P3 | Statistics include: total quizzes attempted, total lessons completed, total XP earned, total achievements unlocked | FR-PROF-001 |

---

### 3.3 Dashboard (M-03)

| ID | Title | Description | Priority | Acceptance Criteria | Dependencies |
|---|---|---|---|---|---|
| FR-DASH-001 | Dashboard Page | The system must display a dashboard as the default landing page after login | P1 | After login, the user is directed to the dashboard | FR-AUTH-004 |
| FR-DASH-002 | Greeting | The dashboard must display a personalized greeting using the user's display name | P2 | "Good morning, [Name]" or equivalent greeting is displayed based on time of day | FR-AUTH-010 |
| FR-DASH-003 | Streak Widget | The dashboard must display the user's current streak | P2 | The streak count and a visual indicator are shown prominently | FR-STREAK-001 |
| FR-DASH-004 | Daily Goals Widget | The dashboard must display today's daily goals and progress toward each | P2 | Each active daily goal is shown with a progress bar and completion percentage | FR-GOAL-001 |
| FR-DASH-005 | XP and Level Widget | The dashboard must display the user's current XP, level, and progress to next level | P2 | Level number, XP total, and a visual progress bar toward the next level are shown | FR-XP-001 |
| FR-DASH-006 | Quick Access Cards | The dashboard must provide quick-access navigation cards for each learning module | P2 | Cards for Vocabulary, Grammar, Reading, Writing, IELTS, IQ Training, Logic Training, and Quiz Library are shown | FR-AUTH-004 |
| FR-DASH-007 | Recent Activity | The dashboard must display a summary of the user's recent learning activity | P3 | The last 5 completed sessions or quiz attempts are listed with module name, score, and timestamp | FR-AUTH-004 |
| FR-DASH-008 | Achievement Notification | The dashboard must highlight recently unlocked achievements | P3 | Achievements unlocked in the past 24 hours are displayed as a notification-style banner or card | FR-ACHIEVE-001 |
| FR-DASH-009 | Module Progress Summary | The dashboard must show a high-level progress indicator for each major learning module | P3 | A compact visual summary shows completion percentage or lesson count per module | FR-PROGRESS-001 |

---

### 3.4 Daily Goals (M-04)

| ID | Title | Description | Priority | Acceptance Criteria | Dependencies |
|---|---|---|---|---|---|
| FR-GOAL-001 | Daily Goal System | The system must support configurable daily learning goals | P2 | Users can set and view daily goals for vocabulary, quizzes, and XP | FR-AUTH-004 |
| FR-GOAL-002 | Default Daily Goals | The system must provide default daily goals on account creation | P2 | New users have pre-configured daily goals active immediately after registration | FR-AUTH-001 |
| FR-GOAL-003 | Goal Progress Tracking | The system must automatically update goal progress as the user completes activities | P2 | Completing a vocabulary session updates the vocabulary goal; completing a quiz updates the quiz goal | FR-GOAL-001 |
| FR-GOAL-004 | Goal Completion State | The system must mark a goal as complete when its target is reached | P2 | A goal transitions from "in progress" to "complete" when the target quantity is met | FR-GOAL-003 |
| FR-GOAL-005 | Daily Goal Reset | All daily goals must reset at midnight (00:00) of the user's configured local timezone | P1 | At the start of a new calendar day in the user's configured timezone, all daily goal progress returns to zero. If no timezone is configured, UTC is used as the default. | FR-GOAL-001, FR-SETTINGS-009 |
| FR-GOAL-006 | Goal Configuration | Users must be able to configure their daily goal targets | P3 | In Settings, users can modify daily targets (e.g., study 10 vocabulary words, complete 2 quizzes) | FR-SETTINGS-001 |
| FR-GOAL-007 | XP from Goal Completion | Completing all daily goals awards a bonus XP reward | P3 | When all goals for a day are completed, the user receives a defined bonus XP amount | FR-XP-001 |
| FR-GOAL-008 | Goal History | The system must record whether daily goals were completed each day | P3 | The Progress module can display historical daily goal completion data | FR-PROGRESS-001 |

---

### 3.5 XP System (M-05)

| ID | Title | Description | Priority | Acceptance Criteria | Dependencies |
|---|---|---|---|---|---|
| FR-XP-001 | XP Earning | The system must award XP to the user upon completion of defined learning activities | P1 | Completing a quiz, finishing a lesson, or meeting a daily goal results in an XP award | FR-AUTH-004 |
| FR-XP-002 | XP Amounts by Activity | XP amounts must be defined per activity type in the database | P1 | Different activities award different XP amounts; these values are not hardcoded | FR-XP-001 |
| FR-XP-003 | Total XP Accumulation | The system must track the user's cumulative total XP | P1 | Total XP increases with each earned amount and is never reduced below zero | FR-XP-001 |
| FR-XP-004 | Level Calculation | The system must calculate the user's level based on their total XP | P1 | Level is calculated using a defined threshold table; the level updates automatically as XP increases | FR-XP-003 |
| FR-XP-005 | Level Thresholds | Level thresholds must be stored in the database, not hardcoded | P1 | Changing a level's XP requirement in the database takes effect without code changes | FR-XP-004 |
| FR-XP-006 | XP Log | Every XP earning event must be recorded with its source, amount, and timestamp | P2 | The system stores a log of each XP event for audit and progress visualization purposes | FR-XP-001 |
| FR-XP-007 | Progress to Next Level | The system must calculate and expose the XP remaining until the next level | P2 | The dashboard and profile show how much XP remains to reach the next level | FR-XP-004 |
| FR-XP-008 | Level Name | Each level must have a named title stored in the database | P3 | Level names (e.g., "Apprentice", "Scholar") are displayed alongside the level number | FR-XP-004 |

---

### 3.6 Streak System (M-06)

| ID | Title | Description | Priority | Acceptance Criteria | Dependencies |
|---|---|---|---|---|---|
| FR-STREAK-001 | Streak Tracking | The system must track the number of consecutive calendar days the user has completed at least one qualifying learning activity (as defined by BR-STREAK-007) | P2 | The streak counter increments by one for each new calendar day — measured in the user's configured timezone — in which at least one qualifying activity is completed | FR-AUTH-004, FR-SETTINGS-009 |
| FR-STREAK-002 | Streak Increment Rule | A streak may only increment once per calendar day | P1 | Completing multiple activities in the same calendar day does not increment the streak more than once | FR-STREAK-001 |
| FR-STREAK-003 | Streak Break | If the user does not complete any activity on a calendar day, the streak resets to zero | P1 | Missing a full calendar day without activity results in the streak counter returning to zero the following day | FR-STREAK-001 |
| FR-STREAK-004 | Longest Streak Record | The system must record the user's all-time longest streak | P2 | The longest streak is tracked separately and is not reset when the current streak breaks | FR-STREAK-001 |
| FR-STREAK-005 | Streak Display | The current streak must be visible on the dashboard and profile | P2 | Both pages show the current streak count with a visual indicator (e.g., flame icon and number) | FR-DASH-003, FR-PROF-009 |
| FR-STREAK-006 | Streak Freeze | The system must support a streak freeze mechanism to protect a streak for one missed day | P3 | When a freeze is active and the user misses a day, the streak is maintained; the freeze is consumed | FR-STREAK-001 |
| FR-STREAK-007 | Streak Freeze Earning | Streak freezes must be earnable through XP or achievements | P3 | Users receive streak freezes as rewards; these are stored and consumable, not unlimited | FR-XP-001, FR-ACHIEVE-001 |

---

### 3.7 Vocabulary (M-07)

| ID | Title | Description | Priority | Acceptance Criteria | Dependencies |
|---|---|---|---|---|---|
| FR-VOC-001 | Vocabulary List | The system must display a browsable list of vocabulary words | P1 | Users can view all vocabulary words available in the platform, with filtering by difficulty and category | FR-AUTH-004 |
| FR-VOC-002 | Word Detail | Each vocabulary word must have a dedicated detail view | P1 | The detail view displays the word, definition, part of speech, example sentences, synonyms, and difficulty level | FR-VOC-001 |
| FR-VOC-003 | Vocabulary Categories | Vocabulary words must be organized into thematic categories | P2 | Users can browse vocabulary by category (e.g., Academic, Business, Daily Life, IELTS) | FR-VOC-001 |
| FR-VOC-004 | Difficulty Levels | Each vocabulary word must have a defined difficulty level | P2 | Difficulty levels (e.g., Beginner, Intermediate, Advanced) are visible and usable as filters | FR-VOC-001 |
| FR-VOC-005 | Study Mode | The system must provide a study mode for reviewing vocabulary words | P1 | In study mode, words are presented one at a time with definition, examples, and navigation controls | FR-VOC-002 |
| FR-VOC-006 | Mark as Learned | Users must be able to mark a vocabulary word as learned | P2 | A "Mark as Learned" action is available on each word; learned words are tracked per user | FR-VOC-005 |
| FR-VOC-007 | Vocabulary Quiz | The system must support quiz-based vocabulary practice | P1 | A vocabulary quiz presents multiple-choice or fill-in-the-blank questions generated from the vocabulary database | FR-VOC-001, FR-QENG-001 |
| FR-VOC-008 | Spaced Repetition Queue | The system must implement a spaced repetition review queue using the SM-2 algorithm | P2 | Words due for review are surfaced based on ease factor, repetition interval, and performance history per the SM-2 schedule. Each word interaction records a quality score (0–5), updated ease factor, interval in days, and next review date. Review logs enforce idempotency per review session. | FR-VOC-006 |
| FR-VOC-009 | Review Mode | The system must provide a dedicated review mode for spaced repetition vocabulary | P2 | The review session presents only words that are due for review based on the spaced repetition schedule | FR-VOC-008 |
| FR-VOC-010 | XP for Vocabulary | Completing vocabulary study and quiz sessions awards XP | P2 | Finishing a study session or passing a vocabulary quiz credits the user with defined XP | FR-XP-001 |
| FR-VOC-011 | Vocabulary Progress | The system must track how many words the user has learned | P2 | The number of learned words and percentage of total vocabulary is tracked and visible in the Progress module | FR-PROGRESS-001 |
| FR-VOC-012 | Search | Users must be able to search vocabulary by word or definition | P3 | A search input filters the vocabulary list in real time | FR-VOC-001 |
| FR-VOC-013 | Study Sessions | The system must track active study sessions | P2 | Study sessions are initialized and finalized on the server to prevent duplicate XP or streak events | FR-VOC-005 |

---

### 3.8 Grammar (M-08)

| ID | Title | Description | Priority | Acceptance Criteria | Dependencies |
|---|---|---|---|---|---|
| FR-GRAM-001 | Grammar Topic List | The system must display a list of grammar topics organized by category and level | P1 | Users can browse grammar topics grouped by category (e.g., Tenses, Articles, Conditionals) and difficulty | FR-AUTH-004 |
| FR-GRAM-002 | Grammar Lesson | Each grammar topic must have a structured lesson page | P1 | The lesson page displays the grammar rule, explanation, usage notes, and contextual examples | FR-GRAM-001 |
| FR-GRAM-003 | Grammar Difficulty Levels | Grammar topics must be organized by difficulty level | P2 | Topics are tagged with a level (Beginner, Intermediate, Advanced) and can be filtered accordingly | FR-GRAM-001 |
| FR-GRAM-004 | Grammar Exercises | Each grammar topic must include practice exercises | P1 | After reading a lesson, the user can complete exercises that test understanding of the grammar rule | FR-GRAM-002 |
| FR-GRAM-005 | Exercise Feedback | Grammar exercises must provide immediate feedback on answers | P1 | After submitting an answer, the user sees whether it was correct and an explanation of why | FR-GRAM-004 |
| FR-GRAM-006 | Grammar Quiz | The system must support grammar quizzes that cover multiple topics | P2 | Grammar quizzes can span one topic or multiple topics and use the shared quiz engine | FR-GRAM-004, FR-QENG-001 |
| FR-GRAM-007 | Mark Topic as Complete | Users must be able to mark a grammar topic as completed | P2 | A completed topic is visually distinguished from an uncompleted one in the topic list | FR-GRAM-002 |
| FR-GRAM-008 | XP for Grammar | Completing grammar lessons and exercises awards XP | P2 | Finishing a grammar lesson or exercise credits the user with defined XP | FR-XP-001 |
| FR-GRAM-009 | Grammar Progress | The system must track grammar topic completion per user | P2 | The number of completed grammar topics and percentage of total are tracked and visible | FR-PROGRESS-001 |

---

### 3.9 Reading (M-09)

| ID | Title | Description | Priority | Acceptance Criteria | Dependencies |
|---|---|---|---|---|---|
| FR-READ-001 | Passage List | The system must display a list of reading passages | P1 | Users can browse reading passages organized by difficulty level and topic category | FR-AUTH-004 |
| FR-READ-002 | Passage Detail | Each reading passage must have a full-text reading view | P1 | The reading view displays the passage title, source type, estimated reading time, and full text | FR-READ-001 |
| FR-READ-003 | Difficulty Levels | Reading passages must be tagged with a difficulty level | P2 | Passages are tagged (Beginner, Intermediate, Advanced) and filterable by difficulty | FR-READ-001 |
| FR-READ-004 | Comprehension Questions | Each passage must have associated comprehension questions | P1 | After reading a passage, the user can attempt comprehension questions tied to that passage | FR-READ-002 |
| FR-READ-005 | Question Types | Comprehension questions must support multiple question types | P2 | Supported auto-graded types: multiple choice and true/false. Short-answer questions are excluded from Version 1 auto-grading and from score calculation (FR-READ-007). Score denominators count only auto-gradable questions. Short-answer support may be added in a future version. | FR-READ-004 |
| FR-READ-006 | Answer Feedback | The system must provide answer feedback after question submission | P1 | Correct and incorrect answers are revealed after the user submits; explanations are shown | FR-READ-004 |
| FR-READ-007 | Reading Score | Completing a passage with questions generates a score | P2 | The score is calculated as the percentage of correct answers and stored for progress tracking | FR-READ-004 |
| FR-READ-008 | XP for Reading | Completing a reading passage with questions awards XP | P2 | Successfully completing reading comprehension questions credits the user with defined XP | FR-XP-001 |
| FR-READ-009 | Reading Progress | The system must track how many passages the user has completed | P2 | Completed passages are recorded and visible in the Progress module | FR-PROGRESS-001 |
| FR-READ-010 | Passage Categories | Reading passages must belong to topic categories | P3 | Categories (e.g., Science, Culture, Academic, News) are available as browse filters | FR-READ-001 |

---

### 3.10 Writing (M-10)

| ID | Title | Description | Priority | Acceptance Criteria | Dependencies |
|---|---|---|---|---|---|
| FR-WRITE-001 | Writing Prompt List | The system must display a list of writing prompts | P1 | Users can browse writing prompts organized by type, difficulty, and topic | FR-AUTH-004 |
| FR-WRITE-002 | Prompt Detail | Each writing prompt must have a full description page | P1 | The prompt detail displays the task description, word count guidance, time suggestion, and any structural guidance | FR-WRITE-001 |
| FR-WRITE-003 | Writing Editor | The system must provide a text editor for composing writing responses | P1 | Users can write their response in a clean text editor with word count display | FR-WRITE-002 |
| FR-WRITE-004 | Response Submission | Users must be able to submit their written response | P1 | On submission, the response is saved with timestamp and linked to the prompt | FR-WRITE-003 |
| FR-WRITE-005 | Response History | The system must store all previous writing submissions | P2 | Users can view a list of all their past writing submissions and review them | FR-WRITE-004 |
| FR-WRITE-006 | Prompt Guidance | Each writing prompt must include structural guidance | P2 | Guidance includes suggested structure (e.g., introduction, body paragraphs, conclusion), tips, and a model outline | FR-WRITE-002 |
| FR-WRITE-007 | Prompt Categories | Writing prompts must belong to thematic categories | P2 | Categories (e.g., Opinion, Descriptive, Analytical, Report) are available as browse filters | FR-WRITE-001 |
| FR-WRITE-008 | Difficulty Levels | Writing prompts must have defined difficulty levels | P2 | Prompts are tagged with a level and filterable by difficulty | FR-WRITE-001 |
| FR-WRITE-009 | XP for Writing | Submitting a written response awards XP | P2 | Successfully submitting a writing response credits the user with defined XP | FR-XP-001 |
| FR-WRITE-010 | Word Count Target | Each prompt must specify a target word count range | P2 | The editor displays the current word count and the target range; the user is warned if below minimum | FR-WRITE-003 |

---

### 3.11 IELTS Preparation (M-11)

| ID | Title | Description | Priority | Acceptance Criteria | Dependencies |
|---|---|---|---|---|---|
| FR-IELTS-001 | IELTS Module Overview | The system must display an IELTS preparation module with clear section divisions | P1 | The IELTS module is accessible and shows sections for Writing Task 1, Writing Task 2, and Reading Practice | FR-AUTH-004 |
| FR-IELTS-002 | Writing Task 1 | The system must support IELTS Academic Writing Task 1 prompts | P1 | Task 1 prompts include a stimulus description (graph, chart, process) and word count requirement of approximately 150 words | FR-WRITE-001 |
| FR-IELTS-003 | Writing Task 2 | The system must support IELTS Writing Task 2 essay prompts | P1 | Task 2 prompts present an opinion, problem, or discussion question requiring a 250-word response | FR-WRITE-001 |
| FR-IELTS-004 | IELTS Reading Practice | The system must provide IELTS-format reading passages and questions | P1 | Passages and questions are structured in IELTS format (True/False/Not Given, matching headings, sentence completion) | FR-READ-001 |
| FR-IELTS-005 | Task Band Guidance | Each IELTS task must include band score guidance | P2 | Users can view what constitutes Band 6, 7, and 8 responses for each task type | FR-IELTS-002, FR-IELTS-003 |
| FR-IELTS-006 | Sample Answers | IELTS writing tasks must include sample responses | P2 | At least one sample answer is shown after the user submits their own response | FR-IELTS-002, FR-IELTS-003 |
| FR-IELTS-007 | Task Submission History | IELTS writing submissions are retrievable as a module-type-filtered subset of the general writing submission history | P2 | Users can review all previous IELTS writing submissions, filtered by task type. *This requirement is fulfilled by FR-WRITE-005; IELTS submissions are stored in the same submission store, distinguished by a `module_type` field (see BR-WRITE-003). No separate storage model is required.* | FR-WRITE-005, FR-IELTS-008 |
| FR-IELTS-008 | IELTS Progress Tracking | IELTS task completions must be counted and tracked separately from general Writing | P2 | The Progress module shows IELTS-specific completion counts and statistics | FR-PROGRESS-001 |
| FR-IELTS-009 | XP for IELTS | Submitting IELTS tasks awards XP | P2 | IELTS task submissions credit the user with defined XP | FR-XP-001 |
| FR-IELTS-010 | IELTS Question Types | IELTS reading questions must include format-accurate question types | P2 | Supported IELTS-specific question types include: T/F/NG, matching features, sentence completion, and summary completion | FR-IELTS-004 |

---

### 3.12 Quiz Library (M-12)

| ID | Title | Description | Priority | Acceptance Criteria | Dependencies |
|---|---|---|---|---|---|
| FR-QLIB-001 | Quiz List | The system must display a browsable library of curated quizzes | P1 | Users can view all available quizzes with title, category, difficulty, and question count | FR-AUTH-004 |
| FR-QLIB-002 | Quiz Categories | Quizzes must be organized into categories | P2 | Quizzes belong to categories (e.g., Vocabulary, Grammar, General Knowledge, Logic, IQ) and are filterable | FR-QLIB-001 |
| FR-QLIB-003 | Quiz Difficulty | Each quiz must have a defined difficulty level | P2 | Difficulty levels are shown and usable as filters | FR-QLIB-001 |
| FR-QLIB-004 | Quiz Detail Page | Each quiz must have a detail page before starting | P2 | The detail page shows: title, description, category, difficulty, question count, estimated time, and past attempt summary | FR-QLIB-001 |
| FR-QLIB-005 | Quiz Attempt | Users must be able to start and attempt any quiz from the library | P1 | Starting a quiz initiates the quiz session engine and presents questions sequentially | FR-QLIB-001, FR-QATTEMPT-001 |
| FR-QLIB-006 | Attempt History | The system must display the user's past attempts for each quiz | P2 | On the quiz detail page, the user sees a history of their previous scores and attempt dates | FR-QATTEMPT-001 |
| FR-QLIB-007 | Search | Users must be able to search the quiz library by title or keyword | P3 | A search input filters the quiz list by title match | FR-QLIB-001 |
| FR-QLIB-008 | Question Randomization | Quiz questions must be randomized on each attempt | P1 | Two consecutive attempts of the same quiz present questions in a different order | FR-QLIB-005 |
| FR-QLIB-009 | XP for Quiz Library | Completing a quiz from the library awards XP | P2 | Finishing a quiz attempt credits the user with XP based on performance | FR-XP-001 |

---

### 3.13 Quiz Builder (M-13)

| ID | Title | Description | Priority | Acceptance Criteria | Dependencies |
|---|---|---|---|---|---|
| FR-QBUILD-001 | Create Quiz | Users must be able to create a new custom quiz | P2 | A quiz creation form allows the user to provide a title, description, and category | FR-AUTH-004 |
| FR-QBUILD-002 | Add Questions | Users must be able to add questions to their custom quiz | P2 | Each question includes a question body, multiple answer options, and a correct answer designation | FR-QBUILD-001 |
| FR-QBUILD-003 | Edit Questions | Users must be able to edit existing questions in their custom quiz | P2 | Any question in a user-created quiz can be modified after creation | FR-QBUILD-002 |
| FR-QBUILD-004 | Delete Questions | Users must be able to remove questions from their custom quiz | P2 | Questions can be individually removed without deleting the entire quiz | FR-QBUILD-002 |
| FR-QBUILD-005 | Delete Quiz | Users must be able to delete a custom quiz they created | P2 | A quiz and all its questions are permanently removed; past attempt records are retained | FR-QBUILD-001 |
| FR-QBUILD-006 | Edit Quiz Settings | Users must be able to edit the title, description, and category of a custom quiz | P2 | Quiz metadata can be changed after creation | FR-QBUILD-001 |
| FR-QBUILD-007 | Minimum Questions | A quiz must have at least one question before it can be attempted | P2 | Attempting a quiz with zero questions is prevented with a user-facing message | FR-QBUILD-002 |
| FR-QBUILD-008 | Custom Quiz Attempt | Custom quizzes can be attempted using the same quiz engine as library quizzes | P2 | A custom quiz is playable from the Quiz Builder section using the shared quiz session engine | FR-QATTEMPT-001 |
| FR-QBUILD-009 | Multiple Choice Format | Custom quiz questions must support multiple choice answer format | P2 | Each question has between 2 and 6 answer options with exactly one correct answer | FR-QBUILD-002 |
| FR-QBUILD-010 | Custom Quiz List | Users must be able to see all quizzes they have created | P2 | A "My Quizzes" view lists all user-created quizzes with title, question count, and last modified date | FR-QBUILD-001 |

---

### 3.14 Quiz Attempts (M-14)

> This module defines the shared **Quiz Session Engine** used by all quiz-producing modules.
> The canonical quiz engine requirement is **FR-QENG-001**. All modules that invoke the quiz engine declare `FR-QENG-001` as a dependency. The alias `FR-QUIZ-ENGINE` is retired; use `FR-QENG-001` in all documents and code.

| ID | Title | Description | Priority | Acceptance Criteria | Dependencies |
|---|---|---|---|---|---|
| FR-QENG-001 | Quiz Engine Entry Point | The system must provide a single, reusable quiz session engine consumed by all learning modules. No module implements its own question presentation, answer recording, or scoring logic. | P1 | Any module initiating a quiz session does so through the shared engine. The engine is agnostic to content type and question source (vocabulary, grammar, IQ, logic, library, or custom). | FR-AUTH-004 |
| FR-QATTEMPT-001 | Quiz Session | The system must manage a stateful quiz session from start to finish | P1 | A quiz session progresses through all questions, tracks answers, and concludes with a result | FR-QENG-001 |
| FR-QATTEMPT-002 | Question Presentation | Each question must be displayed one at a time | P1 | Questions are shown one per screen with clear navigation (question number, total count) | FR-QATTEMPT-001 |
| FR-QATTEMPT-003 | Answer Selection | Users must be able to select an answer for each question | P1 | The user selects one answer option; selection is visually confirmed before proceeding | FR-QATTEMPT-002 |
| FR-QATTEMPT-004 | Answer Submission | Answers are submitted when the user advances to the next question | P1 | Once an answer is confirmed, it is saved and cannot be changed (no going back by default) | FR-QATTEMPT-003 |
| FR-QATTEMPT-005 | Quiz Completion | Completing all questions ends the session and generates a result | P1 | After the final question, the session closes and the results screen is shown | FR-QATTEMPT-004 |
| FR-QATTEMPT-006 | Result Summary | The results screen must display the quiz outcome | P1 | Results show: score percentage, number correct, number incorrect, time taken, and XP earned | FR-QATTEMPT-005 |
| FR-QATTEMPT-007 | Attempt Record | Every completed quiz attempt must be stored permanently | P1 | The attempt record includes: quiz ID, score, answers given, timestamp, and XP awarded | FR-QATTEMPT-005 |
| FR-QATTEMPT-008 | Review Mode | After completion, users must be able to review all questions and answers | P2 | The review screen shows each question, the user's answer, the correct answer, and an explanation | FR-QATTEMPT-005 |
| FR-QATTEMPT-009 | Incorrect Answer Highlight | Incorrectly answered questions must be visually distinguished in review | P2 | Incorrect answers are shown in a distinct style (e.g., red); correct answers are shown in green | FR-QATTEMPT-008 |
| FR-QATTEMPT-010 | XP Award | XP is awarded upon successful completion of a quiz attempt | P1 | XP is granted at session close; the amount is based on score or defined per quiz type | FR-XP-001 |
| FR-QATTEMPT-011 | Time Tracking | The system must record how long each quiz attempt takes | P3 | Time elapsed from first question to submission is stored with the attempt record | FR-QATTEMPT-007 |
| FR-QATTEMPT-012 | Question Explanations | Correct answer explanations must be stored and displayed in review mode | P2 | Each answer in the database includes an optional explanation text shown during review | FR-QATTEMPT-008 |

---

### 3.15 Progress Tracking (M-15)

| ID | Title | Description | Priority | Acceptance Criteria | Dependencies |
|---|---|---|---|---|---|
| FR-PROGRESS-001 | Progress Page | The system must display a dedicated progress page | P2 | The progress page is accessible from navigation and shows learning statistics | FR-AUTH-004 |
| FR-PROGRESS-002 | XP Over Time Chart | The progress page must display XP earned over time as a chart | P2 | A time-series chart shows daily or weekly XP accumulation | FR-XP-006 |
| FR-PROGRESS-003 | Quiz Accuracy Rate | The system must display the user's overall quiz accuracy rate | P2 | Accuracy is shown as a percentage of correct answers across all quiz attempts | FR-QATTEMPT-007 |
| FR-PROGRESS-004 | Module Completion Summary | The progress page must display completion statistics per module | P2 | For each learning module, the number of completed items and total available are shown | FR-AUTH-004 |
| FR-PROGRESS-005 | Vocabulary Progress | The system must display vocabulary-specific progress metrics | P2 | Shows: words learned, words due for review, and accuracy in vocabulary quizzes | FR-VOC-011 |
| FR-PROGRESS-006 | Grammar Progress | The system must display grammar-specific progress metrics | P2 | Shows: topics completed, total topics, and accuracy in grammar exercises | FR-GRAM-009 |
| FR-PROGRESS-007 | Quiz Statistics | The system must display aggregate quiz statistics | P2 | Shows: total quizzes attempted, average score, best score, and most recent attempt | FR-QATTEMPT-007 |
| FR-PROGRESS-008 | Streak History | The system must display the user's streak history | P3 | A calendar-style view or bar chart shows which days the user was active | FR-STREAK-001 |
| FR-PROGRESS-009 | Daily Goal Completion History | The system must display historical daily goal completion | P3 | A chart shows how many days in the past month all daily goals were completed | FR-GOAL-008 |
| FR-PROGRESS-010 | Level History | The system must display the user's leveling milestones | P3 | A timeline or table shows when the user reached each level | FR-XP-004 |

---

### 3.16 Achievements (M-16)

| ID | Title | Description | Priority | Acceptance Criteria | Dependencies |
|---|---|---|---|---|---|
| FR-ACHIEVE-001 | Achievement System | The system must support an unlockable achievement system | P2 | Achievements are defined in the database and unlocked automatically when conditions are met | FR-AUTH-004 |
| FR-ACHIEVE-002 | Achievement Definitions | All achievement definitions must be stored in the database | P1 | Achievement name, description, icon, and unlock condition are database records, not hardcoded | FR-ACHIEVE-001 |
| FR-ACHIEVE-003 | Achievement Unlock | The system must automatically check and unlock achievements when conditions are met | P2 | When an XP milestone, streak milestone, or learning milestone is reached, the relevant achievement is unlocked | FR-ACHIEVE-001 |
| FR-ACHIEVE-004 | Achievement Display | Users must be able to view all achievements in a gallery | P2 | The achievement gallery shows all available achievements with locked/unlocked state | FR-ACHIEVE-001 |
| FR-ACHIEVE-005 | Achievement Detail | Each achievement must display its name, description, and unlock criteria | P2 | Clicking an achievement shows its full description and the condition required to unlock it | FR-ACHIEVE-004 |
| FR-ACHIEVE-006 | One-Time Unlock | Each achievement may only be unlocked once per user | P1 | Re-triggering the unlock condition does not create duplicate achievement records | FR-ACHIEVE-003 |
| FR-ACHIEVE-007 | XP Reward for Achievements | Unlocking an achievement awards XP | P3 | Each achievement grants a defined XP amount upon first unlock | FR-XP-001 |
| FR-ACHIEVE-008 | Achievement Categories | Achievements must be organized into categories | P3 | Categories (e.g., Streak, Vocabulary, Quiz, Level) help users understand how to earn achievements | FR-ACHIEVE-004 |
| FR-ACHIEVE-009 | Dashboard Notification | Recently unlocked achievements appear on the dashboard | P3 | Achievements unlocked in the past 24 hours are highlighted on the dashboard | FR-DASH-008 |

---

### 3.17 IQ Training (M-17)

| ID | Title | Description | Priority | Acceptance Criteria | Dependencies |
|---|---|---|---|---|---|
| FR-IQ-001 | IQ Exercise List | The system must display a list of IQ training exercises | P1 | Users can browse IQ exercises organized by category and difficulty | FR-AUTH-004 |
| FR-IQ-002 | Exercise Categories | IQ exercises must be organized into categories | P2 | Categories include: Pattern Recognition, Spatial Reasoning, Abstract Reasoning, Number Sequences | FR-IQ-001 |
| FR-IQ-003 | Exercise Difficulty | Each IQ exercise must have a defined difficulty level | P2 | Difficulty levels are shown and usable as filters | FR-IQ-001 |
| FR-IQ-004 | Exercise Attempt | Users must be able to attempt IQ exercises using the quiz engine | P1 | IQ exercises use the shared quiz session engine | FR-QENG-001 |
| FR-IQ-005 | Answer Explanations | All IQ questions must include explanations for the correct answer | P2 | After submitting an answer, the explanation is shown in review mode | FR-QATTEMPT-008 |
| FR-IQ-006 | IQ Progress Tracking | IQ exercise completions must be tracked separately | P2 | The Progress module shows IQ-specific completion and accuracy data | FR-PROGRESS-001 |
| FR-IQ-007 | XP for IQ Training | Completing IQ exercises awards XP | P2 | Completing an IQ exercise session credits the user with defined XP | FR-XP-001 |
| FR-IQ-008 | Timed Mode | IQ exercises may optionally impose a time limit per question | P3 | When a time limit is set, a countdown is shown; unanswered questions are marked incorrect | FR-IQ-004 |

---

### 3.18 Logic Training (M-18)

| ID | Title | Description | Priority | Acceptance Criteria | Dependencies |
|---|---|---|---|---|---|
| FR-LOGIC-001 | Logic Exercise List | The system must display a list of logic training exercises | P1 | Users can browse logic exercises organized by category and difficulty | FR-AUTH-004 |
| FR-LOGIC-002 | Exercise Categories | Logic exercises must be organized into categories | P2 | Categories include: Deductive Reasoning, Syllogisms, Logical Sequences, Critical Analysis, Fallacy Identification | FR-LOGIC-001 |
| FR-LOGIC-003 | Exercise Difficulty | Each logic exercise must have a defined difficulty level | P2 | Difficulty levels are shown and usable as filters | FR-LOGIC-001 |
| FR-LOGIC-004 | Exercise Attempt | Users must be able to attempt logic exercises using the quiz engine | P1 | Logic exercises use the shared quiz session engine | FR-QENG-001 |
| FR-LOGIC-005 | Answer Explanations | All logic questions must include detailed explanations | P1 | The reasoning behind the correct answer is shown after submission | FR-QATTEMPT-008 |
| FR-LOGIC-006 | Logic Progress Tracking | Logic exercise completions must be tracked separately | P2 | The Progress module shows logic-specific completion and accuracy data | FR-PROGRESS-001 |
| FR-LOGIC-007 | XP for Logic Training | Completing logic exercises awards XP | P2 | Completing a logic exercise session credits the user with defined XP | FR-XP-001 |

---

### 3.19 Settings (M-19)

| ID | Title | Description | Priority | Acceptance Criteria | Dependencies |
|---|---|---|---|---|---|
| FR-SETTINGS-001 | Settings Page | The system must provide a dedicated settings page | P2 | The settings page is accessible from navigation and contains all user-configurable options | FR-AUTH-004 |
| FR-SETTINGS-002 | Display Name Change | Users must be able to update their display name | P2 | A form field allows the user to change their display name; changes save immediately | FR-PROF-002 |
| FR-SETTINGS-003 | Email Display | The settings page must display the user's registered email | P2 | The email address is shown (read-only or editable) | FR-AUTH-001 |
| FR-SETTINGS-004 | Password Change | Users must be able to change their password from settings | P2 | A password change form requires current password, new password, and confirmation | FR-AUTH-009 |
| FR-SETTINGS-005 | Daily Goal Configuration | Users must be able to configure their daily goal targets | P3 | Settings provide inputs for adjusting daily vocabulary, quiz, and XP goals | FR-GOAL-006 |
| FR-SETTINGS-006 | Theme Preference | Users must be able to switch between light and dark mode | P3 | A toggle switches the UI between light and dark themes; the preference is persisted | FR-AUTH-004 |
| FR-SETTINGS-007 | Validation on Settings Forms | All settings forms must validate input before saving | P1 | Invalid inputs (empty name, mismatched passwords) show field-level error messages | FR-SETTINGS-001 |
| FR-SETTINGS-008 | Success Confirmation | Settings changes must confirm success after saving | P2 | A success message or visual indicator appears after a setting is saved successfully | FR-SETTINGS-001 |
| FR-SETTINGS-009 | Timezone Configuration | Users must be able to configure their local timezone | P2 | A timezone selector (IANA timezone list) allows the user to select their local timezone; the configured timezone is persisted and governs daily goal resets and streak activity window calculations | FR-SETTINGS-001 |

> **TODO — UI_GUIDELINES.md and FEATURES.md:** Define the complete primary navigation structure (sidebar items, item grouping, icons, and mobile collapse behavior) as formal requirement FR-NAV-001. Identified as a missing requirement during architectural review.

---

## 4. Non-Functional Requirements

### 4.1 Performance

| ID | Description | Acceptance Criteria | Priority |
|---|---|---|---|
| NFR-PERF-001 | Page Load Time | All pages must load and become interactive within 3 seconds on a standard local network | P2 |
| NFR-PERF-002 | API Response Time | All API responses must return within 500ms under normal load | P2 |
| NFR-PERF-003 | Quiz Session Responsiveness | Advancing between quiz questions must feel instantaneous (under 200ms) | P1 |
| NFR-PERF-004 | Chart Rendering | Progress charts must render within 1 second of page load | P3 |
| NFR-PERF-005 | Data Caching | Frequently accessed data (module lists, question banks) must be cached to reduce redundant API calls | P2 |

### 4.2 Scalability

| ID | Description | Acceptance Criteria | Priority |
|---|---|---|---|
| NFR-SCALE-001 | Multi-User Schema | The database schema must support multiple users without structural changes | P1 |
| NFR-SCALE-002 | Content Volume | The system must handle at least 10,000 vocabulary words, 1,000 grammar topics, and 5,000 quiz questions without degradation | P2 |
| NFR-SCALE-003 | API Versioning | All API routes must use a versioned namespace (e.g., `/api/v1/`) to allow non-breaking evolution | P2 |
| NFR-SCALE-004 | Modular Architecture | New learning modules must be addable without modifying existing modules | P1 |
| NFR-SCALE-005 | API Pagination | All list endpoints must support page-based pagination. Default page size is 20 items; maximum page size is 100 items. Pagination parameters are `page` and `per_page`. Unpaginated responses are not permitted for list resources. | P1 |

### 4.3 Security

| ID | Description | Acceptance Criteria | Priority |
|---|---|---|---|
| NFR-SEC-001 | Authentication Required | Every API endpoint except login and register must require a valid authentication token | P1 |
| NFR-SEC-002 | Authorization | Users may only read and write their own data | P1 |
| NFR-SEC-003 | Password Hashing | User passwords must never be stored in plain text | P1 |
| NFR-SEC-004 | Input Validation | All user input must be validated on both the frontend and backend | P1 |
| NFR-SEC-005 | CSRF Protection | The API must implement appropriate token-based protection against cross-site request forgery | P1 |
| NFR-SEC-006 | SQL Injection Prevention | All database queries must use parameterized queries or an ORM with prepared statements | P1 |

### 4.4 Reliability

| ID | Description | Acceptance Criteria | Priority |
|---|---|---|---|
| NFR-REL-001 | Error Handling | All API errors must return structured JSON error responses with meaningful messages | P1 |
| NFR-REL-002 | Frontend Error States | All loading, error, and empty states must be handled gracefully in the UI | P1 |
| NFR-REL-003 | Data Integrity | No orphaned records should exist due to delete operations without cascading constraints | P2 |
| NFR-REL-004 | Form Recovery | If a form submission fails, the user's input must not be cleared | P2 |

### 4.5 Maintainability

| ID | Description | Acceptance Criteria | Priority |
|---|---|---|---|
| NFR-MAINT-001 | Documentation Currency | Documentation must be updated before or alongside any feature implementation | P1 |
| NFR-MAINT-002 | Code Consistency | All code must follow the conventions defined in `DEVELOPMENT_RULES.md` | P1 |
| NFR-MAINT-003 | Component Reuse | No UI pattern should be implemented more than once without extracting it into a shared component | P2 |
| NFR-MAINT-004 | Separation of Concerns | Business logic must not reside in controllers (backend) or UI components (frontend) | P1 |

### 4.6 Accessibility

| ID | Description | Acceptance Criteria | Priority |
|---|---|---|---|
| NFR-ACC-001 | Keyboard Navigation | All interactive elements must be operable via keyboard | P2 |
| NFR-ACC-002 | Focus Indicators | Focused elements must have a visible focus ring | P2 |
| NFR-ACC-003 | Color Contrast | Text must meet WCAG 2.1 AA contrast ratio (4.5:1 minimum for normal text) | P2 |
| NFR-ACC-004 | Semantic HTML | Pages must use appropriate semantic HTML elements | P2 |
| NFR-ACC-005 | Form Labels | All form inputs must have associated labels | P1 |

### 4.7 Usability

| ID | Description | Acceptance Criteria | Priority |
|---|---|---|---|
| NFR-USE-001 | Error Messages | All error messages must be specific, actionable, and displayed near the relevant field | P1 |
| NFR-USE-002 | Loading States | All async operations must show a loading indicator | P1 |
| NFR-USE-003 | Empty States | All list and data views must display a helpful empty state message when no data is available | P2 |
| NFR-USE-004 | Navigation | The user must always be able to identify their current location within the application | P2 |
| NFR-USE-005 | Confirmation Dialogs | Destructive actions (delete quiz, clear data) must require confirmation before proceeding | P2 |

### 4.8 Responsiveness

| ID | Description | Acceptance Criteria | Priority |
|---|---|---|---|
| NFR-RESP-001 | Mobile Compatibility | All pages must be functional and readable on screens as narrow as 375px | P2 |
| NFR-RESP-002 | Tablet Compatibility | All pages must be functional and readable on screens as narrow as 768px | P2 |
| NFR-RESP-003 | Desktop Optimization | The primary experience is designed for 1280px and above | P1 |
| NFR-RESP-004 | Sidebar Behavior | The sidebar navigation must collapse gracefully on smaller screen sizes | P2 |

### 4.9 API Standards

| ID | Description | Acceptance Criteria | Priority |
|---|---|---|---|
| NFR-API-001 | Standard Success Envelope | All successful API responses must follow a consistent JSON envelope. Single-resource responses wrap the payload in a `data` key. List responses wrap the array in `data` with pagination metadata in a `meta` key (`page`, `per_page`, `total`, `last_page`). | P1 |
| NFR-API-002 | Standard Error Envelope | All API error responses must follow a consistent JSON envelope containing: `message` (human-readable string), `errors` (object keyed by field name for validation errors; empty object for non-validation errors), and `status` (integer HTTP status code). | P1 |

> **TODO — API_SPEC.md:** Define the exact JSON structure and example payloads for both the success envelope (NFR-API-001) and the error envelope (NFR-API-002).

---

## 5. Business Rules

Business rules define the logic of the platform independent of implementation. They are non-negotiable and must be enforced at the application level.

### 5.1 XP Rules

| ID | Rule |
|---|---|
| BR-XP-001 | XP can never become negative. XP only increases. |
| BR-XP-002 | XP is awarded only upon full completion of a defined activity, not partial completion. |
| BR-XP-003 | XP amounts per activity type are defined in the database and cannot be overridden by the frontend. |
| BR-XP-004 | The same activity instance cannot award XP more than once (e.g., re-submitting a completed writing prompt does not re-award XP). |
| BR-XP-005 | Level thresholds are cumulative. A user with 500 total XP is at the level whose threshold is at or below 500. |
| BR-XP-006 | Valid XP source activity types are: `vocabulary_study_session`, `vocabulary_quiz`, `grammar_lesson`, `grammar_exercise`, `reading_passage`, `writing_submission`, `ielts_task1_submission`, `ielts_task2_submission`, `quiz_attempt`, `achievement_unlock`, `daily_goal_bonus`, `iq_exercise`, `logic_exercise`. No other source types may be used in application code. These types are seeded into an `xp_activity_types` table and are not hardcoded. |

> **TODO — DATABASE.md:** Create the `xp_activity_types` table with columns `type_key` (unique string identifier), `display_name`, and `default_xp_amount`. Seed all 13 types listed in BR-XP-006.

### 5.2 Streak Rules

| ID | Rule |
|---|---|
| BR-STREAK-001 | A streak increments by exactly 1 for each calendar day in which at least one learning activity is completed. |
| BR-STREAK-002 | Multiple activities on the same calendar day count as a single streak day. |
| BR-STREAK-003 | Missing an entire calendar day with no activity resets the current streak to 0. |
| BR-STREAK-004 | The longest streak record is never reduced. It updates only when the current streak surpasses it. |
| BR-STREAK-005 | A streak freeze may only be consumed once per missed day. Consecutive missed days with one freeze results in a streak break on the second missed day. |
| BR-STREAK-006 | Streak freezes must be explicitly earned. They cannot be purchased. |
| BR-STREAK-007 | A qualifying streak activity is any of the following completed actions: (1) completing a quiz session, (2) completing a vocabulary study session of at least 5 words reviewed or studied, (3) completing a grammar lesson together with its associated exercises, (4) completing a reading passage with all comprehension questions answered, (5) submitting a writing response, (6) submitting an IELTS Task 1 or Task 2, (7) completing at least one IQ or Logic exercise session. Visiting a page, browsing content, or partially starting an activity does not qualify. |
| BR-STREAK-008 | Streak freeze grants and consumptions are recorded as individual log entries in a `streak_freeze_log` table. The user's current available freeze count is always computed as the sum of all grants minus the sum of all consumptions for that user. It is never stored as a direct column value on the user record. |

### 5.3 Achievement Rules

| ID | Rule |
|---|---|
| BR-ACHIEVE-001 | Each achievement may only be unlocked once per user account. |
| BR-ACHIEVE-002 | Achievement unlock conditions are defined in the database. They cannot be manually awarded or triggered by the frontend. |
| BR-ACHIEVE-003 | XP awarded for an achievement is a one-time grant at the moment of first unlock. |
| BR-ACHIEVE-004 | An achievement that has been unlocked cannot be re-locked. |
| BR-ACHIEVE-005 | Achievement condition checks must be performed synchronously within the same request lifecycle as the triggering activity, immediately after XP is awarded or a qualifying action is recorded. Achievement checking must use an event listener pattern: modules emit domain events; they must not call the AchievementService directly. This preserves module independence. |
| BR-ACHIEVE-006 | Achievement unlock conditions must be expressed as typed, structured data — not arbitrary string expressions or hardcoded logic. Supported condition types: `xp_total_reached`, `streak_days_reached`, `quiz_completed_count`, `vocabulary_learned_count`, `level_reached`, `lessons_completed_count`. Each achievement stores a single `condition_type` and `condition_value` (integer). |

> **TODO — DATABASE.md:** Design the `achievements` table with `condition_type` and `condition_value` columns using the types defined in BR-ACHIEVE-006. Ensure the backend evaluation engine handles all listed condition types.
> **TODO — FEATURES.md:** Document the full planned achievement list with names, descriptions, icons, and typed conditions.

### 5.4 Daily Goal Rules

| ID | Rule |
|---|---|
| BR-GOAL-001 | Daily goals reset at 00:00 of the user's configured local timezone, stored as an IANA timezone identifier in the user preferences record. If no timezone has been configured, UTC is used as the default. |
| BR-GOAL-002 | A daily goal target must be a positive integer greater than zero. |
| BR-GOAL-003 | Bonus XP for completing all daily goals is granted only once per calendar day, even if goals are re-completed after a target adjustment. |
| BR-GOAL-004 | Daily goal progress is tracked cumulatively within the calendar day and cannot be manually adjusted by the user. |
| BR-GOAL-005 | Daily goal progress updates must be implemented via an event listener pattern. Modules emit domain events upon activity completion; the DailyGoalService listens for these events and updates goal progress. Modules must not call the DailyGoalService directly. This preserves module independence as defined in the guiding principles. |

### 5.5 Quiz Rules

| ID | Rule |
|---|---|
| BR-QUIZ-001 | A quiz attempt record is created at the moment the session starts. It is finalized upon completion. Abandoned attempts are stored as incomplete. |
| BR-QUIZ-002 | Questions within a quiz session are randomized per attempt. The same question order should not be guaranteed across attempts. |
| BR-QUIZ-003 | An answer once submitted within a quiz session cannot be changed. |
| BR-QUIZ-004 | XP from a quiz is awarded only after the session is completed. Abandoning a quiz does not award XP. |
| BR-QUIZ-005 | A user-created quiz must contain at least one question before it can be attempted. |
| BR-QUIZ-006 | Deleting a quiz does not delete historical attempt records associated with that quiz. |
| BR-QUIZ-007 | Library quizzes (curated) and user-created quizzes (Quiz Builder) share a single quiz data entity at the data layer, distinguished by a `source_type` field with values `curated` or `user_created`. The `created_by` foreign key is null for curated quizzes. This unified model ensures the quiz engine operates identically for both quiz sources without separate code paths. |
| BR-QUIZ-008 | Curated library quizzes may contain a maximum of 50 questions. User-created quizzes may contain a maximum of 100 questions. The minimum for any quiz that can be attempted is 1 question (enforced by FR-QBUILD-007). |
| BR-QUIZ-009 | Quiz session completion is idempotent. A quiz attempt record carries a `status` field with values `pending`, `completed`, or `abandoned`. If a completion request arrives for an attempt already in `completed` status, the system returns the existing result without re-awarding XP, creating a duplicate record, or re-triggering achievement checks. |

### 5.6 Content Rules

| ID | Rule |
|---|---|
| BR-CONTENT-001 | All quiz questions, vocabulary words, grammar content, and reading passages must be stored in the database. None may be hardcoded. |
| BR-CONTENT-002 | Questions presented during a quiz session are retrieved from the database at session start, not pre-loaded in the client. |
| BR-CONTENT-003 | Every piece of content has a difficulty level. No content without a difficulty level may be shown to the user. |
| BR-CONTENT-004 | All learning content entities (vocabulary words, grammar topics, reading passages, writing prompts, quiz questions, quizzes, IQ exercises, logic exercises) use soft deletion. A `deleted_at` timestamp column marks a record as logically deleted without removing it from the database. Soft-deleted content is excluded from all browse, search, and quiz-serving queries but is preserved for historical attempt records and user progress data integrity. |
| BR-CONTENT-005 | Difficulty levels are defined in a dedicated `difficulty_levels` table and referenced by all content entities via foreign key. Difficulty values are never stored as raw strings or hardcoded enums in application code. |
| BR-CONTENT-006 | The system must maintain per-user progress records for all learnable content items: (a) a `user_vocabulary` record per user per word, tracking learned status, SM-2 spaced repetition fields, and last interaction timestamp; (b) a `user_grammar_progress` record per user per grammar topic, tracking completion status and completion timestamp; (c) a `user_reading_progress` record per user per passage, tracking completion status and score. All three relationships are multi-user by design. |

> **TODO — DATABASE.md:** Design `user_vocabulary`, `user_grammar_progress`, and `user_reading_progress` tables with appropriate columns, foreign keys, and query-optimised indexes.
> **TODO — DATABASE.md:** Design the `difficulty_levels` table and add a foreign key reference column to all content tables.

### 5.7 Spaced Repetition Rules

| ID | Rule |
|---|---|
| BR-SR-001 | Vocabulary spaced repetition uses the SM-2 algorithm. User performance on each word review is recorded as a quality score on a 0–5 scale: 0 = complete blackout; 1 = incorrect but familiar; 2 = incorrect but easy to recall correctly after seeing the answer; 3 = correct with significant difficulty; 4 = correct with slight hesitation; 5 = perfect recall. |
| BR-SR-002 | Each `user_vocabulary` record stores the following SM-2 fields: `ease_factor` (float, default 2.5), `interval_days` (integer, days until next review; starts at 1), `repetition_count` (integer, number of consecutive successful reviews; starts at 0), and `next_review_at` (timestamp). The SM-2 algorithm updates all four fields after every word interaction. |
| BR-SR-003 | A word is "due for review" when the current date is on or after `next_review_at`. Words not yet interacted with have a null `next_review_at` and do not appear in the review queue — they must be studied at least once before entering the spaced repetition cycle. |
| BR-SR-004 | A quality score below 3 resets `repetition_count` to 0 and `interval_days` to 1 (re-learning phase). The `ease_factor` is reduced by 0.2, with a minimum floor of 1.3. |

> **TODO — FEATURES.md:** Document the complete SM-2 update formula, the vocabulary study session word interaction model, and the full UX flow for study mode and review mode.

### 5.8 Writing and IELTS Rules

| ID | Rule |
|---|---|
| BR-WRITE-001 | XP is awarded for a writing submission only on the first submission per prompt per user. Subsequent submissions (revisions) to the same prompt are saved and accessible in submission history but do not award additional XP. |
| BR-WRITE-002 | A writing or IELTS submission is rejected if the response body is empty or contains only whitespace. The minimum acceptable submission is at least 1 non-whitespace character; modules define recommended minimum word counts separately and surface warnings, not hard blocks, when below the recommended count. |
| BR-WRITE-003 | IELTS writing tasks (Task 1 and Task 2) and general writing prompts share a single `writing_prompts` table and a single `writing_submissions` table, distinguished by a `module_type` column with values `general`, `ielts_task1`, or `ielts_task2`. All progress tracking queries for IELTS vs. general writing filter by this column. |

> **TODO — DATABASE.md:** Design the `writing_prompts` and `writing_submissions` tables with a `module_type` column indexed for per-user, per-type queries.

---

## 6. User Stories

### 6.1 Authentication

- As a new user, I want to register with my name, email, and password, so that I can access the BrainForge platform.
- As a registered user, I want to log in securely, so that I can resume my learning from where I left off.
- As a logged-in user, I want to log out from any device, so that my account remains secure.
- As a user who forgets their password, I want to change my password from the Settings page, so that I can regain full access.

### 6.2 Dashboard

- As a returning user, I want to see a personalized dashboard when I log in, so that I immediately know my current streak, XP, and what to study today.
- As a user, I want to see my daily goals and their progress on the dashboard, so that I stay motivated to complete my planned activities.
- As a user, I want to quickly access any learning module from the dashboard, so that I can start studying without navigating through multiple pages.

### 6.3 Vocabulary

- As a learner, I want to browse vocabulary words by category and difficulty, so that I can focus on words relevant to my current level.
- As a learner, I want to study words one at a time with definitions and example sentences, so that I understand them in context.
- As a learner, I want to mark words as learned and be reminded to review them later, so that I build long-term retention.
- As a learner, I want to take vocabulary quizzes, so that I can test my knowledge and earn XP.

### 6.4 Grammar

- As a learner, I want to browse grammar topics by level, so that I can study the rules relevant to my proficiency.
- As a learner, I want to read clear explanations with examples, so that I understand the rule before practicing it.
- As a learner, I want to complete grammar exercises after reading each topic, so that I confirm my understanding before moving on.

### 6.5 Reading

- As a learner, I want to read passages at my chosen difficulty level, so that I can practice comprehension without being overwhelmed.
- As a learner, I want to answer comprehension questions after reading, so that I test and reinforce my understanding.
- As a learner, I want to see my reading scores and history, so that I can track my comprehension improvement over time.

### 6.6 Writing

- As a learner, I want to respond to structured writing prompts, so that I can practice organizing and expressing my ideas in English.
- As a learner, I want to see a word count target and guidance for each prompt, so that I understand the expected scope of my response.
- As a learner, I want to review my past writing submissions, so that I can reflect on my improvement over time.

### 6.7 IELTS

- As an IELTS candidate, I want to practice Writing Task 1 and Task 2 in a realistic format, so that I prepare for the actual exam.
- As an IELTS candidate, I want to read sample answers after submitting my own, so that I understand what a strong response looks like.
- As an IELTS candidate, I want to practice IELTS-format reading questions, so that I become familiar with question types before the exam.

### 6.8 Quiz System

- As a learner, I want to browse a library of curated quizzes, so that I can find challenging material across all subjects.
- As a learner, I want to attempt a quiz and receive immediate feedback, so that I learn from my mistakes right away.
- As a learner, I want to create my own custom quizzes, so that I can practice material that is most relevant to my personal study goals.
- As a learner, I want to review all my past quiz attempts, so that I can identify patterns in my mistakes.

### 6.9 Progress

- As a learner, I want to see my XP growth over time as a chart, so that I have a clear visual picture of my learning momentum.
- As a learner, I want to see my completion rates per module, so that I understand where I am spending my study time.
- As a learner, I want to see my quiz accuracy rates, so that I can identify which subject areas need more attention.

### 6.10 Achievements

- As a learner, I want to be automatically rewarded when I reach milestones, so that my consistency is recognized.
- As a learner, I want to view all available achievements in a gallery, so that I know what goals to aim for.
- As a learner, I want to see unlocked achievements highlighted on my dashboard, so that progress feels celebrated.

### 6.11 IQ and Logic

- As a learner, I want to train abstract reasoning and logical thinking through structured exercises, so that I sharpen cognitive skills that benefit all areas of learning.
- As a learner, I want to see detailed explanations for logic and IQ answers, so that I learn the reasoning process, not just the answer.

### 6.12 Settings

- As a user, I want to update my display name and password, so that I can keep my account information current.
- As a user, I want to configure my daily goals, so that the platform adapts to my personal study schedule.
- As a user, I want to switch between light and dark mode, so that I can choose the reading environment that is most comfortable.

---

## 7. Acceptance Criteria

> The following are feature-level acceptance criteria using **Given-When-Then** format.

### 7.1 Registration

**Given** a visitor is on the registration page
**When** they submit a valid name, email, and password (minimum 8 characters)
**Then** their account is created, they are authenticated, and they are redirected to the Dashboard.

**Given** a visitor submits a registration form with an email already in use
**When** the form is submitted
**Then** a validation error is shown indicating the email is already registered, and no duplicate account is created.

### 7.2 Login

**Given** a user with a registered account is on the login page
**When** they submit the correct email and password
**Then** they are authenticated and redirected to the Dashboard.

**Given** a user submits an incorrect password
**When** the login form is submitted
**Then** a generic error message is displayed (not revealing which field is incorrect), and no session is created.

### 7.3 XP Earning

**Given** a user completes a quiz
**When** the quiz session ends
**Then** XP is credited to their total, a log entry is created, and the dashboard XP widget reflects the new total.

**Given** a user's total XP crosses a level threshold
**When** the XP is credited
**Then** the user's level increments, the new level is displayed, and any level-based achievements are triggered.

### 7.4 Streak

**Given** a user completes a learning activity today for the first time
**When** the activity is recorded
**Then** today's streak activity is marked, and the streak counter increments (if the previous day also had activity).

**Given** a user does not complete any activity for an entire calendar day
**When** the next calendar day begins
**Then** the current streak resets to 0 (unless a streak freeze is active).

### 7.5 Quiz Attempt

**Given** a user starts a quiz
**When** all questions are answered and submitted
**Then** the results screen is shown with score, correct count, XP earned, and a link to review mode.

**Given** a user is in review mode after completing a quiz
**When** they view an incorrectly answered question
**Then** the question, their answer (highlighted as incorrect), the correct answer, and an explanation are all visible.

### 7.6 Achievement Unlock

**Given** an achievement condition is met (e.g., 7-day streak reached)
**When** the relevant activity is recorded
**Then** the achievement is unlocked exactly once, XP is awarded once, and it appears in the achievement gallery as unlocked.

### 7.7 Daily Goals

**Given** a user has active daily goals
**When** they complete an activity that counts toward a goal
**Then** the goal's progress counter increments automatically, and the dashboard goal widget reflects the updated state.

**Given** it is midnight (00:00)
**When** the new calendar day begins
**Then** all daily goal progress values reset to zero for all users.

### 7.8 Vocabulary Review

**Given** a user has marked words as learned
**When** enough time has passed based on the spaced repetition schedule
**Then** those words are queued for review and appear in the review mode session.

### 7.9 Custom Quiz

**Given** a user creates a quiz with at least one question
**When** they attempt to start that quiz
**Then** the quiz session engine launches with their custom questions.

**Given** a user tries to attempt a custom quiz with zero questions
**When** they click the start button
**Then** the attempt is blocked and a message is shown explaining that at least one question is required.

---

## 8. Constraints

The following constraints are absolute boundaries for Version 1 and must not be exceeded or worked around.

| # | Constraint | Implication |
|---|---|---|
| C-01 | Web platform only | No mobile application, no native APIs, no React Native |
| C-02 | Local development environment only | No cloud hosting, no CDN, no environment-specific configuration beyond localhost |
| C-03 | English language only | All UI text, content, and instructions are in English; no internationalization infrastructure required |
| C-04 | No AI integration | No calls to OpenAI, Gemini, Anthropic, or any LLM. No AI grammar checking, essay feedback, or question generation |
| C-05 | No Listening module | No audio playback, audio file upload, or speech recognition |
| C-06 | No Speaking module | No audio recording, no pronunciation scoring |
| C-07 | No Multiplayer | No real-time features, WebSockets, or competitive sessions |
| C-08 | No Payment system | No Stripe, PayPal, or any billing integration |
| C-09 | No Subscription model | No plans, tiers, or access restrictions based on payment |
| C-10 | No Notification system | No email, push, or in-app notification infrastructure |
| C-11 | No Social features | No user relationships, followers, likes, shares, or community feed |
| C-12 | No Admin Panel | No backend content management UI; content is managed directly through database seeds and migrations |
| C-13 | No Docker | No containerization configuration |
| C-14 | No CI/CD | No automated testing pipelines or deployment scripts |
| C-15 | Single authenticated user | User management supports the architecture for multi-user, but Version 1 is operated by one person |

---

## 9. Assumptions

The following assumptions have been made during the planning phase. If any assumption proves incorrect, the affected requirements must be reviewed and updated.

| # | Assumption | Impact if Incorrect |
|---|---|---|
| A-01 | The user's local machine meets all technical prerequisites (Node.js, PHP, PostgreSQL) | Setup cannot be completed without addressing the environment gap |
| A-02 | Content (vocabulary, grammar, reading passages, questions) will be seeded into the database during development | Without seed data, modules cannot be functionally tested |
| A-03 | Each user configures their local timezone via Settings (FR-SETTINGS-009). Daily goal resets and streak activity windows are calculated using the user's configured IANA timezone. If no timezone is configured, UTC is used as the default. | If a user does not configure their timezone, their streak and daily goal windows will align with UTC midnight, which may not match their actual local midnight |
| A-04 | The user interface is in English only; no translation or localization is anticipated for Version 1 | Any multilingual requirement would necessitate i18n architecture additions |
| A-05 | The quiz engine handles all question types needed by all modules; no module requires a fundamentally different assessment format | If a module (e.g., spelling exercises, drag-and-drop matching) requires an incompatible interaction, the quiz engine must be extended |
| A-06 | Browser-based local storage or cookie management is sufficient for session persistence in development | In a production environment, session security requirements may differ |
| A-07 | All reading passages and writing prompts will be manually created and seeded, not generated | Content creation effort is a human task, not automated |
| A-08 | IELTS content reflects general IELTS Academic format; this is not officially licensed IELTS material | Users understand the content is preparation practice, not official IELTS-certified material |
| A-09 | In Version 1, quiz review mode displays the current version of a question — not the version that was active at the time of the original attempt. Content versioning and question-history tracking are deferred to a future version. | If a question is edited after an attempt, the review mode will show the edited version, which may not match what the user originally answered |
| A-10 | A minimum viable content baseline must be seeded into the database before any module is considered testable. Minimums: Vocabulary (200 words, ≥3 categories), Grammar (20 topics, ≥3 levels), Reading (10 passages, ≥2 difficulty levels), Writing Prompts (15 general prompts), IELTS Task 1 (5 prompts), IELTS Task 2 (5 prompts), Quiz Library (10 curated quizzes), IQ Exercises (20 questions), Logic Exercises (20 questions). | Insufficient seed data prevents meaningful functional testing of any module and will block release validation |

---

## 10. Risks

| # | Risk | Likelihood | Impact | Mitigation |
|---|---|---|---|---|
| R-01 | Content volume bottleneck | High | High | Establish a minimum viable content baseline per module before releasing it; document content requirements alongside technical ones |
| R-02 | Documentation drift | High | High | Enforce the rule that documentation is updated before or alongside code; include documentation review as part of every task definition |
| R-03 | Quiz engine inflexibility | Medium | High | Design the quiz engine to be format-agnostic from the start; avoid assumptions about question type in the data model |
| R-04 | Scope creep from out-of-scope features | Medium | High | All non-goals are formally documented; any deviation requires an explicit update to this document and the roadmap |
| R-05 | XP and achievement logic inconsistency | Medium | Medium | Centralize XP and achievement logic in dedicated service classes; avoid distributing this logic across multiple controllers |
| R-06 | Spaced repetition complexity | Medium | Medium | Define the spaced repetition algorithm in this document before implementation; do not defer the algorithm design to the engineering phase |
| R-07 | Progress data performance | Low | Medium | Ensure all progress-related queries use indexed columns; avoid N+1 queries in progress aggregation |
| R-08 | Over-engineering for scalability | Low | Low | Keep implementation simple for Version 1; scalability provisions in the schema are sufficient; do not build infrastructure that won't be used |

---

## 11. Future Considerations

The following expansions are anticipated but must not influence Version 1 implementation decisions. They are documented here to inform architectural awareness.

| # | Future Feature | Relevant Version | Architectural Pre-Condition |
|---|---|---|---|
| FC-01 | Admin Panel for content management | Version 2 | Role-based access control (RBAC) must be addable to the existing user model |
| FC-02 | Email and in-app notifications | Version 2 | Notification preference schema should be addable without breaking the current user table |
| FC-03 | AI grammar correction and essay scoring | Version 3 | API-first design means AI calls can be added as a service layer without frontend or schema changes |
| FC-04 | Listening module | Version 3 | Media file storage infrastructure (local or CDN) must be addable without disrupting existing content tables |
| FC-05 | Speaking module | Version 3 | Audio recording requires separate browser permission handling; no current architecture conflict |
| FC-06 | Multiplayer quiz battles | Version 4 | Requires WebSocket infrastructure; current REST API is unaffected |
| FC-07 | Leaderboards and social features | Version 4 | User relationship tables must be addable; privacy settings should be anticipated in the user schema |
| FC-08 | Marketplace for community content | Version 5 | Content ownership metadata should be anticipated in content table design |
| FC-09 | Mobile application | Version 5 | The existing REST API is already mobile-compatible by design |
| FC-10 | New learning modules (Math, Programming, Science) | Version 3+ | Modular architecture means new domains add tables and routes without touching existing ones |
| FC-11 | Multi-language UI | Version 4+ | i18n can be layered over the existing component structure without architectural changes |

---

## 12. Cross-References

| Document | Relationship to This Document |
|---|---|
| [`PROJECT_OVERVIEW.md`](PROJECT_OVERVIEW.md) | Parent document; this requirements document is derived from Section 4 (Goals) and Section 6 (Scope) |
| [`FEATURES.md`](FEATURES.md) | Expands each requirement in Section 3 into detailed feature specifications |
| [`USER_FLOW.md`](USER_FLOW.md) | Maps user journeys that implement the user stories defined in Section 6 |
| [`DATABASE.md`](../architecture/DATABASE.md) | Implements the data storage requirements implied by all functional requirements |
| [`API_SPEC.md`](../architecture/API_SPEC.md) | Implements the API contract that fulfills each functional requirement |
| [`UI_GUIDELINES.md`](../development/UI_GUIDELINES.md) | Implements the usability and accessibility non-functional requirements in Section 4 |
| [`DEVELOPMENT_RULES.md`](../development/DEVELOPMENT_RULES.md) | Implements the maintainability, consistency, and code quality non-functional requirements |
| [`ROADMAP.md`](ROADMAP.md) | Sequences the delivery of the functional requirements across development phases |
| [`CHANGELOG.md`](../development/CHANGELOG.md) | Records when each requirement transitions from planned to implemented |

---

*BrainForge Product Requirements — v0.1.0*
*This document is authoritative for all functional and non-functional requirements.*
*All implementation documents (DATABASE.md, API_SPEC.md, UI_GUIDELINES.md) must remain consistent with the requirements defined here.*
