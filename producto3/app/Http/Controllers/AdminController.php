<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incidencia;
use App\Models\Usuario;
use App\Models\TipoServicio;
use App\Models\EmpresaGestora;
use App\Models\Comision;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function incidencias()
    {
        $incidencias = Incidencia::with(['cliente', 'tecnico', 'tipoServicio'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.incidencias', compact('incidencias'));
    }

    public function createIncidencia()
    {
        $tipos = TipoServicio::all();
        $clientes = Usuario::where('rol', 'cliente')->get();
        $tecnicos = Usuario::where('rol', 'tecnico')->get();
        return view('admin.nueva_incidencia', compact('tipos', 'clientes', 'tecnicos'));
    }

    public function storeIncidencia(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required',
            'tipo_servicio_id' => 'required',
            'prioridad' => 'required',
            'fecha_solicitada' => 'required|date',
            'franja_horaria' => 'required',
            'descripcion' => 'required',
            'direccion' => 'required',
            'telefono_contacto' => 'required'
        ]);

        $codigo = 'INC-' . strtoupper(uniqid());

        Incidencia::create([
            'codigo' => $codigo,
            'cliente_id' => $request->cliente_id,
            'tipo_servicio_id' => $request->tipo_servicio_id,
            'descripcion' => $request->descripcion,
            'direccion' => $request->direccion,
            'telefono_contacto' => $request->telefono_contacto,
            'fecha_solicitada' => $request->fecha_solicitada,
            'franja_horaria' => $request->franja_horaria,
            'prioridad' => $request->prioridad,
            'tecnico_id' => $request->tecnico_id ?: null,
            'estado' => 'pendiente'
        ]);

        return redirect()->route('admin.incidencias')->with('success', 'Incidencia creada correctamente');
    }

    public function editIncidencia($id)
    {
        $incidencia = Incidencia::findOrFail($id);
        $tipos = TipoServicio::all();
        $tecnicos = Usuario::where('rol', 'tecnico')->get();
        return view('admin.editar_incidencia', compact('incidencia', 'tipos', 'tecnicos'));
    }

    public function updateIncidencia(Request $request, $id)
    {
        $incidencia = Incidencia::findOrFail($id);
        $incidencia->update([
            'tipo_servicio_id' => $request->tipo_servicio_id,
            'tecnico_id' => $request->tecnico_id ?: null,
            'prioridad' => $request->prioridad,
            'estado' => $request->estado,
            'fecha_solicitada' => $request->fecha_solicitada,
            'franja_horaria' => $request->franja_horaria,
            'descripcion' => $request->descripcion,
            'direccion' => $request->direccion,
            'telefono_contacto' => $request->telefono_contacto
        ]);

        return redirect()->route('admin.incidencias')->with('success', 'Incidencia actualizada correctamente');
    }

    public function cancelarIncidencia($id)
    {
        $incidencia = Incidencia::findOrFail($id);
        $incidencia->estado = 'cancelada';
        $incidencia->save();
        return redirect()->route('admin.incidencias')->with('success', 'Incidencia cancelada correctamente');
    }

    public function tecnicos()
    {
        $tecnicos = Usuario::where('rol', 'tecnico')->get();
        return view('admin.tecnicos', compact('tecnicos'));
    }

    public function createTecnico()
    {
        return view('admin.nuevo_tecnico');
    }

    public function storeTecnico(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'email' => 'required|email|unique:usuarios',
            'telefono' => 'required',
            'password' => 'required|min:6'
        ]);

        Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'password' => \Hash::make($request->password),
            'rol' => 'tecnico'
        ]);

        return redirect()->route('admin.tecnicos')->with('success', 'Técnico creado correctamente');
    }

    public function deleteTecnico($id)
    {
        $tecnico = Usuario::findOrFail($id);
        if (Incidencia::where('tecnico_id', $id)->exists()) {
            return redirect()->route('admin.tecnicos')->with('error', 'No puedes eliminar un técnico con incidencias asignadas');
        }
        $tecnico->delete();
        return redirect()->route('admin.tecnicos')->with('success', 'Técnico eliminado correctamente');
    }

    public function tiposServicio()
    {
        $tipos = TipoServicio::all();
        return view('admin.tipos_servicio', compact('tipos'));
    }

    public function storeTipoServicio(Request $request)
    {
        $request->validate([
            'nombre' => 'required'
        ]);
        TipoServicio::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion
        ]);
        return redirect()->route('admin.tipos')->with('success', 'Tipo de servicio creado correctamente');
    }

    public function deleteTipoServicio($id)
    {
        if (Incidencia::where('tipo_servicio_id', $id)->exists()) {
            return redirect()->route('admin.tipos')->with('error', 'No puedes eliminar un tipo con incidencias asociadas');
        }
        TipoServicio::findOrFail($id)->delete();
        return redirect()->route('admin.tipos')->with('success', 'Tipo eliminado correctamente');
    }

    public function calendario()
    {
        $incidencias = Incidencia::with(['tipoServicio', 'cliente'])
            ->where('estado', '!=', 'cancelada')
            ->get();

        $eventos = $incidencias->map(function($inc) {
            return [
                'id' => $inc->id,
                'title' => $inc->tipoServicio->nombre . ' - ' . $inc->cliente->nombre,
                'start' => $inc->fecha_solicitada,
                'color' => $inc->prioridad == 'urgente' ? '#dc3545' : '#6c757d',
                'extendedProps' => [
                    'codigo' => $inc->codigo,
                    'descripcion' => $inc->descripcion,
                    'direccion' => $inc->direccion,
                    'telefono' => $inc->telefono_contacto,
                    'prioridad' => $inc->prioridad,
                    'estado' => $inc->estado,
                    'franja' => $inc->franja_horaria
                ]
            ];
        });

        return view('admin.calendario', ['eventos' => $eventos]);
    }

    public function gestoras()
    {
        $gestoras = EmpresaGestora::all();
        return view('admin.gestoras', compact('gestoras'));
    }

    public function createGestora()
    {
        return view('admin.nueva_gestora');
    }

    public function storeGestora(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'email' => 'required|email|unique:empresas_gestoras',
            'telefono' => 'required',
            'comision_porcentaje' => 'required|numeric'
        ]);

        $gestora = EmpresaGestora::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'comision_porcentaje' => $request->comision_porcentaje
        ]);

        Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'password' => \Hash::make($request->password ?? '123456'),
            'rol' => 'gestor'
        ]);

        return redirect()->route('admin.gestoras')->with('success', 'Gestora creada correctamente');
    }

    public function comisiones()
    {
        $comisiones = Comision::with(['empresaGestora', 'incidencia'])
            ->orderBy('anio', 'desc')
            ->orderBy('mes', 'desc')
            ->get();

        $liquidaciones = Comision::with('empresaGestora')
            ->selectRaw('empresa_gestora_id, mes, anio, SUM(importe) as total')
            ->groupBy('empresa_gestora_id', 'mes', 'anio')
            ->orderBy('anio', 'desc')
            ->orderBy('mes', 'desc')
            ->get();

        return view('admin.comisiones', compact('comisiones', 'liquidaciones'));
    }
}