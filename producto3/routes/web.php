<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\TecnicoController;
use App\Http\Controllers\GestorController;
use App\Http\Controllers\PerfilController;

// Página principal
Route::get('/', function () {
    return view('home');
})->name('home');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/registro', [AuthController::class, 'showRegistro'])->name('registro');
Route::post('/registro', [AuthController::class, 'registro']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Perfil
Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil');
Route::post('/perfil', [PerfilController::class, 'update'])->name('perfil.update');

// Cliente
Route::get('/cliente/dashboard', [IncidenciaController::class, 'dashboard'])->name('cliente.dashboard');
Route::get('/cliente/avisos', [IncidenciaController::class, 'misAvisos'])->name('cliente.avisos');
Route::get('/cliente/nueva', [IncidenciaController::class, 'create'])->name('cliente.nueva');
Route::post('/cliente/nueva', [IncidenciaController::class, 'store'])->name('cliente.store');
Route::get('/cliente/cancelar/{id}', [IncidenciaController::class, 'cancelar'])->name('cliente.cancelar');

// Admin
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/incidencias', [AdminController::class, 'incidencias'])->name('admin.incidencias');
Route::get('/admin/incidencias/nueva', [AdminController::class, 'createIncidencia'])->name('admin.nueva_incidencia');
Route::post('/admin/incidencias/nueva', [AdminController::class, 'storeIncidencia'])->name('admin.store_incidencia');
Route::get('/admin/incidencias/editar/{id}', [AdminController::class, 'editIncidencia'])->name('admin.editar_incidencia');
Route::post('/admin/incidencias/editar/{id}', [AdminController::class, 'updateIncidencia'])->name('admin.update_incidencia');
Route::get('/admin/incidencias/cancelar/{id}', [AdminController::class, 'cancelarIncidencia'])->name('admin.cancelar_incidencia');
Route::get('/admin/tecnicos', [AdminController::class, 'tecnicos'])->name('admin.tecnicos');
Route::get('/admin/tecnicos/nuevo', [AdminController::class, 'createTecnico'])->name('admin.nuevo_tecnico');
Route::post('/admin/tecnicos/nuevo', [AdminController::class, 'storeTecnico'])->name('admin.store_tecnico');
Route::get('/admin/tecnicos/eliminar/{id}', [AdminController::class, 'deleteTecnico'])->name('admin.delete_tecnico');
Route::get('/admin/tipos', [AdminController::class, 'tiposServicio'])->name('admin.tipos');
Route::post('/admin/tipos', [AdminController::class, 'storeTipoServicio'])->name('admin.store_tipo');
Route::get('/admin/tipos/eliminar/{id}', [AdminController::class, 'deleteTipoServicio'])->name('admin.delete_tipo');
Route::get('/admin/calendario', [AdminController::class, 'calendario'])->name('admin.calendario');
Route::get('/admin/gestoras', [AdminController::class, 'gestoras'])->name('admin.gestoras');
Route::get('/admin/gestoras/nueva', [AdminController::class, 'createGestora'])->name('admin.nueva_gestora');
Route::post('/admin/gestoras/nueva', [AdminController::class, 'storeGestora'])->name('admin.store_gestora');
Route::get('/admin/comisiones', [AdminController::class, 'comisiones'])->name('admin.comisiones');

// Tecnico
Route::get('/tecnico/dashboard', [TecnicoController::class, 'dashboard'])->name('tecnico.dashboard');

// Gestor
Route::get('/gestor/dashboard', [GestorController::class, 'dashboard'])->name('gestor.dashboard');
Route::get('/gestor/nueva', [GestorController::class, 'create'])->name('gestor.nueva');
Route::post('/gestor/nueva', [GestorController::class, 'store'])->name('gestor.store');