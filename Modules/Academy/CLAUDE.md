# Implementation Plan — Sira Academy (Part 1: Global Content Management)

## Context

This is the global side of the Sira Academy module. It is managed by a small number of specific users (currently two: the developer and Ahmad) who are responsible for maintaining the curriculum — levels, sessions, quizzes and questions.

This content is global. It is NOT tenant-scoped. All tenants consume the same Academy content. No city admin can create or edit curriculum content.

---

## Stack

- Laravel 12
- Filament 5 (separate panel — Academy Content Panel)
- Nwidart Modules (`Modules/Academy`)
- Laravel Pennant (access control per user)

---

## Pennant — Access Control

Access to the Academy Content Panel is controlled by a Pennant flag scoped to the `User` model.

```php
Feature::for($user)->active('academy-content-management');
```

This flag is set per specific user ID. It is NOT a role. It is NOT a permission. It is a named list of specific humans who are trusted to manage curriculum.

Enable via artisan command:
```bash
php artisan pennant:grant-user {userId} academy-content-management
```

The Academy Content Panel middleware checks this flag on every request:
```php
abort_unless(Feature::for($request->user())->active('academy-content-management'), 403);
```

Users granted this flag still belong to their own tenant normally. This flag only grants access to the additional global panel.

---

## Filament — Academy Content Panel

This is a completely separate Filament panel from the tenant panel.

Panel ID: `academy-content`
Path: `/academy-content`
Guard: `web` (same users table, different panel)
Middleware: custom Pennant check (see above)

This panel has no tenant context. It operates globally.

---

## Resources

### AcademyLevel Resource

Represents one level of the 3-year programme.

List view columns: title, duration in months, number of sessions, sort order

Create / Edit fields:
- Title (e.g. "Level 1 — Makkische Periode")
- Description (textarea)
- Duration in months
- Sort order

Levels are orderable via drag and drop (sort_order column).

---

### AcademySession Resource

A session is one monthly meeting within a level. Each session can have one quiz.

Managed as a relation manager within the AcademyLevel resource (nested under the level).

List view columns: title, sort order, has quiz (boolean indicator)

Create / Edit fields:
- Title
- Description (textarea, optional)
- Sort order

---

### Quiz Resource

One quiz per session. Managed as a relation manager within the AcademySession resource.

A session can have zero or one quiz. If no quiz exists, the session is considered attendance-only.

Create / Edit fields:
- Title
- Max attempts (default: 3)
- Minimum days between attempts (default: 7)
- Passing score percentage (default: 70)

---

### Quiz Question Resource

Managed as a relation manager within the Quiz resource.

Create / Edit fields:
- Question text (textarea)
- Type (multiple choice / true or false)
- Sort order

#### Answer Options (repeater within question form)

Each question has 2–4 options.

Each option:
- Text
- Is correct (boolean toggle — only one option can be marked correct)

Validation: exactly one option must be marked as correct before saving.

---

## Content Structure Summary

```
AcademyLevel (e.g. Level 1)
└── AcademySession (e.g. Session 3 — Hijra)
    └── Quiz
        └── QuizQuestion
            └── QuizOption (one marked correct)
```

All of the above is global. No tenant can see or edit this panel.

---

## Models

- `AcademyLevel` — global, no team_id
- `AcademySession` — belongs to AcademyLevel
- `Quiz` — belongs to AcademySession (one-to-one)
- `QuizQuestion` — belongs to Quiz
- `QuizOption` — belongs to QuizQuestion

---

## Schema Reference

See `sira-app-data-model.md` for full column definitions.

---

## Out of Scope (backlog)

- Rich text or media content per session (currently text only)
- Downloadable session material managed from this panel
- Versioning of quiz content (if questions change after attempts exist)
- Multiple correct answers per question
-e 
---

# Implementation Plan — Sira Academy (Part 2: Tenant Access)

## Context

This is the tenant-facing side of the Sira Academy module. It allows city admins and Academy Managers to enroll members, issue session tickets, and track progress. It also provides members with their own dashboard showing achievements.

The curriculum content (levels, sessions, quizzes) is global and read-only from the tenant's perspective. Tenant data is limited to enrollments, tickets, and quiz attempts.

---

## Stack

- Laravel 12
- Filament 5 (tenant panel)
- Nwidart Modules (`Modules/Academy`)
- Laravel Pennant (tenant-level access flag)

---

## Pennant — Tenant Access

The Academy section within the tenant panel is NOT available to all tenants.

Feature flag: `academy-panel`, scoped to `Team` model.

```php
Feature::for($team)->active('academy-panel');
```

Check this flag in the Filament panel provider's boot method. If inactive for the current tenant, all Academy navigation items are hidden and routes return 403.

Enable per tenant via artisan command:
```bash
php artisan pennant:grant-team {teamId} academy-panel
```

---

## Roles (Spatie, scoped to team_id)

- `tenant_admin` — full access to Academy tenant resources
- `academy_manager` — can enroll members, issue tickets, view progress. Cannot edit global curriculum content
- `member` — can only see their own dashboard (no Filament access)

---

## Filament — Academy Resources (Tenant Panel)

### Enrollment Resource

An enrollment represents a member participating in the Academy programme within the tenant.

List view columns: member name, current level, started at, sessions attended, completed at (if applicable)

Create enrollment:
- Select user (from tenant's members)
- Select starting level (pulls from global `AcademyLevel` list)
- Started at (date)

Edit enrollment:
- Update current level
- Mark as completed (sets `completed_at`)

A member can only have one active enrollment per tenant.

---

### Session Ticket Resource

Tickets are issued by the Academy Manager after a member physically attends a monthly session. A ticket unlocks the quiz for that session.

Managed as a relation manager within the Enrollment resource.

Issuing a ticket:
- Select session (from global sessions list, filtered to the member's current level)
- Issued at (defaults to today)
- A unique token is generated automatically (`code` column)

Rules:
- A ticket can only be issued once per enrollment per session
- Attempting to issue a duplicate ticket shows a validation error

The ticket code is not shown to the member. It is an internal reference.

---

### Progress View

A read-only overview per enrollment showing:

- All sessions in the member's current level
- For each session: attended (ticket issued) / not attended
- For each session with a quiz: attempt count, passed / not passed
- Overall level completion percentage

This gives the Academy Manager a quick status view without needing to open individual records.

---

## Member Dashboard

Members log into the tenant panel with the `member` role. They see only their own Academy dashboard. No access to other Filament resources.

### Dashboard Sections

#### Current Level
- Level name and description
- Progress bar: X of Y sessions attended this level

#### Sessions
A list of all sessions in the current level showing:
- Session title
- Attended (yes/no)
- Quiz status:
  - No quiz for this session
  - Quiz locked (no ticket issued yet)
  - Quiz available (ticket issued, attempts remaining)
  - Passed (shown as achievement)
  - Failed — attempts remaining
  - Failed — no attempts remaining

#### Achievements
A visual grid of badges for every passed quiz. Each badge shows the session title and the level it belongs to. Failed quizzes are never shown here. Only passes appear as achievements.

---

## Quiz Flow

### Accessing a Quiz

A member can only access a quiz if:
1. They have an active enrollment
2. A ticket has been issued for that session
3. They have not exceeded `quiz.max_attempts`
4. Enough days have passed since their last attempt (`quiz.min_days_between_attempts`)

If any condition is not met, the quiz button is disabled with an explanatory message.

### Taking the Quiz

- Questions are shown one by one or all at once (implementation choice — all at once is simpler)
- Questions are pulled from global `QuizQuestion` and `QuizOption` records
- On submission: create `QuizAttempt` record, create `QuizAttemptAnswer` for each question, calculate score

### Scoring

```
score_percent = (correct_answers / total_questions) * 100
passed = score_percent >= quiz.passing_score_percent
```

### Showing Results

- If `passed = true`: show congratulations message, score, unlock achievement on dashboard
- If `passed = false`: do NOT show the score. Show only: "You have not passed this time. You have X attempts remaining."
- If `passed = false` and no attempts remaining: "You have used all your attempts for this quiz."

The score is intentionally hidden on failure to avoid discouraging members and to maintain the integrity of the learning process.

### Attempt Timing

Before allowing a new attempt, check:
```
last_attempt.completed_at + min_days_between_attempts days <= now
```

If not enough time has passed, show: "Your next attempt will be available on [date]."

---

## Models (Tenant-Scoped)

- `AcademyEnrollment` — belongs to User and AcademyLevel, scoped to team_id
- `AcademySessionTicket` — belongs to AcademyEnrollment and AcademySession
- `QuizAttempt` — belongs to AcademyEnrollment, Quiz, and AcademySessionTicket
- `QuizAttemptAnswer` — belongs to QuizAttempt, QuizQuestion, and QuizOption

Global models consumed as read-only:
- `AcademyLevel`
- `AcademySession`
- `Quiz`
- `QuizQuestion`
- `QuizOption`

---

## Schema Reference

See `sira-app-data-model.md` for full column definitions.

---

## Out of Scope (backlog)

- Email notification when a quiz becomes available
- Email notification when a new level is unlocked
- Certificate generation on level completion
- Online quiz delivery outside of Filament (public quiz URL)
- Leaderboards or member comparisons
