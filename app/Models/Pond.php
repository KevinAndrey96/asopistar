<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pond extends Model
{
    use HasFactory;
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne;
     */
    public function user(){
        return $this->hasOne('App\Models\User','id','user_id');
    }
}
