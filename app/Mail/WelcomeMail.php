<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    /**
     * Create a new message instance.
     *
     * @param $details
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'name' => $this->details['name'],
            'header' => $this->details['header'],
            'body' => $this->details['body'],
            'actionText' => $this->details['actionText'],
            'actionURL' => $this->details['actionURL'],
        ];
        $appName = config('app.name');
        return $this->markdown('emails.welcome')
            ->subject("Welcome to  $appName")
            ->with($data);

    }
}
