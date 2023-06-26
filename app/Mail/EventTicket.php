<?php

namespace App\Mail;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventTicket extends Mailable
{
    use Queueable, SerializesModels;


    // ...
    protected $userName;
    protected $userEmail;
    protected $title;
    protected $mode;
    protected $date;
    protected $time;

    /**
     * Create a new message instance.
     */
    public function __construct($userName, $userEmail, $eventId)
    {
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $event = Event::findOrFail($eventId);
        $this->title = $event->title;
        $this->mode = $event->mode;
        $this->date = $event->startDate;
        $this->time = $event->startTime;
    }

    // ...

    public function build()
    {
        return $this->from('eberth@test.com')
            ->to($this->userEmail)
            ->subject('Extia Event Ticket')
            ->markdown('emails.event_ticket', [
                'userName' => $this->userName,
                'title'=> $this->title,
                'mode' => $this->mode,
                'date' => $this->date,
                'time' => $this->time,
            ]);
    }
}
