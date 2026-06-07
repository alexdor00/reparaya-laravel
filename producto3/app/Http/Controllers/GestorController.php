<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incidencia;
use App\Models\TipoServicio;
use App\Models\EmpresaGestora;
use App\Models\Comision;

class GestorController extends Controller
{
    public function dashboard()
    {
        $gestora = EmpresaGestora::where('email', session('usuario_email') ?? '')
            ->orWhere('nombre', session('usuario_nombre'))
            ->first();

        $incidencias = Incidencia::with(['tipoServicio', 'cliente'])
            ->whereHas('comision', function($q) use ($gestora) {
                $q->where('empresa_gestora_id', $gestora ? $gestora->id : 0);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $comisiones = $gestora ? Comision::where('empresa_gestora_id', $gestora->id)
            ->selectRaw('mes, anio, SUM(importe) as total')
            ->groupBy('mes', 'anio')
            ->orderBy('anio', 'desc')
            ->orderBy('mes', 'desc')
            ->get() : collect();

        return view('gestor.dashboard', compact('gestora', 'incidencias', 'comisiones'));
    }

    public function create()
    {
        $tipos = TipoServicio::all();
        $gestora = EmpresaGestora::where('nombre', session('usuario_nombre'))->first();
        return view('gestor.nueva_incidencia', compact('tipos', 'gestora'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_servicio_id' => 'required',
            'prioridad' => 'required',
            'fecha_solicitada' => 'required|date',
            'franja_horaria' => 'required',
            'descripcion' => 'required',
            'direccion' => 'required',
            'telefono_contacto' => 'required',
            'precio_base' => 'required|numeric'
        ]);

        $codigo = 'INC-' . strtoupper(uniqid());
        $gestora = EmpresaGestora::where('nombre', session('usuario_nombre'))->first();

        $incidencia = Incidencia::create([
            'codigo' => $codigo,
            'cliente_id' => session('usuario_id'),
            'tipo_servicio_id' => $request->tipo_servicio_id,
            'descripcion' => $request->descripcion,
            'direccion' => $request->direccion,
            'telefono_contacto' => $request->telefono_contacto,
            'fecha_solicitada' => $request->fecha_solicitada,
            'franja_horaria' => $request->franja_horaria,
            'prioridad' => $request->prioridad,
            'estado' => 'pendiente'
        ]);

        if ($gestora) {
            $importe = ($request->precio_base * $gestora->comision_porcentaje) / 100;
            Comision::create([
                'incidencia_id' => $incidencia->id,
                'empresa_gestora_id' => $gestora->id,
                'precio_base' => $request->precio_base,
                'porcentaje' => $gestora->comision_porcentaje,
                'importe' => $importe,
                'mes' => date('n'),
                'anio' => date('Y')
            ]);
        }

        return redirect()->route('gestor.dashboard')->with('success', 'Incidencia creada correctamente');
    }
}