<?php

namespace App\Policies;

use App\Models\Url;
use App\Models\User;

class UrlPolicy
{
    public function update(User $user, Url $url): bool
    {
        return $user->id === $url->user_id || $user->isAdmin();
    }

    public function delete(User $user, Url $url): bool
    {
        return $user->id === $url->user_id || $user->isAdmin();
    }
}
