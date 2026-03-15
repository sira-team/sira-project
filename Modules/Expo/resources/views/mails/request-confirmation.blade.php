<x-mail::message>
# Expo-Anfrage bestätigt

Hallo {{ $expoRequest->contact_name }},

vielen Dank für Ihre Expo-Anfrage. Wir haben Ihre Informationen erhalten und werden uns in Kürze mit Ihnen in Verbindung setzen.

**Zusammenfassung Ihrer Anfrage:**

- **Organisation:** {{ $expoRequest->organisation_name }}
- **Kontaktperson:** {{ $expoRequest->contact_name }}
- **E-Mail:** {{ $expoRequest->email }}
@if($expoRequest->phone)
- **Telefon:** {{ $expoRequest->phone }}
@endif
@if($expoRequest->city)
- **Stadt:** {{ $expoRequest->city }}
@endif
@if($expoRequest->preferred_date_from)
- **Bevorzugtes Datum:** {{ $expoRequest->preferred_date_from->format('d.m.Y') }} @if($expoRequest->preferred_date_to)bis {{ $expoRequest->preferred_date_to->format('d.m.Y') }}@endif
@endif
@if($expoRequest->expected_visitors)
- **Erwartete Besucher:** {{ $expoRequest->expected_visitors }}
@endif

Kontakt: {{ $expoRequest->tenant->email }}

Mit freundlichen Grüßen,
**{{ $expoRequest->tenant->name }}**
</x-mail::message>
