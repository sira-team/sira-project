<x-mail::message>
# Ihre Expo-Anfrage wurde akzeptiert

Hallo {{ $expoRequest->contact_name }},

großartig! Ihre Expo-Anfrage wurde akzeptiert. Unser Tenant wird sich in den nächsten Tagen mit Ihnen in Verbindung setzen, um die genauen Details und nächste Schritte zu besprechen.

**Anfrageinformationen:**
- **Organisation:** {{ $expoRequest->organisation_name }}
@if($expoRequest->preferred_date_from)
- **Bevorzugtes Datum:** {{ $expoRequest->preferred_date_from->format('d.m.Y') }} @if($expoRequest->preferred_date_to)bis {{ $expoRequest->preferred_date_to->format('d.m.Y') }}@endif
@endif

Falls Sie weitere Fragen haben, zögern Sie nicht, sich an uns zu wenden.

Kontakt: {{ $expoRequest->tenant->email }}

Mit freundlichen Grüßen,
**{{ $expoRequest->tenant->name }}**
</x-mail::message>
