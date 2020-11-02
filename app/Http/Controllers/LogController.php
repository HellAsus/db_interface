<?php

namespace App\Http\Controllers;
use Auth;
use App\Log;

use Illuminate\Http\Request;
use App\Http\Resources\Log as LogRes;

class LogController extends Controller
{
    public function getLogs ()
    {
        return new LogRes(Log::orderBy('time', 'desc')->get());
    }

    public function writeLog ($data=null, $type=null, $action=null, $login=null)
    {
        Log::insert(
            [
                'user' => auth('api')->user()->login, 
                'action' => $action,
                'data' => $data,
                'type' => $type,
                'login' => $login,
                'time' => NOW()
            ]);
    }
}
