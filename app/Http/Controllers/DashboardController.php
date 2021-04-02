<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * this function returns the dashboard details
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $userId = Auth::id();
        $postCount = DashboardService::getPostsCount();
        $myPostCount = DashboardService::getMyPostCount($userId);
        return ResponseHelper::successResponse('dashboard details',[
            'user' => Auth::user(),
            'post_count' => $postCount,
            'my_post_count' => $myPostCount
        ]);
    }
}
