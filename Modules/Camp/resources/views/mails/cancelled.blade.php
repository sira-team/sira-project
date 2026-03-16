@component('mail::message')
# Registration Cancelled

{{ $registration->camp->tenant->name }}

Hello {{ $registration->participant->name }},

Your camp registration has been cancelled.

If you have any questions, please contact us.

Best regards,
{{ $registration->camp->tenant->name }}
@endcomponent
