<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventRegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The event instance.
     *
     * @var \App\Models\Event
     */
    public $event;

    /**
     * The user who registered.
     *
     * @var \App\Models\User
     */
    public $user;

    /**
     * The total number of attendees.
     *
     * @var int
     */
    public $attendeeCount;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Event  $event
     * @param  \App\Models\User  $user
     * @return void
     */
    public function __construct(Event $event, User $user)
    {
        $this->event = $event;
        $this->user = $user;
        $this->attendeeCount = $event->attendees()->count();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.event-registration-confirmation')
            ->subject('Registration Confirmation: ' . $this->event->name);
    }
}

