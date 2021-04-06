<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * This function returns the comments for a single post
     * @param $postId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($postId)
    {
        $comments = CommentResource::collection(Comment::getComments($postId));
        return  ResponseHelper::successResponse(__('common.data_returned_successfully'), $comments);
    }

    /**
     * This function adds a new comment for a post
     * @param CommentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addComment(CommentRequest $request)
    {
        try {
            $userId = Auth::id();
            $comment = [
                'comment' => $request->comment,
                'post_id' => $request->post_id,
                'user_id' => $userId
            ];
            Comment::create($comment);
            $response = ResponseHelper::successResponse(__('comment.comment_added_successfully'));
        }catch (\Exception $exception){
            $response = ResponseHelper::errorResponse(__('common.some_error'). $exception->getMessage(), 201);
        }
        return $response;
    }
}
