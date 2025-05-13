<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UuidModel;

class AppDatas extends Model
{
    use HasFactory, UuidModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'appdatas';

    protected $fillable = [
        'user_id',	
        'redes',	
        'phone_verified',	
        'code_phone',	
        'register_verified',	
        'email_verified_at',	
        'registered',	
        'created_at',	
        'updated_at'     					

    ];


    public function Users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
