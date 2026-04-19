# Sira App — Data Model

Place this file at `docs/data-model.md`. It is referenced from the root `CLAUDE.md` and from each module `CLAUDE.md`. Read it before writing any migration or model.

## Conventions

- All tables have `id`, `created_at`, `updated_at`
- Tenant-scoped tables have `tenant_id` (foreign key to `tenants`)
- Soft deletes (`deleted_at`) on anything that should be archivable
- Enums defined as PHP-backed string enums in `App\Enums\` or module `Enums\`

---

## Core — App Namespace

### `tenants`
The tenant. One row per city/Verein.

| column | type | notes |
|---|---|---|
| id | bigint | |
| name | string | e.g. "Sira Bonn" |
| slug | string | unique, used for subdomain |
| city | string | |
| country | string | default DE |
| email | string | primary contact |

---

### `users`
Internal users only. Verein members who log into Filament.

| column | type | notes |
|---|---|---|
| id | bigint | |
| tenant_id | FK tenants | primary tenant |
| name | string | |
| email | string | unique |
| password | hashed | |
| email_verified_at | timestamp | nullable |

Spatie `model_has_roles` scoped to `tenant_id` handles per-tenant roles.
Pennant `features` table handles panel access per User and per Team.

---

### `roles` (Spatie, tenant-scoped)

Seeded per tenant on creation via TenantObserver:
- `tenant_admin`
- `academy_manager`
- `camp_manager`
- `expo_manager`
- `member`

Global roles (no team scope, seeded once):
- `super_admin`

---

## Visitors — Public Namespace

### `visitors`
The person who fills out a public form.

| column | type | notes |
|---|---|---|
| id | bigint | |
| name | string | |
| email | string | unique |
| phone | string | nullable |
| password | hashed | nullable — set when they claim their account |
| email_verified_at | timestamp | nullable |

---

### `participants`
The actual person attending a camp. Belongs to a Visitor.

| column | type | notes |
|---|---|---|
| id | bigint | |
| visitor_id | FK visitors | |
| name | string | |
| gender | enum | male, female |
| is_self | boolean | true when visitor registers themselves |

When `is_self = true` the participant IS the visitor.
When `is_self = false` the participant is a dependent or someone else the visitor is registering.
One visitor can have multiple participants.

---

## Module: Camp

### `jugendherbergen` — GLOBAL (no tenant_id)
Shared across all tenants. A JH is a real-world location that exists independently of any camp or tenant.

| column | type | notes |
|---|---|---|
| id | bigint | |
| name | string | |
| address | text | |
| city | string | |
| phone | string | nullable |
| email | string | nullable |
| website | string | nullable |
| notes | text | nullable — general notes about the venue |
| deleted_at | timestamp | nullable |

Only super_admin can create, edit or delete Jugendherbergen.

---

### `jugendherberge_rooms` — GLOBAL (no tenant_id)
Fixed rooms at the JH. These change rarely (new building construction).

| column | type | notes |
|---|---|---|
| id | bigint | |
| jugendherberge_id | FK jugendherbergen | |
| name | string | e.g. "Zimmer 3", "Blaues Zimmer" |
| floor | string | e.g. "EG", "OG 1" |
| capacity | integer | number of beds |

Only super_admin can manage these. Tenants read them when assigning beds.

---

### `jugendherberge_contracts`
The negotiated agreement between a tenant and a JH for a specific camp. Bridges the global JH to the tenant's camp.

| column | type | notes                                             |
|---|---|---------------------------------------------------|
| id | bigint |                                                   |
| jugendherberge_id | FK jugendherbergen |                                                   |
| camp_id | FK camps | unique — one contract per camp                    |
| price_per_person_per_night | decimal | what was negotiated                               |
| contracted_beds | integer | min participants the JH agreed to host            |
| contract_date | date | nullable                                          |
| notes | text | nullable — cancellation terms, special conditions |

Tenant-scoped via `camp_id → tenant_id`. A tenant can only see contracts for their own camps.

---

### `camps`
Created per tenant by a Camp Manager or Tenant Admin.

| column                | type | notes |
|-----------------------|---|---|
| id                    | bigint | |
| tenant_id             | FK tenants | |
| name                  | string | e.g. "Sommer Camp 2025" |
| starts_at             | date | |
| ends_at               | date | |
| capacity              | integer | total participant registrations allowed |
| price                 | decimal | price per participant in EUR |
| target_group          | enum | children, adults, family |
| age_min               | integer | nullable |
| age_max               | integer | nullable |
| gender_policy         | enum | mixed, separated, brothers_only, sisters_only |
| registration_opens_at | timestamp | nullable |
| registration_ends_at  | timestamp | nullable |
| iban                  | string | for Überweisung instructions in confirmation email |
| bank_recipient        | string | |
| notes                 | text | nullable — internal notes |
| deleted_at            | timestamp | nullable |

**Three participant numbers exist:**
1. `predicted_participants` — set during planning, drives cost calculator
2. `contracted_beds` — from JugendherbergeContract, the legal/logistical ceiling
3. confirmed registrations — count of `camp_registrations` where `status = confirmed` (derived, not stored)

These are intentionally separate. Do not try to consolidate them.

---

### `camp_expenses`
Costs associated with a camp. Used for cost planning and post-camp tracking.

| column | type | notes |
|---|---|---|
| id | bigint | |
| camp_id | FK camps | |
| category | enum | see CampExpenseCategory enum below |
| title | string | e.g. "Sprit für 5 Volunteers", "Prediction: Busmiete" |
| description | text | nullable |
| amount | decimal | in EUR |

**CampExpenseCategory enum:**
- `uebernachtung` — JH accommodation costs
- `verpflegung` — food and drinks
- `material` — consumables: paper, pens, crafts, items used up during camp
- `aktivitaeten` — activities: firewood, rented equipment, kiosk supplies
- `transport` — travel: car rental, Sprit reimbursement for volunteers
- `investition` — reusable purchases: speaker, projector, equipment that survives the camp
- `sonstiges` — anything that doesn't fit the above

Note: accommodation costs from the JH contract can be auto-generated as a `uebernachtung` expense entry for reference, but the contract itself is the authoritative source. Do not double-count.

---

### `camp_registrations`
One registration per participant per camp.

| column | type | notes |
|---|---|---|
| id | bigint | |
| camp_id | FK camps | |
| visitor_id | FK visitors | who submitted the form |
| participant_id | FK participants | who is attending |
| status | enum | pending, confirmed, waitlisted, cancelled |
| payment_status | enum | pending, paid, cancelled |
| waitlist_position | integer | nullable |
| registered_at | timestamp | set at form submission — used for waitlist ordering |
| confirmed_at | timestamp | nullable |
| cancelled_at | timestamp | nullable |
| cancellation_reason | text | nullable |
| internal_notes | text | nullable |

`registered_at` is always set at submission, never at confirmation. Waitlist order is always by `registered_at` ASC.

---

### `camp_room_assignments`
Assigned by Camp Manager after confirmation. Uses JH rooms directly.

| column | type | notes |
|---|---|---|
| id | bigint | |
| camp_registration_id | FK camp_registrations | |
| jugendherberge_room_id | FK jugendherberge_rooms | rooms come from the global JH, not per-camp |
| assigned_at | timestamp | |
| assigned_by | FK users | |

---

### `camp_notification_logs`

| column | type | notes |
|---|---|---|
| id | bigint | |
| camp_registration_id | FK camp_registrations | |
| type | enum | registration_received, confirmed, waitlisted, waitlist_promoted, payment_reminder, room_assigned, cancelled |
| sent_at | timestamp | |
| recipient_email | string | |

---

## Module: Expo

### `expo_requests`
Submitted via public form by a mosque or organisation.

| column | type | notes |
|---|---|---|
| id | bigint | |
| tenant_id | FK tenants | resolved from subdomain at submission |
| contact_name | string | |
| organisation | string | |
| email | string | |
| phone | string | nullable |
| city | string | |
| preferred_date_from | date | nullable |
| preferred_date_to | date | nullable |
| message | text | nullable |
| status | enum | new, in_review, accepted, declined, completed |
| internal_notes | text | nullable |

---

### `expos`

| column | type | notes |
|---|---|---|
| id | bigint | |
| tenant_id | FK tenants | |
| expo_request_id | FK expo_requests | nullable |
| name | string | |
| location_name | string | |
| location_address | text | |
| date | date | |
| status | enum | planned, completed, cancelled |
| notes | text | nullable |
| deleted_at | timestamp | nullable |

---

### `stations`
Tenant-owned inventory items.

| column | type | notes |
|---|---|---|
| id | bigint | |
| tenant_id | FK tenants | |
| name | string | |
| description | text | nullable |
| sort_order | integer | |
| deleted_at | timestamp | nullable |

---

### `station_physical_materials`

| column | type | notes |
|---|---|---|
| id | bigint | |
| station_id | FK stations | |
| type | enum | miniature, poster, video_screen, other |
| name | string | |
| notes | text | nullable |

---

### `station_digital_materials`

| column | type | notes |
|---|---|---|
| id | bigint | |
| station_id | FK stations | |
| title | string | |
| file_path | string | private storage |
| file_type | enum | pdf, pptx, docx |
| language | string | default: de |
| uploaded_by | FK users | |
| file_size_kb | integer | nullable |

---

### `expo_stations`
Which stations are brought to a specific expo, and who is responsible for each.

| column | type | notes |
|---|---|---|
| id | bigint | |
| expo_id | FK expos | |
| station_id | FK stations | |
| responsible_user_id | FK users | nullable — the team member responsible for this station |
| sort_order | integer | |

Unique constraint on `(expo_id, station_id)` — a station cannot be assigned to the same expo twice.

---

## Module: Sira Academy

### Global Content (managed via Academy Content Panel)

#### `academy_levels`

| column | type | notes |
|---|---|---|
| id | bigint | |
| title | string | |
| description | text | |
| sort_order | integer | |

---

#### `academy_sessions`

| column | type | notes |
|---|---|---|
| id | bigint | |
| academy_level_id | FK academy_levels | |
| title | string | |
| description | text | nullable |
| sort_order | integer | |

---

#### `quizzes`

| column | type | notes |
|---|---|---|
| id | bigint | |
| academy_session_id | FK academy_sessions | unique |
| title | string | |
| max_attempts | integer | default 3 |
| min_days_between_attempts | integer | default 7 |
| passing_score_percent | integer | default 70 |

---

#### `quiz_questions`

| column | type | notes |
|---|---|---|
| id | bigint | |
| quiz_id | FK quizzes | |
| question | text | |
| type | enum | multiple_choice, true_false |
| sort_order | integer | |

---

#### `quiz_options`

| column | type | notes |
|---|---|---|
| id | bigint | |
| quiz_question_id | FK quiz_questions | |
| text | string | |
| is_correct | boolean | exactly one per question must be true |

---

### Per-Tenant Academy Data

#### `academy_enrollments`

| column | type | notes |
|---|---|---|
| id | bigint | |
| tenant_id | FK tenants | |
| user_id | FK users | |
| academy_level_id | FK academy_levels | current level |
| started_at | date | |
| completed_at | date | nullable |

---

#### `academy_session_tickets`
Issued after a member attends a session. Unlocks the quiz.

| column | type | notes |
|---|---|---|
| id | bigint | |
| academy_enrollment_id | FK academy_enrollments | |
| academy_session_id | FK academy_sessions | |
| issued_by | FK users | |
| issued_at | timestamp | |
| code | string | unique generated token |

One ticket per enrollment per session. Duplicate = validation error.

---

#### `quiz_attempts`

| column | type | notes |
|---|---|---|
| id | bigint | |
| academy_enrollment_id | FK academy_enrollments | |
| quiz_id | FK quizzes | |
| academy_session_ticket_id | FK academy_session_tickets | |
| attempt_number | integer | 1, 2 or 3 |
| started_at | timestamp | |
| completed_at | timestamp | nullable |
| score_percent | integer | nullable |
| passed | boolean | nullable |

Score is only shown to the user when `passed = true`.

---

#### `quiz_attempt_answers`

| column | type | notes |
|---|---|---|
| id | bigint | |
| quiz_attempt_id | FK quiz_attempts | |
| quiz_question_id | FK quiz_questions | |
| quiz_option_id | FK quiz_options | selected answer |

---

## Pennant Feature Flags

| feature | scoped to | notes |
|---|---|---|
| `academy-content` | `User` | only specific named users |
| `expo` | `Tenant` | tenant has expo module enabled |
| `academy` | `Tenant` | tenant has academy module enabled |

Camp panel is always accessible — no flag.

---

## Summary — Global vs Tenant Ownership

| model | global or tenant | managed by |
|---|---|---|
| Tenant | — | super_admin |
| User | tenant | tenant_admin |
| Visitor | neither (public) | self |
| Participant | neither (public) | visitor |
| Jugendherberge | global | super_admin |
| JugendherbergeRoom | global | super_admin |
| JugendherbergeContract | tenant (via camp) | camp_manager |
| Camp | tenant | camp_manager |
| CampExpense | tenant (via camp) | camp_manager |
| CampRegistration | tenant (via camp) | visitor (public) |
| CampRoomAssignment | tenant (via camp) | camp_manager |
| Expo | tenant | expo_manager |
| ExpoRequest | tenant | public |
| Station | tenant | expo_manager |
| AcademyLevel / Session / Quiz | global | academy content managers |
