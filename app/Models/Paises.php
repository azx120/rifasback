<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidModel;

class Paises extends Model
{
    use HasFactory, UuidModel;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paises';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'nombre', 'code_contry', 'created_at', 'updated_at'	
 
    ];

        /**
     * Obtiene los ciudades.
     */
    public function ciudades()
    {
        return $this->hasMany(Ciudades::class,'paises_id', 'id');
    }

}
