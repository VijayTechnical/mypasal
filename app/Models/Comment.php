<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['post_id','comment','answer_id','user_id'];


    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function post()
    {
        return $this->belognsTo(Post::class,'post_id','id');
    }

    public function answer()
    {
        return $this->hasOne(Comment::class,'answer_id');
    }
}
