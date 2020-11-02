<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Acc extends Model
{
    public $timestamps = false;
    protected $connection = 'mysql2';
    protected $table = 'acc_ses2';

    public function client ()
    {
        return $this->hasOne('App\Client', 'id', 'user_id');
    }
}
