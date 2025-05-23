<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\PaisesController;
use App\Http\Controllers\CiudadesController;
use App\Http\Controllers\ProvinciasController;
use App\Http\Controllers\PlanesController;
use App\Http\Controllers\PagosController;
use App\Http\Controllers\TalonariosController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ParticipantsController;
use App\Http\Controllers\ExtrasController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/home', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::get('/users', [UsersController::class, 'index'])->middleware(['auth'])->middleware(['auth'])->name('users');
Route::get('/users/crear-usuario', [UsersController::class, 'create'])->middleware(['auth'])->name('users.create');
Route::post('/users/guardar-usuario', [UsersController::class, 'store'])->middleware(['auth'])->name('users.store');
Route::get('/users/{id}/editar-usuario', [UsersController::class, 'edit'])->middleware(['auth'])->name('users.edit');
Route::post('/users/{id}/actualizar-usuario', [UsersController::class, 'update'])->middleware(['auth'])->name('users.update');
Route::get('/users/{id}/ver-usuario', [UsersController::class, 'show'])->middleware(['auth'])->name('users.show');
Route::delete('/users/{id}/deshabilitar-usuario', [UsersController::class, 'destroy'])->middleware(['auth'])->name('users.disable');


Route::get('talonarios', [TalonariosController::class, 'index'])->middleware(['auth'])->name('talonarios');
Route::get('talonarios/nuevo-talonario', [TalonariosController::class, 'create'])->middleware(['auth'])->name('talonarios.create');
Route::post('talonarios/guardar-talonario', [TalonariosController::class, 'store'])->middleware(['auth'])->name('talonarios.store');
Route::get('talonarios/{id}/ver-talonario', [TalonariosController::class, 'show'])->middleware(['auth'])->name('talonarios.show');
Route::get('talonarios/{id}/editar-talonario', [TalonariosController::class, 'edit'])->middleware(['auth'])->name('talonarios.edit');
Route::post('talonarios/{id}/actualizar-talonario', [TalonariosController::class, 'update'])->middleware(['auth'])->name('talonarios.update');
Route::delete('talonarios/{id}/eliminar-talonario', [TalonariosController::class, 'destroy'])->middleware(['auth'])->name('talonarios.destroy');

Route::post('talonarios/tomar-numero', [TalonariosController::class, 'tomar_numero'])->middleware(['auth'])->name('talonarios.tomar-numero');
Route::post('talonarios/accion-numbers', [TalonariosController::class, 'accion_numbers'])->middleware(['auth'])->name('talonarios.accion-numbers');
Route::get('talonarios/{id}/selecionar-ganadores', [TalonariosController::class, 'selecionar_ganadores'])->middleware(['auth'])->name('talonarios.accion-numbers');
Route::post('talonarios/get-winner', [TalonariosController::class, 'get_winner'])->middleware(['auth'])->name('talonarios.get-winner');
Route::post('talonarios/get-great-winner', [TalonariosController::class, 'get_great_winner'])->middleware(['auth'])->name('talonarios.get-winner');
Route::post('talonarios/get-winner-manual', [TalonariosController::class, 'get_winner_manual'])->middleware(['auth'])->name('talonarios.get-winner');

Route::get('participantes', [ParticipantsController::class, 'index'])->middleware(['auth'])->name('participantes');
Route::get('participantes/nuevo-participante', [ParticipantsController::class, 'create'])->middleware(['auth'])->name('participantes.create');
Route::post('participantes/guardar-participante', [ParticipantsController::class, 'store'])->middleware(['auth'])->name('participantes.store');
Route::get('participantes/{id}/ver-participante', [ParticipantsController::class, 'show'])->middleware(['auth'])->name('participantes.show');
Route::get('participantes/{ci}/ver-participante-cedula', [ParticipantsController::class, 'show_by_ci'])->middleware(['auth'])->name('participantes.show');
Route::get('participantes/{id}/editar-participante', [ParticipantsController::class, 'edit'])->middleware(['auth'])->name('participantes.edit');
Route::post('participantes/{id}/actualizar-participante', [ParticipantsController::class, 'update'])->middleware(['auth'])->name('participantes.update');
Route::delete('participantes/{id}/eliminar-participante', [ParticipantsController::class, 'destroy'])->middleware(['auth'])->name('participantes.destroy');
Route::get('participantes/{ci}/ver-participante-id', [ParticipantsController::class, 'participante'])->middleware(['auth'])->name('participantes.edit');

Route::get('ciudades', [CiudadesController::class, 'index'])->middleware(['auth'])->name('ciudades');
Route::get('ciudades/nueva-ciudad', [CiudadesController::class, 'create'])->middleware(['auth'])->name('ciudad.create');
Route::post('ciudades/guardar-ciudad', [CiudadesController::class, 'store'])->middleware(['auth'])->name('ciudad.store');
Route::get('ciudades/{id}/ver-ciudad', [CiudadesController::class, 'show'])->name('ciudad.show');
Route::get('ciudades/{id}/editar-ciudad', [CiudadesController::class, 'edit'])->middleware(['auth'])->name('ciudad.edit');
Route::post('ciudades/{id}/actualizar-ciudad', [CiudadesController::class, 'update'])->middleware(['auth'])->name('ciudad.update');
Route::delete('ciudades/{id}/eliminar-ciudad', [CiudadesController::class, 'destroy'])->middleware(['auth'])->name('ciudad.destroy');
Route::get('ciudades/{id}/ciudades-por-provincia', [CiudadesController::class, 'ciudades'])->name('ciudades.ciudades_por_provincia');


Route::get('provincias', [ProvinciasController::class, 'index'])->middleware(['auth'])->name('provincias');
Route::get('provincias/nueva-provincias', [ProvinciasController::class, 'create'])->middleware(['auth'])->name('provincias.create');
Route::post('provincias/guardar-provincias', [ProvinciasController::class, 'store'])->middleware(['auth'])->name('provincias.store');
Route::get('provincias/{id}/ver-provincias', [ProvinciasController::class, 'show'])->middleware(['auth'])->name('provincias.show');
Route::get('provincias/{id}/editar-provincias', [ProvinciasController::class, 'edit'])->middleware(['auth'])->name('provincias.edit');
Route::post('provincias/{id}/actualizar-provincias', [ProvinciasController::class, 'update'])->middleware(['auth'])->name('provincias.update');
Route::delete('provincias/{id}/eliminar-provincias', [ProvinciasController::class, 'destroy'])->middleware(['auth'])->name('provincias.destroy');
Route::get('provincias/{id}/provincias-por-pais', [ProvinciasController::class, 'provincias'])->name('provincias.provincia_por_pais');

Route::get('planes', [PlanesController::class, 'index'])->middleware(['auth'])->name('planes');
Route::get('planes/nuevo-plan', [PlanesController::class, 'create'])->middleware(['auth'])->name('planes.create');
Route::post('planes/guardar-plan', [PlanesController::class, 'store'])->middleware(['auth'])->name('planes.store');
Route::get('planes/{id}/ver-plan', [PlanesController::class, 'show'])->middleware(['auth'])->name('planes.show');
Route::get('planes/{id}/editar-plan', [PlanesController::class, 'edit'])->middleware(['auth'])->name('planes.edit');
Route::post('planes/{id}/actualizar-plan', [PlanesController::class, 'update'])->middleware(['auth'])->name('planes.update');
Route::delete('planes/{id}/eliminar-plan', [PlanesController::class, 'destroy'])->middleware(['auth'])->name('planes.destroy');


Route::get('pagos', [PagosController::class, 'index'])->middleware(['auth'])->name('pagos');
Route::get('pagos/{id}/ver-pago', [PagosController::class, 'show'])->middleware(['auth'])->name('pagos.show');
Route::get('pagos/{id}/editar-pago', [PagosController::class, 'edit'])->middleware(['auth'])->name('pagos.edit');
Route::post('pagos/{id}/actualizar-pago', [PagosController::class, 'update'])->middleware(['auth'])->name('pagos.update');

Route::get('pagos/{id}/pagos-talonario', [PagosController::class, 'pagos'])->middleware(['auth'])->name('pagos.pagos');

Route::get('banner', [ExtrasController::class, 'banner'])->middleware(['auth'])->name('banner');
Route::post('banner/guardar-banner', [ExtrasController::class, 'store_banner'])->middleware(['auth'])->name('banner.store');

require __DIR__.'/auth.php';
