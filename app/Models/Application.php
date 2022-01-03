<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name','email','phone','vacancy_id','resume'];

    public function vacancy()
    {
        return $this->belongsTo(Vacancy::class,'vacancy_id','id');
    }
}
