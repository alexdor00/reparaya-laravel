<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoServicio extends Model
{
    protected $table = 'tipos_servicio';
    
    protected $fillable = [
        'nombre', 'descripcion'
    ];

    public $timestamps = false;

    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'tipo_servicio_id');
    }
}