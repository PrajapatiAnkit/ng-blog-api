<?php

namespace App\Models;

use App\Casts\Json;
use http\QueryString;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;
    const ACTIVE = 1;
    const INACTIVE = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','slug','content','featured_image','tags',
        'categories','user_id','status'
    ];

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

    protected $appends = ['post_categories'];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'title' => 'string',
        'slug' => 'string',
        'content' => 'string',
        'featured_image' => 'string',
        'tags' => 'string',
        'categories' => Json::class,
        'created_at' => 'date:d/m/Y',
        'user_id ' => 'int',
        'status' => 'int',
    ];

    /**
     * This function creates/updates the post
     * @param $request
     * @param $featureImage
     * @param $userId
     * @return object
     */
    public static function savePost($request, $featureImage, $userId)
    {
        $postData = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'tags' => $request->tags,
            'categories' => explode(',', $request->categories),
            'user_id' => $userId
        ];
        if ($featureImage){
            $postData['featured_image'] = $featureImage;
        }
        return self::updateOrCreate(['id' => $request->post_id], $postData);
    }

    /**
     * This is is special function called 'scopes' to enhance the
     * query result before returning to client side
     * @param $query
     * @return QueryString
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('posts.created_at','DESC');
    }

    /**
     * This is special function accessor in laravel to modify the value before
     * sending it to client side
     * @param $value
     * @return string
     */
    public function getFeaturedImageAttribute($value): string
    {
        if($value){
            return asset('storage/uploads/thumbnail/'.$value);
        }
        return '';
    }
    public function scopePostSelect($query)
    {
        return $query->select('posts.*','users.name as author')
            ->join('users','posts.user_id','users.id')
            ->latest();
    }
    /**
     * This is special function accessor in laravel to modify the value before
     * sending it to client side
     * @param $status
     * @return string
     */
    public function getStatusAttribute($status): string
    {
        return $status == 1 ? 'Active': 'Inactive';
    }
    public function getPostCategoriesAttribute()
    {
        $categoriesNames = '';
        if ($this->categories){
            $categoriesId =  $this->categories;
            $categoriesNames = Category::whereIn('id', $categoriesId)->pluck('name');
            if($categoriesNames){
                $categoriesNames = $categoriesNames->toArray();
                $categoriesNames =  implode(',',$categoriesNames);
            }
        }
        return $categoriesNames;
    }

    /**
     * This is the laravel relationship to get comments of its posts
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class,'post_id','id')
            ->select('comments.id','comments.post_id','comments.comment','users.name as person_name')
            ->join('users','comments.user_id','users.id');
    }
}
