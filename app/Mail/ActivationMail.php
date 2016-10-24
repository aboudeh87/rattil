<?php

namespace App\Mail;


use App\User;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivationMail extends Mailable
{

    use SerializesModels;

    /**
     * @var \App\User
     */
    private $user;

    /**
     * Create a new message instance.
     *
     * @param \App\User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.activation', ['user' => $this->user])
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject(trans('emails.activation_subject'));
    }
}
