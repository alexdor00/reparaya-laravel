<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incidencia;
use App\Models\TipoServicio;

class IncidenciaController extends Controller
{
    public function dashboard()
    {
        return view('cliente.dashboard');
    }

    public function misAvisos()
    {
        $incidencias = Incidencia::with('tipoServicio')
            ->where('cliente_id', session('usuario_id'))
            ->orderBy('created_at', 'desc')
            ->get();
        return view('cliente.mis_avisos', compact('incidencias'));
    }

    public function create()
    {
        $tipos = TipoServicio::all();
        return view('cliente.nueva_incidencia', compact('tipos'));
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
            'telefono_contacto' => 'required'
        ]);

        if ($request->prioridad == 'estandar') {
            $fecha = new \DateTime($request->fecha_solicitada);
            $hoy = new \DateTime();
            $diferencia = $hoy->diff($fecha)->days;

            if ($diferencia < 2) {
                return back()->with('error', 'Los servicios estándar necesitan 48 horas de antelación');
            }
        }

        $codigo = 'INC-' . strtoupper(uniqid());

        Incidencia::create([
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

        return redirect()->route('cliente.avisos')->with('success', 'Solicitud creada correctamente con código ' . $codigo);
    }

    public function cancelar($id)
    {
        $incidencia = Incidencia::where('id', $id)
            ->where('cliente_id', session('usuario_id'))
            ->firstOrFail();

        $fecha = new \DateTime($incidencia->fecha_solicitada);
        $hoy = new \DateTime();
        $diferencia = $hoy->diff($fecha)->days;

        if ($diferencia < 2) {
            return redirect()->route('cliente.avisos')->with('error', 'No puedes cancelar con menos de 48 horas de antelación');
        }

        $incidencia->estado = 'cancelada';
        $incidencia->save();

        return redirect()->route('cliente.avisos')->with('success', 'Incidencia cancelada correctamente');
    }
}