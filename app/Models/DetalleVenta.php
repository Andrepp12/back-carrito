<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalle_venta';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id', 'venta_id', 'producto_id', 'cantidad', 'precio_unitario', 'subtotal', 'estado'
    ]; 

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
