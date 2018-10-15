<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class T_alumnos extends Model
{
        protected  $table = 't_alumnos';
        // Relacion
        
        public function alumnos(){
            return $this->belongsTo('App\User','user_id');
        }
}
