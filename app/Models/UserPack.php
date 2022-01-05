<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPack extends Model
{
    use HasFactory;

    const PAYMENT_COMPLETED = 1;
    const PAYMENT_PENDING = 0;

    protected $fillable = ['user_id','ad_pack_id','type','valid','valid_parameter','size','price','payment_status','ref'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ad_pack()
    {
        return $this->belongsTo(AdPacks::class);
    }
}
