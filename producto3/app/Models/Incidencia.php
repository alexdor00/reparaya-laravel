<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    protected $table = 'incidencias';
    
    protected $fillable = [
        'codigo', 'cliente_id', 'tipo_servicio_id', 'descripcion',
        'direccion', 'telefono_contacto', 'fecha_solicitada',
        'franja_horaria', 'prioridad', 'estado', 'tecnico_id'
    ];

    public function cliente()
    {
        return $this->belongsTo(Usuario::class, 'cliente_id');
    }

    public function tecnico()
    {
        return $this->belongsTo(Usuario::class, 'tecnico_id');
    }

    public function tipoServicio()
    {
        return $this->belongsTo(TipoServicio::class, 'tipo_servicio_id');
    }

    public function comision()
    {
        return $this->hasOne(Comision::class, 'incidencia_id');
    }
}