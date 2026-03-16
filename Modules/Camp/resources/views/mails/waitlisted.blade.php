@component('mail::message')
# Camp Registration — Waitlist

{{ $registration->camp->tenant->name }}

Hello {{ $registration->participant->name }},

Thank you for your registration. Unfortunately, the camp has reached capacity and you have been placed on the waitlist.

**Your Waitlist Position:** #{{ $registration->waitlist_position }}

We will notify you immediately if a spot becomes available. Please do not make any payment yet.

Best regards,
{{ $registration->camp->tenant->name }}
@endcomponent
