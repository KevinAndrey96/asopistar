<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feeding extends Model
{
    use HasFactory;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne;
     */
    public function user(){
        return $this->hasOne('App\Models\User','id','user_id');
    }
    public function pond(){
        return $this->hasOne('App\Models\Pond','id','pond_id');
    }
}
