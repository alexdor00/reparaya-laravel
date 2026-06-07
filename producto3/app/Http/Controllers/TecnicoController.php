<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incidencia;

class TecnicoController extends Controller
{
    public function dashboard()
    {
        $hoy = date('Y-m-d');

        $serviciosHoy = Incidencia::with(['tipoServicio', 'cliente'])
            ->where('tecnico_id', session('usuario_id'))
            ->where('fecha_solicitada', $hoy)
            ->where('estado', '!=', 'cancelada')
            ->get();

        $proximos = Incidencia::with(['tipoServicio', 'cliente'])
            ->where('tecnico_id', session('usuario_id'))
            ->where('fecha_solicitada', '>', $hoy)
            ->where('estado', '!=', 'cancelada')
            ->orderBy('fecha_solicitada', 'asc')
            ->get();

        return view('tecnico.dashboard', compact('serviciosHoy', 'proximos'));
    }
}