<?php

namespace App\Policies;

use App\Models\CV;
use App\Models\User;

class CVPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user, CV $cv) {
        return $user ->id === $cv->user->id;
    }

    public function delete(User $user, CV $cv) {
        return $user ->id === $cv->user->id;
    }

    public function update(User $user, CV $cv) {
        return $user->id === $cv->user_id;
    }
}
