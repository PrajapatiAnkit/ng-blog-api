<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        try {
            $posts = Post::select('posts.*','users.name as author')
                ->join('users','posts.user_id','users.id')
                ->orderBy('posts.created_at','DESC')
                ->get();
            $response = ResponseHelper::successResponse(config('constants.lang.data_returned_successfully'),$posts);
        }catch (\Exception $exception){
            $response = ResponseHelper::errorResponse(__('common.lang.data_returned_successfully'). $exception->getMessage(), 201);
        }
        return $response;
    }
    /**
     * This function creates/updates the post
     * @param PostRequest $postRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function savePost(PostRequest $postRequest)
    {
        $userId = Auth::id();
        Post::savePost($postRequest, $userId);
        try {
            $response = ResponseHelper::successResponse('Post created');
        }catch (\Exception $exception){
            $response = ResponseHelper::errorResponse("some error". $exception->getMessage(), 201);
        }
        return $response;
    }

    /**
     * This function returns the post detail
     * @param $postId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPostDetail($postId)
    {
        $post = Post::findOrFail($postId);
        return ResponseHelper::successResponse(__('common.data_returned_successfully'),$post);
    }
}
