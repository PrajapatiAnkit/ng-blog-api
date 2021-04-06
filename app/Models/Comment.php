<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    const ACTIVE = 1;
    const INACTIVE = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['comment', 'post_id', 'user_id', 'status'];

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
    protected $hidden = ['updated_at'];

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
        'comment' => 'string',
        'post_id' => 'int',
        'user_id' => 'int',
        'status' => 'int',
        'created_at' => 'date:d/m/Y',
    ];
    public function scopeActive($query)
    {
        return $query->where('comments.status', self::ACTIVE);
    }

    /**
     * This function returns the comment for a post
     * @param $postId
     * @return collection
     */
    public static function getComments($postId)
    {
        return self::select('comments.*','users.name as commented_by')
            ->join('users','comments.user_id','users.id')
            ->where('comments.post_id', $postId)
            ->orderBy('comments.id', 'DESC')
            ->active()
            ->get();
    }
}
