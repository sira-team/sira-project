@component('mail::message')
# Payment Reminder

{{ $registration->camp->tenant->name }}

Hello {{ $registration->participant->name }},

This is a friendly reminder that your camp registration payment is still outstanding.

## Outstanding Payment

**Amount:** €{{ number_format($registration->camp->price, 2) }}

**IBAN:** {{ $registration->camp->iban }}

**Recipient:** {{ $registration->camp->bank_recipient }}

Please arrange payment as soon as possible to complete your registration.

Best regards,
{{ $registration->camp->tenant->name }}
@endcomponent
