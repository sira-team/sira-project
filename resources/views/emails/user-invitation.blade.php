<x-mail::message>
# Willkommen bei {{ $team->name }}

Hallo {{ $user->name }},

Sie wurden eingeladen, dem Portal von **{{ $team->name }}** beizutreten.

Bitte klicken Sie auf den Button unten, um Ihr Passwort zu setzen und Ihren Account zu aktivieren. Der Link ist 7 Tage gültig.

<x-mail::button :url="$setupUrl">
Passwort festlegen
</x-mail::button>

Mit freundlichen Grüßen,<br>
{{ $team->name }}
</x-mail::message>