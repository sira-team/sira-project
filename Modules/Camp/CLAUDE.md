# Sira App — Modules/Camp/CLAUDE.md

Read the root `CLAUDE.md` before this file.

---

## What This Module Does

The Camp module handles everything related to organising camps — venue management, cost planning, public registration, waitlist management, bed assignment, payment tracking and email notifications.

---

## Panels

**ID:** `camp`
**Path:** `/camp`
**Guard:** `web`
**Tenant model:** `Tenant`
**Access:** `camp_manager`, `tenant_admin`
**Pennant:** none — always available to all tenants
**Location:** `Modules/Camp/app/Providers/Filament/CampPanelProvider.php`

Run after resources exist:
```bash
php artisan shield:generate --all --panel=camp
```

---

## Visitor Model & Hierarchy

Visitors are external people who submit public forms. They live in the `visitors` table and are never mixed with internal `users`.

A visitor can be either a **guardian** (root level, has an email) or a **child** linked to a guardian via the `visitor_children` pivot table. There is no `parent_id` column on `visitors` — the relationship is stored in a separate pivot.

- `Visitor::children()` — `belongsToMany(Visitor::class, 'visitor_children', 'parent_id', 'child_id')->withPivot('relationship')->using(VisitorChild::class)`
- `Visitor::guardians()` — `belongsToMany(Visitor::class, 'visitor_children', 'child_id', 'parent_id')->withPivot('relationship')->using(VisitorChild::class)`

The `visitor_children` pivot table stores the `relationship` (enum: `father`, `mother`, `uncle`, `aunt`) between guardian and child, allowing a child to have multiple guardians and capturing the exact relation type.

Child visitors have no email — notifications always go to the guardian's email.

There is no separate Participant model. All participant data lives directly on the `Visitor` model.

---

## Models & Tables

| Model        | Table               |
|--------------|---------------------|
| Visitor      | visitors            |
| VisitorChild | visitor_children    |
| Hostel       | hostels             |
| HostelRoom   | hostel_rooms        |
| CampContract | camp_contracts      |
| Camp         | camps               |
| CampVisitor  | camp_visitor        |
| CampUser     | camp_user           |
| CampExpense  | camp_expenses       |

---

### Visitor

`visitors` table. Guard: `visitor`. Global model — no `tenant_id`.

**Columns:**
- `id`
- `name`
- `email` (nullable — child visitors have no email; notifications go to the guardian's email)
- `phone` (nullable)
- `date_of_birth` (nullable)
- `gender` (enum: `male`, `female` — nullable)
- `allergies` (nullable text)
- `medications` (nullable text)
- `medical_notes` (nullable text)
- `emergency_contact_name` (nullable)
- `emergency_contact_phone` (nullable)
- `timestamps`

---

### Hostel

Global model, no `tenant_id`, soft deletes. Managed in Super Admin Panel only. Tenant users read hostels when creating contracts — they cannot modify them.

**Columns:** `id`, `name`, `address`, `city`, `phone`, `email`, `website`, `notes`, `timestamps`

**Relationships:**
- `Hostel::rooms()` — `hasMany(HostelRoom::class)`
- `Hostel::contracts()` — `hasMany(CampContract::class)`

The Hostel and HostelRoom resources live in the Camp Panel with `$isScopedToTenant = false`. They are global (no `tenant_id`) and shared across all tenants. Discovered via a second `discoverResources` call from `Filament/Resources/` alongside the cluster resources in `Filament/Clusters/Camp/Resources/`.

**HostelRoom form fields:** name, floor, capacity. Rooms are fixed per venue — no gender field, gender-based assignment is handled by the camp's gender policy.

---

### HostelRoom

Belongs to `Hostel`. Global.

**Columns:** `id`, `hostel_id`, `name`, `floor`, `capacity`, `timestamps`

---

### CampContract

Table: `camp_contracts`. Tenant-scoped via `camp_id`. One contract per camp.

**Columns:**
- `id`
- `camp_id` (FK → camps)
- `hostel_id` (FK → hostels)
- `price_per_person_per_night` (decimal)
- `includes_catering` (boolean — whether hostel catering is part of the contracted price)
- `contracted_beds` (integer) — **the capacity minimum for participant registrations**
- `contract_date` (nullable date)
- `notes` (nullable text — cancellation terms, special conditions)
- `timestamps`

> **Capacity lives here, not on `Camp`.** When checking whether a new registration fits, compare confirmed + pending `camp_visitor` records against `contracted_beds`. If no contract exists yet, capacity is unconstrained.

> **Catering note:** many hostels offer catering and non-catering packages at different prices. `includes_catering` reflects which was contracted. If catering is not included, food costs are expected as a `CampExpense` with category `catering`.

---

### Camp

Table: `camps`. Tenant-scoped, soft deletes.

**Columns:**
- `id`
- `tenant_id`
- `name`
- `starts_at` (date)
- `ends_at` (date)
- `description` (nullable longText)
- `internal_notes` (nullable longText)
- `target_group` (enum: `children`, `teenagers`, `adults`)
- `age_min` (nullable integer)
- `age_max` (nullable integer)
- `gender_policy` (enum: `all`, `male`, `female`)
- `participants_bring_food` (boolean)
- `registration_opens_at` (nullable datetime)
- `registration_ends_at` (nullable datetime)
- `price_per_participant` (decimal, EUR)
- `timestamps`

**Relationships:**
- `Camp::contract()` — `hasOne(CampContract::class)`
- `Camp::visitors()` — `belongsToMany(Visitor::class, 'camp_visitor')->withPivot(...)`
- `Camp::expenses()` — `hasMany(CampExpense::class)`
- `Camp::supportStaff()` — `belongsToMany(User::class, 'camp_user')`

**Two participant numbers — never consolidate:**
1. `contracted_beds` — from `CampContract`, the hostel's agreed ceiling
2. Confirmed visitors — live count of `camp_visitor` where `status = confirmed`

---

### CampVisitor

Table: `camp_visitor`. Pivot between `Visitor` and `Camp`.

**Columns:**
- `id`
- `camp_id` (FK → camps)
- `visitor_id` (FK → visitors)
- `status` (enum: `pending`, `waitlisted`, `confirmed`, `paid`, `cancelled`)
- `price` (decimal, EUR — actual price charged; may differ from camp default for sibling discounts)
- `special_wishes` (nullable text — e.g. preferred roommates)
- `room_id` (nullable FK → `hostel_rooms`)
- `waitlist_position` (nullable integer)
- `registered_at` (timestamp)
- `timestamps`

**Status lifecycle:** `pending` → `confirmed` → `paid`. Or `pending` → `waitlisted` → `pending` (on promotion). Any status → `cancelled`.

**Relationships:**
- `CampVisitor::visitor()` — `belongsTo(Visitor::class)`
- `CampVisitor::camp()` — `belongsTo(Camp::class)`
- `CampVisitor::room()` — `belongsTo(HostelRoom::class)`

---

### CampUser

Table: `camp_user`. Pivot between `Camp` and `User` (internal users).

**Columns:**
- `camp_id` (FK → camps)
- `user_id` (FK → users)
---

### CampExpense

Table: `camp_expenses`. Tenant-scoped via `camp_id`.

**Columns:**
- `id`
- `camp_id` (FK → camps)
- `user_id` (FK → users — who submitted / paid this expense)
- `category` (enum: `CampExpenseCategory`)
- `title`
- `description` (nullable text)
- `amount` (decimal, EUR)
- `receipt_image` (nullable string — path on private storage disk)
- `timestamps`

**CampExpenseCategory enum values:**
- `accommodation`
- `catering`
- `materials`
- `activities`
- `transport`
- `investment`
- `other`

---

## Filament — Camp Panel Resources

### Camp Resource

**List view columns:** name, hostel name (via contract), dates, contracted capacity, confirmed visitor count, waitlisted count, registration status

**Create / Edit form fields:**
- Name
- Starts at / ends at (date)
- Description (textarea, nullable)
- Internal notes (textarea, nullable)
- Target group
- Age min / max (optional)
- Gender policy (`all`, `male`, `female`)
- Food provided / participants bring food (toggles)
- Registration open (toggle)
- Registration opens at / ends_at (optional datetime)
- Price per participant

---

### CampContract — Infolist Section on Camp View

Not a RelationManager. Shown as a Section inside the Camp infolist with three conditional header actions: **Add contract** (when none exists), **Edit** and **Delete** (when one exists).

**Form fields:**
- Hostel (searchable select from global `hostels` table)
- Price per person per night
- Catering included (toggle)
- Contracted participants ← capacity ceiling
- Contracted supporters
- Contract date (optional)
- Notes

A camp without a contract can be created. The cost calculator will show a notice until a contract exists.

---

### CampExpense Resource

Relation manager within Camp resource.

**List view columns:** category (badge), title, amount, submitted by (user name)

**Form fields:** category, title, description (optional), amount, receipt image (optional, private disk)

`user_id` is set automatically from `auth()->id()` — not shown in the form.

---

### CampVisitor Resource

Relation manager on Camp showing all `camp_visitor` records.

**List view columns:** visitor name, email (parent email if child visitor), registered at, status, price, waitlist position

**Actions per record:**
- Confirm (status → confirmed, send confirmation email)
- Mark as paid (status → paid)
- Assign room (modal, rooms filtered by linked hostel; gender policy `male`/`female` restricts available rooms)
- Move to waitlist
- Cancel (triggers waitlist promotion, send cancellation email)
- View health info (modal: allergies, medications, medical_notes, emergency contact)

**Bulk actions:** confirm selected, mark selected as paid, export CSV

---

### CampUser Resource

Relation manager on Camp showing assigned internal users.

**Form:** user select (searchable, from tenant users)

---

### Cost Calculator Widget

Displayed on Camp detail page. Shows a notice if no contract exists.

**Note: negative totals are valid.** When `price_per_participant = 0` the total may show a deficit — do not add validation preventing this.

**Displays:**
- Accommodation cost: `contract.price_per_person_per_night × (contracted_beds) × camp_nights`
- Catering status: "Catering included in accommodation price" or "Catering not included — see expenses"
- Total expenses per category
- Grand total
- Price per participant: `(accommodation_cost + total_expenses) / contracted_beds`

Camp nights = `ends_at - starts_at` in days.

---

## Public Registration Form

Route: `/{tenant:slug}/camps/{camp}/register`
Plain Blade view. No login required.

### Mode: `children`

Visitor is always a parent registering one or more children.

**Parent fields (once):** guardian name, email, phone (optional)

**Child repeater (one or more):** relationship (father/mother/uncle/aunt), full name, date of birth, gender, allergies, medications, medical_notes, emergency contact name + phone

**Terms acceptance (required, once)**

Each child → own `Visitor` (no email). Each child is linked to the guardian via a `VisitorChild` pivot record storing the `relationship`. Each child → own `CampVisitor` record.

---

### Mode: `adults`

Single visitor registering themselves.

**Fields:** full name, date of birth, gender, email, phone, allergies, medications, medical_notes, emergency contact name + phone, terms

One submission → one root `Visitor` → one `CampVisitor` record.

---

### Mode: `mixed`

Radio: **myself** or **my child**.

Myself → adults form, root visitor. My child → children form with repeater, child visitors linked to parent.

---

### On Submission

Run for each visitor in the submission:
1. Find or create guardian `Visitor` by email
2. For child registrations: create child `Visitor` (no email), create `VisitorChild` pivot record with `parent_id`, `child_id`, and `relationship`
3. For self-registration: update the guardian's own health/demographic fields; use guardian as the participant
4. Count confirmed + pending `camp_visitor` records for this camp (via `$camp->campVisitors()`)
5. If count < `camp_contract.contracted_beds` (or no contract) → status = `pending`
6. If count ≥ `camp_contract.contracted_beds` → status = `waitlisted`, assign `waitlist_position`
7. Set `price` from `camp.price_per_participant` (apply sibling discounts here if applicable)
8. Create `CampVisitor` record using `participant->id` as `visitor_id`
9. Send notification email — IBAN sourced from `tenant.iban` / `tenant.bank_recipient_name`

---

## Waitlist Logic

**Promotion trigger:** confirmed or pending `CampVisitor` record is cancelled.

**Promotion process:**
1. Find lowest `waitlist_position` record for this camp
2. Set status → `pending`, clear `waitlist_position`
3. Send waitlist promotion email
4. Renumber remaining waitlisted positions from 1

Promotion is not automatic confirmation. Camp Manager manually confirms and marks payment.

---

## Email Notifications

All emails queued. All include tenant name in header. IBAN sourced from `Tenant`.

| type | trigger | key content |
|---|---|---|
| `registration_received` | form submission, capacity available | tenant IBAN, payment instructions |
| `waitlisted` | form submission, camp full | waitlist position |
| `waitlist_promoted` | cancellation triggers promotion | tenant IBAN, deadline warning |
| `confirmed` | Camp Manager confirms | camp dates, location, room if assigned |
| `payment_reminder` | manual button in Filament | outstanding amount, tenant IBAN |
| `room_assigned` | room assignment saved | room name, camp dates |
| `cancelled` | cancellation action | brief message |

Notification recipient is always the root visitor's email. For child visitors, resolve `visitor->parent->email`.

---

## Permissions (auto-generated by Shield)

- `ViewAny:Camp`, `View:Camp`, `Create:Camp`, `Update:Camp`, `Delete:Camp`
- `ViewAny:CampVisitor`, `View:CampVisitor`, `Update:CampVisitor`
- `ViewAny:CampExpense`, `Create:CampExpense`, `Update:CampExpense`, `Delete:CampExpense`
- `ViewAny:CampContract`, `Create:CampContract`, `Update:CampContract`
- `Manage:RoomAssignment` (custom permission — define in `filament-shield.php`)
- `Manage:Payments` (custom permission — define in `filament-shield.php`)

---

## Out of Scope (backlog)

- Visitor login and registration history dashboard
- Automatic waitlist confirmation on payment
- Online payment — bank transfer only
- Inter-tenant supporter access
- Camp preparation checklists (v2)
- Sibling discount automation (currently set manually via `price` on `CampVisitor`)
