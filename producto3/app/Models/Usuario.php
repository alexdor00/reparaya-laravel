<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';
    
    protected $fillable = [
        'nombre', 'email', 'password', 'rol', 'telefono'
    ];

    protected $hidden = [
        'password'
    ];

    public function incidenciasComoCliente()
    {
        return $this->hasMany(Incidencia::class, 'cliente_id');
    }

    public function incidenciasComoTecnico()
    {
        return $this->hasMany(Incidencia::class, 'tecnico_id');
    }
}