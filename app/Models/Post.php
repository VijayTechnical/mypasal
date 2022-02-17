<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const CONDITION = [

        'Good/Fair',
        'Old',
        'New',
        'Like New',
    ];

    public const DELIVERY_LOCATION = [

        'Within My Area',
        'Within My City',
        'Almost Anywhere in Nepal',

    ];

    protected $fillable = ['title', 'slug', 'description', 'selling_price', 'is_negotiable', 'location', 'home_delivery', 'delivery_location', 'expire_date', 'is_sold', 'status', 'custom_fields', 'user_id', 'category_id', 'condition', 'thumbnail', 'featured', 'views', 'tag', 'delivery_charge', 'youtube_link'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopenotSold($query)
    {
        return $query->where('is_sold', 0);
    }

    public function Location()
    {
        return $this->belongsTo(Location::class,'location_id','id')->with('Location');
    }

    public function scopenotExpire($query)
    {
        return $query->whereDate('expire_date', '>', Carbon::now());
    }
}
