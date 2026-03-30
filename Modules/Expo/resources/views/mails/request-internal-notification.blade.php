<x-mail::message>
# Neue Expo-Anfrage

Eine neue Expo-Anfrage wurde eingereicht.

**Kontaktperson:** {{ $expoRequest->contact_name }}
**Organisation:** {{ $expoRequest->organisation_name }}
**E-Mail:** {{ $expoRequest->email }}
@if($expoRequest->phone)
**Telefon:** {{ $expoRequest->phone }}
@endif
@if($expoRequest->city)
**Stadt:** {{ $expoRequest->city }}
@endif
@if($expoRequest->preferred_date_from)
**Bevorzugtes Datum:** {{ $expoRequest->preferred_date_from->format('d.m.Y') }} @if($expoRequest->preferred_date_to)bis {{ $expoRequest->preferred_date_to->format('d.m.Y') }}@endif
@endif

@if($expoRequest->message)
**Nachricht:**
{{ $expoRequest->message }}
@endif

<x-mail::button :url="url('/expo/'.$expoRequest->tenant->slug.'/expo-requests/'.$expoRequest->id.'/edit')">
Anfrage anzeigen
</x-mail::button>

Mit freundlichen Grüßen,
**{{ $expoRequest->tenant->name }}**
</x-mail::message>
