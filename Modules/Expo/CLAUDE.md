# Implementation Plan — Expo Module

## Context

This is a nwidart Laravel module called `Expo`. It handles expo requests from mosques and organisations, planning of expos, and per-tenant station inventory management including physical and digital materials.

The app is multi-tenant. Each tenant is a city-based Verein. Not every tenant hosts expos. Access to this module is controlled via Laravel Pennant.

---

## Stack

- Laravel 12
- Filament 5 (admin panel, tenant-scoped)
- Nwidart Modules (`Modules/Expo`)
- Laravel Pennant (feature flags)
- Laravel Storage (file uploads)
- Blade (public expo request form)

---

## Pennant

The Expo panel is NOT available to all tenants.

Feature flag: `expo-panel`, scoped to `Tenant` model.

```php
Feature::for($tenant)->active('expo-panel');
```

Check this flag in the Filament panel provider's `boot` method to conditionally register the Expo plugin. If the flag is inactive for the current tenant, the Expo navigation items are hidden entirely and routes return 403.

Enable per tenant via artisan command:
```bash
php artisan pennant:grant-tenant {tenantId} expo-panel
```

---

## Roles (Spatie, scoped to tenant_id)

Roles that have access in Filament:
- `tenant_admin` — full access
- `expo_manager` — full access to expo resources within their tenant

---

## Public Form — Expo Request

Route: `/{tenant:slug}/expo/request`

Plain Blade view. No login required. Tenant branding shown at top.

### Form Fields

- Contact name
- Organisation name (mosque or institution)
- Email address
- Phone number (optional)
- City
- Preferred date range (from / to, optional)
- Expected number of visitors (optional)
- Message / additional info (textarea, optional)

### On Submission

1. Resolve tenant from subdomain
2. Create `ExpoRequest` record with `status = new` and `tenant_id`
3. Send confirmation email to the contact
4. Send internal notification email to the tenant's primary contact email (`tenants.email`)

### Confirmation Email to Contact

Content:
- Organisation name
- Summary of submitted details
- "We have received your request and will be in touch shortly."
- Tenant name and contact email

---

## Filament — Expo Request Resource

### Request List View

Columns: contact name, organisation, city, preferred dates, status, submitted at

Statuses: `new`, `in_review`, `accepted`, `declined`, `completed`

Actions per request:
- View details
- Change status
- Create Expo from this request (only visible when status is `accepted`)
- Add internal notes

### Status Flow

`new` → `in_review` → `accepted` → `completed`
`in_review` → `declined`

When status changes to `accepted`:
- Send acceptance email to contact with next steps

When status changes to `declined`:
- Send decline email to contact

---

## Filament — Expo Resource

An Expo is a planned or completed event. It can be created from an accepted request or directly by an Expo Manager.

### Expo List View

Columns: name, location, date, status, number of stations

Statuses: `planned`, `completed`, `cancelled`

### Create / Edit Expo Form

Fields:
- Name
- Linked expo request (optional, searchable select)
- Location name
- Location address
- Date
- Status
- Notes (internal)

### Station Assignment

A relation manager on the Expo showing which stations are assigned to it.

**Columns:** station name, responsible person, sort order

**Actions:**
- Add station (select from tenant's station inventory — stations already assigned to this expo are excluded from the select list)
- Assign or change responsible person (select from tenant's users)
- Remove station
- Reorder stations (drag and drop, sets `sort_order`)

The `(expo_id, station_id)` combination is unique at the database level. The select when adding a station must also filter out already-assigned stations in the UI so the constraint is never hit in normal use.

`responsible_user_id` is nullable — a station can be added without a responsible person assigned yet. This allows planning the station list first and assigning people later.

This gives Expo Managers a per-expo planning view: which stations are coming, and who owns each one.

---

## Filament — Station Resource

Stations are the core inventory of the Expo module. Each tenant manages their own.

### Station List View

Columns: name, number of physical materials, number of digital materials, sort order

Actions: edit, delete (soft), reorder

### Create / Edit Station Form

Fields:
- Name (e.g. "Station 3 – Geburt des Propheten")
- Description (short plain text — context for new members)
- Sort order

#### Physical Materials (repeater)

Each entry:
- Type (miniature / poster / video screen / other)
- Name
- Notes (optional)

#### Digital Materials (file upload section)

Each entry:
- Title
- File upload (accepted types: PDF, PPTX, DOCX — max 20MB)
- Language (default: de)

On upload:
- Store file via Laravel Storage (private disk)
- Record file path, file type, file size, uploaded by user ID

### Downloading Digital Materials

All authenticated users within the tenant can download digital materials.

Download route: `/filament/expo/stations/{station}/materials/{material}/download`

This route:
1. Checks user is authenticated and belongs to the correct tenant
2. Retrieves the file from private storage
3. Returns a streamed download response with the original filename

Files are never served via public URL. Always through the authenticated download route.

### Deleting Digital Materials

Expo Manager or Tenant Admin can delete a digital material.

On delete:
1. Remove the file from storage
2. Delete the `StationDigitalMaterial` record

---

## Models

- `ExpoRequest` — tenant-scoped
- `Expo` — tenant-scoped, soft deletes, belongs to ExpoRequest (nullable)
- `ExpoStation` — pivot between Expo and Station
- `Station` — tenant-scoped, soft deletes
- `StationPhysicalMaterial` — belongs to Station
- `StationDigitalMaterial` — belongs to Station

---

## Schema Reference

See `sira-app-data-model.md` for full column definitions.

---

## Out of Scope (backlog)

- Inter-tenant material lending / borrowing log
- Public expo archive or gallery
- QR codes per station for visitor self-guided experience
