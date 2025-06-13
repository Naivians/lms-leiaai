<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserRestrictions
{
    /**
     * Check if the user is restricted from performing certain actions.
     *
     * @return bool
     */

    /**
     * Check if the user can perform a specific action.
     *
     * @param string $action
     * @return bool
     */
    public function canPerformAction(string $action): bool
    {
        return Gate::allows($action);
    }
}
