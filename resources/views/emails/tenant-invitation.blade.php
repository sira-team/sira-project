<x-mail::message>
# Welcome to Sira App

You have been invited to manage **{{ $tenant->name }}** on the Sira App.

To get started, please set up your password by clicking the button below. This link will expire in 7 days.

<x-mail::button :url="$signedUrl">
Set Up Your Password
</x-mail::button>

If you have any questions, please contact the Sira App tenant.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
