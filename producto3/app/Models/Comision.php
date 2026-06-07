<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comision extends Model
{
    protected $table = 'comisiones';
    
    protected $fillable = [
        'incidencia_id', 'empresa_gestora_id', 'precio_base',
        'porcentaje', 'importe', 'mes', 'anio'
    ];

    public function incidencia()
    {
        return $this->belongsTo(Incidencia::class, 'incidencia_id');
    }

    public function empresaGestora()
    {
        return $this->belongsTo(EmpresaGestora::class, 'empresa_gestora_id');
    }
}