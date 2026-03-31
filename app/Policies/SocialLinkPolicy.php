<?php

namespace App\Policies;

use App\Models\SocialLink;
use App\Models\User;

class SocialLinkPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SocialLink $socialLink): bool
    {
        return $socialLink->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SocialLink $socialLink): bool
    {
        return $socialLink->user_id === $user->id;
    }
}
