<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'venta';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id', 'cliente_id', 'total', 'fecha', 'estado',
    ]; 

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
