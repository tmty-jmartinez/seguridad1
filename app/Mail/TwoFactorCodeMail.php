<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TwoFactorCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;

    /**
     * Create a new message instance.
     *
     * @param string $code
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Explicitly specify the view to be used for the email
        return $this->view('emails.two-factor-code')
                    ->subject('Your Verification Code')
                    ->with(['code' => $this->code]);
    }
}

