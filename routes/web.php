<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Usercontroller;
use App\Http\Controllers\MensajeController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TipoMensajeController;
use App\Http\Controllers\TipoPersonaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//Route::get('/personas', [PersonaController::class, 'index'])->name('personas.index');
Route::resource('/personas',PersonaController::class) ;
Route::resource('/mensajes',MensajeController::class);
Route::resource('/tipo_mensajes',TipoMensajeController::class);
Route::resource('/tipo_personas',TipoPersonaController::class);

Route::resource('users',Usercontroller::class)->names('admin.users') ;

Route::resource('/roles',RoleController::class) ;


Auth::routes();
//Route::get('/cambiar-contrasena', 'PerfilController@cambiarContrasena')->name('cambiar_contrasena');


//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('/homes',HomeController::class) ;
//Route::get('datatable/personas', 'DatatableController@persona')->name('datatable.persona');

//Route::get('/datatable/personas', [PersonaController::class, 'datatable'])->name('datatable.personas');






//  use App\Http\Controllers\MyTestController;

//  Route::get('list', [MyTestController::class, 'dataTableLogic']);
