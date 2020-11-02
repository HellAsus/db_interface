<?php

namespace App\Http\Resources;

use Carbon\CarbonInterval;
use Illuminate\Http\Resources\Json\JsonResource;


class Client extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'fio' => $this->fio,
            'ip' => $this->acc->ip ?? '',
            'address' => $this->street()->first()->name . " " . $this->block . "/" . $this->kv,
            'ipTime' => $this->acc->ses_start ?? '',
            'duration' => $this->acc ? CarbonInterval::seconds($this->acc->ses_time)->cascade()->locale('ru_RU')->forHumans() : '',
        ];
    }
}
