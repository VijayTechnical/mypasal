<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vacancy extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name','slug','description','requirement','open_position','education','qualification','experience','status','expire_date'];

    public function applications(){

        return $this->hasMany(Application::class);
    }
}
