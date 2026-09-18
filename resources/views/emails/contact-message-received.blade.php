<x-mail::message>
# New contact message

**From:** {{ $contactMessage->name }} ({{ $contactMessage->email }})
@if($contactMessage->phone)
**Phone:** {{ $contactMessage->phone }}
@endif
@if($contactMessage->subject)
**Subject:** {{ $contactMessage->subject }}
@endif

{{ $contactMessage->message }}

<x-mail::button :url="url('/admin/contact-messages')">
View in Admin
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
