<?php

namespace App\Http\Resources;

use Carbon\CarbonInterval;
use Illuminate\Http\Resources\Json\JsonResource;


class Stat extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->client = $this->client[0] ?? $this->client;
        return [
            'fio' => $this->client->fio,
            'ip' => $this->ip,
            'address' => $this->client->street()->first()->name . " " . $this->client->block . "/" . $this->client->kv,
            'ipTime' => $this->date ?? $this->ses_start,
            'duration' => CarbonInterval::seconds($this->time ?? $this->ses_time)->cascade()->locale('ru_RU')->forHumans(),
        ];
    }
}
