<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventTicket extends Mailable
{
    use Queueable, SerializesModels;

    protected $userEmail;
    public $userName;

    

    /**
     * Create a new message instance.
     */
    public function __construct($userEmail)
    {
        $this->userEmail = $userEmail;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Extia Event Ticket',
        );
    }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     $userName = $this->userName;
    //     return (new Content)->with([
    //         'userName' => $userName,
    //     ])->markdown('emails.event_ticket');
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    // public function attachments(): array
    // {
    //     return [];
    // }

    public function build()
    {
        return $this->from('eberth@test.com')
                    ->to($this->userEmail)
                    ->subject('Extia Event Ticket')
                    ->markdown('emails.event_ticket', [
                        $this->userName
                    ]);
    }
}
