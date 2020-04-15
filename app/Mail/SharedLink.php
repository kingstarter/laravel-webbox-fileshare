<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SharedLink extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * This is the shared link ending date
     */
    public $validUntil;
    /**
     * This is the shared link URL passed to the email
     */
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $path, string $validUntil)
    {
        $this->validUntil = $validUntil;
        $this->url = preg_replace('/\/*$/', '', config('app.url')) . '/' . $path;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Use from address as given by config/mail.php
        return $this->markdown('emails.shared.link');
    }
}
