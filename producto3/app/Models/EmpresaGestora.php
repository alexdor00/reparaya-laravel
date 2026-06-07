<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpresaGestora extends Model
{
    protected $table = 'empresas_gestoras';
    
    protected $fillable = [
        'nombre', 'email', 'telefono', 'comision_porcentaje'
    ];

    public function comisiones()
    {
        return $this->hasMany(Comision::class, 'empresa_gestora_id');
    }
}