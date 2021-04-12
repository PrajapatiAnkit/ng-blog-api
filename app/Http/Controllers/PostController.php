<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Http\Requests\PostRequest;
use App\Models\Favorite;
use App\Models\Post;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * This function loads all the posts created by users
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $favoritePosts = Favorite::getFavoritesPostsIds(Auth::id());
            $posts = Post::select('posts.*','users.name as author')
                ->join('users','posts.user_id','users.id')
                ->latest()
                ->paginate(10);
            $response = ResponseHelper::successResponse(__('common.data_returned_successfully'),[
                'posts' => $posts,
                'favorites' => $favoritePosts
            ]);
        }catch (\Exception $exception){
            $response = ResponseHelper::errorResponse(__('common.some_error'). $exception->getMessage(), 201);
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
        try {
            $userId = Auth::id();
            $fileName = null;
            if ($postRequest->feature_image != ''){
                $thumbnailPath = config('constants.paths.thumbnail');
                $fileName = Str::slug($postRequest->title).'_'.time().'.png';
                $base64Image = $postRequest->feature_image;
                if ($postRequest->post_id){
                    $post = Post::findOrFail($postRequest->post_id,['featured_image']);
                    $preFile = $post->getRawOriginal('featured_image');
                }else{
                    $preFile = null;
                }
                MediaService::uploadMedia($base64Image,$fileName, $thumbnailPath, $preFile);
            }
            Post::savePost($postRequest, $fileName, $userId);
            $response = ResponseHelper::successResponse(__('post.post_saved_successfully'));
        }catch (\Exception $exception){
            $response = ResponseHelper::errorResponse(__('common.some_error'). $exception->getMessage(), 201);
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
        $favoritePosts = Favorite::getFavoritesPostsIds(Auth::id());
        $post = Post::select('posts.*','users.name as author')
            ->join('users','posts.user_id','users.id')
            ->where('posts.id',$postId)
            ->first();
        if ($post){
            return ResponseHelper::successResponse(__('common.data_returned_successfully'),['post' => $post,'favorites' => $favoritePosts]);
        }else{
            return ResponseHelper::errorResponse(__('common.data_returned_successfully'),404);
        }
    }

    /**
     * This function markes the post as favorite
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markFavorite(Request $request)
    {
        try{
            $userId = Auth::id();
            if ($request->status == Favorite::FAVORITE) {
                $message = __('post.marked_favorites');
            }else{
                $message = __('post.marked_un_favorites');
            }
            Favorite::markFavorite($request->post_id, $userId, $request->status);
            return ResponseHelper::successResponse($message);
        }catch(\Exception $e){
            return  ResponseHelper::errorResponse(__('common.some_error'). $e->getMessage(), 201);
        }
    }
}
