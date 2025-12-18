<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventRegistrationNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The event instance.
     *
     * @var \App\Models\Event
     */
    public $event;

    /**
     * The attendee who registered.
     *
     * @var \App\Models\User
     */
    public $attendee;

    /**
     * The total number of attendees.
     *
     * @var int
     */
    public $attendeeCount;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Event $event, User $attendee)
    {
        $this->event = $event;
        $this->attendee = $attendee;
        $this->attendeeCount = $event->attendees()->count();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.event-registration')
            ->subject('New Registration for: '.$this->event->name);
    }
}
