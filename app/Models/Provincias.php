<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidModel;

class Provincias extends Model
{
    use HasFactory, UuidModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id','name', 'country_id'
    ];

    public function Provincias()
    {
        return $this->belongsTo(Paises::class, 'paises_id', 'id');
    }

    /**
     * Obtiene los sectores.
     */
    public function sectores()
    {
        return $this->hasMany(Sectores::class,'ciudades_id', 'id');
    }

    /**
     * Obtiene llas imagenes de galeria.
     */
    public function galeries()
    {
        return $this->hasMany(Galery::class,'ciudades_id', 'id');
    }
}
