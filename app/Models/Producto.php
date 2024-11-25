<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'producto';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'imagen',
        'categoria_id',
        'estado',
    ];

    // Relación con Categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
    public function getImagenAttribute($value)
    {
        return $value ? Storage::url($value) : null;
    }
}