<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name','status','url','menu_id'];

    public function menu(){
        return $this->belongsTo(Menu::class);
    }

    public function child(){
        return $this->hasMany(Menu::class,'menu_id','id');
    }

}
