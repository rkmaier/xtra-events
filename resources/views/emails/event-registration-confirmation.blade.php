@component('mail::message')
# Event Registration Confirmation

Hello {{ $user->name }},

Thank you for registering for **{{ $event->name }}**!

## Event Details

- **Event Name:** {{ $event->name }}
- **Event Date:** {{ \Carbon\Carbon::parse($event->date)->format('F j, Y \a\t g:i A') }}
- **Description:** {{ $event->description }}

@if($event->image)
@component('mail::panel')
Event Image: [View Event]({{ route('events.index') }})
@endcomponent
@endif

## Registration Information

- **Registered At:** {{ now()->format('F j, Y \a\t g:i A') }}
@if($event->limit > 0)
- **Event Capacity:** {{ $event->limit }} attendees
- **Current Attendees:** {{ $attendeeCount }}
- **Spots Remaining:** {{ max(0, $event->limit - $attendeeCount) }}
@endif

We look forward to seeing you at the event!

@component('mail::button', ['url' => route('events.index')])
View All Events
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

