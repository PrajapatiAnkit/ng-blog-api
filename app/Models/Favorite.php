<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Favorite extends Model
{
    use HasFactory;
    const FAVORITE = 1;
    const UN_FAVORITE = 2;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['post_id', 'post_id', 'user_id','status'];

    /**
     * The attributes that aren't mass assignable.
     * @var array
     */
    protected $guarded = ['created_at', 'updated_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at','updated_at'];

    /**
     * fields will be Carbon-ized
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'post_id' => 'int',
        'user_id' => 'int',
        'status' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * This function marks the post to be favorite for a user
     * @param $postId
     * @param $userId
     * @param $status
     * @return object
     */
    public static function markFavorite($postId, $userId, $status)
    {
        if ($status == self::FAVORITE){
            return self::create([
                'post_id' => $postId,
                'user_id' => $userId,
                'status' => $status,
            ]);
        }else{
            return self::where(['post_id' => $postId, 'user_id' => $userId])->delete();
        }
    }

    /**
     * This function returns the favorite post ids
     * @param $userId
     * @return array
     */
    public static function getFavoritesPostsIds($userId)
    {
        return self::where('user_id', $userId)->pluck('post_id');
    }
}
