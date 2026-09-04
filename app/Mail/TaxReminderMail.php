<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Vehicle;

class TaxReminderMail extends Mailable
{
    use Queueable, SerializesModels;
    public $vehicle;

    public function __construct(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
    }

    public function build()
    {
        return $this->subject('Pemberitahuan Jatuh Tempo Pajak Kendaraan - ' . $this->vehicle->nopol)
                    ->view('emails.tax-reminder');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tax Reminder Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tax-reminder',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
