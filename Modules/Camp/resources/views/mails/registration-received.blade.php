@component('mail::message')
# Camp Registration Received

{{ $registration->camp->tenant->name }}

Hello {{ $registration->participant->name }},

Thank you for registering for our camp. Your registration has been received and is pending confirmation.

## Payment Instructions

Please transfer the camp fee to the following account:

**Amount:** €{{ number_format($registration->camp->price, 2) }}

**IBAN:** {{ $registration->camp->iban }}

**Recipient:** {{ $registration->camp->bank_recipient }}

**Important:** Your registration is not confirmed until we verify your payment. Please include your registration details in the payment reference.

Best regards,
{{ $registration->camp->tenant->name }}
@endcomponent
