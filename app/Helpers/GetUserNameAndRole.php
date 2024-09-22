<?php

namespace App\Helpers;
use App\Models\Admin;
use App\Models\Employee;

class GetUserNameAndRole
{
    /**
     * Get the user's name and roles.
     *
     * @param \App\Models\User $user
     * @return array
     */
    public static function getUserDetails($userId)
    {
        if (session('user_type') === 'Admin') {
            $user = Admin::find($userId);
            return [
                'name' => $user->name,
                'role' => 'Admin',
            ];
        } else {
            $user = Employee::with('role')->with('location')->find($userId);
            return [
                'name' => $user->name,
                'role' => $user->role->name,
                'location' => $user->location->name,
            ];
        }
    }
}
