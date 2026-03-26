@component('mail::message')
# Camp Registration Confirmed

{{ $registration->camp->tenant->name }}

Hello {{ $registration->participant->name }},

Your camp registration has been confirmed! We look forward to seeing you.

## Camp Details

**Dates:** {{ $registration->camp->starts_at->format('d.m.Y') }} – {{ $registration->camp->ends_at->format('d.m.Y') }}

**Location:** {{ $registration->camp->contract?->hostel->name ?? 'To be announced' }}

@if($registration->campRoomAssignment)
**Room:** {{ $registration->campRoomAssignment->room->name }}
@endif

Best regards,
{{ $registration->camp->tenant->name }}
@endcomponent
