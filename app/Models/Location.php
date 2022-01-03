<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name','lat','lang','status','show_in_home','parent_id'];

    public function location()
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class,'location');
    }
}
