<x-mail::message>
# Mitteilung zu Ihrer Expo-Anfrage

Hallo {{ $expoRequest->contact_name }},

vielen Dank für Ihre Expo-Anfrage. Leider ist es uns nicht möglich, Ihren Anfrage zum aktuellen Zeitpunkt zu unterstützen.

Falls Sie Fragen zur Ablehnung haben oder zukünftig ein Interesse an einer Zusammenarbeit besteht, nehmen Sie gerne Kontakt mit uns auf.

Kontakt: {{ $expoRequest->tenant->email }}

Mit freundlichen Grüßen,
**{{ $expoRequest->tenant->name }}**
</x-mail::message>
