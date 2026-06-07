<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReparaYa - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    @yield('styles')
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark @yield('navbar-color', 'bg-dark')">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">ReparaYa</a>
        @if(session('usuario_id'))
        <div class="ms-auto d-flex align-items-center gap-2">
            <span class="text-light">Hola, {{ session('usuario_nombre') }}</span>
            <a href="{{ route('perfil') }}" class="btn btn-outline-light btn-sm">Mi Perfil</a>
            <a href="{{ route('logout') }}" class="btn btn-outline-light btn-sm">Cerrar Sesion</a>
        </div>
        @else
        <div class="ms-auto d-flex gap-2">
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Iniciar Sesion</a>
            <a href="{{ route('registro') }}" class="btn btn-warning btn-sm">Registrarse</a>
        </div>
        @endif
    </div>
</nav>

<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @yield('content')
</div>

<footer class="bg-dark text-light text-center py-3 mt-5">
    <p class="mb-0">ReparaYa - Gestion de Averias Domesticas</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>