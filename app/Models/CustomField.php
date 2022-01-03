<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomField extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['type','placeholder','title','is_required','status','values','highlight'];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
