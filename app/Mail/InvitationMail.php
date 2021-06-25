<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Auth\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class InvitationMail.
 */
class InvitationMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * The invitation that grants access.
     *
     * @var Invitation
     */
    protected Invitation $invitation;

    /**
     * Create a new message instance.
     *
     * @param Invitation $invitation
     * @return void
     */
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->subject(__('nova.invitation_subject'))
            ->markdown('mail.invitation')
            ->with('url', route('register', ['token' => $this->invitation->token]));
    }
}