<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class Newsletter extends Mailable
{
    public $body;

    /**
     * Newsletter constructor.
     * @param array $body
     */
    public function __construct(array $body)
    {
        $this->subject = $body['title'];
        $this->body = $body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.news-letter');
    }
}
