<x-mail::message>
# New Contact Form Submission

You have received a new message from the Git Infosys Contact form.

**From:** {{ $contactMessage->name }} ({{ $contactMessage->email }})  
**Phone:** {{ $contactMessage->phone }}  
**Subject:** {{ $contactMessage->subject }}

---

### Message
{{ $contactMessage->message }}

---

<x-mail::button :url="config('app.url') . '/secure-admin/contact-messages/' . $contactMessage->id">
View in Admin Panel
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
