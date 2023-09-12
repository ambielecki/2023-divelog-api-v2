<?php

namespace App\Policies;

use App\Models\DiveLog;
use App\Models\User;

class DiveLogPolicy
{
    public function __construct() {}

    public function read(User $user, DiveLog $dive_log): bool {
        return $user->id === $dive_log->user_id;
    }
}
