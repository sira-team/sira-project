@component('mail::message')
# Spot Available — Camp Registration Confirmed

{{ $registration->camp->tenant->name }}

Hello {{ $registration->participant->name }},

Great news! A spot has become available at our camp and you have been promoted from the waitlist.

## Payment Instructions

Please transfer the camp fee as soon as possible:

**Amount:** €{{ number_format($registration->camp->price, 2) }}

**IBAN:** {{ $registration->camp->iban }}

**Recipient:** {{ $registration->camp->bank_recipient }}

Payment must be received soon to secure your spot.

Best regards,
{{ $registration->camp->tenant->name }}
@endcomponent
