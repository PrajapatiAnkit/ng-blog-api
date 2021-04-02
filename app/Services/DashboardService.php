<?php


namespace App\Services;


use App\Models\Post;

class DashboardService
{
    /**
     * This function returns the total post counts
     * @return int
     */
    public static function getPostsCount()
    {
        return Post::count();
    }

    /**
     * This function retruns the current loggedin user post counts
     * @param $userId
     * @return int
     */
    public static function getMyPostCount($userId)
    {
        return Post::where('user_id', $userId)->count();
    }
}
