<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\UuidModel;


class Participants extends Model
{
    use HasFactory, UuidModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',	
        'ci',	
        'name',	
        'lastname',	
        'phone',	
        'city_id',	
        'created_at',	
        'updated_at',
    ];

}
