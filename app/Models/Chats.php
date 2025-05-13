<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidModel;

class Chats extends Model
{
    use HasFactory, UuidModel;

    protected $table = 'chats';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id', 'id_user', 'phone', 'chat', 'created_at', 'updated_at'
    ];

    /**
     * Obtiene el sector de la subcategoria.
     */
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Obtiene el sector de la subcategoria.
     */
    public function Business()
    {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }
    

   
}
