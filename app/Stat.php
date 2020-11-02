<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    public $timestamps = false;
    protected $connection = 'mysql2';
    protected $table = 'stat';

    public function client ()
    {
        return $this->hasMany('App\Client', 'id', 'user_id');
    }
}
