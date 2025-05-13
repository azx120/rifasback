<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppDatasResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id, 
            'user_id' => $this->user_id,	
            'redes' => $this->redes,	
            'phone_verified' => $this->phone_verified,	
            'code_phone' => $this->code_phone,	
            'register_verified' => $this->register_verified,	
            'email_verified_at' => $this->email_verified_at,	
            'registered' => $this->registered,	
            'created_at' =>  $this->format('m/d/Y'),	
            'updated_at' =>  $this->format('m/d/Y'),	    
        ];
    }
}
