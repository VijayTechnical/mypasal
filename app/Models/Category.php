<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name','icon','image','slug','status','parent_id','show_in_home','position','ad_image','link'];


    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function custom_fields()
    {
        return $this->belongsToMany(CustomField::class);
    }

    public function childrens()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(Post::class)->where('status', 1)->where('is_sold', 0);
    }

    public function packs()
    {
        return $this->belongsToMany(AdPacks::class);
    }

    public function childrenRecursive()
    {
        return $this->childrens()->where('status',1)->with('childrenRecursive');
    }

}

