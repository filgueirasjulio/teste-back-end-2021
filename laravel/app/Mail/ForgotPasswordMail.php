<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    
    public $user;
    public $token;

    /**
     * __construct
     *
     * @param  mixed $user
     * @param  mixed $token
     * @return void
     */
    public function __construct(User $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.forgot_password')
            ->subject("Alteração de senha")
            ->with([
                'resetPasswordLink' => config('app.url') . '/recover-password?token=' . $this->token,
            ]);
    }
}
