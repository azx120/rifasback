<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AppDatasResource;

class UsersResource extends JsonResource
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
            'name' => $this->name,	
            'apellido' => $this->apellido,	
            'email' => $this->email,	
            'sexo' => $this->sexo,	
            'dni' => $this->dni,	
            'edad' => $this->edad,	
            'telefono' => $this->telefono,	
            'paises_id' => $this->paises_id,	
            'ciudad_id' => $this->ciudad_id,	
            'provincia_id' => $this->provincia_id,	
            'sector_id' => $this->sector_id,	
            'direccion' => $this->direccion,	
            'rol' => $this->rol,	
            'password' => $this->password,	
            'remember_token' => $this->remember_token,	
            'created_at' => $this->format('m/d/Y'),	
            'updated_at' => $this->format('m/d/Y'),
            'appDatas' =>  AppDatasResource::collection($this->appDatas)
        ];    
    }
}
