<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdPacks extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['type','size','valid','valid_parameter','price','discount','description','status'];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
