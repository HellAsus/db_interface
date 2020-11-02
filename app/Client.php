<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    public $timestamps = false;
    protected $connection = 'mysql2';
    protected $table = 'users';

    public function street ()
    {
        return $this->hasOne('App\Street', 'id', 'street_id');
    }

    public function acc ()
    {
        return $this->hasOne('App\Acc', 'user_id', 'id');
    }

}
