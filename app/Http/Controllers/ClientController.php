<?php

namespace App\Http\Controllers;

use Validator;
use Arr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Client;
use App\Stat;
use App\Acc;
use App\Http\Controllers\LogController;
use App\Http\Resources\Stat as StatRes;
use App\Http\Resources\Client as ClientRes;

class ClientController extends Controller
{
    public function check (Request $request)
    {
        $Log = new LogController();
        if ($query = $request->get('query')){
            $validator = Validator::make($request->all(), ['query' => 'required|regex:/^(?:[А-ЯЁа-яё]+ ){2}[А-ЯЁа-яё]+$/u']);
            if ($validator->fails()) {
                $Log->writeLog($query, false, 'Search');
                return ['error' => "Не корректный запрос!"];
            } else {
                $clients = Client::where('fio', 'like' ,"%$query%")
                ->whereDate('users.exp_date', '>=', NOW())
                ->with('acc')
                ->get();
                if ($clients->count()) {
                    $Log->writeLog($query, true, 'Search');
                    return ClientRes::collection($clients);
                } else {
                    return ['error' => "Данные не найдены"];
                }
            }
        } elseif ($request->post()){
            $validator = Validator::make($request->all(), ['ip' => 'required|ipv4']);
            if ($validator->fails()) {
                $Log->writeLog($request->ip, false, 'Search');
                return ['error' => "Не корректный запрос!"];
            } else {
                $fromTime = Carbon::createFromTimeString("$request->startDate $request->startTime");
                $toTime = Carbon::createFromTimeString("$request->endDate $request->endTime");

                $clients = Stat::where('ip', '=', $request->ip)
                ->whereBetween('date', [$fromTime, $toTime])
                ->with('client')->get();

                if($clients->count() > 3) return ['error' => "Укажите временные рамки точнее."];

                if ($clients->isEmpty()){
                    $clients = Acc::where('ip', '=', $request->ip)
                    ->with('client')->get();
                }
                $Log->writeLog("$request->ip from $fromTime to $toTime", true, 'Search');
                return StatRes::collection($clients);
            }
        }
    }


}
