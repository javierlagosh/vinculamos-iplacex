<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GPTController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ParametrosController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\IniciativasController;

use App\Http\Controllers\AutenticationController;
use App\Http\Controllers\Auth\ForgotPasswordController;



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
//Todo: rutas de acceso global.


Route::get('/chat', [GPTController::class, 'index'])->name('chat.index');
Route::any('/chat/send-message', [GPTController::class, 'sendMessage'])->name('chat.sendMessage');


Route::get('/', [AutenticationController::class, 'ingresar'])->name('ingresar.formulario')->middleware('verificar.sesion');
Route::get('ingresar', [AutenticationController::class, 'ingresar'])->name('ingresar.formulario')->middleware('verificar.sesion');
Route::post('ingresar', [AutenticationController::class, 'validarIngreso'])->name('auth.ingresar');
Route::get('salir', [AutenticationController::class, 'cerrarSesion'])->name('auth.cerrar');
Route::get('registrarSuperadmin', [AutenticationController::class, 'registrarSuperadmin'])->name('registrarsuperadmin.formulario');
Route::post('registrarSuperadmin', [AutenticationController::class, 'guardarSuperadmin'])->name('auth.registrar.superadmin');



// Reset password routes
// Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
// Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
// Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
// Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');


Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

//TODO: Ruta INVI
Route::get('admin/iniciativa/invi/datos', [IniciativasController::class, 'datosIndice']);
Route::post('admin/iniciativa/invi/guardar', [IniciativasController::class, 'guardarDatosIndice']);
Route::get('admin/iniciativas/invi/ids',[IniciativasController::class,'obtenerIDs']);

//Evaluacion de las iniciativas
Route::get('admin/iniciativa/listar-evaluaciones', [IniciativasController::class, 'listarEvaluaciones']);
Route::delete('admin/eliminar-evaluacion/', [IniciativasController::class, 'eliminarEvaluacion'])->name('admin.eliminar.evaluacion');
Route::delete('admin/eliminar-evaluacion/', [IniciativasController::class, 'eliminarEvaluacionManual'])->name('admin.eliminar.evaluacion.manual');

Route::middleware('verificar.superadmin')->group(function () {
    // inicio rutas para gestionar usuarios
    Route::get('superadmin/listar-usuarios', [SuperadminController::class, 'listarUsuarios'])->name('superadmin.listar.usuarios');
    Route::get('superadmin/crear-usuario', [SuperadminController::class, 'crearUsuario'])->name('superadmin.crear.usuario');
    Route::post('superadmin/listar-usuarios', [SuperadminController::class, 'guardarAdmin'])->name('superadmin.registrar.admin');
    Route::get('superadmin/editar-usuario/{usua_nickname}', [SuperadminController::class, 'editarUsuario'])->name('superadmin.usuario.editar');
    Route::post('superadmin/editar-usuario/{usua_nickname}', [SuperadminController::class, 'actualizarUsuario'])->name('superadmin.usuario.actualizar');
    Route::put('superadmin/habilitar-usuario/{usua_nickname}', [SuperadminController::class, 'habilitarAdmin'])->name('superadmin.habilitar.admin');
    Route::put('superadmin/deshabilitar-usuario/{usua_nickname}', [SuperadminController::class, 'deshabilitarAdmin'])->name('superadmin.deshabilitar.admin');
    Route::delete('superadmin/eliminar-usuario/', [SuperadminController::class, 'eliminarAdmin'])->name('superadmin.eliminar.admin');
    Route::get('superadmin/clave-usuario/{usua_nickname}', [SuperadminController::class, 'editarClaveUsuario'])->name('superadmin.claveusuario.cambiar');
    Route::post('superadmin/clave-usuario/{usua_nickname}', [SuperadminController::class, 'actualizarClaveUsuario'])->name('superadmin.claveusuario.actualizar');
    // fin rutas para gestionar usuarios
});

Route::get('dashboard', [DashboardController::class, 'Index'])->name('dashboard.ver');
Route::get('reportes', [DashboardController::class, 'reportes'])->name('reportes.ver');

Route::post('dashboard/sedes-datos', [DashboardController::class, 'sedesDatos']);
Route::post('dashboard/componentes-datos', [DashboardController::class, 'componentesDatos']);

Route::middleware('verificar.admin')->group(function () {

    Route::get('admin/home', function () {
        return view('admin.home');
    })->name('admin.home');

    //TODO: Evaluacion de evidenciavinculam_demo_v2
    Route::get('admin/iniciativas/{inic_codigo}/evaluar', [IniciativasController::class, 'evaluarIniciativa'])->name('admin.evaluar.iniciativa');
    Route::get('admin/iniciativas/{inic_codigo}/evaluar/invitar', [IniciativasController::class, 'evaluarIniciativaInvitar'])->name('admin.evaluar.iniciativa.invitar');
    Route::post('admin/iniciativas/invitar-evaluacion', [IniciativasController::class, 'invitarEvaluacion'])->name('admin.invitar.evaluacion');
    //Primer paso de la creacion de evaluacion
    Route::post('admin/iniciativas/crear-evaluacion', [IniciativasController::class, 'crearEvaluacion'])->name('admin.crear.evaluacion');
    Route::post('admin/iniciativas/eliminar-evaluacion', [IniciativasController::class, 'eliminarEvaluacionInciativa'])->name('admin.eliminar.evaluacion.iniciativa');
    Route::post('admin/eliminar-invitado/{evainv_codigo}', [IniciativasController::class, 'eliminarInvitadoEvaluacion'])->name('admin.eliminar.invitacion');
    Route::delete('admin/eliminar-invitado-docente/{evainv_codigo}', [IniciativasController::class, 'eliminarInvitadoEvaluacionDocente'])->name('admin.eliminar.invitacion.docente');
    Route::delete('admin/eliminar-invitado-externo/{evainv_codigo}', [IniciativasController::class, 'eliminarInvitadoEvaluacionExterno'])->name('admin.eliminar.invitacion.externo');
    Route::post('admin/iniciativas/carga-individual-evaluacion', [IniciativasController::class, 'cargaIndividualEvaluacion'])->name('admin.iniciativa.evaluar.enviar.cargaIndividual');
    //Route::post('admin/iniciativas/procesar-archivo', [IniciativasController::class, 'procesarArchivo'])->name('procesarArchivo');
    //ver resultados de la evaluacion
    Route::get('admin/iniciativas/{inic_codigo}/evaluacion/resultados/{invitado}', [IniciativasController::class, 'verEvaluacion'])->name('admin.ver.evaluacion');

    Route::get('admin/iniciativas/{inic_codigo}/evaluar2', [IniciativasController::class, 'evaluarIniciativa2'])->name('admin.evaluar.iniciativa2');
    Route::get('admin/iniciativas/evaluar', [IniciativasController::class, 'guardarEvaluacion']);
    Route::get('admin/iniciativas/ingresoEvaluacion', [IniciativasController::class, 'guardarEvaluacion2']);
    // TODO: inicio rutas para gestionar parametros

    Route::get('admin/chat', [GPTController::class, 'index'])->name('admin.chat.index');
    Route::any('admin/chat/send-message', [GPTController::class, 'sendMessage'])->name('admin.chat.sendMessage');
    Route::any('admin/chat/revisar-objetivo', [GPTController::class, 'revisarObjetivo'])->name('admin.chat.revisarObjetivo');
    Route::post('admin/iniciativas/{inic_codigo}/odsGuardar', [IniciativasController::class, 'saveODS'])->name('admin.iniciativas.odsGuardar');

    //Ambito de COntribucion
    Route::get('admin/listar-ambito', [ParametrosController::class, 'listarAmbitos'])->name('admin.listar.ambitos');
    Route::delete('admin/eliminar-ambito/', [ParametrosController::class, 'eliminarAmbitos'])->name('admin.eliminar.ambitos');
    Route::put('admin/editar-ambito/{amb_codigo}', [ParametrosController::class, 'actualizarAmbitos'])->name('admin.actualizar.ambitos');
    Route::post('admin/crear-ambito/', [ParametrosController::class, 'crearAmbitos'])->name('admin.crear.ambitos');

    //Ambito de Acción
    Route::get('admin/listar-ambitosaccion', [ParametrosController::class, 'listarAmbitosAccion'])->name('admin.listar.ambitosaccion');
    Route::delete('admin/eliminar-ambitosaccion/', [ParametrosController::class, 'eliminarAmbitosAccion'])->name('admin.eliminar.ambitosaccion');
    Route::put('admin/editar-ambitosaccion/{amac_codigo}', [ParametrosController::class, 'actualizarAmbitosAccion'])->name('admin.actualizar.ambitosaccion');
    Route::post('admin/crear-ambitosaccion/', [ParametrosController::class, 'crearAmbitosAccion'])->name('admin.crear.ambitosaccion');

    //Programas
    Route::get('admin/listar-programas', [ParametrosController::class, 'listarProgramas'])->name('admin.listar.programas');
    Route::delete('admin/eliminar-programas/', [ParametrosController::class, 'eliminarProgramas'])->name('admin.eliminar.programas');
    Route::put('admin/editar-programas/{prog_codigo}', [ParametrosController::class, 'actualizarProgramas'])->name('admin.actualizar.programas');
    Route::post('admin/crear-programas/', [ParametrosController::class, 'crearProgramas'])->name('admin.crear.programas');

    //Convenios
    Route::get('admin/listar-convenios', [ParametrosController::class, 'listarConvenios'])->name('admin.listar.convenios');
    Route::delete('admin/eliminar-convenios/', [ParametrosController::class, 'eliminarConvenios'])->name('admin.eliminar.convenios');
    Route::post('admin/convenios/crear', [ParametrosController::class, 'crearConvenios'])->name('admin.crear.convenios');
    Route::post('admin/convenios/{conv_codigo}/actualizar', [ParametrosController::class, 'actualizarConvenios'])->name('admin.actualizar.convenios');
    Route::post('admin/convenios/{conv_codigo}/descargar', [ParametrosController::class, 'descargarConvenios'])->name('admin.descargar.convenios');

    //Sedes
    Route::get('admin/listar-sedes', [ParametrosController::class, 'listarSedes'])->name('admin.listar.sedes');
    Route::post('/admin/crear/sedes', [ParametrosController::class, 'crearSede'])->name('admin.crear.sedes');
    Route::delete('admin/eliminar-sedes', [ParametrosController::class, 'eliminarSedes'])->name('admin.eliminar.sedes');
    Route::get('admin/editar/sedes/{sede_codigo}', [ParametrosController::class, 'editarSedes'])->name('admin.editar.sedes');
    Route::put('admin/actualizar/sedes/{sede_codigo}', [ParametrosController::class, 'actualizarSedes'])->name('admin.actualizar.sedes');

    //Dispositivos
    Route::get('admin/listar-dispositivos', [ParametrosController::class, 'listarDispositivos'])->name('admin.listar.dispositivos');
    Route::post('admin/crear-dispositivo/', [ParametrosController::class, 'crearDispositivo'])->name('admin.crear.dispositivo');
    Route::put('admin/editar-carreras/{dispositivo_id}', [ParametrosController::class, 'actualizarDispositivo'])->name('admin.actualizar.dispositivo');
    Route::delete('admin/eliminar-dispositivo/', [ParametrosController::class, 'eliminarDispositivo'])->name('admin.eliminar.dispositivo');

    //Asignaturas
    Route::get('admin/listar-asignaturas', [ParametrosController::class, 'listarAsignaturas'])->name('admin.listar.asignaturas');
    Route::post('admin/crear-asignatura/', [ParametrosController::class, 'crearAsignatura'])->name('admin.crear.asignatura');
    Route::put('admin/editar-carreras/{asignatura_id}', [ParametrosController::class, 'actualizarAsignatura'])->name('admin.actualizar.asignatura');
    Route::delete('admin/eliminar-asignatura/', [ParametrosController::class, 'eliminarAsignatura'])->name('admin.eliminar.asignatura');

    //Centro de costos
    Route::get('admin/listar-centro-costos', [ParametrosController::class, 'listarCentroCostos'])->name('admin.listar.ccostos');
    Route::post('admin/crear-centro-costos', [ParametrosController::class, 'crearCentroCostos'])->name('admin.crear.ccostos');
    Route::put('admin/editar-centro-costos/{ceco_codigo}', [ParametrosController::class, 'actualizarCentroCostos'])->name('admin.actualizar.ccostos');
    Route::delete('admin/eliminar-centro-costos/', [ParametrosController::class, 'eliminarCentroCosotos'])->name('admin.eliminar.ccostos');

    //Centro de simulacion
    Route::get('admin/listar-centro-simulacion', [ParametrosController::class, 'listarCentroSimulacion'])->name('admin.listar.centro-simulacion');
    Route::post('admin/crear-centro-simulacion/', [ParametrosController::class, 'crearCentroSimulacion'])->name('admin.crear.centro-simulacion');
    Route::put('admin/editar-centro-simulacion/{cs_codigo}', [ParametrosController::class, 'actualizarCentroSimulacion'])->name('admin.actualizar.centro-simulacion');
    Route::delete('admin/eliminar-centro-simulacion/', [ParametrosController::class, 'eliminarCentroSimulacion'])->name('admin.eliminar.centro-simulacion');

    //Carreras
    Route::get('admin/listar-carreras', [ParametrosController::class, 'listarCarreras'])->name('admin.listar.carreras');
    Route::get('admin/listar-area-especialidad', [ParametrosController::class, 'listarAes'])->name('admin.listar.aespecialidad');
    Route::delete('admin/eliminar-carreras/', [ParametrosController::class, 'eliminarCarreras'])->name('admin.eliminar.carreras');
    Route::delete('admin/eliminar-aes/', [ParametrosController::class, 'eliminarAes'])->name('admin.eliminar.aes');
    Route::put('admin/editar-carrera/{care_codigo}', [ParametrosController::class, 'actualizarCarrera'])->name('admin.actualizar.carrera');
    Route::put('admin/editar-aes/{aes_codigo}', [ParametrosController::class, 'actualizarAes'])->name('admin.actualizar.aes');
    Route::post('admin/crear-carreras/', [ParametrosController::class, 'crearCarreras'])->name('admin.crear.carreras');
    Route::post('admin/crear-aes/', [ParametrosController::class, 'crearAes'])->name('admin.crear.aes');
    //Escuelas
    Route::get('admin/listar-escuelas', [ParametrosController::class, 'listarEscuelas'])->name('admin.listar.escuelas');
    Route::delete('admin/eliminar-escuelas/', [ParametrosController::class, 'eliminarEscuelas'])->name('admin.eliminar.escuelas');
    Route::put('escuelas/{escu_codigo}/actualizar', [ParametrosController::class, 'actualizarEscuelas'])->name('admin.actualizar.escuelas');
    Route::post('admin/crear-escuelas/', [ParametrosController::class, 'crearEscuelas'])->name('admin.crear.escuelas');

    //Socios Comunitarios
    Route::get('admin/listar-socios', [ParametrosController::class, 'listarSocios'])->name('admin.listar.socios');
    Route::delete('admin/eliminar-socios/', [ParametrosController::class, 'eliminarSocios'])->name('admin.eliminar.socios');
    Route::put('socios/{escu_codigo}/actualizar', [ParametrosController::class, 'actualizarSocios'])->name('admin.actualizar.socios');
    Route::post('admin/crear-socios/', [ParametrosController::class, 'crearSocios'])->name('admin.crear.socios');
    Route::post('admin/socios/listar-subgrupos', [ParametrosController::class, 'subgruposBygrupos']);

    //Mecanismos
    Route::get('admin/mecanismos/listar', [ParametrosController::class, 'listarMecanismos'])->name('admin.listar.mecanismos');
    Route::post('admin/mecanismos/crear', [ParametrosController::class, 'crearMecanismos'])->name('admin.crear.mecanismos');
    Route::delete('admin/mecanismos/eliminar', [ParametrosController::class, 'eliminarMecanismos'])->name('admin.eliminar.mecanismos');
    Route::put('admin/mecanismos/actualizar/{meca_codigo}', [ParametrosController::class, 'actualizarMecanismos'])->name('admin.actualizar.mecanismos');

    //Grupos Interes
    Route::get('admin/grupos/listar', [ParametrosController::class, 'listarGrupos'])
        ->name('admin.listar.grupos_int');
    Route::post('admin/grupos/crear', [ParametrosController::class, 'crearGrupo'])
        ->name('admin.crear.grupos_int');
    Route::delete('admin/grupos/eliminar', [ParametrosController::class, 'eliminarGrupo'])
        ->name('admin.eliminar.grupo');
    Route::put('admin/grupos/actualizar/{grin_codigo}', [ParametrosController::class, 'actualizarGrupos'])
        ->name('admin.actualizar.grupos');

    // SubGrupoInteres
    Route::get('admin/listar-subgrupos', [ParametrosController::class, 'listarSubGrupoInteres'])->name('admin.listar.subgrupos');
    Route::delete('admin/eliminar-subgrupos/', [ParametrosController::class, 'eliminarSubGrupoInteres'])->name('admin.eliminar.subgrupos');
    Route::put('admin/editar-subgrupos/{sugr_codigo}', [ParametrosController::class, 'actualizarSubGrupoInteres'])->name('admin.actualizar.subgrupos');
    Route::post('admin/crear-subgrupos/', [ParametrosController::class, 'crearSubGrupoInteres'])->name('admin.crear.subgrupos');

    //Tipos de actividad
    Route::get('/admin/listar/tipoact', [ParametrosController::class, 'listarTipoact'])
        ->name('admin.listar.tipoact');
    Route::post('/admin/crear/tipoact', [ParametrosController::class, 'crearTipoact'])
        ->name('admin.crear.tipoact');
    Route::put('/admin/actualizar/tipoact/{tiac_codigo}', [ParametrosController::class, 'actualizarTipoact'])
        ->name('admin.actualizar.tipoact');
    Route::delete('/admin/eliminar/tipoact', [ParametrosController::class, 'eliminarTipoact'])
        ->name('admin.eliminar.tipoact');

    //Tematicas
    Route::get('/admin/listar/tematica', [ParametrosController::class, 'listarTematica'])
        ->name('admin.listar.tematica');
    Route::post('/admin/crear/tematica', [ParametrosController::class, 'crearTematica'])
        ->name('admin.crear.tematica');
    Route::put('/admin/actualizar/tematica/{tema_codigo}', [ParametrosController::class, 'actualizarTematica'])
        ->name('admin.actualizar.tematica');
    Route::delete('/admin/eliminar/tematica', [ParametrosController::class, 'eliminarTematica'])
        ->name('admin.eliminar.tematica');

    // Unidades
    Route::get('admin/listar-unidades', [ParametrosController::class, 'listarUnidades'])->name('admin.listar.unidades');
    Route::delete('admin/eliminar-unidades/', [ParametrosController::class, 'eliminarUnidades'])->name('admin.eliminar.unidades');
    Route::put('admin/editar-unidades/{unid_codigo}', [ParametrosController::class, 'actualizarUnidades'])->name('admin.actualizar.unidades');
    Route::post('admin/crear-unidades/', [ParametrosController::class, 'crearUnidades'])->name('admin.crear.unidades');

    // SubUnidades
    Route::get('admin/listar-subunidades', [ParametrosController::class, 'listarSubUnidades'])->name('admin.listar.subunidades');
    Route::delete('admin/eliminar-subunidades/', [ParametrosController::class, 'eliminarSubUnidades'])->name('admin.eliminar.subunidades');
    Route::put('admin/editar-subunidades/{suni_codigo}', [ParametrosController::class, 'actualizarSubUnidades'])->name('admin.actualizar.subunidades');
    Route::post('admin/crear-subunidades/', [ParametrosController::class, 'crearSubUnidades'])->name('admin.crear.subunidades');

    // Tipo Iniciativa
    Route::get('admin/listar-tipoiniciativa', [ParametrosController::class, 'listarTipoIniciativa'])->name('admin.listar.tipoiniciativa');
    Route::delete('admin/eliminar-tipoiniciativa/', [ParametrosController::class, 'eliminarTipoIniciativa'])->name('admin.eliminar.tipoiniciativa');
    Route::put('admin/editar-tipoiniciativa/{tmec_codigo}', [ParametrosController::class, 'actualizarTipoIniciativa'])->name('admin.actualizar.tipoiniciativa');
    Route::post('admin/crear-tipoiniciativa/', [ParametrosController::class, 'crearTipoIniciativa'])->name('admin.crear.tipoiniciativa');

    // actividad
    Route::get('admin/listar-actividad', [BitacoraController::class, 'listarActividad'])->name('admin.listar.actividades');
    Route::delete('admin/eliminar-actividad/', [BitacoraController::class, 'eliminarActividad'])->name('admin.eliminar.actividades');
    Route::put('admin/editar-actividad/{nombreprefijo_codigo}', [BitacoraController::class, 'actualizarActividad'])->name('admin.actualizar.actividades');
    Route::post('admin/crear-actividad/', [BitacoraController::class, 'crearActividad'])->name('admin.crear.actividades');

    // RecursosHumanos
    Route::get('admin/listar-rrhh', [ParametrosController::class, 'listarRecursosHumanos'])->name('admin.listar.rrhh');
    Route::delete('admin/eliminar-rrhh/', [ParametrosController::class, 'eliminarRecursosHumanos'])->name('admin.eliminar.rrhh');
    Route::put('admin/editar-rrhh/{trrhh_codigo}', [ParametrosController::class, 'actualizarRecursosHumanos'])->name('admin.actualizar.rrhh');
    Route::post('admin/crear-rrhh/', [ParametrosController::class, 'crearRecursosHumanos'])->name('admin.crear.rrhh');

    // TipoInfraestructuras
    Route::get('admin/listar-tipoinfra', [ParametrosController::class, 'listarTipoInfraestructuras'])->name('admin.listar.tipoinfra');
    Route::delete('admin/eliminar-tipoinfra/', [ParametrosController::class, 'eliminarTipoInfraestructuras'])->name('admin.eliminar.tipoinfra');
    Route::put('admin/editar-tipoinfra/{tinf_codigo}', [ParametrosController::class, 'actualizarTipoInfraestructuras'])->name('admin.actualizar.tipoinfra');
    Route::post('admin/crear-tipoinfra/', [ParametrosController::class, 'crearTipoInfraestructuras'])->name('admin.crear.tipoinfra');

    // Componentes
    Route::get('admin/listar-componente', [ParametrosController::class, 'listarComponentes'])->name('admin.listar.componente');
    Route::delete('admin/eliminar-componente/', [ParametrosController::class, 'eliminarComponentes'])->name('admin.eliminar.componente');
    Route::put('admin/editar-componente/{comp_codigo}', [ParametrosController::class, 'actualizarComponentes'])->name('admin.actualizar.componente');
    Route::post('admin/crear-componente/', [ParametrosController::class, 'crearComponentes'])->name('admin.crear.componente');


    // fin rutas para gestionar parametros


    //TODO: Inicio de rutas para iniciativas
    Route::get('admin/iniciativas/listar', [IniciativasController::class, 'listarIniciativas'])->name('admin.iniciativa.listar');

    Route::get('admin/iniciativas/excel',[IniciativasController::class,'generarExcel'])->name('admin.iniciativas.excel');
    Route::get('/iniciativas/pdf', [IniciativasController::class, 'descargarPDF'])->name('iniciativas.resumenPDF');

    Route::get('admin/iniciativas/{inic_codigo}/detalles', [IniciativasController::class, 'mostrarDetalles'])->name('admin.iniciativas.detalles');
    Route::get('admin/iniciativas/{inic_codigo}/pdf', [IniciativasController::class, 'mostrarPDF'])->name('admin.iniciativas.pdf');
    Route::get('admin/iniciativas/{inic_codigo}/listar/resultado',[IniciativasController::class,'listadoResultados'])->name('admin.resultados.listado');
    Route::post('admin/iniciativas/{inic_codigo}/resultados', [IniciativasController::class, 'actualizarResultados'])->name('admin.resultados.actualizar');

    Route::get('admin/iniciativas/crear/paso1', [IniciativasController::class, 'crearPaso1'])->name('admin.inicitiativas.crear.primero');
    Route::post('admin/iniciativas/crear/paso1', [IniciativasController::class, 'verificarPaso1'])->name('admin.paso1.verificar');
    Route::get('admin/iniciativas/{inic_codigo}/editar/paso1', [IniciativasController::class, 'editarPaso1'])->name('admin.editar.paso1');
    Route::put('admin/iniciativas/{inic_codigo}/paso1', [IniciativasController::class, 'actualizarPaso1'])->name('admin.actualizar.paso1');

    Route::get('admin/iniciativas/{inic_codigo}/contribucion2030',[IniciativasController::class,'mostrarOds'])->name('admin.iniciativas.agendaods');
    // Route::get('admin/iniciativas/crear/paso1',[IniciativasController::class,'crearPaso1'])->name('admin.inicitiativas.crear.primero');

    Route::post('admin/iniciativas/crear/{inic_codigo}/paso2', [IniciativasController::class, 'verificarPaso2'])->name('admin.paso2.verificar');
    Route::get('admin/iniciativas/{inic_codigo}/paso2', [IniciativasController::class, 'editarPaso2'])->name('admin.editar.paso2');
    Route::post('admin/iniciativa/guardar-resultado', [IniciativasController::class, 'guardarResultado'])->name('admin.resultado.guardar');
    Route::get('admin/iniciativa/listar-resultados', [IniciativasController::class, 'listarResultados'])->name('admin.resultados.listar');
    Route::post('admin/iniciativa/eliminar-resultado', [IniciativasController::class, 'eliminarResultado'])->name('admin.resultado.eliminar');
    // TODO: PASO 3
    Route::get('admin/iniciativa/{inic_codigo}/editar/paso3', [IniciativasController::class, 'editarPaso3'])->name('admin.editar.paso3');
    Route::post('admin/crear-iniciativa/guardar-dinero', [IniciativasController::class, 'guardarDinero'])->name('admin.dinero.guardar');
    Route::get('admin/crear-iniciativa/consultar-dinero', [IniciativasController::class, 'consultarDinero'])->name('admin.dinero.consultar');
    Route::get('admin/crear-iniciativa/buscar-tipoinfra', [IniciativasController::class, 'buscarTipoInfra'])->name('admin.tipoinfra.buscar');
    Route::get('admin/crear-iniciativa/listar-tipoinfra', [IniciativasController::class, 'listarTipoInfra'])->name('admin.tipoinfra.listar');
    Route::post('admin/crear-iniciativa/guardar-infraestructura', [IniciativasController::class, 'guardarInfraestructura'])->name('admin.infra.guardar');
    Route::get('admin/crear-iniciativa/listar-infraestructura', [IniciativasController::class, 'listarInfraestructura'])->name('admin.infra.listar');
    Route::post('admin/crear-iniciativa/eliminar-infraestructura', [IniciativasController::class, 'eliminarInfraestructura'])->name('admin.infra.eliminar');
    Route::get('admin/crear-iniciativa/consultar-infraestructura', [IniciativasController::class, 'consultarInfraestructura'])->name('admin.infra.consultar');
    Route::get('admin/crear-iniciativa/listar-tiporrhh', [IniciativasController::class, 'listarTipoRrhh'])->name('admin.tiporrhh.listar');
    Route::get('admin/crear-iniciativa/recursos', [IniciativasController::class, 'listarRecursos'])->name('admin.recursos.listar');
    Route::get('admin/crear-iniciativa/listar-rrhh', [IniciativasController::class, 'listarRrhh'])->name('admin.rrhh.listar');
    Route::get('admin/crear-iniciativa/buscar-tiporrhh', [IniciativasController::class, 'buscarTipoRrhh'])->name('admin.tiporrhh.buscar');
    Route::post('admin/crear-iniciativa/guardar-rrhh', [IniciativasController::class, 'guardarRrhh'])->name('admin.rrhh.guardar');
    Route::post('admin/crear-iniciativa/eliminar-rrhh', [IniciativasController::class, 'eliminarRRHH']);
    Route::get('admin/crear-iniciativa/consultar-rrhh', [IniciativasController::class, 'consultarRrhh'])->name('admin.rrhh.consultar');



    Route::post('admin/iniciativas/crear/socio', [IniciativasController::class, 'guardarSocioComunitario'])->name('admin.iniciativas.crear.socio');
    Route::get('admin/crear/iniciativa/listar-internos', [IniciativasController::class, 'listarInternos']);
    Route::get('admin/crear/iniciativa/listar-externos', [IniciativasController::class, 'listarExternos']);
    Route::post('admin/actualizar/participantes-internos', [IniciativasController::class, 'actualizarInternos']);
    Route::post('admin/iniciativas/agregar/participantes-externos', [IniciativasController::class, 'agregarExternos']);





    //todo: Update state iniciativa
    Route::post('/admin/iniciativas/update-state', [IniciativasController::class, 'updateState'])->name('admin.iniciativas.updateState');
    //todo: Add result iniciativa
    Route::get('admin/iniciativa/{inic_codigo}/cobertura', [IniciativasController::class, 'completarCobertura'])->name('admin.cobertura.index');
    Route::post('admin/iniciativa/{inic_codigo}/cobertura-interna', [IniciativasController::class, 'actualizarCobertura'])->name('admin.cobertura.interna.update');
    Route::post('admin/iniciativa/{inic_codigo}/cobertura-externa', [IniciativasController::class, 'actualizarCoberturaEx'])->name('admin.cobertura.externa.update');

    //todo:evidencias de iniciativas

    Route::get('admin/iniciativas/{inic_codigo}/listar-evidencias', [IniciativasController::class, 'listarEvidencia'])->name('admin.evidencias.listar');
    Route::post('admin/iniciativas/{inic_codigo}/guardar-evidencias', [IniciativasController::class, 'guardarEvidencia'])->name('admin.evidencia.guardar');
    Route::put('admin/iniciativa/evidencia/{inev_codigo}', [IniciativasController::class, 'actualizarEvidencia'])->name('admin.evidencia.actualizar');
    Route::post('admin/iniciativas/{inic_codigo}/descargar-evidencia', [IniciativasController::class, 'descargarEvidencia'])->name('admin.evidencia.descargar');
    Route::delete('admin/iniciativa/evidencia/{inev_codigo}', [IniciativasController::class, 'eliminarEvidencia'])->name('admin.evidencia.eliminar');
    // Route::get('admin/inicicativas/{inic_codigo}/cobertura',[IniciativasController::class,'ingresarCobertura'])->name('admin.cobertura.listar');

    //todo:fin evidencias de iniciativas

    Route::put('admin/iniciativa/resultados/', [IniciativasController::class, 'actualizarResultado'])->name('admin.resultado.actualizar');
    Route::put('admin/socio/paso2/actualizar/', [IniciativasController::class, 'actualizarSocioPaso2'])->name('admin.socio.paso2.actualizar');

    Route::delete('admin/iniciativas/eliminar', [IniciativasController::class, 'eliminarIniciativas'])->name('admin.iniciativa.eliminar');
    // Route::post('admin/iniciativas/obtener-escuelas',[IniciativasController::class,'escuelasBySede']);
    Route::post('admin/iniciativas/obtener-escuelas/paso2', [IniciativasController::class, 'escuelasBySedesPaso2']);
    Route::post('admin/iniciativas/obtener-actividades', [IniciativasController::class, 'actividadesByMecanismos']);
    Route::post('admin/iniciativas/obtener-mecanismos', [IniciativasController::class, 'mecanismoByActividades']);
    Route::post('admin/iniciativas/obtener-socio/paso2', [IniciativasController::class, 'sociosBySubgrupos']);
    Route::post('admin/iniciativas/obtener-pais', [IniciativasController::class, 'paisByTerritorio']);
    Route::post('admin/iniciativas/obtener-escuelas', [IniciativasController::class, 'escuelasBySedes']);
    Route::post('admin/iniciativas/obtener-comunas', [IniciativasController::class, 'comunasByRegiones']);
    Route::post('admin/iniciativas/obtener-carreras', [IniciativasController::class, 'carrerasByEscuelas1']);
    Route::post('admin/iniciativas/obtener-regiones', [IniciativasController::class, 'regionesByMacrozonas']);
    Route::post('admin/iniciativas/obtener-Dispositivo', [IniciativasController::class, 'DispositivoByInstrumento']);
    Route::post('admin/iniciativas/obtener-ambitos', [IniciativasController::class, 'AmbitosByInstrumento']);
    Route::post('admin/iniciativas/obtener-ImpactoInterno', [IniciativasController::class, 'ImpactoInternoByInstrumento']);
    Route::post('admin/iniciativas/obtener-ImpactoExterno', [IniciativasController::class, 'ImpactoExternoByInstrumento']);
    Route::post('admin/inicitiativa/eliminar-externo', [IniciativasController::class, 'eliminarExterno']);

    //TODO: Evaluacion de evidenciavinculam_demo_v2
    Route::get('admin/iniciativas/{inic_codigo}/evaluar', [IniciativasController::class, 'evaluarIniciativa'])->name('admin.evaluar.iniciativa');
    Route::post('admin/iniciativas/evaluar', [IniciativasController::class, 'guardarEvaluacion'])->name('admin.guardar.evaluacion');
    // ruta evaluacion invitar
    Route::get('admin/iniciativas/{inic_codigo}/evaluar/invitar/{invitado}', [IniciativasController::class, 'iniciativaEvaluarInvitar'])->name('admin.iniciativa.evaluar.invitar');
    // ruta evaluacion correo
    Route::get('admin/iniciativas/{inic_codigo}/evaluar/invitar/{invitado}/correo', [IniciativasController::class, 'iniciativaEvaluarInvitarCorreo'])->name('admin.iniciativa.evaluar.invitar.correo');
    Route::post('admin/iniciativas/evaluar/correo/enviar', [IniciativasController::class, 'iniciativaEvaluarEnviarCorreo'])->name('admin.iniciativa.evaluar.enviar.correo');
    //fin de rutas para iniciativas
    Route::put('admin/iniciativas/invitado/{evainv_codigo}', [ParametrosController::class, 'actualizarInvitado'])->name('admin.actualizar.invitados');
    // TODO: inicio rutas para gestionar usuarios
    Route::get('admin/listar-usuarios', [UsuariosController::class, 'listarUsuarios'])->name('admin.listar.usuarios');
    Route::post('admin/crear-usuario', [UsuariosController::class, 'crearUsuario'])->name('admin.crear.usuarios');
    Route::delete('admin/eliminar-usuario/', [UsuariosController::class, 'eliminarUsuario'])->name('admin.eliminar.usuarios');
    Route::post('admin/editar-usuario/{usua_nickname}', [UsuariosController::class, 'editarUsuario'])->name('admin.actualizar.usuarios');
    Route::put('admin/habilitar-usuario/{usua_nickname}', [UsuariosController::class, 'habilitarUsuario'])->name('admin.habilitar.usuarios');
    Route::put('admin/deshabilitar-usuario/{usua_nickname}', [UsuariosController::class, 'deshabilitarUsuario'])->name('admin.deshabilitar.usuarios');
    Route::post('admin/clave-usuario/{usua_nickname}', [UsuariosController::class, 'actualizarClaveUsuario'])->name('admin.actualizar.claveusuario');
    //fin de rutas para administrar usuarios

    //TODO: Evaluacion de evidenciavinculam_demo_v2
    Route::get('admin/iniciativas/{inic_codigo}/evaluar', [IniciativasController::class, 'evaluarIniciativa'])->name('admin.evaluar.iniciativa');
    Route::get('admin/iniciativas/{inic_codigo}/evaluar2', [IniciativasController::class, 'evaluarIniciativa2'])->name('admin.evaluar.iniciativa2');
    Route::get('admin/iniciativas/evaluar',[IniciativasController::class,'guardarEvaluacion']);
    Route::get('admin/iniciativas/ingresoEvaluacion',[IniciativasController::class,'guardarEvaluacion2']);




    // TODO: inicio rutas ODS
    Route::get('admin/ods', function () {
        return view('admin.ods.listar');
    })->name('admin.listar.ods');

    //fin de rutas para ODS
});


Route::middleware('verificar.digitador')->group(function () {
    Route::get('digitador/iniciativas/listar', [IniciativasController::class, 'listarIniciativas'])->name('digitador.iniciativa.listar');
    Route::get('digitador/iniciativas/{inic_codigo}/detalles', [IniciativasController::class, 'mostrarDetalles'])->name('digitador.iniciativas.detalles');
    Route::get('digitador/iniciativas/{inic_codigo}/pdf', [IniciativasController::class, 'mostrarPDF'])->name('digitador.iniciativas.pdf');
    Route::get('digitador/iniciativas/{inic_codigo}/listar/resultado',[IniciativasController::class,'listadoResultados'])->name('digitador.resultados.listado');
    Route::post('digitador/iniciativas/{inic_codigo}/resultados', [IniciativasController::class, 'actualizarResultados'])->name('digitador.resultados.actualizar');

    //TODO: PASO 1
    Route::get('digitador/iniciativas/crear/paso1', [IniciativasController::class, 'crearPaso1'])->name('digitador.inicitiativas.crear.primero');
    Route::post('digitador/iniciativas/crear/paso1', [IniciativasController::class, 'verificarPaso1'])->name('digitador.paso1.verificar');
    Route::get('digitador/iniciativas/{inic_codigo}/editar/paso1', [IniciativasController::class, 'editarPaso1'])->name('digitador.editar.paso1');
    Route::put('digitador/iniciativas/{inic_codigo}/paso1', [IniciativasController::class, 'actualizarPaso1'])->name('digitador.actualizar.paso1');

    // TODO: PASO 2
    Route::post('digitador/iniciativas/crear/{inic_codigo}/paso2', [IniciativasController::class, 'verificarPaso2'])->name('digitador.paso2.verificar');
    Route::get('digitador/iniciativas/{inic_codigo}/paso2', [IniciativasController::class, 'editarPaso2'])->name('digitador.editar.paso2');
    Route::post('digitador/iniciativa/guardar-resultado', [IniciativasController::class, 'guardarResultado'])->name('digitador.resultado.guardar');
    Route::get('digitador/iniciativa/listar-resultados', [IniciativasController::class, 'listarResultados'])->name('digitador.resultados.listar');
    Route::post('digitador/iniciativa/eliminar-resultado', [IniciativasController::class, 'eliminarResultado'])->name('digitador.resultado.eliminar');
    // TODO: PASO 3
    Route::get('digitador/iniciativa/{inic_codigo}/editar/paso3', [IniciativasController::class, 'editarPaso3'])->name('digitador.editar.paso3');
    Route::post('digitador/crear-iniciativa/guardar-dinero', [IniciativasController::class, 'guardarDinero'])->name('digitador.dinero.guardar');
    Route::get('digitador/crear-iniciativa/consultar-dinero', [IniciativasController::class, 'consultarDinero'])->name('digitador.dinero.consultar');
    Route::get('digitador/crear-iniciativa/buscar-tipoinfra', [IniciativasController::class, 'buscarTipoInfra'])->name('digitador.tipoinfra.buscar');
    Route::get('digitador/crear-iniciativa/listar-tipoinfra', [IniciativasController::class, 'listarTipoInfra'])->name('digitador.tipoinfra.listar');
    Route::post('digitador/crear-iniciativa/guardar-infraestructura', [IniciativasController::class, 'guardarInfraestructura'])->name('digitador.infra.guardar');
    Route::get('digitador/crear-iniciativa/listar-infraestructura', [IniciativasController::class, 'listarInfraestructura'])->name('digitador.infra.listar');
    Route::post('digitador/crear-iniciativa/eliminar-infraestructura', [IniciativasController::class, 'eliminarInfraestructura'])->name('digitador.infra.eliminar');
    Route::get('digitador/crear-iniciativa/consultar-infraestructura', [IniciativasController::class, 'consultarInfraestructura'])->name('digitador.infra.consultar');
    Route::get('digitador/crear-iniciativa/listar-tiporrhh', [IniciativasController::class, 'listarTipoRrhh'])->name('digitador.tiporrhh.listar');
    Route::get('digitador/crear-iniciativa/recursos', [IniciativasController::class, 'listarRecursos'])->name('digitador.recursos.listar');
    Route::get('digitador/crear-iniciativa/listar-rrhh', [IniciativasController::class, 'listarRrhh'])->name('digitador.rrhh.listar');
    Route::get('digitador/crear-iniciativa/buscar-tiporrhh', [IniciativasController::class, 'buscarTipoRrhh'])->name('digitador.tiporrhh.buscar');
    Route::post('digitador/crear-iniciativa/guardar-rrhh', [IniciativasController::class, 'guardarRrhh'])->name('digitador.rrhh.guardar');
    Route::post('digitador/crear-iniciativa/eliminar-rrhh', [IniciativasController::class, 'eliminarRRHH']);
    Route::get('digitador/crear-iniciativa/consultar-rrhh', [IniciativasController::class, 'consultarRrhh'])->name('digitador.rrhh.consultar');




    Route::post('digitador/iniciativas/obtener-escuelas/paso2', [IniciativasController::class, 'escuelasBySedesPaso2']);
    Route::post('digitador/iniciativas/obtener-actividades', [IniciativasController::class, 'actividadesByMecanismos']);
    Route::post('digitador/iniciativas/obtener-mecanismos', [IniciativasController::class, 'mecanismoByActividades']);
    Route::post('digitador/iniciativas/obtener-socio/paso2', [IniciativasController::class, 'sociosBySubgrupos']);
    Route::post('digitador/iniciativas/obtener-pais', [IniciativasController::class, 'paisByTerritorio']);
    Route::post('digitador/iniciativas/obtener-escuelas', [IniciativasController::class, 'escuelasBySedes']);
    Route::post('digitador/iniciativas/obtener-comunas', [IniciativasController::class, 'comunasByRegiones']);
    Route::post('digitador/iniciativas/obtener-carreras', [IniciativasController::class, 'carrerasByEscuelas1']);
    Route::post('digitador/iniciativas/obtener-regiones', [IniciativasController::class, 'regionesByMacrozonas']);
    Route::post('digitador/iniciativas/obtener-Dispositivo', [IniciativasController::class, 'DispositivoByInstrumento']);
    Route::post('digitador/iniciativas/obtener-ImpactoInterno', [IniciativasController::class, 'ImpactoInternoByInstrumento']);
    Route::post('digitador/iniciativas/obtener-ImpactoExterno', [IniciativasController::class, 'ImpactoExternoByInstrumento']);
    Route::post('digitador/inicitiativa/eliminar-externo', [IniciativasController::class, 'eliminarExterno']);

    // TODO: ODS
    Route::any('digitador/chat/send-message', [GPTController::class, 'sendMessage'])->name('digitador.chat.sendMessage');
    Route::any('digitador/chat/revisar-objetivo', [GPTController::class, 'revisarObjetivo'])->name('digitador.chat.revisarObjetivo');
    Route::post('digitador/iniciativas/{inic_codigo}/odsGuardar', [IniciativasController::class, 'saveODS'])->name('digitador.iniciativas.odsGuardar');




    Route::get('digitador/iniciativas/{inic_codigo}/listar-evidencias', [IniciativasController::class, 'listarEvidencia'])->name('digitador.evidencias.listar');
    Route::post('digitador/iniciativas/{inic_codigo}/guardar-evidencias', [IniciativasController::class, 'guardarEvidencia'])->name('digitador.evidencia.guardar');
    Route::put('digitador/iniciativa/evidencia/{inev_codigo}', [IniciativasController::class, 'actualizarEvidencia'])->name('digitador.evidencia.actualizar');
    Route::post('digitador/iniciativas/{inic_codigo}/descargar-evidencia', [IniciativasController::class, 'descargarEvidencia'])->name('digitador.evidencia.descargar');
    Route::delete('digitador/iniciativa/evidencia/{inev_codigo}', [IniciativasController::class, 'eliminarEvidencia'])->name('digitador.evidencia.eliminar');

    Route::get('digitador/iniciativa/{inic_codigo}/cobertura', [IniciativasController::class, 'completarCobertura'])->name('digitador.cobertura.index');
    Route::post('digitador/iniciativa/{inic_codigo}/cobertura-interna', [IniciativasController::class, 'actualizarCobertura'])->name('digitador.cobertura.interna.update');
    Route::post('digitador/iniciativa/{inic_codigo}/cobertura-externa', [IniciativasController::class, 'actualizarCoberturaEx'])->name('digitador.cobertura.externa.update');

    //todo:evidencias de iniciativas

    Route::get('digitador/iniciativas/{inic_codigo}/listar-evidencias', [IniciativasController::class, 'listarEvidencia'])->name('digitador.evidencias.listar');
    Route::post('digitador/iniciativas/{inic_codigo}/guardar-evidencias', [IniciativasController::class, 'guardarEvidencia'])->name('digitador.evidencia.guardar');
    Route::put('digitador/iniciativa/evidencia/{inev_codigo}', [IniciativasController::class, 'actualizarEvidencia'])->name('digitador.evidencia.actualizar');
    Route::post('digitador/iniciativas/{inic_codigo}/descargar-evidencia', [IniciativasController::class, 'descargarEvidencia'])->name('digitador.evidencia.descargar');
    Route::delete('digitador/iniciativa/evidencia/{inev_codigo}', [IniciativasController::class, 'eliminarEvidencia'])->name('digitador.evidencia.eliminar');
    // Route::get('digitador/inicicativas/{inic_codigo}/cobertura',[IniciativasController::class,'ingresarCobertura'])->name('digitador.cobertura.listar');

    //todo:fin evidencias de iniciativas

    Route::put('digitador/iniciativa/resultados/', [IniciativasController::class, 'actualizarResultado'])->name('digitador.resultado.actualizar');
    Route::put('digitador/socio/paso2/actualizar/', [IniciativasController::class, 'actualizarSocioPaso2'])->name('digitador.socio.paso2.actualizar');

    Route::delete('digitador/iniciativas/eliminar', [IniciativasController::class, 'eliminarIniciativas'])->name('digitador.iniciativa.eliminar');



    //TODO: Evaluacion de evidenciavinculam_demo_v2
    Route::get('digitador/iniciativas/{inic_codigo}/evaluar', [IniciativasController::class, 'evaluarIniciativa'])->name('digitador.evaluar.iniciativa');
    Route::get('digitador/iniciativas/{inic_codigo}/evaluar/invitar', [IniciativasController::class, 'evaluarIniciativaInvitar'])->name('digitador.evaluar.iniciativa.invitar');
    Route::post('digitador/iniciativas/invitar-evaluacion', [IniciativasController::class, 'invitarEvaluacion'])->name('digitador.invitar.evaluacion');
    //Primer paso de la creacion de evaluacion
    Route::post('digitador/iniciativas/crear-evaluacion', [IniciativasController::class, 'crearEvaluacion'])->name('digitador.crear.evaluacion');
    Route::post('digitador/iniciativas/eliminar-evaluacion', [IniciativasController::class, 'eliminarEvaluacionInciativa'])->name('digitador.eliminar.evaluacion.iniciativa');
    Route::post('digitador/eliminar-invitado/{evainv_codigo}', [IniciativasController::class, 'eliminarInvitadoEvaluacion'])->name('digitador.eliminar.invitacion');
    Route::delete('digitador/eliminar-invitado-docente/{evainv_codigo}', [IniciativasController::class, 'eliminarInvitadoEvaluacionDocente'])->name('digitador.eliminar.invitacion.docente');
    Route::delete('digitador/eliminar-invitado-externo/{evainv_codigo}', [IniciativasController::class, 'eliminarInvitadoEvaluacionExterno'])->name('digitador.eliminar.invitacion.externo');
    Route::post('digitador/iniciativas/carga-individual-evaluacion', [IniciativasController::class, 'cargaIndividualEvaluacion'])->name('digitador.iniciativa.evaluar.enviar.cargaIndividual');
    //Route::post('digitador/iniciativas/procesar-archivo', [IniciativasController::class, 'procesarArchivo'])->name('procesarArchivo');
    //ver resultados de la evaluacion
    Route::get('digitador/iniciativas/{inic_codigo}/evaluacion/resultados/{invitado}', [IniciativasController::class, 'verEvaluacion'])->name('digitador.ver.evaluacion');

    Route::get('digitador/iniciativas/{inic_codigo}/evaluar2', [IniciativasController::class, 'evaluarIniciativa2'])->name('digitador.evaluar.iniciativa2');
    Route::get('digitador/iniciativas/evaluar', [IniciativasController::class, 'guardarEvaluacion']);
    Route::get('digitador/iniciativas/ingresoEvaluacion', [IniciativasController::class, 'guardarEvaluacion2']);

    Route::post('digitador/crear-socios/', [ParametrosController::class, 'crearSocios'])->name('digitador.crear.socios');


    Route::post('digitador/iniciativas/crear/socio', [IniciativasController::class, 'guardarSocioComunitario'])->name('digitador.iniciativas.crear.socio');
    Route::get('digitador/crear/iniciativa/listar-internos', [IniciativasController::class, 'listarInternos']);
    Route::get('digitador/crear/iniciativa/listar-externos', [IniciativasController::class, 'listarExternos']);
    Route::post('digitador/actualizar/participantes-internos', [IniciativasController::class, 'actualizarInternos']);
    Route::post('digitador/iniciativas/agregar/participantes-externos', [IniciativasController::class, 'agregarExternos']);
});



//ruta para evaluacion de iniciativas
Route::get('/{evatotal_encriptado}/unirse', [IniciativasController::class, 'AutoInvitacionEvaluacion']);
Route::post('/evaluaciones/unirse', [IniciativasController::class, 'guardarInvitacion'])->name('evaluacion.auto.invitarse');

Route::get('/evaluaciones/{evatotal_encriptado}', [IniciativasController::class, 'evaluaEstudiante']);
Route::post('/evaluaciones/guardar', [IniciativasController::class, 'guardarEvaluacionEstudiante'])->name('evaluacion.guardar.estudiante');

use App\Http\Controllers\EmailController;

Route::post('/send-email-estudiante', [IniciativasController::class, 'sendEmailEstudiante'])->name('send.email');
//fin de rutas para evaluacion de iniciativas
//eliminar todas las evaluaciones relacionadas a un id admin.eliminar.todas.las.evaluaciones
Route::get('admin/iniciativas/{inic_codigo}/evaluar/propuesta/{invitado}', [IniciativasController::class, 'evaluarIniciativaPaso2'])->name('admin.evaluar.paso2');
Route::delete('admin/eliminar-todas-las-evaluaciones', [IniciativasController::class, 'eliminarTodasLasEvaluaciones'])->name('admin.eliminar.todas.las.evaluaciones');
Route::post('admin/iniciativas/evaluar/manual', [IniciativasController::class, 'guardarEvaluacionManual'])->name('admin.guardar.evaluacion.manual');

use App\Http\Controllers\MailController;

Route::post('/send-email', [MailController::class, 'sendEmail'])->name('enviar.email');
Route::post('admin/iniciativas/procesar-archivo', [IniciativasController::class, 'procesarTexto'])->name('procesarTexto');
Route::post('admin/actualizar-iniciativa/actualizar-infraestructura', [IniciativasController::class, 'actualizarInfraestructura'])->name('admin.infra.actualizar');
Route::post('admin/actualizar-iniciativa/actualizar-rrhh', [IniciativasController::class, 'actualizarRrhh'])->name('admin.rrhh.actualizar');


Route::post('/evaluaciones/guardar/qr/', [IniciativasController::class, 'guardarEvaluacionQR'])->name('evaluacion.guardar.desde.qr');
Route::get('/evaluaciones/{evatotal_encriptado}/desde-qr', [IniciativasController::class, 'evaluaEstudianteDesdeQR']);
//qr evaluacion
Route::get('evaluaciones/{evatotal_encriptado}/qr', [IniciativasController::class, 'mostrarQr'])->name('admin.qr.evaluacion');


Route::post('/recuperar/send-email', [ForgotPasswordController::class, 'sendRecoveryEmail'])->name('enviar.correo.recuperacion');
Route::get('evaluaciones/{evatotal_encriptado}/qr', [IniciativasController::class, 'mostrarQr'])->name('admin.qr.evaluacion');
