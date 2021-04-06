<?php


namespace App\Services;


use App\Models\User;

class UserService
{
    /**
     * This function returns the user detail
     * @param $userId
     * @return object
     */
    public static function getUserData($userId)
    {
        return User::findOrFail($userId);
    }
}
