<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if ($usuario && Hash::check($request->password, $usuario->password)) {
            session([
                'usuario_id' => $usuario->id,
                'usuario_nombre' => $usuario->nombre,
                'usuario_rol' => $usuario->rol
            ]);

            if ($usuario->rol == 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($usuario->rol == 'tecnico') {
                return redirect()->route('tecnico.dashboard');
            } elseif ($usuario->rol == 'gestor') {
                return redirect()->route('gestor.dashboard');
            } else {
                return redirect()->route('cliente.dashboard');
            }
        }

        return back()->with('error', 'Email o contraseña incorrectos');
    }

    public function showRegistro()
    {
        return view('auth.registro');
    }

    public function registro(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'email' => 'required|email|unique:usuarios',
            'telefono' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
            'rol' => 'cliente'
        ]);

        return redirect()->route('login')->with('success', 'Cuenta creada correctamente');
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('home');
    }
}