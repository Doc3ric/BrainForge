# BrainForge — Feature Specifications

> **Document Type:** Product Documentation
> **Location:** `docs/product/FEATURES.md`
> **Version:** 0.1.2
> **Last Updated:** 2026-07-31 (Amended: Phase 4 Vocabulary)
> **Status:** Frozen
> **Derived From:** `PRODUCT_REQUIREMENTS.md`

## Purpose
This document describes how every feature in BrainForge behaves from the user's perspective. It bridges the gap between the functional requirements (`PRODUCT_REQUIREMENTS.md`) and the technical implementation, detailing user journeys, UI states, validation rules, and gamification integration.

---

## 🏗️ Shared Infrastructure

### Quiz Session Engine (M-14)
*This shared engine is invoked by Vocabulary, Grammar, Quiz Library, Quiz Builder, IQ Training, and Logic Training.*

1. **Feature Overview**: A stateful, unified engine for presenting questions, recording answers, and generating results.
2. **Purpose**: To provide a consistent assessment experience across all learning modules without duplicating logic.
3. **User Value**: Familiar and predictable quiz interface, immediate feedback, and seamless progression.
4. **Related Requirements**: FR-QENG-001, FR-QATTEMPT-001 to 012, BR-QUIZ-001 to 009.
5. **Entry Points**: "Start Quiz" or "Start Exercise" buttons in dependent modules.
6. **User Journey**: User clicks Start -> Sees Question 1 -> Selects Answer -> Submits -> Advances -> Repeats until final question -> Views Result Summary -> Optionally enters Review Mode.
7. **Functional Behavior**: Initializes session via API. Randomizes question order per attempt. Presents one question per screen. Locks in answers upon submission (no backward navigation). Finalizes session upon last question submission.
8. **Validation Rules**: Answer selection is required to proceed. Session must have at least 1 question.
9. **Empty States**: Not applicable (blocked by minimum 1 question rule).
10. **Loading States**: Full-page skeleton loader while fetching initial question batch. Spinner overlay on the "Next/Submit" button while recording answers.
11. **Error States**: "Network Error: Unable to submit answer. Retrying..." toast notification.
12. **Edge Cases**: Browser refresh resumes the current question via session state. Abandoned quizzes are marked incomplete and award zero XP. Duplicate completion requests are idempotent and return the existing result.
13. **XP & Gamification**: Awards XP upon successful completion (amount based on score and module type).
14. **Achievement Integration**: Emits `QuizCompleted` event. Triggers condition checks for quiz count and accuracy milestones.
15. **Daily Goal Integration**: Emits event to DailyGoalService to increment "Quizzes Completed" targets.
16. **Streak Integration**: Triggers streak check for qualifying activities (BR-STREAK-007).
17. **Dependencies**: Dependent module must provide questions matching the standard schema.
18. **Future Expansion**: Timed per-question countdowns, drag-and-drop question types, audio questions.
19. **Out of Scope**: Adaptive difficulty (branching logic) during the quiz, multiplayer real-time battles.

### Global Pagination & List UX Standard (NFR-SCALE-005)
*This standard applies universally to all list-based views (Quiz Library, Progress History, Vocabulary List, Writing History, etc.).*
1. **Feature Overview**: A unified approach to loading and rendering large datasets in the UI.
2. **Functional Behavior**: All list views utilize an infinite-scroll "Load More" pattern. The frontend requests the next page when the user scrolls near the bottom of the list.
3. **Data Constraints**: The default page size is 20 items. The maximum page size allowed by the backend is 100 items. Unpaginated list requests are strictly prohibited.
4. **Loading States**: Initial load uses a skeleton grid/list matching the expected item count. Subsequent page loads display an inline spinner at the bottom of the list.
5. **Empty States**: If the first page returns 0 items, a localized "No items found" message with a relevant call-to-action is displayed.
6. **End-of-List Indicators**: When the final page is reached, the spinner is replaced by a subtle "You've reached the end" text indicator.

---

## 🔐 Core Platform

### Global Navigation (FR-NAV-001)
1. **Feature Overview**: The primary routing and layout scaffold for the application.
2. **Desktop Hierarchy**: A persistent left-hand sidebar divided into logical groups: Core (Dashboard, Progress, Achievements), Learning (Vocabulary, Grammar, Reading, Writing, IELTS), Quizzes (Library, Builder), and Cognitive (IQ, Logic). Settings and Logout reside at the bottom.
3. **Mobile Behavior**: The sidebar collapses into a hidden drawer accessible via a hamburger menu in the top navigation bar.
4. **Active State**: The currently active route is visually highlighted in the sidebar (e.g., contrasting background color and bold text).
5. **Authenticated vs. Unauthenticated**: The sidebar is only visible to authenticated users. Unauthenticated users see a minimalist top header with Login/Register links.
6. **Accessibility**: All navigation items must be keyboard-focusable and include appropriate ARIA roles.

### Authentication (M-01)
1. **Feature Overview**: User registration, login, logout, and session persistence.
2. **Purpose**: Securely identify users and protect their data.
3. **User Value**: Allows users to save their progress, earn XP, and access their data across sessions.
4. **Related Requirements**: FR-AUTH-001 to 010.
5. **Entry Points**: Landing Page (Login/Register buttons), unauthenticated access to protected routes.
6. **User Journey**: User navigates to Register -> Enters details -> Clicks Submit -> Redirected to Dashboard. Returning users: Login -> Enters credentials -> Redirected to Dashboard. Logout from Sidebar -> Redirected to Login.
7. **Functional Behavior**: Authenticates against PostgreSQL via Laravel Sanctum. Issues an HTTP-only cookie for session persistence. Protects all routes except `/login` and `/register`.
8. **Validation Rules**: Email must be unique and valid. Password >= 8 characters. Display name cannot be empty.
9. **Empty States**: N/A for Auth forms.
10. **Loading States**: Submit button shows a spinner/disabled state during API request.
11. **Error States**: Inline validation errors ("Email already in use"). Generic "Invalid credentials" on login failure.
12. **Edge Cases**: Expired session token redirects to login with a "Session expired" toast. Attempting to access login while already authenticated redirects to Dashboard.
13. **XP & Gamification**: None.
14. **Achievement Integration**: Registration is a prerequisite for all achievements.
15. **Daily Goal Integration**: None.
16. **Streak Integration**: None.
17. **Dependencies**: Laravel Sanctum, PostgreSQL `users` table.
18. **Future Expansion**: OAuth/Social Login (Google, GitHub), Two-Factor Authentication (2FA).
19. **Out of Scope**: Email verification, social login, multiple sessions management UI.

### User Profile (M-02)
1. **Feature Overview**: Dedicated page displaying user identity, level, XP, and learning statistics.
2. **Purpose**: Give the user a central identity and progress summary.
3. **User Value**: Fosters ownership and pride in learning accomplishments.
4. **Related Requirements**: FR-PROF-001 to 010.
5. **Entry Points**: Clicking the user avatar in the Sidebar/Header.
6. **User Journey**: User clicks profile icon -> Views statistics, join date, current level, and bio -> Optionally uploads a new avatar.
7. **Functional Behavior**: Fetches aggregated statistics (total XP, quizzes taken, achievements). Handles avatar upload to local storage.
8. **Validation Rules**: Avatar upload must be an image file (JPG, PNG, WEBP) under 2MB. Bio max 300 characters.
9. **Empty States**: If no custom avatar, displays a generated placeholder (initials). If no bio, displays "No bio provided."
10. **Loading States**: Profile skeleton loader displaying shapes of stats cards.
11. **Error States**: "Failed to load profile data" full-page error. "Image too large" toast on avatar upload.
12. **Edge Cases**: Uploading a non-image file triggers immediate frontend validation.
13. **XP & Gamification**: Displays Total XP, current Level, and progress bar to next level.
14. **Achievement Integration**: Displays a summary of unlocked achievements.
15. **Daily Goal Integration**: None.
16. **Streak Integration**: Displays current streak and longest streak.
17. **Dependencies**: File storage system for avatars.
18. **Future Expansion**: Public profiles, user search, custom profile banners.
19. **Out of Scope**: Social following, public leaderboards, messaging.

### Dashboard (M-03)
1. **Feature Overview**: The default landing page summarizing activity, goals, and quick actions.
2. **Purpose**: Provide immediate direction on what to study next.
3. **User Value**: Reduces friction to start learning; keeps motivation high by front-loading progress.
4. **Related Requirements**: FR-DASH-001 to 009.
5. **Entry Points**: Default route after login (`/dashboard`).
6. **User Journey**: User logs in -> Sees personalized greeting -> Checks Daily Goals widget -> Reviews Streak -> Clicks a quick-access card to start a module.
7. **Functional Behavior**: Aggregates data from Goals, XP, Streak, and Recent Activity APIs into distinct widgets.
8. **Validation Rules**: N/A (Read-only view).
9. **Empty States**: "No recent activity" message in the recent activity widget for new users. "No daily goals set" (fallback if defaults were removed).
10. **Loading States**: Independent skeleton loaders for each widget (allows partial progressive rendering).
11. **Error States**: If a specific widget fails to load (e.g., Recent Activity), that widget shows a localized "Failed to load" state with a retry button, keeping the rest of the dashboard usable.
12. **Edge Cases**: Greeting changes based on user's local timezone (Morning, Afternoon, Evening).
13. **XP & Gamification**: Prominently displays Level and XP progress bar.
14. **Achievement Integration**: Displays a notification/banner for recently unlocked achievements (past 24h).
15. **Daily Goal Integration**: Prominent Daily Goals widget showing progress bars for today's targets.
16. **Streak Integration**: Prominent Streak widget (flame icon + count + available freeze balance).
17. **Dependencies**: Relies on Progress, XP, Streak, and Goal aggregation APIs.
18. **Future Expansion**: Customizable widget layouts, recommended lessons AI widget.
19. **Out of Scope**: Drag-and-drop dashboard customization.

### Settings (M-19)
1. **Feature Overview**: Central hub for account, preference, and goal configuration.
2. **Purpose**: Allow users to tailor the platform to their needs.
3. **User Value**: Personalization and control over account security.
4. **Related Requirements**: FR-SETTINGS-001 to 009.
5. **Entry Points**: Sidebar navigation "Settings" link.
6. **User Journey**: User navigates to Settings -> Updates display name, timezone, theme, or daily goals -> Clicks Save -> Sees success confirmation.
7. **Functional Behavior**: Persists preferences to the database. Updates UI theme immediately (Light/Dark).
8. **Validation Rules**: Daily goal targets must be integers > 0. Timezone must be a valid IANA string.
9. **Empty States**: N/A.
10. **Loading States**: Inline spinner on "Save Changes" buttons.
11. **Error States**: Field-level validation errors.
12. **Edge Cases**: Changing timezone dynamically shifts the reset window for daily goals, but does not retroactively change past streak calculations.
13. **XP & Gamification**: Configures the Daily Goal targets that award XP.
14. **Achievement Integration**: None directly.
15. **Daily Goal Integration**: Interface for defining daily targets.
16. **Streak Integration**: Interface for defining the timezone used for streak windows.
17. **Dependencies**: None.
18. **Future Expansion**: Notification preferences, billing settings.
19. **Out of Scope**: Custom CSS themes, language selection.

---

## 🏆 Gamification & Progression

### XP System (M-05)
1. **Feature Overview**: Global experience point system tracking all learning activities.
2. **Purpose**: Quantify effort and drive long-term engagement through leveling.
3. **User Value**: Tangible proof of work; psychological reward for completing tasks.
4. **Related Requirements**: FR-XP-001 to 008, BR-XP-001 to 006.
5. **Entry Points**: Everywhere (XP updates passively upon activity completion).
6. **User Journey**: User completes an activity -> Toast notification appears "+15 XP" -> Dashboard updates -> If threshold crossed, "Level Up!" modal appears.
7. **Functional Behavior**: Listens for domain events (e.g., `VocabularySessionCompleted`). Looks up XP value from `xp_activity_types`. Inserts log entry. Recalculates total XP and Level.
8. **Validation Rules**: XP cannot be negative. Source type must exist in `xp_activity_types`.
9. **Empty States**: Total XP starts at 0. Level starts at 1.
10. **Loading States**: N/A (Backend synchronous process).
11. **Error States**: Handled gracefully (transaction rollback if XP log fails).
12. **Edge Cases**: Multiple events firing simultaneously (handled by DB transactions to prevent race conditions on Total XP).
13. **XP & Gamification**: *This is the core gamification engine.*
14. **Achievement Integration**: Emits `XPAwarded` and `LevelReached` events triggering achievement checks.
15. **Daily Goal Integration**: Supports XP accumulation as a Daily Goal target type.
16. **Streak Integration**: None directly (both react to the same activities).
17. **Dependencies**: `xp_activity_types` seed data.
18. **Future Expansion**: XP multipliers (e.g., Weekend double XP), XP economy (spending XP on cosmetics).
19. **Out of Scope**: XP decay, negative XP (penalties).

### Streak System (M-06)
1. **Feature Overview**: Tracks consecutive calendar days of learning activity.
2. **Purpose**: Drive daily habit formation.
3. **User Value**: Strong psychological motivator to return to the platform daily to avoid "breaking the streak."
4. **Related Requirements**: FR-STREAK-001 to 007, BR-STREAK-001 to 008.
5. **Entry Points**: Dashboard widget, Profile page.
6. **User Journey**: User logs in -> Completes first activity -> Streak increments -> Visual celebration. User unlocks a milestone achievement -> Earns a Streak Freeze -> Toast notification appears. User misses a day -> Streak Freeze is automatically consumed -> Notification warns user upon next login -> Streak is maintained. If no freeze is available -> Streak breaks to 0.
7. **Functional Behavior**: Listens for qualifying domain events (BR-STREAK-007). Increments streak if no activity occurred today. Evaluates missed days at 00:00 local time. Automatically consumes a freeze from the balance (calculated as grants minus consumptions) if a day is missed.
8. **Validation Rules**: Only qualifying activities (BR-STREAK-007) increment the streak.
9. **Empty States**: Streak = 0.
10. **Loading States**: N/A.
11. **Error States**: N/A.
12. **Edge Cases**: User changes timezone mid-day (handled by storing timezone at time of last activity). Using a streak freeze consumes it automatically on a missed day.
13. **XP & Gamification**: Integrates closely with XP visual feedback.
14. **Achievement Integration**: Emits `StreakIncremented` event; triggers streak-based achievements (e.g., "7 Day Streak").
15. **Daily Goal Integration**: Completing a daily goal is often a qualifying streak activity.
16. **Streak Integration**: *Core feature.* The available freeze balance is tracked globally and displayed on the Dashboard widget. Freezes are explicitly earned via specific milestone achievements.
17. **Dependencies**: User's timezone setting.
18. **Future Expansion**: Streak repair (purchasing a missed day repair).
19. **Out of Scope**: Buying streak freezes with real money.

### Daily Goals (M-04)
1. **Feature Overview**: Configurable daily targets for learning volume.
2. **Purpose**: Provide structured, achievable daily milestones.
3. **User Value**: Gives users a clear "when to stop" signal for the day, preventing burnout while ensuring progress.
4. **Related Requirements**: FR-GOAL-001 to 008, BR-GOAL-001 to 005.
5. **Entry Points**: Dashboard widget, Settings page.
6. **User Journey**: User sees 0/10 Vocabulary target -> Completes a vocabulary session -> Target updates to 5/10 -> Completes again -> Target hits 10/10 -> Goal marked complete -> Earns bonus XP.
7. **Functional Behavior**: Listens for domain events. Increments progress counters. Resets all progress to 0 at 00:00 in user's configured timezone.
8. **Validation Rules**: Targets must be > 0.
9. **Empty States**: If no goals configured, dashboard widget displays "Configure your daily goals" prompt.
10. **Loading States**: Progress bar animation.
11. **Error States**: N/A.
12. **Edge Cases**: Goal target is lowered mid-day below current progress (immediately marks as complete). Bonus XP is only awarded once per day even if target is changed and re-completed.
13. **XP & Gamification**: Awards Bonus XP upon completing all daily goals.
14. **Achievement Integration**: Triggers achievements for "Completed all daily goals X times".
15. **Daily Goal Integration**: *Core feature.*
16. **Streak Integration**: Overlaps with streak (completing a goal always counts as a streak activity).
17. **Dependencies**: None.
18. **Future Expansion**: Goal streaks, smart goal recommendations.
19. **Out of Scope**: Goals spanning multiple days (Weekly/Monthly goals).

### Achievements (M-16)
1. **Feature Overview**: Unlockable badges awarded for reaching milestones.
2. **Purpose**: Long-term engagement and celebrating major milestones.
3. **User Value**: Collectible rewards that represent dedication and mastery.
4. **Related Requirements**: FR-ACHIEVE-001 to 009, BR-ACHIEVE-001 to 006.
5. **Entry Points**: Dashboard notification, Profile gallery.
6. **User Journey**: User reaches 50 quizzes -> `QuizCompleted` event fires -> Condition `quiz_completed_count >= 50` passes -> Achievement unlocked -> Toast notification appears -> User views it in Profile gallery.
7. **Functional Behavior**: Event listener intercepts all learning events. Evaluates typed conditions (e.g., `xp_total_reached`, `streak_days_reached`) against user state. Inserts unlock record if passed and not already unlocked.
8. **Validation Rules**: Achievements can only be unlocked once per user.
9. **Empty States**: Gallery shows locked achievements as grayed out with silhouette icons.
10. **Loading States**: Skeleton gallery grid.
11. **Error States**: N/A.
12. **Edge Cases**: Unlocking multiple achievements simultaneously (e.g., level up + XP milestone + streak milestone) queues notifications to display sequentially.
13. **XP & Gamification**: Awards a one-time XP bonus upon unlock.
14. **Achievement Integration**: *Core feature.*
15. **Daily Goal Integration**: None.
16. **Streak Integration**: Triggers based on streak milestones.
17. **Dependencies**: Typed conditions evaluation engine in backend.
18. **Future Expansion**: Hidden/Secret achievements, tiered achievements (Bronze/Silver/Gold).
19. **Out of Scope**: Manually awarding achievements, leaderboards of achievements.

### Progress Tracking (M-15)
1. **Feature Overview**: Comprehensive charts and statistics of user activity.
2. **Purpose**: Visualize long-term growth and identify weak areas.
3. **User Value**: Provides analytical insight into learning habits and module mastery.
4. **Related Requirements**: FR-PROGRESS-001 to 010.
5. **Entry Points**: Sidebar navigation "Progress" link.
6. **User Journey**: User navigates to Progress -> Views XP over time chart -> Scrolls down to view accuracy rates in quizzes -> Views vocabulary learned count.
7. **Functional Behavior**: Aggregates data dynamically from `xp_logs`, `quiz_attempts`, and user-content relationship tables. Displays via frontend charting library.
8. **Validation Rules**: N/A (Read-only).
9. **Empty States**: "Not enough data yet" for charts if user has < 2 days of activity.
10. **Loading States**: Chart skeleton loaders.
11. **Error States**: "Failed to load chart data."
12. **Edge Cases**: Extremely high volume of `xp_logs` (queries must be optimized or cached to meet 500ms NFR). Historical attempts linked to soft-deleted content (e.g., a deleted quiz or retired grammar topic) are rendered as "[Deleted Content] - Score: X". Action buttons like "Retake" or "Review" are disabled for soft-deleted entities to preserve historical accuracy without breaking UX.
13. **XP & Gamification**: Visualizes XP history.
14. **Achievement Integration**: None directly.
15. **Daily Goal Integration**: Shows historical goal completion chart.
16. **Streak Integration**: Shows streak calendar history.
17. **Dependencies**: Frontend charting library (e.g., Recharts).
18. **Future Expansion**: Export data to CSV, comparison with average users.
19. **Out of Scope**: Predictive analytics (e.g., "You will reach Level 10 by Friday").

---

## 📚 Learning Modules

### Vocabulary (M-07)
1. **Feature Overview**: Word learning, categorisation, and spaced repetition review.
2. **Purpose**: Structured acquisition of English vocabulary.
3. **User Value**: Efficient memory retention using scientifically proven SM-2 algorithm.
4. **Related Requirements**: FR-VOC-001 to 012, BR-SR-001 to 004, BR-CONTENT-004 to 006.
5. **Entry Points**: Dashboard quick-access card, Sidebar "Vocabulary" link.
6. **User Journey**: User browses category -> Enters Study Mode -> Reads definition and examples -> Marks as "Learned". Later: User sees "10 Words Due for Review" -> Enters Review Mode -> Grades recall (0-5) -> SM-2 updates next review date.
7. **Functional Behavior**: Manages a unified list of words. `user_vocabulary` pivot tracks learned status and SM-2 variables. "Study Mode" introduces new words and uses server-side study-session records for idempotency and streak tracking. "Review Mode" presents only words where `next_review_at <= now()` and enforces idempotency via review logs.
8. **Validation Rules**: Cannot review a word that hasn't been marked learned. Study session must contain at least 5 words to qualify for a streak increment.
9. **Empty States**: "No words due for review right now."
10. **Loading States**: Skeleton list for browsing; spinner when grading a review.
11. **Error States**: "Failed to save review score."
12. **Edge Cases**: User marks a word "Blackout" (score < 3) -> Word immediately re-enters learning phase (interval = 1 day).
13. **XP & Gamification**: XP awarded for completing study sessions and quizzes.
14. **Achievement Integration**: Emits events triggering vocabulary volume achievements (e.g., "Learned 500 Words").
15. **Daily Goal Integration**: Contributes to "Vocabulary Words Studied" daily target.
16. **Streak Integration**: Completing a study session of >= 5 words increments streak.
17. **Dependencies**: Shared Quiz Engine (for vocabulary quizzes). SM-2 algorithm implementation.
18. **Future Expansion**: Audio pronunciations, user-submitted example sentences.
19. **Out of Scope**: Automated word scraping, spelling mini-games.

### Grammar (M-08)
1. **Feature Overview**: Structured grammar lessons with rules and practice exercises.
2. **Purpose**: Build foundational understanding of English sentence structure.
3. **User Value**: Clear, leveled explanations followed immediately by practical application.
4. **Related Requirements**: FR-GRAM-001 to 009, BR-CONTENT-004 to 006.
5. **Entry Points**: Sidebar "Grammar" link.
6. **User Journey**: User browses topics by level (e.g., "Present Perfect") -> Reads the lesson -> Clicks "Practice" -> Completes inline exercises -> Submits -> Sees immediate feedback -> Marks topic as completed.
7. **Functional Behavior**: Tracks completion via `user_grammar_progress`. Practice exercises evaluate against predefined correct answers.
8. **Validation Rules**: Must answer all practice exercises to mark topic as completed.
9. **Empty States**: "No topics available for this level."
10. **Loading States**: Skeleton text blocks for lesson loading.
11. **Error States**: "Failed to submit exercise answers."
12. **Edge Cases**: User abandons lesson halfway through exercises (progress is not saved; must restart exercises).
13. **XP & Gamification**: XP awarded for completing the lesson and its exercises.
14. **Achievement Integration**: Emits events for grammar volume achievements.
15. **Daily Goal Integration**: None specifically (contributes to global XP goal).
16. **Streak Integration**: Completing a lesson + exercises increments streak.
17. **Dependencies**: Shared Quiz Engine (for multi-topic grammar quizzes).
18. **Future Expansion**: Video lesson integration.
19. **Out of Scope**: AI-based free-text grammar correction.

### Reading (M-09)
1. **Feature Overview**: Passages of text followed by comprehension questions.
2. **Purpose**: Improve reading speed, comprehension, and vocabulary in context.
3. **User Value**: Engaging content paired with rigorous assessment.
4. **Related Requirements**: FR-READ-001 to 010, BR-CONTENT-004 to 006.
5. **Entry Points**: Sidebar "Reading" link.
6. **User Journey**: User selects a passage -> Reads text (timer optionally tracks speed) -> Proceeds to questions -> Answers multiple-choice/TF questions -> Submits -> Receives score and feedback.
7. **Functional Behavior**: Displays full text. Questions are auto-graded. Tracks completion and score via `user_reading_progress`.
8. **Validation Rules**: Short-answer questions (if any) are excluded from auto-grading and score denominator.
9. **Empty States**: "No passages available in this category."
10. **Loading States**: Skeleton text blocks.
11. **Error States**: "Failed to load passage."
12. **Edge Cases**: User closes tab before submitting questions (attempt is lost, passage remains incomplete).
13. **XP & Gamification**: XP awarded based on comprehension score.
14. **Achievement Integration**: Triggers achievements for reading volume and perfect scores.
15. **Daily Goal Integration**: Contributes to global XP goal.
16. **Streak Integration**: Completing a passage with questions increments streak.
17. **Dependencies**: None.
18. **Future Expansion**: Highlight to translate, text-to-speech reading.
19. **Out of Scope**: Live OCR from uploaded documents.

### Writing (M-10)
1. **Feature Overview**: Structured writing prompts with a text editor and submission history.
2. **Purpose**: Practice expressive output and essay structuring.
3. **User Value**: A focused environment to practice writing with clear constraints and historical tracking.
4. **Related Requirements**: FR-WRITE-001 to 010, BR-WRITE-001 to 003.
5. **Entry Points**: Sidebar "Writing" link.
6. **User Journey**: User selects a prompt -> Reads structural guidance and word count target -> Types response in editor -> Live word count updates -> Submits -> Response saved to history.
7. **Functional Behavior**: Stores submissions in `writing_submissions`. Tracks word count dynamically in the UI.
8. **Validation Rules**: Submission must contain at least 1 non-whitespace character. Warns user if below target word count (but allows submission).
9. **Empty States**: "You haven't submitted any writing yet" in History view.
10. **Loading States**: Spinner on submit button.
11. **Error States**: "Failed to save submission."
12. **Edge Cases**: Network disconnect during writing (editor state should be preserved in local storage to prevent data loss).
13. **XP & Gamification**: XP awarded *only on the first submission* per prompt (BR-WRITE-001). Revisions yield 0 XP.
14. **Achievement Integration**: Triggers writing volume achievements.
15. **Daily Goal Integration**: Contributes to global XP goal.
16. **Streak Integration**: Submitting a response increments streak.
17. **Dependencies**: Shared submission model with IELTS module.
18. **Future Expansion**: Peer review system, rich text editor (bold/italics).
19. **Out of Scope**: AI automated essay scoring and grammar checking.

### IELTS Preparation (M-11)
1. **Feature Overview**: IELTS-formatted writing and reading practice.
2. **Purpose**: Exam-specific preparation.
3. **User Value**: High-fidelity practice mimicking actual IELTS exam structures, complete with sample band answers.
4. **Related Requirements**: FR-IELTS-001 to 010, BR-WRITE-002, BR-WRITE-003.
5. **Entry Points**: Sidebar "IELTS" link.
6. **User Journey**: User selects Writing Task 1 -> Views graph/chart stimulus -> Writes 150+ words -> Submits -> Unlocks and reads a Band 8 sample answer for comparison.
7. **Functional Behavior**: Operates exactly like the Writing module but uses a specific `module_type` filter for prompts. Exposes IELTS-specific band guidance.
8. **Validation Rules**: Warns if below 150 words (Task 1) or 250 words (Task 2).
9. **Empty States**: "No IELTS tasks available."
10. **Loading States**: Spinner on submit. Image loading skeletons for Task 1 charts.
11. **Error States**: "Failed to save submission."
12. **Edge Cases**: Same as Writing module.
13. **XP & Gamification**: XP awarded on first submission.
14. **Achievement Integration**: Triggers IELTS-specific achievements.
15. **Daily Goal Integration**: Contributes to global XP.
16. **Streak Integration**: Submitting an IELTS task increments streak.
17. **Dependencies**: Shared Writing infrastructure.
18. **Future Expansion**: IELTS Listening and Speaking mock exams.
19. **Out of Scope**: Official IELTS band grading.

---

## 📝 Quiz System

### Quiz Library (M-12)
1. **Feature Overview**: Browsable catalog of curated, multi-subject quizzes.
2. **Purpose**: Provide varied, challenging assessments across all topics.
3. **User Value**: A central hub to test general knowledge and language skills on demand.
4. **Related Requirements**: FR-QLIB-001 to 009, BR-QUIZ-007 to 009.
5. **Entry Points**: Sidebar "Quiz Library" link.
6. **User Journey**: User browses library -> Filters by "Grammar" -> Clicks Quiz Detail -> Views high score and past attempts -> Clicks "Start" -> Directed to Quiz Session Engine.
7. **Functional Behavior**: Queries unified `quizzes` table filtering by `source_type = curated`. Displays paginated list.
8. **Validation Rules**: Curated quizzes have a max of 50 questions (BR-QUIZ-008).
9. **Empty States**: "No quizzes found matching your filters."
10. **Loading States**: Skeleton grid for quiz cards.
11. **Error States**: "Failed to load library."
12. **Edge Cases**: User attempts a quiz they have taken before (allowed, questions are randomized again).
13. **XP & Gamification**: XP awarded via the Quiz Engine.
14. **Achievement Integration**: Triggers via Quiz Engine.
15. **Daily Goal Integration**: Triggers via Quiz Engine.
16. **Streak Integration**: Triggers via Quiz Engine.
17. **Dependencies**: Depends entirely on Shared Quiz Engine (M-14) for the actual attempt.
18. **Future Expansion**: Quiz rating system (1-5 stars).
19. **Out of Scope**: Admin UI for creating curated quizzes (managed via DB seeds).

### Quiz Builder (M-13)
1. **Feature Overview**: Tools for users to create and manage custom quizzes.
2. **Purpose**: Enable personalized, targeted practice.
3. **User Value**: Allows users to focus on their specific weak points by building tailored assessments.
4. **Related Requirements**: FR-QBUILD-001 to 010, BR-QUIZ-007 to 009.
5. **Entry Points**: Sidebar "My Quizzes" link.
6. **User Journey**: User clicks "Create Quiz" -> Enters Title -> Adds 5 custom multiple-choice questions -> Saves -> Clicks "Attempt" -> Directed to Quiz Session Engine.
7. **Functional Behavior**: Provides CRUD operations on `quizzes` (where `source_type = user_created`) and `quiz_questions`.
8. **Validation Rules**: Max 100 questions per quiz. Must have at least 1 question to attempt. Each question must have exactly one marked correct answer.
9. **Empty States**: "You haven't created any custom quizzes yet."
10. **Loading States**: Inline spinners when adding/deleting questions.
11. **Error States**: "Validation failed: A question must have at least 2 options."
12. **Edge Cases**: User deletes a question while an attempt is in progress (the session Engine operates on a snapshot taken at start; deletion doesn't break the active session). Soft-deleted quizzes remain in the database for historical progress integrity but are hidden from the active library.
13. **XP & Gamification**: Attempting custom quizzes awards XP (handled by Quiz Engine).
14. **Achievement Integration**: Attempting triggers Quiz Engine achievements. Creating quizzes triggers builder achievements.
15. **Daily Goal Integration**: Attempts trigger goal progress.
16. **Streak Integration**: Attempts trigger streak.
17. **Dependencies**: Shared Quiz Engine for execution.
18. **Future Expansion**: Sharing custom quizzes with other users via links.
19. **Out of Scope**: Importing questions from CSV.

---

## 🧠 Cognitive Training

### IQ Training (M-17)
1. **Feature Overview**: Abstract reasoning, spatial, and pattern recognition exercises.
2. **Purpose**: Train cognitive flexibility and problem-solving speed.
3. **User Value**: Sharpens general intelligence, complementing language acquisition.
4. **Related Requirements**: FR-IQ-001 to 008.
5. **Entry Points**: Sidebar "IQ Training" link.
6. **User Journey**: User selects category (Pattern Recognition) -> Clicks Start -> Directed to Quiz Engine -> Completes visual sequence questions -> Reviews explanations.
7. **Functional Behavior**: Functions as a specialized quiz category utilizing the Quiz Engine. Includes image-heavy questions.
8. **Validation Rules**: Standard Quiz Engine rules.
9. **Empty States**: "No IQ exercises available in this category."
10. **Loading States**: Image skeleton loaders during the quiz.
11. **Error States**: "Failed to load exercise."
12. **Edge Cases**: Images failing to load in a quiz break the question (handled by `alt` text and frontend retry logic).
13. **XP & Gamification**: Awards XP via Quiz Engine.
14. **Achievement Integration**: Emits events for IQ-specific volume achievements.
15. **Daily Goal Integration**: Contributes to "Quizzes Completed".
16. **Streak Integration**: Completing a session increments streak.
17. **Dependencies**: Shared Quiz Engine.
18. **Future Expansion**: Timed mode with strict per-question limits.
19. **Out of Scope**: Official IQ scoring or certification.

### Logic Training (M-18)
1. **Feature Overview**: Deductive reasoning, syllogisms, and critical analysis exercises.
2. **Purpose**: Improve analytical thinking and logical deduction.
3. **User Value**: Enhances ability to construct and deconstruct arguments (highly relevant for IELTS Task 2).
4. **Related Requirements**: FR-LOGIC-001 to 007.
5. **Entry Points**: Sidebar "Logic Training" link.
6. **User Journey**: User selects category (Syllogisms) -> Clicks Start -> Directed to Quiz Engine -> Answers text-based logic puzzles -> Reviews detailed reasoning explanations.
7. **Functional Behavior**: Functions as a specialized quiz category utilizing the Quiz Engine. Emphasizes long-form text explanations in Review Mode.
8. **Validation Rules**: Standard Quiz Engine rules.
9. **Empty States**: "No logic exercises available in this category."
10. **Loading States**: Standard Quiz Engine loaders.
11. **Error States**: "Failed to load exercise."
12. **Edge Cases**: Standard Quiz Engine edge cases.
13. **XP & Gamification**: Awards XP via Quiz Engine.
14. **Achievement Integration**: Emits events for Logic-specific volume achievements.
15. **Daily Goal Integration**: Contributes to "Quizzes Completed".
16. **Streak Integration**: Completing a session increments streak.
17. **Dependencies**: Shared Quiz Engine.
18. **Future Expansion**: Fallacy identification mini-games.
19. **Out of Scope**: Free-text logical proof evaluation.

---
*BrainForge Feature Specifications — v0.1.0*
*Approved for Implementation Phase.*
