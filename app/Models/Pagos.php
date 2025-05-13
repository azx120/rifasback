<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidModel;

class Pagos extends Model
{
    use HasFactory, UuidModel;

    protected $table = 'pagos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id', 'id_user', 'id_plan', 'idTransaccion', 'status','created_at', 'updated_at'
    ];


    /**
     * Obtiene el sector de la subcategoria.
     */
    public function Users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

   
}
