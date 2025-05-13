<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Traits\UuidModel;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, UuidModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'name' ,	
        'apellido',	
        'email',	
        'sexo',	
        'dni',	
        'edad',	
        'telefono',	
        'paises_id',	
        'ciudad_id',	
        'provincia_id',	
        'sector_id',	
        'direccion',	
        'rol',	
        'password',	
        'remember_token',	
        'created_at',	
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Obtiene las imagenes de galeria.
     */
    public function AppDatas()
    {
        return $this->belongsTo(AppDatas::class, 'id', 'user_id');
    }

    /**
     * Obtiene las imagenes de galeria.
     */
    public function Chats()
    {
        return $this->belongsTo(Chats::class, 'id', 'user_id');
    }

    /**
     * Obtiene las imagenes de galeria.
     */
    public function Pagos()
    {
        return $this->hasmany(Pagos::class);
    }
}
