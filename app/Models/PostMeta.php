<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostMeta extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['post_id','custom_field_id','value'];
}
