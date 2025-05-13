<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidModel;

class Talonarios extends Model
{
    use HasFactory, UuidModel;

    protected $table = 'talonarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id', 'user_id', 'precio', 'numero', 'numeros',	'status', 'created_at', 'updated_at'
    ];


   
}
