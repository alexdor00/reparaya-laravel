@extends('layouts.app')

@section('title', 'Inicio')
@section('navbar-color', 'bg-dark')

@section('content')
<div class="p-5 mb-4 text-white rounded-3" style="background: linear-gradient(135deg, #dc3545, #343a40);">
    <div class="container py-4 text-center">
        <h1 class="display-4 fw-bold">Gestion de Averias Domesticas</h1>
        <p class="lead">Fontaneria, electricidad, carpinteria y mas. Tecnicos cualificados a domicilio.</p>
        <a href="{{ route('registro') }}" class="btn btn-light btn-lg mt-3">Solicitar Tecnico</a>
    </div>
</div>

<div class="container mt-5">
    <h2 class="text-center mb-4">Nuestros Servicios</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card text-center p-4 shadow-sm">
                <h4>Fontaneria</h4>
                <p class="text-muted">Tuberias, grifos y desagues.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-4 shadow-sm">
                <h4>Electricidad</h4>
                <p class="text-muted">Instalaciones y averias electricas.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-4 shadow-sm">
                <h4>Carpinteria</h4>
                <p class="text-muted">Puertas, ventanas y muebles.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-4 shadow-sm">
                <h4>Pintura</h4>
                <p class="text-muted">Interior y exterior con acabados de calidad.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-4 shadow-sm">
                <h4>Albanileria</h4>
                <p class="text-muted">Reformas y reparaciones de obra.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-4 shadow-sm">
                <h4>Servicio Urgente</h4>
                <p class="text-muted">Atencion en menos de 24 horas.</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-light mt-5 py-5">
    <div class="container">
        <h2 class="text-center mb-4">Como Funciona</h2>
        <div class="row text-center">
            <div class="col-md-4">
                <h1 class="text-danger fw-bold">1</h1>
                <h5>Registrate</h5>
                <p class="text-muted">Crea tu cuenta con tu email.</p>
            </div>
            <div class="col-md-4">
                <h1 class="text-danger fw-bold">2</h1>
                <h5>Solicita un Tecnico</h5>
                <p class="text-muted">Describe la averia y elige fecha.</p>
            </div>
            <div class="col-md-4">
                <h1 class="text-danger fw-bold">3</h1>
                <h5>Recibe Asistencia</h5>
                <p class="text-muted">Un tecnico acudira a tu domicilio.</p>
            </div>
        </div>
    </div>
</div>
@endsection