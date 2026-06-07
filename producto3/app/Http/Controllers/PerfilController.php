<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class PerfilController extends Controller
{
    public function show()
    {
        $usuario = Usuario::find(session('usuario_id'));
        return view('perfil', compact('usuario'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'email' => 'required|email|unique:usuarios,email,' . session('usuario_id'),
            'telefono' => 'nullable'
        ]);

        $usuario = Usuario::find(session('usuario_id'));
        $usuario->nombre = $request->nombre;
        $usuario->email = $request->email;
        $usuario->telefono = $request->telefono;

        if ($request->password) {
            if ($request->password != $request->password_confirmation) {
                return back()->with('error', 'Las contraseñas no coinciden');
            }
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();
        session(['usuario_nombre' => $usuario->nombre]);

        return back()->with('success', 'Perfil actualizado correctamente');
    }
}