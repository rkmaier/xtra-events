@component('mail::message')
# New Event Registration

{{ $attendee->name }} has registered for your event **{{ $event->name }}**.

## Event Details
- **Event Name:** {{ $event->name }}
- **Event Date:** {{ \Carbon\Carbon::parse($event->date)->format('Y.m.d. H:i') }}
- **Description:** {{ $event->description }}

## Attendee Information
- **Name:** {{ $attendee->name }}
- **Email:** {{ $attendee->email }}
- **Registered At:** {{ now()->format('Y.m.d. H:i') }}

## Current Registration Status
- **Total Attendees:** {{ $attendeeCount }}
@if($event->limit > 0)
- **Event Limit:** {{ $event->limit }}
- **Spots Remaining:** {{ max(0, $event->limit - $attendeeCount) }}
@endif

@component('mail::button', ['url' => route('events.index')])
View events
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

