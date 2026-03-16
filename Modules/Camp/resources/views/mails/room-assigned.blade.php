@component('mail::message')
# Room Assigned

{{ $registration->camp->tenant->name }}

Hello {{ $registration->participant->name }},

Your room for the camp has been assigned!

## Room Details

**Room:** {{ $registration->campRoomAssignment->room->name }}

**Camp Dates:** {{ $registration->camp->starts_at->format('d.m.Y') }} – {{ $registration->camp->ends_at->format('d.m.Y') }}

Best regards,
{{ $registration->camp->tenant->name }}
@endcomponent
