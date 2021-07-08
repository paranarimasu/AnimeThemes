<?php

declare(strict_types=1);

namespace App\Nova\Actions\Auth;

use App\Mail\InvitationMail;
use App\Models\Auth\Invitation;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

/**
 * Class ResendInvitationAction.
 */
class ResendInvitationAction extends Action
{
    use InteractsWithQueue;
    use Queueable;

    /**
     * Get the displayable name of the action.
     *
     * @return array|string|null
     */
    public function name(): array | string | null
    {
        return __('nova.resend_invitation');
    }

    /**
     * Perform the action on the given models.
     *
     * @param ActionFields $fields
     * @param Collection $models
     * @return array
     * @throws Exception
     */
    public function handle(ActionFields $fields, Collection $models): array
    {
        $resentInvitations = [];

        foreach ($models as $model) {
            // Don't send mail for invitation that have been claimed
            if ($model->isOpen()) {
                // Reset token
                $model->token = Invitation::createToken();
                $model->save();

                // Send replacement email
                Mail::to($model->email)->queue(new InvitationMail($model));
                array_push($resentInvitations, $model->name);
            }
        }

        if (! empty($resentInvitations)) {
            return Action::message(__('nova.resent_invitations_for_users', ['users' => implode(', ', $resentInvitations)]));
        } else {
            return Action::danger(__('nova.resent_invitations_for_none'));
        }
    }
}