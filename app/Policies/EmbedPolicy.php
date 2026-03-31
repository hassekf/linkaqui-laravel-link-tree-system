<?php

namespace App\Policies;

use App\Models\Embed;
use App\Models\User;

class EmbedPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Embed $embed): bool
    {
        return $embed->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Embed $embed): bool
    {
        return $embed->user_id === $user->id;
    }
}
