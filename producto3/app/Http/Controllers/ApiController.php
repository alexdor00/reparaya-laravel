<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incidencia;

class ApiController extends Controller
{
    public function serviciosPorZona()
    {
        $zonas = [
            'Centro' => ['direcciones' => ['centro', 'gran via', 'sol', 'mayor']],
            'Norte' => ['direcciones' => ['norte', 'tetuan', 'fuencarral']],
            'Sur' => ['direcciones' => ['sur', 'vallecas', 'usera', 'villaverde']],
            'Este' => ['direcciones' => ['este', 'moratalaz', 'vicalvaro']],
            'Oeste' => ['direcciones' => ['oeste', 'carabanchel', 'latina']]
        ];

        $incidencias = Incidencia::where('estado', 'completada')->get();
        $total = $incidencias->count();

        $resultado = [];
        foreach ($zonas as $zona => $config) {
            $count = $incidencias->filter(function($inc) use ($config) {
                foreach ($config['direcciones'] as $dir) {
                    if (stripos($inc->direccion, $dir) !== false) {
                        return true;
                    }
                }
                return false;
            })->count();

            $resultado[] = [
                'zona' => $zona,
                'total_servicios' => $count,
                'porcentaje' => $total > 0 ? round(($count / $total) * 100, 2) : 0
            ];
        }

        return response()->json($resultado);
    }
}