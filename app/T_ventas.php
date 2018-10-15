<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;
class T_ventas extends Model
{
    //
    protected  $table = 't_ventas';
//    
    public function calificaciones(){
            return $this->belongsTo('App\User','user_id');
        }
}
