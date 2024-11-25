<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Cliente extends Authenticatable
{
    protected $table = 'cliente';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id', 'nombre', 'correo', 'password', 'telefono', 'direccion', 'estado',
    ]; 

    protected $hidden = [
        'password',
    ];
}
