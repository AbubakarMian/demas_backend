<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendGeneralEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }


    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        // return new Envelope(
        // from: new Address('admin@demas.com', 'Admin'),
        //     subject: 'Send General Email',
        // );


        // to: new Address(
        //     $this->details['to_email'] ?? 'admin@demas.com',
        //     $this->details['to_name'] ?? 'Demas'
        // ),

        $to = [];

        $to[] =            [
            $this->details['to_email'] ?? 'abubakrmianmamoon@gmail.com',
            $this->details['to_name'] ?? 'Abubakar'
        ];;

        $to[] =            [
            $this->details['to_email'] ?? 'abubakarhere90@gmail.com',
            $this->details['to_name'] ?? 'AbubakarHere'
        ];;
        foreach ($this->details['recipient_emails'] as $to_email) {
            $to[] = [
                    $this->details['to_email'] ?? $to_email['email'],
                    $this->details['to_name'] ?? $to_email['name']
                ];
        }
        return new Envelope(
            from: new Address(
                $this->details['from_email'] ?? 'admin@demas.com',
                $this->details['from_name'] ?? 'Demas'
            ),
            to: $to,
            subject: $this->details['subject'],
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: $this->details['view'],
            with: [
                'data' => $this->details['data'] ?? [],
            ],
        );
        // return new Content(
        //     view: 'view.name',
        // );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        // Attachment::fromPath('/path/to/file')
        //             ->as('name.pdf')
        //             ->withMime('application/pdf'),
        // return $this->details['attachments'] ?? [];
        $attachements_email = [];
        $attachments = $this->details['attachments'] ?? [];
        foreach ($attachments as $key => $attachment) {
            $attachements_email[] = Attachment::fromPath($attachment['path'])
            ->as($attachment['name'])
            ->withMime($attachment['mime']);
        }
        return $attachements_email;
        // return [
        //     Attachment::fromPath('/path/to/file')
        //             ->as('name.pdf')
        //             ->withMime('application/pdf'),
        // ];

       
        // return [];
    }
}
