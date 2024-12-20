<?php

namespace App\Http\Controllers;

use App\Models\Actividades;
use App\Models\Ambitos;
use App\Models\AmbitosAccion;
use App\Models\AreaEspecialidad;
use App\Models\Carreras;
use App\Models\Comuna;
use App\Models\Convenios;
use App\Models\Escuelas;
use App\Models\GruposInteres;
use App\Models\Iniciativas;
use App\Models\Mecanismos;
use App\Models\Pais;
use App\Models\Regiones;
use App\Models\Programas;
use App\Models\ProgramasContribuciones;
use App\Models\Dispositivos;
use App\Models\IniciativasDispositivos;
use App\Models\Sedes;
use App\Models\SedesSocios;
use App\Models\SedesEscuelas;
use App\Models\SedesProgramas;
use App\Models\TipoIniciativa;
use App\Models\SociosComunitarios;
use App\Models\SubGruposInteres;
use App\Models\Tematicas;
use App\Models\TipoActividades;
use App\Models\TipoActividadesMetas;
use App\Models\TipoIniciativas;
use App\Models\TipoUnidades;
use App\Models\TipoRRHH;
use App\Models\TipoInfraestructura;
use App\Models\MecanismosActividades;
use App\Models\ProgramasActividades;
use App\Models\Unidades;
use App\Models\SubUnidades;
use App\Models\Componentes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use App\Models\Asignaturas;
use App\Models\CentroSimulacion;
use App\Models\IniciativasCentroSimulacion;
use App\Models\CarrerasAsignaturas;
use App\Models\AmbitosTiac;
use App\Models\IniciativasAmbitos;
use App\Models\DispositivosTiac;
use App\Models\TipoActividadAmbitoAccion;
use App\Models\EvaluacionInvitado;
use App\Models\CentroCostos;
use App\Models\CostosDinero;
use App\Models\CostosInfraestructura;
use App\Models\CostosRrhh;

class ParametrosController extends Controller
{
    protected $nombreRol;

    //TODO: Ambito de contribucion
    public function listarAmbitos()
    {
        return view('admin.parametros.ambitos', [
            'ambitos' => Ambitos::orderBy('amb_codigo', 'asc')->get(),
            'tiacs' => TipoActividades::orderBy('tiac_codigo', 'asc')->get(),
            'ambitosTiac' => AmbitosTiac::all()
        ]);
    }

    public function crearAmbitos(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'nombre' => 'required|max:100',
        ], [
            'nombre.required' => 'El nombre es requerido.',
            'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (100).',
        ]);

        if ($validacion->fails()) {
            return redirect()->route('admin.listar.ambitos')->withErrors($validacion)->withInput();
        }

        $ambito = new Ambitos();
        $ambito->amb_nombre = $request->input('nombre');
        $ambito->amb_descripcion = $request->input('descripcion');
        $ambito->amb_director = $request->input('director');
        $ambito->amb_creado = now();
        $ambito->amb_actualizado = now();
        // Guardar el programa en la base de datos
        $ambito->save();

        $amb_codigo = $ambito->amb_codigo;

        $tiacArray = [];
        $tiacs = $request->input('tiacs', []);


        foreach ($tiacs as $se) {
            array_push(
                $tiacArray,
                [
                    'amb_codigo' => $amb_codigo,
                    'tiac_codigo' => $se,

                ]
            );
        }

        $relacCrear = AmbitosTiac::insert($tiacArray);

        if (!$relacCrear) {
            Ambitos::where('amb_codigo', $amb_codigo)->delete();
            return redirect()->back()->with('errorAmbito', 'Ocurrió un error durante el registro del impacto, intente más tarde.')->withInput();
        }




        return redirect()->back()->with('exitoAmbito', 'Impacto creado exitosamente');
    }

    public function eliminarAmbitos(Request $request)
    {

        $ambito = Ambitos::where('amb_codigo', $request->amb_codigo)->first();
        $iniciativasAmbitos = IniciativasAmbitos::where('amb_codigo', $request->amb_codigo)->get();
        // eliminar
        foreach ($iniciativasAmbitos as $iniciativaAmbito) {
            //eliminar todas los registros de los ambitos con ese amb_codigo
            IniciativasAmbitos::where('amb_codigo', $request->amb_codigo)->delete();
        }

        $iniciativas = Iniciativas::all();
        $iniciativasAmbitos = IniciativasAmbitos::all();
        // eliminar los registros de iniciativas que no existan y esten relacionados con el ambito
        foreach ($iniciativasAmbitos as $iniciativaAmbito) {
            $iniciativa = Iniciativas::where('inic_codigo', $iniciativaAmbito->inic_codigo)->first();
            if (!$iniciativa) {
                IniciativasAmbitos::where('inic_codigo', $iniciativaAmbito->inic_codigo)->delete();
            }
        }
        try {
            AmbitosTiac::where('amb_codigo', $amb_codigo)->delete();
        } catch (\Throwable $th) {
            //throw $th;
        }

        if (!$ambito) {
            return redirect()->route('admin.listar.ambitos')->with('errorAmbito', 'El impacto no se encuentra registrado en el sistema.');
        }

        $ambito = Ambitos::where('amb_codigo', $request->amb_codigo)->delete();

        return redirect()->route('admin.listar.ambitos')->with('exitoAmbito', 'El impacto fue eliminado correctamente.');
    }

    public function actualizarAmbitos(Request $request, $amb_codigo)
    {
        $validacion = Validator::make($request->all(), [
            'nombre' => 'required|max:255',
        ], [
            'nombre.required' => 'El nombre es requerido.',
            'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (255).',
        ]);

        if ($validacion->fails()) {
            return redirect()->route('admin.listar.ambitos')->withErrors($validacion)->withInput();
        }

        $ambito = Ambitos::find($amb_codigo);
        //return redirect()->route('admin.listar.ambitos')->with('errorAmbito', $amb_codigo);
        if (!$ambito) {
            return redirect()->route('admin.listar.ambitos')->with('errorAmbito', 'El impacto no se encuentra registrado en el sistema.')->withInput();;
        }

        $ambito->amb_nombre = $request->input('nombre');
        $ambito->amb_descripcion = $request->input('descripcion');
        $ambito->amb_director = $request->input('director');
        $ambito->amb_creado = now();
        $ambito->amb_actualizado = now();

        // Guardar la actualización del programa en la base de datos
        $ambito->save();

        AmbitosTiac::where('amb_codigo', $amb_codigo)->delete();

        $tiacArray = [];
        $tiacs = $request->input('tiacs', []);


        foreach ($tiacs as $se) {
            array_push(
                $tiacArray,
                [
                    'amb_codigo' => $amb_codigo,
                    'tiac_codigo' => $se,

                ]
            );
        }

        $relacCrear = AmbitosTiac::insert($tiacArray);

        if (!$relacCrear) {
            Ambitos::where('amb_codigo', $amb_codigo)->delete();
            return redirect()->back()->with('errorAmbito', 'Ocurrió un error durante el registro del impacto, intente más tarde.')->withInput();
        }

        return redirect()->back()->with('exitoAmbito', 'Impacto actualizado exitosamente')->withInput();;
    }

    //TODO: Ambito de acción
    public function listarAmbitosAccion()
    {
        return view('admin.parametros.aaccion', [
            'ambitos' => AmbitosAccion::orderBy('amac_codigo', 'asc')->get()
        ]);
    }

    public function crearAmbitosAccion(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'nombre_aa' => 'required|max:100',
        ], [
            'nombre_aa.required' => 'El nombre es requerido.',
            'nombre_aa.max' => 'El nombre excede el máximo de caracteres permitidos (100).',
        ]);

        if ($validacion->fails()) {
            return redirect()->route('admin.listar.ambitosaccion')->withErrors($validacion)->withInput();
        }

        $ambito = new AmbitosAccion();
        $ambito->amac_nombre = $request->input('nombre_aa');
        $ambito->amac_descripcion = $request->input('descripcion_aa');
        $ambito->amac_director = $request->input('director_aa');
        $ambito->amac_creado = now();
        $ambito->amac_actualizado = now();

        // Guardar el programa en la base de datos
        $ambito->save();

        return redirect()->back()->with('exitoAmbito', 'Ámbito de acción creado exitosamente');
    }

    public function eliminarAmbitosAccion(Request $request)
    {
        $ambito = AmbitosAccion::where('amac_codigo', $request->amac_codigo)->first();

        if (!$ambito) {
            return redirect()->route('admin.listar.ambitosaccion')->with('errorAmbito', 'El ámbito de acción  no se encuentra registrado en el sistema.');
        }

        $ambito = AmbitosAccion::where('amac_codigo', $request->amac_codigo)->delete();

        return redirect()->route('admin.listar.ambitosaccion')->with('exitoAmbito', 'El ámbito de acción  fue eliminado correctamente.');
    }

    public function actualizarAmbitosAccion(Request $request, $amac_codigo)
    {
        $validacion = Validator::make($request->all(), [
            'nombre_aa' => 'required|max:255',
        ], [
            'nombre_aa.required' => 'El nombre es requerido.',
            'nombre_aa.max' => 'El nombre excede el máximo de caracteres permitidos (255).',
        ]);

        if ($validacion->fails()) {
            return redirect()->route('admin.listar.ambitosaccion')->withErrors($validacion)->withInput();
        }

        $ambito = AmbitosAccion::find($amac_codigo);
        //return redirect()->route('admin.listar.ambitos')->with('errorAmbito', $amb_codigo);
        if (!$ambito) {
            return redirect()->route('admin.listar.ambitosaccion')->with('errorAmbito', 'El ámbito de acción no se encuentra registrado en el sistema.')->withInput();;
        }

        $ambito->amac_nombre = $request->input('nombre_aa');
        $ambito->amac_descripcion = $request->input('descripcion_aa');
        $ambito->amac_director = $request->input('director_aa');
        $ambito->amac_creado = now();
        $ambito->amac_actualizado = now();

        // Guardar la actualización del programa en la base de datos
        $ambito->save();

        return redirect()->back()->with('exitoAmbito', 'Ámbito de acción  actualizado exitosamente')->withInput();;
    }

    //TODO: Programas
    public function listarProgramas()
    {
        $programas = Programas::orderBy('prog_codigo', 'asc')->get();
        $tipos = AmbitosAccion::orderBy('amac_codigo', 'asc')->get();
        $ACTIVIDADES = TipoActividades::all();
        $PROGRA_ACTI = ProgramasActividades::all();
        $tiposIniciativas = TipoIniciativas::orderBy('tmec_codigo', 'asc')->get();
        $CONTRIS = Ambitos::all();
        $PROCONS = ProgramasContribuciones::all();

        return view('admin.parametros.programs', compact('programas', 'tipos', 'ACTIVIDADES', 'PROGRA_ACTI', 'tiposIniciativas', 'CONTRIS', 'PROCONS'));
    }

    public function crearProgramas(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:255',
            'ambito' => 'required',
            /* 'tipo' => 'required', */
        ], [
            'nombre.required' => 'El nombre es requerido.',
            'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (255).',
            'ambito.required' => 'Seleccione un ámbito de acción.',
            /* 'tipo.required' => 'Seleccione un tipo de iniciativa.', */
        ]);



        $programas = Programas::insertGetId([
            'prog_nombre' => $request->nombre,
            'prog_ano' => $request->ano,
            'tmec_codigo' => $request->tipo,
            'prog_descripcion' => $request->descripcion,
            'prog_director' => $request->director,
            'prog_meta_socios' => $request->meta_socios,
            'prog_meta_iniciativas' => $request->meta_iniciativas,
            'prog_meta_estudiantes' => $request->meta_estudiantes,
            'prog_meta_docentes' => $request->meta_docentes,
            'prog_meta_beneficiarios' => $request->meta_beneficiarios,
            'prog_meta_asignaturas' => $request->meta_asignaturas,
            'prog_meta_n_carreras' => $request->meta_n_carreras,
            'prog_meta_n_asignaturas' => $request->meta_n_asignaturas,
            'amac_codigo' => $request->ambito,
            'prog_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'prog_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'prog_nickname_mod' => Session::get('admin')->usua_nickname,
            'prog_rol_mod' => Session::get('admin')->rous_codigo,
        ]);

        if (!$programas) {
            return redirect()->back()->with('errorPrograma', 'Ocurrió un error al ingresar al socio, intente más tarde.')->withInput();
        }

        $prog_codigo = $programas;
        $proco = [];

        $contris = $request->input('contribucion', []);
        foreach ($contris as $activ) {
            array_push($proco, [
                'prog_codigo' => $prog_codigo,
                'amb_codigo' => $activ,
                'proco_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                'proco_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                'proco_nickname_mod' => Session::get('admin')->usua_nickname,
                'proco_rol_mod' => Session::get('admin')->rous_codigo,
            ]);
        }


        $procoCrear = ProgramasContribuciones::insert($proco);
        if (!$procoCrear) {
            ProgramasContribuciones::where('prog_codigo', $prog_codigo)->delete();
            return redirect()->back()->with('errorPrograma', 'Ocurrió un error durante el registro de las sedes, intente más tarde.')->withInput();
        }

        return redirect()->back()->with('exitoPrograma', 'Programa creado exitosamente')->withInput();;
    }

    public function eliminarProgramas(Request $request)
    {
        $programa = Programas::where('prog_codigo', $request->prog_codigo)->first();

        if (!$programa) {
            return redirect()->route('admin.listar.programas')->with('errorPrograma', 'El programa no se encuentra registrado en el sistema.');
        }
        /*
                $verificar = Iniciativas::select('inic_codigo')->where('prog_codigo', $request->prog_codigo);
                if ($verificar) {
                    return redirect()->route('admin.listar.programas')->with('errorPrograma', 'No es posible eliminar, el programa está siendo utilizado en una iniciativa');
                } */
        // Eliminar actividades relacionadas
        ProgramasActividades::where('prog_codigo', $request->prog_codigo)->delete();
        ProgramasContribuciones::where('prog_codigo', $request->prog_codigo)->delete();

        // Eliminar el programa
        $programa->delete();

        return redirect()->route('admin.listar.programas')->with('exitoPrograma', 'El programa fue eliminado correctamente.');
    }

    public function actualizarProgramas(Request $request, $prog_codigo)
    {
        $validacion = Validator::make($request->all(), [
            'nombre' => 'required|max:255',
            'ambito' => 'required',
            /* 'tipo' => 'required', */
        ], [
            'nombre.required' => 'El nombre es requerido.',
            'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (255).',
            'ambito.required' => 'Seleccione un ámbito de acción.',
            /* 'tipo.required' => 'Seleccione un tipo de iniciativa.', */

        ]);
        if ($validacion->fails()) {
            return redirect()->route('admin.listar.mecanismos')->withErrors($validacion)->withInput();
        }

        $programa = Programas::find($prog_codigo);

        if (!$programa) {
            return redirect()->route('admin.listar.programas')->with('errorPrograma', 'El programa no se encuentra registrado en el sistema.')->withInput();;
        }

        ProgramasContribuciones::where('prog_codigo', $prog_codigo)->delete();

        $programa->prog_nombre = $request->nombre;
        $programa->prog_ano = $request->ano;
        $programa->tmec_codigo = $request->tipo;
        $programa->prog_descripcion = $request->descripcion;
        $programa->prog_director = $request->director;
        $programa->prog_meta_socios = $request->meta_socios;
        $programa->prog_meta_iniciativas = $request->meta_iniciativas;
        $programa->prog_meta_estudiantes = $request->meta_estudiantes;
        $programa->prog_meta_docentes = $request->meta_docentes;
        $programa->prog_meta_beneficiarios = $request->meta_beneficiarios;
        $programa->prog_meta_asignaturas = $request->meta_asignaturas;
        $programa->prog_meta_n_carreras = $request->meta_n_carreras;
        $programa->prog_meta_n_asignaturas = $request->meta_n_asignaturas;
        $programa->amac_codigo = $request->ambito;
        $programa->prog_creado = Carbon::now()->format('Y-m-d H:i:s');
        $programa->prog_actualizado = Carbon::now()->format('Y-m-d H:i:s');
        $programa->prog_nickname_mod = Session::get('admin')->usua_nickname;
        $programa->prog_rol_mod = Session::get('admin')->rous_codigo;

        // Guardar la actualización del programa en la base de datos
        $programa->save();

        $proco = [];

        $contris = $request->input('contribuciont', []);
        foreach ($contris as $activ) {
            array_push($proco, [
                'prog_codigo' => $prog_codigo,
                'amb_codigo' => $activ,
                'proco_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                'proco_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                'proco_nickname_mod' => Session::get('admin')->usua_nickname,
                'proco_rol_mod' => Session::get('admin')->rous_codigo,
            ]);
        }


        $procoCrear = ProgramasContribuciones::insert($proco);
        if (!$procoCrear) {
            ProgramasContribuciones::where('prog_codigo', $prog_codigo)->delete();
            return redirect()->back()->with('errorPrograma', 'Ocurrió un error durante el registro de las sedes, intente más tarde.')->withInput();
        }

        return redirect()->back()->with('exitoPrograma', 'Programa actualizado exitosamente');
    }

    //TODO: Parametro Convenios
    public function listarConvenios()
    {
        return view('admin.parametros.convenios', [
            'convenios' => Convenios::orderBy('conv_codigo', 'asc')->get()
        ]);
    }

    public function descargarConvenios($conv_codigo)
    {
        try {
            $convenio = Convenios::where('conv_codigo', $conv_codigo)->first();
            if (!$convenio) {
                return redirect()->back()->with('errorConvenio', 'El convenio no se encuentra registrado o no esta vigente en el sistema');
            }

            $archivo = public_path($convenio->conv_ruta_archivo);
            // return $archivo;
            $cabeceras = array(
                'Content-Type: ' . $convenio->conv_mime,
                'Cache-Control: no-cache, no-store, must-revalidate',
                'Pragma: no-cache'
            );
            return Response::download($archivo, $convenio->conv_nombre_archivo, $cabeceras);
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorConvenio', 'Ocurrió un problema al descargar el conveio, intente mas tarde');
        }
    }

    public function eliminarConvenios(Request $request)
    {
        $verificarDrop = Convenios::where('conv_codigo', $request->conv_codigo)->first();
        if (!$verificarDrop) {
            return redirect()->route('admin.listar.convenios')->with('errorConvenio', 'El documento de colaboración no se encuentra registrado en el sistema.');
        }

        try {
            $verificarDropFile = unlink($verificarDrop->conv_ruta_archivo);
        } catch (\Exception $e) {
            echo "Archivo no encontrado: " . $e->getMessage();
        }
        /*
                $verificar = Iniciativas::select('inic_codigo')->where('conv_codigo', $request->conv_codigo)->first();
                if ($verificar) {
                    return redirect()->route('admin.listar.convenios')->with('errorConvenio', 'No es posible eliminar, el documento de colaboración está siendo utilizado en una iniciativa');
                } */

        $Drop = Convenios::where('conv_codigo', $request->conv_codigo)->delete();
        if (!$Drop) {
            return redirect()->back()->with('errorConvenio', 'El documento de colaboración no se pudo eliminar, intente más tarde.');
        }

        return redirect()->route('admin.listar.convenios')->with('exitoConvenio', 'El documento de colaboración fue eliminado correctamente.');
    }

    public function actualizarConvenios(Request $request, $conv_codigo)
    {

        $validacion = $request->validate(
            [
                'nombre' => 'required|max:255',
                'nombrearchivo' => 'required|max:100',
            ],
            [
                'nombre.required' => 'El nombre es requerido.',
                'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (255).',
                'nombrearchivo.required' => 'El nombre del archivo es requerido.',
                'nombrearchivo.max' => 'El nombre del archivo excede el máximo de caracteres permitidos (100).',
            ]
        );


        //CAmbiar nombre del archivo
        $file_path = Convenios::select('conv_ruta_archivo')->where(['conv_codigo' => $conv_codigo])->first();
        $file_path = $file_path->conv_ruta_archivo;
        $rutaArchivo = $file_path;
        $nuevoNombre = $request->input('nombrearchivo');
        $rutaCompleta = public_path($rutaArchivo);
        $rutaCompleta = str_replace("/", "\\", $rutaCompleta);

        if (!$validacion) {
            return redirect()->route('admin.listar.convenios')->with('errorConvenio', 'Problemas al actualizar el documento de colaboración.')->withInput();;
        }

        $archivo = $request->file('archivo');

        //return redirect()->route('admin.listar.convenios')->with('errorConvenio', $archivo);
        if ($archivo) {
            $extension = $archivo->getClientOriginalExtension();
            $rutaConvenio = 'files/convenios/' . $request->input('nombrearchivo') . '.' . $extension;

            if (File::exists(public_path($rutaConvenio)))
                File::delete(public_path($rutaConvenio));
            $moverArchivo = $archivo->move(public_path('files/convenios'), $request->input('nombrearchivo') . '.' . $extension);
            if (!$moverArchivo) {
                return redirect()->back()->with('errorConvenio', 'Ocurrió un error durante el registro del documento de colaboración, intente más tarde.')->withInput();;
            }


            if (File::exists($rutaCompleta))
                File::delete($rutaCompleta);
            $convenio = Convenios::where(['conv_codigo' => $conv_codigo])->update([
                'conv_ruta_archivo' => 'files/convenios/' . $request->input('nombrearchivo') . '.' . $extension,
            ]);
        }


        //return redirect()->route('admin.listar.convenios')->with('errorConvenio', $rutaCompleta);

        if (File::exists($rutaCompleta)) {
            $directorio = dirname($rutaCompleta);
            $extension = pathinfo($rutaCompleta, PATHINFO_EXTENSION);
            $nuevaRuta = $directorio . '/' . $nuevoNombre . '.' . $extension;

            File::move($rutaCompleta, $nuevaRuta);
            $convenio = Convenios::where(['conv_codigo' => $conv_codigo])->update([
                'conv_ruta_archivo' => 'files/convenios/' . $nuevoNombre . '.' . $extension,
            ]);
        }

        $convenio = Convenios::where(['conv_codigo' => $conv_codigo])->update([
            'conv_nombre' => $request->input('nombre'),
            'conv_tipo' => $request->input('tipo'),
            'conv_descripcion' => $request->input('descripcion'),
            'conv_nombre_archivo' => $request->input('nombrearchivo'),
            'conv_actualizado' => now(),
            // 'usua_rol_mod' => Session::get('admin')->rous_codigo,
        ]);


        /* $archivo = $request->file('archivo');
        //Guardar PDF de los convenios
        if ($archivo){
            //Obtener la extension del FILE subido
            $extension = $archivo->getClientOriginalExtension();
            return redirect()->back()->with('errorConvenio', $extension);
            $rutaConvenio = 'files/convenios/' . $request->input('nombrearchivo') . '.'. $extension;

            if (File::exists(public_path($rutaConvenio))) File::delete(public_path($rutaConvenio));

            $moverArchivo = $archivo->move(public_path('files/convenios'), $request->input('nombrearchivo') . '.'. $extension);
            if (!$moverArchivo) {
                Convenios::where('conv_codigo', $conv_codigo)->delete();
                return redirect()->back()->with('errorConvenio', 'Ocurrió un error durante el registro del convenio, intente más tarde.');
            }

        } */

        return redirect()->back()->with('exitoConvenio', 'Documentos de colaboración actualizado existosamente')->withInput();
    }

    public function crearConvenios(Request $request)
    {
        $validacion = $request->validate(
            [
                'nombre' => 'required|max:255',
                // 'nombrearchivo' => 'required|max:100',
                'archivo' => 'required',
            ],
            [
                'nombre.required' => 'El nombre es requerido.',
                'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (255).',
                // 'nombrearchivo.required' => 'El nombre del archivo es requerido.',
                // 'nombrearchivo.max' => 'El nombre del archivo excede el máximo de caracteres permitidos (100).',
                'archivo.required' => 'El archivo del convenio es requerido.',
            ]
        );
        if (!$validacion)
            return redirect()->route('admin.listar.convenios')->with('errorConvenio', 'Problemas al crear el documento de colaboración.');

        $convGuardar = Convenios::insertGetId([
            'conv_nombre' => $request->nombre,
            'conv_tipo' => $request->tipo,
            'conv_descripcion' => $request->descripcion,
            'conv_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'conv_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'conv_rol_mod' => Session::get('admin')->rous_codigo,
            'conv_nickname_mod' => Session::get('admin')->usua_nickname
        ]);

        if (!$convGuardar) {
            return redirect()->back()->with('errorConvenio', 'Ocurrió un error durante el registro del documento de colaboración, intente más tarde.')->withInput();
        }

        $archivo = $request->file('archivo');

        $rutaConvenio = 'files/convenios/' . $convGuardar;
        if (File::exists(public_path($rutaConvenio)))
            File::delete(public_path($rutaConvenio));
        $moverArchivo = $archivo->move(public_path('files/convenios'), $convGuardar);

        if (!$moverArchivo) {
            Convenios::where('conv_codigo', $convGuardar)->delete();
            return redirect()->back()->with('errorConvenio', 'Ocurrió un error al registrar el docuemnto de colaboracion, intente más tarde.');
        }

        $convActualizar = Convenios::where('conv_codigo', $convGuardar)->update([
            'conv_ruta_archivo' => 'files/convenios/' . $convGuardar,
            'conv_mime' => $archivo->getClientMimeType(),
            'conv_nombre_archivo' => $archivo->getClientOriginalName()
        ]);

        if (!$convActualizar) {
            return redirect()->back()->with('errorEvidencia', 'Ocurrió un error al registrar la evidencia, intente más tarde.');
        }
        return redirect()->back()->with('exitoConvenio', 'Documento de colaboración creado existosamente')->withInput();
        // $convenio = new Convenios();
        // $convenio->conv_nombre = $request->input('nombre');
        // $convenio->conv_tipo = $request->input('tipo');
        // $convenio->conv_descripcion = $request->input('descripcion');
        // $convenio->conv_nombre_archivo = $archivo->getClientOriginalName();
        // $convenio->conv_mine = $archivo->getClientMimeType();
        // $convenio->conv_visible = $request->input('visible', 1);
        // //TODO: SI NO QUEREMOS MORIR, CAMBIAR ESTO
        // $convenio->conv_creado = now();
        // $convenio->conv_actualizado = now();

        // $convenio->conv_nickname_mod = Session::get('admin')->usua_nickname;
        // $convenio->conv_rol_mod = Session::get('admin')->rous_codigo;

        // if (File::exists(public_path($rutaConvenio)))
        //     File::delete(public_path($rutaConvenio));
        // $moverArchivo = $archivo->move(public_path('files/convenios'), $request->input('nombre'));
        // if (!$moverArchivo) {
        //     Convenio::where('')
        //     return redirect()->back()->with('errorConvenio', 'Ocurrió un error durante el registro del documento de colaboración, intente más tarde.')->withInput();
        // }

        // $convenio->conv_ruta_archivo = $rutaConvenio;

        // $convenio->save();


    }

    //TODO: Parametro Sedes
    public function listarSedes()
    {
        return view('admin.parametros.sedes', [
            'sedes' => Sedes::orderBy('sede_codigo', 'asc')->get()
        ]);
    }

    public function crearSede(Request $request)
    {
        // Validar los datos enviados en el formulario
        $validatedData = $request->validate([
            'sede_nombre' => 'required|string',
            // 'meta_estudiantes' => 'required|numeric',
            // 'meta_docentes' => 'required|numeric',
            /* 'sede_meta_socios' => 'required|numeric',
            'sede_meta_iniciativas' => 'required|numeric', */
        ], [
            'sede_nombre.required' => 'El campo Nombre de la sede es requerido.',
            // 'meta_estudiantes.required' => 'El campo Estudiantes es requerido.',
            // 'meta_docentes.required' => 'El campo Docentes es requerido.',
            /* 'sede_meta_socios.required' => 'El campo Socios es requerido.', */
            /* 'sede_meta_iniciativas.required' => 'El campo Iniciativas es requerido.', */
            // 'meta_estudiantes.numeric' => 'El campo Estudiantes debe ser numérico.',
            // 'meta_docentes.numeric' => 'El campo Docentes debe ser numérico.',
            /* 'sede_meta_socios.numeric' => 'El campo Socios debe ser numérico.',
            'sede_meta_iniciativas.numeric' => 'El campo Iniciativas debe ser numérico.', */
        ]);

        // Crear una nueva instancia del modelo Sede
        $sede = new Sedes();
        $sede->sede_nombre = $request->input('sede_nombre');
        $sede->sede_descripcion = $request->input('sede_descripcion');
        $sede->sede_direccion = $request->input('direccion');
        $sede->sede_meta_estudiantes = $request->input('meta_estudiantes');
        $sede->sede_meta_docentes = $request->input('meta_docentes');
        $sede->sede_meta_socios = $request->input('meta_socios');
        $sede->sede_meta_iniciativas = $request->input('meta_iniciativas');
        $sede->sede_meta_beneficiarios = $request->input('meta_beneficiarios');
        $sede->sede_meta_egresados = $request->input('meta_egresados');
        $sede->sede_meta_serv = $request->input('sede_meta_serv');
        $sede->sede_meta_ext = $request->input('sede_meta_ext');

        // Obtener los datos de la sesión
        $sede->sede_visible = $request->input('sede_visible', 1);
        $sede->sede_creado = Session::get('sede_creado');
        $sede->sede_actualizado = Session::get('sede_actualizado');
        $sede->sede_nickname_mod = Session::get('admin')->usua_nickname;
        $sede->sede_rol_mod = Session::get('admin')->rous_codigo;

        // Guardar la sede en la base de datos
        $sede->save();
        $mensaje = 'La sede "'. $sede->sede_nombre . '" fue creada correctamente.';
        // Redireccionar o realizar alguna acción adicional si es necesario
        return redirect()->back()->with('exitoSede', $mensaje);
    }

    public function eliminarSedes(Request $request)
    {
        $nombresede = Sedes::select('sede_nombre')->where('sede_codigo', $request->sedecodigo)->first();
        $verificarDrop = Sedes::where('sede_codigo', $request->sedecodigo)->first();

        if (!$verificarDrop) {
            return redirect()->route('admin.listar.sedes')->with('errorSede', 'La sede no se encuentra registrada en el sistema.');
        }

        $sededrop = Sedes::where('sede_codigo', $request->sedecodigo)->delete();
        if (!$sededrop) {
            return redirect()->back()->with('errorSede', 'Ocurrió un error en el sistema.');
        }

        $mensaje = 'La sede "'. $nombresede->sede_nombre . '" fue eliminada correctamente.';

        return redirect()->route('admin.listar.sedes')->with('exitoSede', $mensaje);
    }

    public function actualizarSedes(Request $request, $sede_codigo)
    {
        $sede = Sedes::find($sede_codigo);

        if (!$sede) {
            return redirect()->route('admin.listar.sedes')->with('errorSede', 'El campus no se encuentra registrado en el sistema.');
        }

        // Validar los datos enviados en el formulario
        $validatedData = $request->validate([
            'sede_nombre' => 'required|string',
            // 'meta_estudiantes' => 'required|numeric',
            // 'meta_docentes' => 'required|numeric',
            /* 'sede_meta_socios' => 'required|numeric',
            'sede_meta_iniciativas' => 'required|numeric', */
        ], [
            'sede_nombre.required' => 'El campo Nombre de la sede es requerido.',
            // 'meta_estudiantes.required' => 'El campo Estudiantes es requerido.',
            // 'meta_docentes.required' => 'El campo Docentes es requerido.',
            /* 'sede_meta_socios.required' => 'El campo Socios es requerido.',
            'sede_meta_iniciativas.required' => 'El campo Iniciativas es requerido.', */
            // 'meta_estudiantes.numeric' => 'El campo Estudiantes debe ser numérico.',
            // 'meta_docentes.numeric' => 'El campo Docentes debe ser numérico.',
            /* 'sede_meta_socios.numeric' => 'El campo Socios debe ser numérico.',
            'sede_meta_iniciativas.numeric' => 'El campo Iniciativas debe ser numérico.', */
        ]);

        $sede->sede_nombre = $request->input('sede_nombre');
        $sede->sede_descripcion = $request->input('sede_descripcion');
        $sede->sede_direccion = $request->input('direccion');
        $sede->sede_meta_estudiantes = $request->input('meta_estudiantes');
        $sede->sede_meta_docentes = $request->input('meta_docentes');
        $sede->sede_meta_socios = $request->input('meta_socios');
        $sede->sede_meta_iniciativas = $request->input('meta_iniciativas');
        $sede->sede_meta_beneficiarios = $request->input('meta_beneficiarios');
        $sede->sede_meta_egresados = $request->input('meta_egresados');
        $sede->sede_meta_serv = $request->input('sede_meta_serv');
        $sede->sede_meta_ext = $request->input('sede_meta_ext');

        // Resto de la lógica para actualizar la sede
        $sede->save(); // Guardar los cambios en la base de datos

        $mensaje = 'La sede "'. $sede->sede_nombre . '" fue actualizada correctamente.';

        return redirect()->route('admin.listar.sedes')->with('exitoSede', $mensaje);
    }

    //TODO: Parametro Dispositivo

    public function listarDispositivos()
    {
        $asignaturas = Asignaturas::orderBy('id', 'asc')->get();
        $carreras = Carreras::orderBy('care_codigo', 'asc')->get();

        $carrerasAsignaturas = CarrerasAsignaturas::all();
        $instrumentos = TipoActividades::orderBy('tiac_codigo', 'asc')->get();
        $dispositivosTiac = DispositivosTiac::all();

        $dispositivos = Dispositivos::orderBy('id', 'asc')->get();
        return view('admin.parametros.dispositivos', [
            'dispositivos' => $dispositivos,
            'asignaturas' => $asignaturas,
            'carreras' => $carreras,
            'carrerasAsignaturas' => $carrerasAsignaturas,
            'instrumentos' => $instrumentos,
            'dispositivosTiac' => $dispositivosTiac,


        ]);
    }
    public function crearDispositivo(Request $request)
    {
        $request->validate([
            'dispositivo_nombre' => 'required|max:255',
        ], [
            'dispositivo_nombre.required' => 'El nombre del dispositivo es requerido.',
            'dispositivo_nombre.max' => 'El nombre del dispositivo excede el máximo de caracteres permitidos (255).',
        ]);

        $dispositivo = new Dispositivos();
        $dispositivo->nombre = $request->dispositivo_nombre;
        $dispositivo->tiac_codigo = $request->tiac_codigo;
        $dispositivo->meta_adm = $request->input('meta_adm') ?? null;
        $dispositivo->meta_edu = $request->input('meta_edu') ?? null;
        $dispositivo->meta_salud = $request->input('meta_salud') ?? null;
        $dispositivo->meta_tec = $request->input('meta_tec') ?? null;
        $dispositivo->meta_gastr = $request->input('meta_gastr') ?? null;
        $dispositivo->meta_inf = $request->input('meta_inf') ?? null;
        $dispositivo->meta_const = $request->input('meta_const') ?? null;
        $dispositivo->meta_desa = $request->input('meta_desa') ?? null;
        $dispositivo->save();

        return redirect()->back()->with('exitoDispositivo', 'Dispositivo creado exitosamente');
    }

    public function  actualizarDispositivo(Request $request, $dispositivo_id)
    {
        $dispositivo = Dispositivos::where('id', $dispositivo_id)->first();
        $dispositivo->nombre = $request->asignatura_nombre;
        $dispositivo->tiac_codigo = $request->tiac_codigo;
        $dispositivo->meta_adm = $request->input('meta_adm') ?? null;
        $dispositivo->meta_edu = $request->input('meta_edu') ?? null;
        $dispositivo->meta_salud = $request->input('meta_salud') ?? null;
        $dispositivo->meta_tec = $request->input('meta_tec') ?? null;
        $dispositivo->meta_gastr = $request->input('meta_gastr') ?? null;
        $dispositivo->meta_inf = $request->input('meta_inf') ?? null;
        $dispositivo->meta_const = $request->input('meta_const') ?? null;
        $dispositivo->meta_desa = $request->input('meta_desa') ?? null;
        $dispositivo->save();

        return redirect()->back()->with('exitoDispositivo', 'Dispositivo actualizado exitosamente');
    }


    public function eliminarDispositivo(Request $request)
    {
        $dispositivo = Dispositivos::where('id', $request->dispositivo_id)->first();
        $dispositivo->delete();
        return redirect()->route('admin.listar.dispositivos')->with('exitoDispositivo', 'Dispositivo eliminado correctamente.');
    }

    //TODO: Parametro asignaturas
    public function listarAsignaturas()
    {
        $asignaturas = Asignaturas::orderBy('id', 'asc')->get();
        $carreras = Carreras::orderBy('care_codigo', 'asc')->get();

        $carrerasAsignaturas = CarrerasAsignaturas::all();



        return view('admin.parametros.asignaturas', [
            'asignaturas' => $asignaturas,
            'carreras' => $carreras,
            'carrerasAsignaturas' => $carrerasAsignaturas,
        ]);
    }



    public function crearAsignatura(Request $request)
    {
        if ($request->asignatura_nombre == null) {
            return redirect()->back()->with('errorAsignatura', 'El nombre de la asignatura es requerido.');
        }

        $asignatura = new Asignaturas();
        $asignatura->nombre = $request->asignatura_nombre;
        $asignatura->descripcion = $request->descripcion;
        $asignatura->save();
        $idAsignatura = $asignatura->id;

        foreach ($request->carreras as $carrera) {

            $carrerasAsignaturas = new CarrerasAsignaturas();
            $carrerasAsignaturas->care_codigo = $carrera;
            $carrerasAsignaturas->asignatura_id = $idAsignatura;
            $carrerasAsignaturas->save();
        }
        return redirect()->back()->with('exitoAsignatura', 'Asignatura creada exitosamente');
    }

    public function actualizarAsignatura(Request $request, $asignatura_id)
    {

        $asignatura = Asignaturas::where('id', $asignatura_id)->first();
        $carrerasAsignaturas = CarrerasAsignaturas::where('asignatura_id', $asignatura_id)->get();
        //eliminar carreras asignaturas
        foreach ($carrerasAsignaturas as $carreraAsignatura) {
            $carreraAsignatura->delete();
        }
        //acturalizar carreras asignaturas
        foreach ($request->carreras as $carrera) {
            $carrerasAsignaturas = new CarrerasAsignaturas();
            $carrerasAsignaturas->care_codigo = $carrera;
            $carrerasAsignaturas->asignatura_id = $asignatura_id;
            $carrerasAsignaturas->save();
        }
        //actualizar asignatura
        $asignatura->nombre = $request->asignatura_nombre;
        $asignatura->descripcion = $request->descripcion;
        $asignatura->save();

        return redirect()->back()->with('exitoAsignatura', 'Asignatura actualizada exitosamente');
    }

    public function eliminarAsignatura(Request $request)
    {
        $asignatura = Asignaturas::where('id', $request->asignatura_id)->first();
        $carrerasAsignaturas = CarrerasAsignaturas::where('asignatura_id', $request->asignatura_id)->get();

        if (!$asignatura) {
            return redirect()->route('admin.listar.asignaturas')->with('errorAsignatura', 'La asignatura no se encuentra registrada en el sistema.');
        }
        //se eliminan las carreras asociadas a esa asignatura
        foreach ($carrerasAsignaturas as $carreraAsignatura) {
            $carreraAsignatura->delete();
        }
        //se elimina la asignatura
        $asignatura->delete();


        return redirect()->route('admin.listar.asignaturas')->with('exitoAsignatura', 'La asignatura fue eliminada correctamente.');
    }

    //TODO: Parametro asignaturas
    public function listarCentroSimulacion()
    {
        $centroSimulacion = CentroSimulacion::orderBy('cs_codigo', 'asc')->get();
        return view('admin.parametros.centroSimulacion', [
            'centroSimulacion' => $centroSimulacion,
        ]);
    }



    public function crearCentroSimulacion(Request $request)
    {
        if ($request->cs_nombre == null) {
            return redirect()->back()->with('errorAsignatura', 'El nombre del centro de simulacion es requerido.');
        }

        $centroSimulacion = new CentroSimulacion();
        $centroSimulacion->cs_nombre = $request->cs_nombre;
        $centroSimulacion->save();
        return redirect()->back()->with('exitoAsignatura', 'Centro de simulación creado exitosamente');
    }

    public function actualizarCentroSimulacion(Request $request, $cs_codigo)
    {

        $centroSimulacion = CentroSimulacion::where('cs_codigo', $cs_codigo)->first();

        //actualizar asignatura
        $centroSimulacion->cs_nombre = $request->cs_nombre;
        $centroSimulacion->save();

        return redirect()->back()->with('exitoAsignatura', 'Centro de simulación actualizado exitosamente');
    }

    public function eliminarCentroSimulacion(Request $request)
    {

        $centroSimulacion = CentroSimulacion::where('cs_codigo', $request->cs_codigo)->first();
        $InciativasCentroSimulacion = IniciativasCentroSimulacion::where('cs_codigo', $request->cs_codigo)->get();

        if (!$centroSimulacion) {
            return redirect()->route('admin.listar.centro-simulacion')->with('errorAsignatura', 'El centro de simulación no se encuentra registrada en el sistema.');
        }
        //se eliminan las carreras asociadas a esa asignatura
        foreach ($InciativasCentroSimulacion as $cs) {
            $cs->delete();
        }
        //se elimina la asignatura
        $centroSimulacion->delete();


        return redirect()->route('admin.listar.centro-simulacion')->with('exitoAsignatura', 'El centro de simulación fue eliminado correctamente.');
    }

    //TODO: INICIO Centro de costos

    public function listarCentroCostos()
    {
        $centroCostos = CentroCostos::select('ceco_codigo', 'ceco_nombre', 'ceco_visible')
            ->orderBy('ceco_codigo', 'asc')
            ->get();
        return view('admin.parametros.centrocostos', compact('centroCostos'));
    }

    public function crearCentroCostos(Request $request)
    {
        if ($request->ceco_nombre == null) {
            return redirect()->back()->with('errorCentroCosto', 'El nombre del centro de costos es requerido.');
        }

        $centroCostos = new CentroCostos();
        $centroCostos->ceco_nombre = $request->ceco_nombre;
        $centroCostos->ceco_visible = 1;
        $centroCostos->ceco_creado = now();
        $centroCostos->ceco_actualizado = now();
        $user = Session::get('admin') ?? Session::get('digitador');
        if ($user) {
            $centroCostos->ceco_nickname_mod = $user->usua_nickname;
            $centroCostos->ceco_rol_mod = $user->rous_codigo;
        }
        $centroCostos->save();
        return redirect()->back()->with('exitoCentroCosto', 'Centro de costos creado exitosamente');
    }
    public function actualizarCentroCostos(Request $request, $ceco_codigo)
    {

        $centroCostos = CentroCostos::where('ceco_codigo', $ceco_codigo)->first();

        //actualizar asignatura
        $centroCostos->ceco_nombre = $request->ceco_nombre;
        $centroCostos->ceco_actualizado = now();
        $user = Session::get('admin') ?? Session::get('digitador');
        if ($user) {
            $centroCostos->ceco_nickname_mod = $user->usua_nickname;
            $centroCostos->ceco_rol_mod = $user->rous_codigo;
        }
        $centroCostos->save();

        return redirect()->back()->with('exitoCentroCosto', 'Centro de costos actualizado exitosamente');
    }

    public function eliminarCentroCosotos(Request $request)
    {

        $centroCostos = CentroCostos::where('ceco_codigo', $request->ceco_codigo)->first();

        if (!$centroCostos) {
            return redirect()->route('admin.listar.ccostos')->with('errorCentroCostos', 'El centro de costos no se encuentra registrada en el sistema.');
        }

        $CostosDinero = CostosDinero::where('ceco_codigo',$request->ceco_codigo)->delete();
        $CostosInfraestructura = CostosInfraestructura::where('ceco_codigo',$request->ceco_codigo)->delete();
        $CostosRrhh = CostosRrhh::where('ceco_codigo',$request->ceco_codigo)->delete();

        //se elimina la asignatura
        $centroCostos->delete();


        return redirect()->route('admin.listar.ccostos')->with('exitoCentroCostos', 'El centro de simulación fue eliminado correctamente.');
    }
    //TODO: FIN Centro de costos

    //TODO: Parametro Carreras
    public function listarCarreras()
    {
        $carreras = Carreras::orderBy('care_codigo', 'asc')->get();
        $escuelas = Escuelas::orderBy('escu_codigo', 'asc')->get();
        $aes = AreaEspecialidad::orderBy('aes_codigo', 'asc')->get();

        return view('admin.parametros.carreras', [
            'carreras' => $carreras,
            'escuelas' => $escuelas,
            'aes' => $aes
        ]);
    }

    public function listarAes()
    {
        $carreras = Carreras::orderBy('care_codigo', 'asc')->get();
        $escuelas = Escuelas::orderBy('escu_codigo', 'asc')->get();

        $aes = AreaEspecialidad::orderBy('aes_codigo', 'asc')->get();

        return view('admin.parametros.areaespecialidad', [
            'carreras' => $carreras,
            'escuelas' => $escuelas,
            'aes' => $aes
        ]);
    }
    public function eliminarAes(Request $request)
    {
        $verificarDrop = AreaEspecialidad::where('aes_codigo', $request->aes_codigo)->first();

        if (!$verificarDrop) {
            return redirect()->route('admin.listar.aespecialidad')->with('errorCarrera', 'El área no se encuentra registrada en el sistema.');
        }
        $Drop = AreaEspecialidad::where('aes_codigo', $request->aes_codigo)->delete();
        if (!$Drop) {
            return redirect()->back()->with('errorCarrera', 'El área no se pudo eliminar, intente más tarde.');
        }

        return redirect()->route('admin.listar.aespecialidad')->with('exitoCarrera', 'El área fue eliminada correctamente.');
    }


    public function eliminarCarreras(Request $request)
    {
        $verificarDrop = Carreras::where('care_codigo', $request->care_codigo)->first();
        if (!$verificarDrop) {
            return redirect()->route('admin.listar.carreras')->with('errorCarrera', 'La carrera no se encuentra registrada en el sistema.');
        }
        $Drop = Carreras::where('care_codigo', $request->care_codigo)->delete();
        if (!$Drop) {
            return redirect()->back()->with('errorCarrera', 'La carrera no se pudo eliminar, intente más tarde.');
        }

        return redirect()->route('admin.listar.carreras')->with('exitoCarrera', 'La carrera fue eliminada correctamente.');
    }

    public function actualizarCarrera(Request $request, $care_codigo)
    {

        // Obtener la carrera por su código
        $carrera = Carreras::where('care_codigo', $care_codigo)->first();

        // Verificar si la carrera existe
        if (!$carrera) {
            return redirect()->back()->with('errorCarrera', 'La carrera no se encuentra registrada en el sistema.');
        }

        $validacion = $request->validate([
            'care_nombre' => 'required|max:255',
            /* 'care_director' => 'required|max:100', */
            /* 'care_institucion' => 'required|max:100', */
            'escu_codigo' => 'required',
        ], [
            'care_nombre.required' => 'El nombre es requerido.',
            'care_nombre.max' => 'El nombre excede el máximo de caracteres permitidos (255).',
            /* 'care_director.required' => 'El nombre del director es requerido.',
            'care_director.max' => 'El nombre del director excede el máximo de caracteres permitidos (100).', */
            /* 'care_institucion.required' => 'El nombre de la institución es requerido.',
            'care_institucion.max' => 'El nombre de la institución excede el máximo de caracteres permitidos (100).', */
            'escu_codigo.required' => 'Seleccione una escuela.',
        ]);

        if (!$validacion) {
            return redirect()->back()->with('errorCarrera', 'Problemas al actualizar la carrera.');
        }

        // Actualizar los campos de la carrera con los valores del formulario
        $carrera->care_nombre = $request->input('care_nombre');
        $carrera->care_descripcion = $request->input('care_descripcion');
        $carrera->escu_codigo = $request->input('escu_codigo');
        $carrera->aes_codigo = $request->input('aes_codigo');
        $carrera->care_meta_estudiantes = $request->input('meta_estudiantes');
        $carrera->care_meta_docentes = $request->input('meta_docentes');
        $carrera->care_meta_soc_comunitarios = $request->input('meta_comunitarios');
        $carrera->care_meta_benificiarios = $request->input('meta_benicifiarios');
        $carrera->care_meta_Iniciativas = $request->input('meta_iniciativas');

        // Guardar los cambios en la carrera
        $carrera->save();

        return redirect()->back()->with('exitoCarrera', 'La carrera ha sido actualizada correctamente.');
    }

    public function actualizarAes(Request $request, $aes_codigo)
    {
        // Obtener la carrera por su código
        $aes = AreaEspecialidad::where('aes_codigo', $aes_codigo)->first();

        // Verificar si la carrera existe
        if (!$aes) {
            return redirect()->back()->with('errorCarrera', 'El área de especialidad no se encuentra registrada en el sistema.');
        }

        $validacion = $request->validate([
            'aes_nombre' => 'required|max:255',
        ], [
            'aes_nombre.required' => 'El nombre es requerido.',
            'aes_nombre.max' => 'El nombre excede el máximo de caracteres permitidos (255).'
        ]);

        if (!$validacion) {
            return redirect()->back()->with('errorCarrera', 'Problemas al actualizar el área.');
        }

        $aes->aes_nombre = $request->input('aes_nombre');
        $aes->save();

        return redirect()->back()->with('exitoCarrera', 'El área ha sido actualizada correctamente.');
    }


    public function crearCarreras(Request $request)
    {
        $validacion = $request->validate([
            'care_nombre' => 'required|max:255',
            /* 'care_director' => 'required|max:100', */
            /* 'care_institucion' => 'required|max:100', */
            'escu_codigo' => 'required',
        ], [
            'care_nombre.required' => 'El nombre es requerido.',
            'care_nombre.max' => 'El nombre excede el máximo de caracteres permitidos (255).',
            /* 'care_director.required' => 'El nombre del director es requerido.',
            'care_director.max' => 'El nombre del director excede el máximo de caracteres permitidos (100).', */
            /* 'care_institucion.required' => 'El nombre de la institución es requerido.',
            'care_institucion.max' => 'El nombre de la institución excede el máximo de caracteres permitidos (100).', */
            'escu_codigo.required' => 'Seleccione una escuela.',
        ]);

        if (!$validacion) {
            return redirect()->route('admin.listar.escuelas')->with('errorEscuela', 'Problemas al crear la escuela.');
        }

        $carrera = new Carreras();
        $carrera->care_nombre = $request->input('care_nombre');
        $carrera->care_descripcion = $request->input('care_descripcion');
        $carrera->escu_codigo = $request->input('escu_codigo');
        $carrera->aes_codigo = $request->input('aes_codigo');
        $carrera->care_meta_estudiantes = $request->input('meta_estudiantes');
        $carrera->care_meta_docentes = $request->input('meta_docentes');
        $carrera->care_meta_soc_comunitarios = $request->input('meta_comunitarios');
        $carrera->care_meta_benificiarios = $request->input('meta_benicifiarios');
        $carrera->care_meta_Iniciativas = $request->input('meta_iniciativas');
        $carrera->care_creado = now();
        // $carrera->care_director = $request->input('care_director');

        // Guardar la carrera en la base de datos
        $carrera->save();

        return redirect()->back()->with('exitoCarrera', 'Carrera creada exitosamente');
    }

    public function crearAes(Request $request)
    {
        $validacion = $request->validate([
            'aes_nombre' => 'required|max:255',
        ], [
            'aes_nombre.required' => 'El nombre es requerido.',
            'aes_nombre.max' => 'El nombre excede el máximo de caracteres permitidos (255).'
        ]);

        if (!$validacion) {
            return redirect()->route('admin.listar.escuelas')->with('errorEscuela', 'Problemas al crear el área.');
        }

        $aes = new AreaEspecialidad();
        $aes->aes_nombre = $request->input('aes_nombre');

        // Guardar la aes en la base de datos
        $aes->save();

        return redirect()->back()->with('exitoCarrera', 'Área creada exitosamente');
    }


    //TODO: Parametro Escuelas
    public function listarEscuelas()
    {

        return view('admin.parametros.escuelas', [
            'escuelas' => Escuelas::orderBy('escu_codigo', 'asc')->get(),
            'sedes' => Sedes::all(),
            'sedesxescuelas' => SedesEscuelas::all(),
        ]);
    }

    public function eliminarEscuelas(Request $request)
    {
        $verificarDrop = Escuelas::where('escu_codigo', $request->escu_codigo)->first();
        $nombre = Escuelas::select('escu_nombre')->where('escu_codigo', $request->escu_codigo)->first();
        if (!$verificarDrop) {
            return redirect()->route('admin.listar.escuelas')->with('error', 'La unidad no se encuentra registrada en el sistema.');
        }
        /*
                $verificar = Carreras::select('escu_codigo')->where('escu_codigo', $request->escu_codigo);
                if ($verificar) {
                    return redirect()->route('admin.listar.escuelas')->with('errorEscuela', 'No es posible eliminar, la escuela está siendo utilizada en una carrera');
                } */
        $drop = SedesEscuelas::where('escu_codigo', $request->escu_codigo)->delete();

        $Drop = Escuelas::where('escu_codigo', $request->escu_codigo)->delete();
        if (!$Drop) {
            return redirect()->back()->with('error', 'La unidad no se pudo eliminar, intente más tarde.');
        }

        $mensaje = 'La unidad "'. $nombre->escu_nombre . '" fue eliminada correctamente.';
        return redirect()->route('admin.listar.escuelas')->with('exito', $mensaje);
    }

    public function actualizarEscuelas(Request $request, $escu_codigo)
    {
        // Obtener la escuela por su código
        $escuela = Escuelas::where('escu_codigo', $escu_codigo)->first();
        $drop = SedesEscuelas::where('escu_codigo', $escu_codigo)->delete();

        // Verificar si la escuela existe
        if (!$escuela) {
            return redirect()->back()->with('error', 'La unidad no se encuentra registrada en el sistema.');
        }

        // Validar los campos del formulario
        $validacion = $request->validate([
            'escu_nombre' => 'required|max:255',
            'escu_director' => 'required|max:255',
        ], [
            'escu_nombre.required' => 'El nombre del área es requerido.',
            'escu_nombre.max' => 'El nombre del área excede el máximo de caracteres permitidos (255).',
            'escu_director.required' => 'El nombre del director es requerido.',
            'escu_director.max' => 'El nombre del director excede el máximo de caracteres permitidos (255).',
        ]);

        // Actualizar los campos de la escuela con los valores del formulario
        $escuela->escu_nombre = $request->input('escu_nombre');
        $escuela->escu_descripcion = $request->input('descripcion');
        $escuela->escu_director = $request->input('escu_director');
        $escuela->escu_meta_serv = $request->input('escu_meta_serv');
        $escuela->escu_meta_ext = $request->input('escu_meta_ext');
        $escuela->escu_meta_con = $request->input('escu_meta_con');
        $escuela->escu_meta_red = $request->input('escu_meta_red');

        // actualizar tabla metas: servicio disciplinar
        $meta_serv = DB::table('metas')
                    ->where('escu_codigo', $escu_codigo)
                    ->where('tiac_codigo', 5)
                    ->first();
        if($meta_serv){
            $meta_serv = DB::table('metas')
                   ->where('escu_codigo', $escu_codigo)
                   ->where('tiac_codigo', 5)
                   ->update(['meta' => $request->input('escu_meta_serv')]);
        }
        // actualizar tabla metas: extensión academica
        $meta_ext = DB::table('metas')
                        ->where('escu_codigo', $escu_codigo)
                        ->where('tiac_codigo', 3)
                        ->first();
        if ($meta_ext) {
        DB::table('metas')
            ->where('escu_codigo', $escu_codigo)
            ->where('tiac_codigo', 3)
            ->update(['meta' => $request->input('escu_meta_ext')]);
        }

        // actualizar tabla metas: consejos consultivos
        $meta_con = DB::table('metas')
                        ->where('escu_codigo', $escu_codigo)
                        ->where('tiac_codigo', 1)
                        ->first();
        if ($meta_con) {
                DB::table('metas')
                    ->where('escu_codigo', $escu_codigo)
                    ->where('tiac_codigo', 1)
                    ->update(['meta' => $request->input('escu_meta_con')]);
            }

    // actualizar tabla metas: red laboral
        $meta_red = DB::table('metas')
                        ->where('escu_codigo', $escu_codigo)
                        ->where('tiac_codigo', 2)
                        ->first();
        if ($meta_red) {
        DB::table('metas')
            ->where('escu_codigo', $escu_codigo)
            ->where('tiac_codigo', 2)
            ->update(['meta' => $request->input('escu_meta_red')]);
        }
        // Guardar los cambios en la escuela
        $escuela->save();

        $sed = [];
        $seds = $request->input('sedes', []);

        foreach ($seds as $se) {
            array_push(
                $sed,
                [
                    'sede_codigo' => $se,
                    'escu_codigo' => $escu_codigo,
                    'seec_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                    'seed_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                    'seec_nickname_mod' => Session::get('admin')->usua_nickname,
                    'seec_rol_mod' => Session::get('admin')->rous_codigo,
                ]
            );
        }

        $relacCrear = SedesEscuelas::insert($sed);
        $mensaje = 'La unidad "'. $escuela->escu_nombre . '" fue actualizada correctamente.';
        return redirect()->back()->with('exito', $mensaje);
    }


    public function crearEscuelas(Request $request)
    {
        $validacion = $request->validate(
            [
                'nombre' => 'required|max:255',
                'director' => 'required|max:100',
            ],
            [
                'nombre.required' => 'El nombre es requerido.',
                'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (255).',
                'director.required' => 'El nombre del director es requerido.',
                'director.max' => 'El nombre del director excede el máximo de caracteres permitidos (100).',
            ]
        );
        if (!$validacion)
            return redirect()->route('admin.listar.escuelas')->with('error', 'Problemas al crear la unidad.');

        //$escuela = new Escuelas();
        ///* $escuela->escu_codigo = Escuelas::count() + 1; *///TODO: ERROR DE ESCUELA
        //$escuela->escu_nombre = $request->input('nombre');
        //$escuela->escu_descripcion = $request->input('descripcion');
        //$escuela->escu_director = $request->input('director');
        ///* $escuela->escu_intitucion = $request->input('institucion',1); */
        //
        //$escuela->escu_visible = $request->input('care_visible', 1);
        ////TODO: SI NO QUEREMOS MORIR, CAMBIAR ESTO
        //$escuela->escu_creado = now();
        //$escuela->escu_actualizado = now();
        //
        //$escuela->escu_nikcname_mod = Session::get('admin')->usua_nickname;
        //$escuela->escu_rol_mod = Session::get('admin')->rous_codigo;
        //
        //$escuela->save();

        $escuela = Escuelas::insertGetId([
            'escu_nombre' => $request->input('nombre'),
            'escu_descripcion' => $request->input('descripcion'),
            'escu_director' => $request->input('director'),
            'escu_visible' => 1,
            'escu_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'escu_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'escu_nikcname_mod' => Session::get('admin')->usua_nickname,
            'escu_rol_mod' => Session::get('admin')->rous_codigo,
            'escu_meta_serv' => $request->input('escu_meta_serv'),
            'escu_meta_ext' => $request->input('escu_meta_ext'),
            'escu_meta_con' => $request->input('escu_meta_con'),
            'escu_meta_red' => $request->input('escu_meta_red'),
        ]);

        $sed = [];
        $seds = $request->input('sedes', []);

        foreach ($seds as $se) {
            array_push(
                $sed,
                [
                    'sede_codigo' => $se,
                    'escu_codigo' => $escuela,
                    'seec_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                    'seed_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                    'seec_nickname_mod' => Session::get('admin')->usua_nickname,
                    'seec_rol_mod' => Session::get('admin')->rous_codigo,
                ]
            );
        }

        $relacCrear = SedesEscuelas::insert($sed);
        $mensaje = 'La unidad "'. $request->input('nombre') . '" fue creada correctamente.';

        return redirect()->back()->with('exito', $mensaje);
    }

    //TODO: Parametro Sociso COmunitarios
    public function listarSocios()
    {
        $socios = SociosComunitarios::orderBy('soco_codigo', 'asc')->get();
        $sedesT = Sedes::orderBy('sede_codigo', 'asc')->get();
        $SedeSocios = SedesSocios::all();
        $grupos = GruposInteres::orderBy('grin_codigo', 'asc')->get();
        $subgrupos = SubGruposInteres::all();
        return view('admin.parametros.socios', compact('sedesT', 'socios', 'SedeSocios', 'grupos', 'subgrupos'));
    }
    public function subgruposBygrupos(Request $request)
    {
        $subgrupo = SubGruposInteres::where('grin_codigo', $request->grin_codigo)->get();

        return response()->json($subgrupo);
    }

    public function eliminarSocios(Request $request)
    {
        $verificarDrop = SociosComunitarios::where('soco_codigo', $request->soco_codigo)->first();
        if (!$verificarDrop) {
            return redirect()->route('admin.listar.socios')->with('errorSocio', 'El socio comunitario no se encuentra registrado en el sistema.');
        }
        /* $Drop = SedesSocios::where('soco_codigo', $request->soco_codigo)->delete(); */
        $Drop = SociosComunitarios::where('soco_codigo', $request->soco_codigo)->delete();
        if (!$Drop) {
            return redirect()->back()->with('errorSocio', 'El socio comunitario no se pudo eliminar, intente más tarde.');
        }
        return redirect()->route('admin.listar.socios')->with('exitoSocio', 'El socio comunitario fue eliminado correctamente.');
    }

    public function actualizarSocios(Request $request, $soco_codigo)
    {
        // Obtener la escuela por su código
        $socio = SociosComunitarios::where('soco_codigo', $soco_codigo)->first();

        // Verificar si la escuela existe
        if (!$socio) {
            return redirect()->back()->with('errorSocio', 'El socio comunitario no se encuentra registrado en el sistema.');
        }

        // Validar los campos del formulario
        $validacion = $request->validate(
            [
                'nombre' => 'required|max:255',
                'nombre_contraparte' => 'required|max:255',
                'subgrupo' => 'required'
                /* 'domicilio' => 'required|max:255', */
                /* 'telefono' => 'required|max:255', */
                /* 'email' => 'required|max:255', */
                /* 'sedesT' => 'required_without_all:nacional', // 'sedesT' es requerido si 'nacional' no está marcado
                'nacional' => 'required_without_all:sedesT', // 'nacional' es requerido si no se selecciona ninguna sede */

            ],
            [
                'nombre.required' => 'El nombre del socio comunitario es requerido.',
                'nombre.max' => 'El nombre del socio comunitario excede el máximo de caracteres permitidos (255).',
                'nombre_contraparte.required' => 'El nombre de la contraparte es requerido.',
                'nombre_contraparte.max' => 'El nombre de la contraparte excede el máximo de caracteres permitidos (255).',
                'subgrupo.required' => 'Es necesario que seleccione un subgrupo de interés.'
                /* 'domicilio.required' => 'El domicilio de la contraparte es requerido.',
                'domicilio.max' => 'El domicilio de la contraparte excede el máximo de caracteres permitidos (255).',
                'telefono.required' => 'El teléfono de la contraparte del director es requerido.',
                'telefono.max' => 'El teléfono de la contraparte excede el máximo de caracteres permitidos (255).',
                'email.required' => 'El email de la contraparte es requerido.',
                'email.max' => 'El email de la contraparte excede el máximo de caracteres permitidos (255).', */
                /* 'sedesT.required_without_all' => 'Es necesario que seleccione al menos una sede a la cual este asociada el socio comunitario.',
                'nacional.required_without_all' => 'Es necesario que seleccione al menos una sede a la cual este asociada el socio comunitario.', */

            ]
        );

        /* $Drop = SedesSocios::where('soco_codigo', $soco_codigo)->delete(); */
        /* if (!$Drop) {
            return redirect()->back()->with('errorSocio', $soco_codigo);
        } */
        $socio = SociosComunitarios::where(['soco_codigo' => $soco_codigo])->update([
            'grin_codigo' => $request->input('grupo'),
            'sugr_codigo' => $request->input('subgrupo'),
            'soco_nombre_socio' => $request->input('nombre'),
            'soco_nombre_contraparte' => $request->input('nombre_contraparte'),
            'soco_domicilio_socio' => $request->input('domicilio'),
            'soco_telefono_contraparte' => $request->input('telefono'),
            'soco_email_contraparte' => $request->input('email'),
        ]);


        return redirect()->back()->with('exitoSocio', 'El socio comunitario ha sido actualizado correctamente.')->withInput();
    }


    public function crearSocios(Request $request)
    {
        $validacion = $request->validate(
            [
                'nombre' => 'required|max:255',
                'nombre_contraparte' => 'required|max:255',
                /* 'domicilio' => 'required|max:255', */
                /* 'telefono' => 'required|max:255', */
                /* 'email' => 'required|max:255', */
                /* 'sedesT' => 'required_without_all:nacional', // 'sedesT' es requerido si 'nacional' no está marcado
                'nacional' => 'required_without_all:sedesT', // 'nacional' es requerido si no se selecciona ninguna sede */

            ],
            [
                'nombre.required' => 'El nombre del socio comunitario es requerido.',
                'nombre.max' => 'El nombre del socio comunitario excede el máximo de caracteres permitidos (255).',
                'nombre_contraparte.required' => 'El nombre de la contraparte es requerido.',
                'nombre_contraparte.max' => 'El nombre de la contraparte excede el máximo de caracteres permitidos (255).',
                /* 'domicilio.required' => 'El domicilio de la contraparte es requerido.',
                'domicilio.max' => 'El domicilio de la contraparte excede el máximo de caracteres permitidos (255).',
                'telefono.required' => 'El teléfono de la contraparte del director es requerido.',
                'telefono.max' => 'El teléfono de la contraparte excede el máximo de caracteres permitidos (255).',
                'email.required' => 'El email de la contraparte es requerido.',
                'email.max' => 'El email de la contraparte excede el máximo de caracteres permitidos (255).', */
                /* 'sedesT.required_without_all' => 'Es necesario que seleccione al menos una sede a la cual este asociada el socio comunitario.',
                'nacional.required_without_all' => 'Es necesario que seleccione al menos una sede a la cual este asociada el socio comunitario.', */

            ]
        );
        if (!$validacion)
            return redirect()->route('admin.listar.socios')->with('error', 'Problemas al crear el socio comunitario.');

        $MacaActi = SociosComunitarios::insertGetId([
            'soco_nombre_socio' => $request->nombre,
            'soco_nombre_contraparte' => $request->nombre_contraparte,
            'soco_domicilio_socio' => $request->domicilio,
            'soco_telefono_contraparte' => $request->telefono,
            'soco_email_contraparte' => $request->email,
            'grin_codigo' => $request->grupo,
            'sugr_codigo' => $request->subgrupo ?? $request->subgrupo2,
        ]);



        return redirect()->back()->with('socoExito', 'Se agregó el socio comunitario correctamente.')->withInput();
    }


    //TODO: funciones de mecanismos para parametrizar
    public function listarMecanismos()
    {
        $mecanismos = Mecanismos::orderBy('meca_codigo', 'asc')->get();
        // $Mecanismos_Actividades = MecanismosActividades::all();
        // $ACTIVIDADES = TipoActividades::all();

        // $tipos = TipoIniciativas::orderBy('tmec_codigo', 'asc')->get();

        return view('admin.parametros.mecanismos', compact('mecanismos'));
    }

    public function crearMecanismos(Request $request)
    {

        $request->validate([
            'meca_nombre' => 'required|max:255',
        ], [
            'meca_nombre.required' => 'El nombre del mecanismo es requerido.',
            'meca_nombre.max' => 'El nombre del mecanismo excede el máximo de caracteres permitidos (255).',
        ]);


        $mecanismo = Mecanismos::insertGetId([
            'meca_nombre' => $request->meca_nombre,
            'tmec_codigo' => $request->tipo,
            'meca_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'meca_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'meca_nickname_mod' => Session::get('admin')->usua_nickname,
            'meca_rol_mod' => Session::get('admin')->rous_codigo,
            // Añade el resto de los campos del modelo si son necesarios.
        ]);
        if (!$mecanismo) {
            return redirect()->back()->with('Mecanismo', 'Ocurrió un error al Crear el mecanismo.')->withInput();
        }
        // $meca_codigo = $mecanismo;
        // $proco = [];
        // $contris = $request->input('actividades', []);
        // foreach ($contris as $activ) {
        //     array_push($proco, [
        //         'meca_codigo' => $meca_codigo,
        //         'tiac_codigo' => $activ,
        //         'meac_creado' => Carbon::now()->format('Y-m-d H:i:s'),
        //         'meac_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
        //         'meac_nickname_mod' => Session::get('admin')->usua_nickname,
        //         'meac_rol_mod' => Session::get('admin')->rous_codigo,
        //     ]);
        // }
        // $procoCrear = MecanismosActividades::insert($proco);
        // if (!$procoCrear) {
        //     ProgramasActividades::where('id_meca', $meca_codigo)->delete();
        //     return redirect()->back()->with('errorMecanismo', 'Ocurrió un error durante el registro de mecanismos, intente más tarde.')->withInput();
        // }
        return redirect()->route('admin.listar.mecanismos')
            ->with('exitoMecanismo', 'Mecanismo creado exitosamente.');
    }


    public function eliminarMecanismos(Request $request)
    {
        $mecanismo = Mecanismos::where('meca_codigo', $request->meca_codigo)->first();

        if (!$mecanismo) {
            return redirect()->route('admin.listar.mecanismos')->with('errorMecanismo', 'El mecanismo no se encuentra registrado en el sistema.');
        }
        $macanimos_actividades = MecanismosActividades::where('meca_codigo', $request->meca_codigo)->delete();
        /*
                $verificar = Iniciativas::select('inic_codigo')->where('meca_codigo', $request->meca_codigo)->first();
                if ($verificar) {
                    return redirect()->route('admin.listar.mecanismos')->with('errorMecanismo', 'No es posible eliminar, el mecanismo está siendo utilizado en una iniciativa');
                }
         */
        $inicMecanismo = Iniciativas::where('meca_codigo', $request->meca_codigo)->get();
        if (sizeof($inicMecanismo) > 0)
            return redirect()->route('admin.listar.mecanismos')->with('errorMecanismo', 'El mecanismo no se puede eliminar porque se encuentra asociado a una iniciativa.');

        $mecanismo->delete();

        return redirect()->route('admin.listar.mecanismos')->with('exitoMecanismo', 'El mecanismo fue eliminado correctamente.');
    }

    public function actualizarMecanismos(Request $request, $meca_codigo)
    {
        $request->validate([
            'meca_nombre' => 'required|max:255',
            // 'actividades' => 'required',
        ], [
            'meca_nombre.required' => 'El nombre del mecanismo es requerido.',
            'meca_nombre.max' => 'El nombre del mecanismo excede el máximo de caracteres permitidos (255).',
            // 'actividades[].required' => 'Un tipo de actividad es necesaria.',
        ]);


        $mecanismo = Mecanismos::find($meca_codigo);

        if (!$mecanismo) {
            return redirect()->route('admin.listar.mecanismos')->with('errorMecanismo', 'El mecanismo no se encuentra registrado en el sistema.');
        }
        $mecanismo->update([
            'meca_nombre' => $request->meca_nombre,
            'tmec_codigo' => $request->tipo,
            'meca_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'meca_nickname_mod' => Session::get('admin')->usua_nickname,
            'meca_rol_mod' => Session::get('admin')->rous_codigo,
        ]);

        return redirect()->route('admin.listar.mecanismos')->with('exitoMecanismo', 'Mecanismo actualizado exitosamente.');
    }


    //TODO: funciones de grupos interes
    public function listarGrupos()
    {
        $grupos_int = GruposInteres::all();
        return view('admin.parametros.grupos', compact('grupos_int'));
    }

    public function crearGrupo(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'grin_nombre' => 'required|max:255',
        ], [
            'grin_nombre.required' => 'El nombre del grupo es requerido.',
            'grin_nombre.max' => 'El nombre del grupo excede el máximo de caracteres permitidos (255).',
        ]);

        if ($validacion->fails()) {
            return redirect()->route('admin.listar.grupos_int')->withErrors($validacion)->withInput();
        }

        $grupo = new GruposInteres();
        $grupo->grin_nombre = $request->input('grin_nombre');
        // Añade el resto de los campos del modelo si son necesarios.
        $grupo->save();

        return redirect()->route('admin.listar.grupos_int')->with('exitoGrupo', 'Grupo de interés creado exitosamente.');
    }

    public function eliminarGrupo(Request $request)
    {
        $grupo = GruposInteres::where('grin_codigo', $request->grin_codigo)->first();

        if (!$grupo) {
            return redirect()->route('admin.listar.grupos_int')->with('errorGrupo', 'El grupo de interés no se encuentra registrado en el sistema.');
        }

        $grupo->delete();

        return redirect()->route('admin.listar.grupos_int')->with('exitoGrupo', 'El grupo de interés fue eliminado correctamente.');
    }

    public function actualizarGrupos(Request $request, $grin_codigo)
    {
        $validacion = Validator::make($request->all(), [
            'grin_nombre' => 'required|max:255',
        ], [
            'grin_nombre.required' => 'El nombre del grupo es requerido.',
            'grin_nombre.max' => 'El nombre del grupo excede el máximo de caracteres permitidos (255).',
        ]);

        if ($validacion->fails()) {
            return redirect()->route('admin.listar.grupos_int')->withErrors($validacion)->withInput();
        }

        $grupo = GruposInteres::find($grin_codigo);

        if (!$grupo) {
            return redirect()->route('admin.listar.grupos_int')->with('errorGrupo', 'El grupo de interés no se encuentra registrado en el sistema.');
        }

        $grupo->grin_nombre = $request->input('grin_nombre');
        // Añade el resto de los campos del modelo si son necesarios.
        $grupo->save();

        return redirect()->route('admin.listar.grupos_int')->with('exitoGrupo', 'Grupo de interés actualizado exitosamente.');
    }

    //TODO: funciones para el tipo de actividad
    public function listarTipoact()
    {
        // Obtener todos los tipos de actividad desde la base de datos
        $tipoact = TipoActividades::all();
        $sedes = Sedes::all();
        $componentes = Componentes::all();
        $tiac_metas = TipoActividadesMetas::all();
        $mecanismos = Mecanismos::all();
        $ambito_accion = AmbitosAccion::all();
        $mecanismos_actividades = [];
        foreach ($tipoact as $actividad) {
            $mecanismos_actividades[$actividad->tiac_codigo] = MecanismosActividades::where('tiac_codigo', $actividad->tiac_codigo)
                ->pluck('meca_codigo')
                ->toArray();
        }

        $tiac_amac = [];
        foreach ($tipoact as $actividad) {
            $tiac_amac[$actividad->tiac_codigo] = TipoActividadAmbitoAccion::where('tiac_codigo', $actividad->tiac_codigo)
                ->pluck('amac_codigo')
                ->toArray();
        }
        // return $mecanismos_actividades;
        return view('admin.parametros.tipoactividad', compact('tipoact', 'sedes', 'componentes', 'tiac_metas', 'mecanismos', 'mecanismos_actividades', 'ambito_accion', 'tiac_amac'));
    }


    public function crearTipoact(Request $request)
    {
        $request->validate([
            'tiac_nombre' => 'required|max:255',
        ]);



        $tiac_creado = TipoActividades::insertGetId([
            'comp_codigo' => $request->input('componente'),
            'tiac_nombre' => $request->input('tiac_nombre'),
            'tiac_meta' => $request->input('meta'),
            'meta_adm' => $request->input('meta_adm') ?? null,
            'meta_edu' => $request->input('meta_edu') ?? null,
            'meta_salud' => $request->input('meta_salud') ?? null,
            'meta_tec' => $request->input('meta_tec') ?? null,
            'meta_gastr' => $request->input('meta_gastr') ?? null,
            'meta_inf' => $request->input('meta_inf') ?? null,
            'meta_const' => $request->input('meta_const') ?? null,
            'meta_desa' => $request->input('meta_desa') ?? null,
            'tiac_visible' => 1,
            'tiac_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'tiac_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'tiac_nickname_mod' => Session::get('admin')->usua_nickname,
            'tiac_rol_mod' => Session::get('admin')->rous_codigo,

        ]);
        $sedess = [];
        $sedes_input = $request->input('sede_codigo', []);
        $metas_input = $request->input('metas', []);

        for ($i = 0; $i < count($sedes_input); $i++) {
            array_push($sedess, [
                'tiac_codigo' => $tiac_creado,
                'sede_codigo' => $sedes_input[$i],
                'tiacme_meta' => $metas_input[$i],
                'tiacme_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                'tiacme_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                'tiacme_nickname_mod' => Session::get('admin')->usua_nickname,
                'tiacme_rol_mod' => Session::get('admin')->rous_codigo,
            ]);
        }
        $tiac_codigo = $tiac_creado;

        $ambito_accion = $request->input('ambito_accion', []);

        $tiac_ambito = [];

        foreach ($ambito_accion as $ambitoaccion) {
            array_push($tiac_ambito, [
                'amac_codigo' => $ambitoaccion,
                'tiac_codigo' => $tiac_codigo,
            ]);
        }
        $tiacamacCrear = TipoActividadAmbitoAccion::insert($tiac_ambito);


        $mecanismos = $request->input('mecanismos', []);
        $mecanismo_actividades = [];

        foreach ($mecanismos as $mecanismo) {
            array_push($mecanismo_actividades, [
                'meca_codigo' => $mecanismo,
                'tiac_codigo' => $tiac_codigo,
                'meac_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                'meac_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                'meac_nickname_mod' => Session::get('admin')->usua_nickname,
                'meac_rol_mod' => Session::get('admin')->rous_codigo,
            ]);
        }
        $procoCrear = MecanismosActividades::insert($mecanismo_actividades);
        $insertar_metas = TipoActividadesMetas::insert($sedess);

        return redirect()->route('admin.listar.tipoact')->with('exitoTipoact', 'El instrumento se creó correctamente.');
    }

    public function actualizarTipoact(Request $request, $tiac_codigo)
    {
        $request->validate([
            'tiac_nombre' => 'required|max:255',
        ]);

        $tipoact = TipoActividades::find($tiac_codigo);
        if (!$tipoact) {
            return redirect()->route('admin.listar.tipoact')->with('errorTipoact', 'Instrumento no encontrado.');
        }

        $tipoact->update([
            'comp_codigo' => $request->input('componente'),
            'tiac_nombre' => $request->input('tiac_nombre'),
            'tiac_meta' => $request->input('meta'),
            'meta_adm' => $request->input('meta_adm') ?? null,
            'meta_edu' => $request->input('meta_edu') ?? null,
            'meta_salud' => $request->input('meta_salud') ?? null,
            'meta_tec' => $request->input('meta_tec') ?? null,
            'meta_gastr' => $request->input('meta_gastr') ?? null,
            'meta_inf' => $request->input('meta_inf') ?? null,
            'meta_const' => $request->input('meta_const') ?? null,
            'meta_desa' => $request->input('meta_desa') ?? null,
            'tiac_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'tiac_nickname_mod' => Session::get('admin')->usua_nickname,
            'tiac_rol_mod' => Session::get('admin')->rous_codigo,
        ]);

        $drop = TipoActividadesMetas::where('tiac_codigo', $tiac_codigo)->delete();
        $sedess = [];
        $sedes_input = $request->input('sede_codigo', []);
        $metas_input = $request->input('metas', []);

        for ($i = 0; $i < count($sedes_input); $i++) {
            array_push($sedess, [
                'tiac_codigo' => $tiac_codigo,
                'sede_codigo' => $sedes_input[$i],
                'tiacme_meta' => $metas_input[$i],
                'tiacme_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                'tiacme_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                'tiacme_nickname_mod' => Session::get('admin')->usua_nickname,
                'tiacme_rol_mod' => Session::get('admin')->rous_codigo,
            ]);
        }

        $mecanismos = $request->input('mecanismos', []);
        $mecanismo_actividades = [];

        foreach ($mecanismos as $mecanismo) {
            array_push($mecanismo_actividades, [
                'meca_codigo' => $mecanismo,
                'tiac_codigo' => $tiac_codigo,
                'meac_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                'meac_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                'meac_nickname_mod' => Session::get('admin')->usua_nickname,
                'meac_rol_mod' => Session::get('admin')->rous_codigo,
            ]);
        }
        $procoCrear = MecanismosActividades::insert($mecanismo_actividades);


        $ambito_accion = $request->input('ambito_accion', []);
        $tiac_amac = [];

        foreach ($ambito_accion as $ambitoaccion) {
            array_push($tiac_amac, [
                'amac_codigo' => $ambitoaccion,
                'tiac_codigo' => $tiac_codigo,
            ]);
        }
        $drop = TipoActividadAmbitoAccion::where('tiac_codigo', $tiac_codigo)->delete();
        $tiac_amac_Crear = TipoActividadAmbitoAccion::insert($tiac_amac);
        $insertar_metas = TipoActividadesMetas::insert($sedess);

        return redirect()->route('admin.listar.tipoact')->with('exitoTipoact', 'El instrumento se actualizó correctamente.');
    }

    public function eliminarTipoact(Request $request)
    {
        $request->validate([
            'tiac_codigo' => 'required|numeric',
        ]);
        try {


            $tipoact = TipoActividades::find($request->input('tiac_codigo'));
            if (!$tipoact) {
                return redirect()->route('admin.listar.tipoact')->with('errorTipoact', 'Instrumento no encontrado.');
            }
            $dropMecaActi = MecanismosActividades::where('tiac_codigo', $request->tiac_codigo)->delete();
            $drop = TipoActividadesMetas::where('tiac_codigo', $request->input('tiac_codigo'))->delete();
            /* $verificar = Iniciativas::select('tiac_codigo')->where('tiac_codigo', $request->tiac_codigo);
            if ($verificar) {
                return redirect()->route('admin.listar.tipoact')->with('errorTipoact', 'No es posible eliminar, el Instrumento está siendo utilizado en una iniciativa');
            } */

            $tipoact->delete();

            return redirect()->route('admin.listar.tipoact')->with('exitoTipoact', 'El instrumento se eliminó correctamente.');
        } catch (\Throwable $th) {
            $codigo_tiac = $request->input('tiac_codigo');
            $iniciativasVinculadas = Iniciativas::where('tiac_codigo', $codigo_tiac)->get();
            //obtener id de las iniciativas vinculadas y mostrarlas en el mensaje
            $errorMessage = 'El instrumento no se puede eliminar porque se encuentra asociado a las siguientes iniciativas con ID: ';
            foreach ($iniciativasVinculadas as $iniciativa) {
                $errorMessage .= $iniciativa->inic_codigo . ', ';
            }
            $errorMessage = substr($errorMessage, 0, -2) . '.';

            $errorMessage .= ' Por favor, elimine las iniciativas vinculadas antes de eliminar el instrumento.';

            return redirect()->route('admin.listar.tipoact')->with('errorTipoact', $errorMessage);
        }
    }

    //TODO: funciones de tematicas
    public function listarTematica()
    {
        $tematica = Tematicas::all();
        return view('admin.parametros.tematica', compact('tematica'));
    }

    public function crearTematica(Request $request)
    {
        $request->validate([
            'tema_nombre' => 'required|max:255|unique:tematicas'
        ]);

        $tematica = new Tematicas();
        $tematica->tema_nombre = $request->tema_nombre;
        $tematica->save();

        return redirect()->route('admin.listar.tematica')->with('exitoTematica', 'Tematica creada exitosamente.');
    }

    public function actualizarTematica(Request $request, $tema_codigo)
    {
        $request->validate([
            'tema_nombre' => 'required|max:255|unique:tematicas,tema_nombre,' . $tema_codigo . ',tema_codigo'
        ]);

        $tematica = Tematicas::find($tema_codigo);
        if ($tematica) {
            $tematica->tema_nombre = $request->tema_nombre;
            $tematica->save();
            return redirect()->route('admin.listar.tematica')->with('exitoTematica', 'Tematica actualizada exitosamente.');
        }

        return redirect()->route('admin.listar.tematica')->with('errorTematica', 'La Tematica no fue encontrada.');
    }

    public function eliminarTematica(Request $request)
    {
        $tema_codigo = $request->tema_codigo;
        $tematica = Tematicas::find($tema_codigo);
        if ($tematica) {
            $tematica->delete();
            return redirect()->route('admin.listar.tematica')->with('exitoTematica', 'Tematica eliminada exitosamente.');
        }

        return redirect()->route('admin.listar.tematica')->with('errorTematica', 'La Tematica no fue encontrada.');
    }

    /* $socio = new SociosComunitarios();
    $socio->sugr_codigo = $request->input('grupo',1);
    $socio->soco_nombre_socio = $request->input('nombre');
    $socio->soco_nombre_contraparte = $request->input('nombre_contraparte');
    $socio->soco_domicilio_socio = $request->input('domicilio');
    $socio->soco_telefono_contraparte = $request->input('telefono');
    $socio->soco_email_contraparte = $request->input('email'); */

    /* $socio->soco_visible = $request->input('care_visible', 1);
    //TODO: SI NO QUEREMOS MORIR, CAMBIAR ESTO
    $socio->soco_creado = now();
    $socio->soco_actualizado = now();

    $socio->soco_nikcname_mod = Session::get('admin')->usua_nickname;
    $socio->soco_rol_mod = Session::get('admin')->rous_codigo; */

    //TODO: Unidad
    //--------------------------------------
    //CAMBIAR NOMBRE MODELO POR: Unidades
    //--------------------------------------

    public function listarUnidades()
    {

        $REGISTROS = Unidades::orderBy('unid_codigo', 'asc')->get();
        $REGISTROS2 = TipoUnidades::orderBy('tuni_codigo', 'asc')->get();

        return view('admin.parametros.unidades', [
            'REGISTROS' => $REGISTROS,
            'REGISTROS2' => $REGISTROS2
        ]);
    }

    public function crearUnidades(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'nombre' => 'required|max:100',
            /* 'idcampo1' => 'required', */
        ], [
            'nombre.required' => 'El nombre es requerido.',
            'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (100).',
            /* 'idcampo1.required' => 'El idcampo1 es requerido.', */
        ]);

        if ($validacion->fails()) {
            return redirect()->route('admin.listar.unidades')->withErrors($validacion)->withInput();
        }

        $tipoUnidad = TipoUnidades::firstOrCreate([
            'tuni_nombre' => "Unidad",
        ]);

        $nuevo = new Unidades();
        $nuevo->unid_nombre = $request->input('nombre');
        $nuevo->tuni_codigo = $tipoUnidad->tuni_codigo;
        $nuevo->unid_descripcion = $request->input('descripcion');
        $nuevo->unid_responsable = $request->input('responsable');
        $nuevo->unid_nombre_cargo = $request->input('nombre_cargo');
        $nuevo->unid_creado = Carbon::now()->format('Y-m-d H:i:s');
        $nuevo->unid_actualizado = Carbon::now()->format('Y-m-d H:i:s');
        $nuevo->unid_visible = 1;
        $nuevo->unid_nickname_mod = Session::get('admin')->usua_nickname;
        $nuevo->unid_rol_mod = Session::get('admin')->rous_codigo;

        $nuevo->save();

        return redirect()->back()->with('exito', 'Unidad creada exitosamente');
    }

    public function eliminarUnidades(Request $request)
    {
        $eliminado = Unidades::where('unid_codigo', $request->unid_codigo)->first();
        if (!$eliminado) {
            return redirect()->route('admin.listar.unidades')->with('error', 'La Unidad no se encuentra registrada en el sistema.');
        }

        $eliminado = Unidades::where('unid_codigo', $request->unid_codigo)->delete();
        return redirect()->route('admin.listar.unidades')->with('exito', 'La Unidad fue eliminada correctamente.');
    }

    public function actualizarUnidades(Request $request, $unid_codigo)
    {
        $validacion = Validator::make($request->all(), [
            'nombre' => 'required|max:100',
            /* 'idcampo1' => 'required', */
        ], [
            'nombre.required' => 'El nombre es requerido.',
            'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (100).',
            /* 'idcampo1.required' => 'El idcampo1 es requerido.', */
        ]);

        if ($validacion->fails()) {
            return redirect()->route('admin.listar.unidades')->withErrors($validacion)->withInput();
        }

        $editado = Unidades::find($unid_codigo);
        //return redirect()->route('admin.listar.ambitos')->with('errorAmbito', $amb_codigo);
        if (!$editado) {
            return redirect()->route('admin.listar.unidades')->with('error', 'La Unidad no se encuentra registrada en el sistema.')->withInput();
        }

        $tipoUnidad = TipoUnidades::firstOrCreate([
            'tuni_nombre' => "Unidad",
        ]);

        $editado->unid_nombre = $request->input('nombre');
        $editado->tuni_codigo = $tipoUnidad->tuni_codigo;
        $editado->unid_descripcion = $request->input('descripcion');
        $editado->unid_responsable = $request->input('responsable');
        $editado->unid_nombre_cargo = $request->input('nombre_cargo');
        $editado->unid_actualizado = Carbon::now()->format('Y-m-d H:i:s');
        $editado->unid_visible = 1;
        $editado->unid_nickname_mod = Session::get('admin')->usua_nickname;
        $editado->unid_rol_mod = Session::get('admin')->rous_codigo;
        $editado->save();

        return redirect()->back()->with('exito', 'Unidad actualizada exitosamente')->withInput();;
    }
    //TODO: SubUnidad
    //--------------------------------------
    //CAMBIAR NOMBRE MODELO POR: SubUnidades
    //--------------------------------------

    public function listarSubUnidades()
    {

        $REGISTROS = SubUnidades::orderBy('suni_codigo', 'asc')->get();
        $REGISTROS2 = Unidades::orderBy('unid_codigo', 'asc')->get();

        return view('admin.parametros.subunidades', [
            'REGISTROS' => $REGISTROS,
            'REGISTROS2' => $REGISTROS2
        ]);
    }

    public function crearSubUnidades(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'nombre' => 'required|max:100',
            'select_join' => 'required',
            /* 'idcampo1' => 'required', */
        ], [
            'nombre.required' => 'El nombre es requerido.',
            'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (100).',
            'select_join.required' => 'La unidad es requerida.',
        ]);

        if ($validacion->fails()) {
            return redirect()->route('admin.listar.subunidades')->withErrors($validacion)->withInput();
        }

        $nuevo = new SubUnidades();
        $nuevo->suni_nombre = $request->input('nombre');
        $nuevo->unid_codigo = $request->input('select_join');
        $nuevo->suni_responsable = $request->input('responsable');
        $nuevo->suni_descripcion = $request->input('descripcion');
        /* $nuevo->suni_idcampo1 = $request->input('idcampo1'); */
        $nuevo->suni_creado = Carbon::now()->format('Y-m-d H:i:s');
        $nuevo->suni_actualizado = Carbon::now()->format('Y-m-d H:i:s');
        $nuevo->suni_visible = 1;
        $nuevo->suni_nickname_mod = Session::get('admin')->usua_nickname;
        $nuevo->suni_rol_mod = Session::get('admin')->rous_codigo;

        $nuevo->save();

        $mensaje = '¡SubUnidad "' . $nuevo->suni_nombre .'" creada exitosamente!';

        return redirect()->back()->with('exitoSubUnidad', $mensaje);
    }

    public function eliminarSubUnidades(Request $request)
    {
        //verificar si suni_codigo != 1 o 2
        if ($request->suni_codigo == 1 || $request->suni_codigo == 2) {
            return redirect()->route('admin.listar.subunidades')->with('errorSubUnidad', 'No es posible eliminar la SubUnidad seleccionada ya que contiene metas personalizadas asociadas, por favor contacte un administrador si la desea eliminar.');
        }

        $eliminado = SubUnidades::where('suni_codigo', $request->suni_codigo)->first();
        $nombre = $eliminado->suni_nombre;
        if (!$eliminado) {
            return redirect()->route('admin.listar.subunidades')->with('errorSubUnidad', 'La SubUnidad no se encuentra registrada en el sistema.');
        }

        $eliminado = SubUnidades::where('suni_codigo', $request->suni_codigo)->delete();

        $mensaje = '¡SubUnidad "' . $nombre .'" eliminada exitosamente!';
        return redirect()->route('admin.listar.subunidades')->with('exitoSubUnidad', $mensaje);
    }

    public function actualizarSubUnidades(Request $request, $suni_codigo)
    {
        $validacion = Validator::make($request->all(), [
            'nombre' => 'required|max:100',
            'select_join' => 'required',
            /* 'idcampo1' => 'required', */
        ], [
            'nombre.required' => 'El nombre es requerido.',
            'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (100).',
            'select_join.required' => 'La unidad es requerida.',
        ]);


        if ($validacion->fails()) {
            return redirect()->route('admin.listar.subunidades')->withErrors($validacion)->withInput();
        }

        $editado = SubUnidades::find($suni_codigo);
        //return redirect()->route('admin.listar.ambitos')->with('errorAmbito', $amb_codigo);
        if (!$editado) {
            return redirect()->route('admin.listar.subunidades')->with('errorSubUnidad', 'La SubUnidad no se encuentra registrada en el sistema.')->withInput();
        }

        $editado->suni_nombre = $request->input('nombre');
        $editado->unid_codigo = $request->input('select_join');
        $editado->suni_responsable = $request->input('responsable');
        $editado->suni_descripcion = $request->input('descripcion');
        $editado->suni_actualizado = Carbon::now()->format('Y-m-d H:i:s');
        $editado->suni_visible = 1;
        $editado->suni_nickname_mod = Session::get('admin')->usua_nickname;
        $editado->suni_rol_mod = Session::get('admin')->rous_codigo;
        $editado->suni_meta1 = $request->input('suni_meta1') ?? 0;
        $editado->suni_meta2 = $request->input('suni_meta2') ?? 0;
        $editado->suni_meta3 = $request->input('suni_meta3') ?? 0;
        $editado->suni_meta4 = $request->input('suni_meta4') ?? 0;
        $editado->suni_meta5 = $request->input('suni_meta5') ?? 0;
        $editado->save();

        return redirect()->back()->with('exito', 'SubUnidad actualizada exitosamente')->withInput();
        ;
    }
    //TODO: Tipo de iniciativa
    //--------------------------------------
    //CAMBIAR NOMBRE MODELO POR: TipoIniciativas
    //--------------------------------------

    public function listarTipoIniciativa()
    {
        return view('admin.parametros.tipoiniciativas', ['REGISTROS' => TipoIniciativas::orderBy('tmec_codigo', 'asc')->get()]);
    }

    public function crearTipoIniciativa(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'nombre' => 'required|max:100',
            /* 'idcampo1' => 'required', */
        ], [
            'nombre.required' => 'El nombre es requerido.',
            'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (100).',
            /* 'idcampo1.required' => 'El idcampo1 es requerido.', */
        ]);

        if ($validacion->fails()) {
            return redirect()->route('admin.listar.tipoiniciativa')->withErrors($validacion)->withInput();
        }

        $nuevo = new TipoIniciativas();
        $nuevo->tmec_nombre = $request->input('nombre');
        $nuevo->tmec_creado = Carbon::now()->format('Y-m-d H:i:s');
        $nuevo->tmec_actualizado = Carbon::now()->format('Y-m-d H:i:s');
        $nuevo->tmec_visible = 1;
        $nuevo->tmec_nickname_mod = Session::get('admin')->usua_nickname;
        $nuevo->tmec_rol_mod = Session::get('admin')->rous_codigo;

        $nuevo->save();

        return redirect()->back()->with('exito', 'Tipo de iniciativa creado exitosamente');
    }

    public function eliminarTipoIniciativa(Request $request)
    {
        $eliminado = TipoIniciativas::where('tmec_codigo', $request->tmec_codigo)->first();
        if (!$eliminado) {
            return redirect()->route('admin.listar.tipoiniciativa')->with('error', 'El Tipo de iniciativa no se encuentra registrado en el sistema.');
        }

        $eliminado = TipoIniciativas::where('tmec_codigo', $request->tmec_codigo)->delete();
        return redirect()->route('admin.listar.tipoiniciativa')->with('exito', 'El Tipo de iniciativa fue eliminado correctamente.');
    }

    public function actualizarTipoIniciativa(Request $request, $tmec_codigo)
    {
        $validacion = Validator::make($request->all(), [
            'nombre' => 'required|max:100',
            /* 'idcampo1' => 'required', */
        ], [
            'nombre.required' => 'El nombre es requerido.',
            'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (100).',
            /* 'idcampo1.required' => 'El idcampo1 es requerido.', */
        ]);

        if ($validacion->fails()) {
            return redirect()->route('admin.listar.tipoiniciativa')->withErrors($validacion)->withInput();
        }

        $editado = TipoIniciativas::find($tmec_codigo);
        //return redirect()->route('admin.listar.ambitos')->with('errorAmbito', $amb_codigo);
        if (!$editado) {
            return redirect()->route('admin.listar.tipoiniciativa')->with('error', 'El Tipo de iniciativa no se encuentra registrado en el sistema.')->withInput();
        }

        $editado->tmec_nombre = $request->input('nombre');
        $editado->tmec_actualizado = Carbon::now()->format('Y-m-d H:i:s');
        $editado->tmec_visible = 1;
        $editado->tmec_nickname_mod = Session::get('admin')->usua_nickname;
        $editado->tmec_rol_mod = Session::get('admin')->rous_codigo;
        $editado->save();

        return redirect()->back()->with('exito', 'Tipo de iniciativa actualizado exitosamente')->withInput();;
    }

    //Todo: funciones de actividades
    public function listarActividad()
    {
        $ACTIVIDADES = Actividades::all();
        return view('admin.parametros.actividades', compact('ACTIVIDADES'));
    }

    public function crearActividad(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'nombre' => 'required|max:200',
            'fecha' => 'required|date',
            'fecha_cumplimiento' => 'required|date',
            'acuerdos' => 'required|max:255',
        ], [
            'nombre.required' => 'El nombre de la actividad es requerido.',
            'nombre.max' => 'El nombre de la actividad excede el máximo de caracteres permitidos (100).',
            'fecha.required' => 'La fecha de creación es requerida.',
            'fecha.date' => 'La fecha de creación no tiene un formato válido.',
            'fecha_cumplimiento.required' => 'La fecha de cumplimiento es requerida.',
            'fecha_cumplimiento.date' => 'La fecha de cumplimiento no tiene un formato válido.',
            'acuerdos.required' => 'Los acuerdos son requeridos.',
            'acuerdos.max' => 'Los acuerdos exceden el máximo de caracteres permitidos (255).',
        ]);

        if ($validacion->fails()) {
            return redirect()->route('admin.listar.actividades')->withErrors($validacion)->withInput();
        }

        $nuevaActividad = new Actividades();
        $nuevaActividad->acti_nombre = $request->input('nombre');
        $nuevaActividad->acti_acuerdos = $request->input('acuerdos');
        $nuevaActividad->acti_fecha = Carbon::createFromFormat('Y-m-d', $request->input('fecha'));
        $nuevaActividad->acti_fecha_cumplimiento = Carbon::createFromFormat('Y-m-d', $request->input('fecha_cumplimiento'));
        // Otros campos si es necesario
        $nuevaActividad->acti_actualizado = Carbon::now()->format('Y-m-d H:i:s');
        $nuevaActividad->acti_visible = 1;
        $nuevaActividad->acti_nickname_mod = Session::get('admin')->usua_nickname;
        $nuevaActividad->acti_rol_mod = Session::get('admin')->rous_codigo;
        $nuevaActividad->save();

        return redirect()->back()->with('exitoActividades', 'Actividad creada exitosamente');
    }

    public function editarActividad(Request $request, $acti_codigo)
    {
        $validacion = Validator::make($request->all(), [
            'nombre' => 'required|max:200',
            'fecha' => 'required|date',
            'fecha_cumplimiento' => 'required|date',
            'acuerdos' => 'required|max:255',
        ], [
            'nombre.required' => 'El nombre de la actividad es requerido.',
            'nombre.max' => 'El nombre de la actividad excede el máximo de caracteres permitidos (100).',
            'fecha.required' => 'La fecha de creación es requerida.',
            'fecha.date' => 'La fecha de creación no tiene un formato válido.',
            'fecha_cumplimiento.required' => 'La fecha de cumplimiento es requerida.',
            'fecha_cumplimiento.date' => 'La fecha de cumplimiento no tiene un formato válido.',
            'acuerdos.required' => 'Los acuerdos son requeridos.',
            'acuerdos.max' => 'Los acuerdos exceden el máximo de caracteres permitidos (255).',
        ]);

        if ($validacion->fails()) {
            return redirect()->route('admin.listar.actividades')->withErrors($validacion)->withInput();
        }

        $actividad = Actividades::find($acti_codigo);
        if (!$actividad) {
            return redirect()->back()->with('errorActividades', 'La actividad no existe');
        }

        $actividad->acti_nombre = $request->input('nombre');
        $actividad->acti_acuerdos = $request->input('acuerdos');
        $actividad->acti_fecha = Carbon::createFromFormat('Y-m-d', $request->input('fecha'));
        $actividad->acti_fecha_cumplimiento = Carbon::createFromFormat('Y-m-d', $request->input('fecha_cumplimiento'));
        // Otros campos si es necesario
        $actividad->acti_actualizado = Carbon::now()->format('Y-m-d H:i:s');
        $actividad->acti_nickname_mod = Session::get('admin')->usua_nickname;
        $actividad->acti_rol_mod = Session::get('admin')->rous_codigo;
        $actividad->save();

        return redirect()->back()->with('exitoActividades', 'Actividad actualizada exitosamente');
    }



    public function eliminarActividad(Request $request)
    {
        $acti_codigo = $request->input('acti_codigo');

        $actividad = Actividades::find($acti_codigo);
        if (!$actividad) {
            return redirect()->back()->with('errorActividades', 'La actividad no existe');
        }

        $actividad->delete();

        return redirect()->back()->with('exitoActividades', 'Actividad eliminada exitosamente');
    }

    //TODO: Sub-grupo de interés
    //--------------------------------------
    //CAMBIAR NOMBRE MODELO POR: SubGruposInteres
    //--------------------------------------

    public function listarSubGrupoInteres()
    {
        // EN CASO DE NECESITAR OTROS DATOS AL ENRUTAR
        $REGISTROS = SubGruposInteres::orderBy('sugr_codigo', 'asc')->get();
        $REGISTROS2 = GruposInteres::orderBy('grin_codigo', 'asc')->get();

        return view('admin.parametros.subgrupo', [
            'REGISTROS' => $REGISTROS,
            'REGISTROS2' => $REGISTROS2
        ]);
    }

    public function crearSubGrupoInteres(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'nombre' => 'required|max:100',
            /* 'idcampo1' => 'required', */
        ], [
            'nombre.required' => 'El nombre es requerido.',
            'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (100).',
            /* 'idcampo1.required' => 'El idcampo1 es requerido.', */
        ]);

        if ($validacion->fails()) {
            return redirect()->route('admin.listar.subgrupos')->withErrors($validacion)->withInput();
        }

        $nuevo = new SubGruposInteres();
        $nuevo->sugr_nombre = $request->input('nombre');
        $nuevo->grin_codigo = $request->input('select_join');
        $nuevo->sugr_creado = Carbon::now()->format('Y-m-d H:i:s');
        $nuevo->sugr_actualizado = Carbon::now()->format('Y-m-d H:i:s');
        $nuevo->sugr_visible = 1;
        $nuevo->sugr_nickname_mod = Session::get('admin')->usua_nickname;
        $nuevo->sugr_rol_mod = Session::get('admin')->rous_codigo;

        $nuevo->save();

        return redirect()->back()->with('exito', 'Sub-grupo de interés creado exitosamente');
    }

    public function eliminarSubGrupoInteres(Request $request)
    {
        $eliminado = SubGruposInteres::where('sugr_codigo', $request->sugr_codigo)->first();
        if (!$eliminado) {
            return redirect()->route('admin.listar.subgrupos')->with('error', 'El Sub-grupo de interés no se encuentra registrado en el sistema.');
        }

        $eliminado = SubGruposInteres::where('sugr_codigo', $request->sugr_codigo)->delete();
        return redirect()->route('admin.listar.subgrupos')->with('exito', 'El Sub-grupo de interés fue eliminado correctamente.');
    }

    public function actualizarSubGrupoInteres(Request $request, $sugr_codigo)
    {
        $validacion = Validator::make($request->all(), [
            'nombre' => 'required|max:100',
            /* 'idcampo1' => 'required', */
        ], [
            'nombre.required' => 'El nombre es requerido.',
            'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (100).',
            /* 'idcampo1.required' => 'El idcampo1 es requerido.', */
        ]);

        if ($validacion->fails()) {
            return redirect()->route('admin.listar.subgrupos')->withErrors($validacion)->withInput();
        }

        $editado = SubGruposInteres::find($sugr_codigo);
        if (!$editado) {
            return redirect()->route('admin.listar.subgrupos')->with('error', 'El Sub-grupo de interés no se encuentra registrado en el sistema.')->withInput();
        }

        $editado->sugr_nombre = $request->input('nombre');
        $editado->grin_codigo = $request->input('select_join');
        $editado->sugr_actualizado = Carbon::now()->format('Y-m-d H:i:s');
        $editado->sugr_visible = 1;
        $editado->sugr_nickname_mod = Session::get('admin')->usua_nickname;
        $editado->sugr_rol_mod = Session::get('admin')->rous_codigo;
        $editado->save();

        return redirect()->back()->with('exito', 'Sub-grupo de interés actualizado exitosamente')->withInput();;
    }

    //TODO: Recurso Humano
    //--------------------------------------
    //CAMBIAR NOMBRE MODELO POR: TipoRRHH
    //--------------------------------------

    public function listarRecursosHumanos()
    {
        return view('admin.parametros.tiporrhh', ['REGISTROS' => TipoRRHH::orderBy('trrhh_codigo', 'asc')->get()]);
    }

    public function crearRecursosHumanos(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'nombre' => 'required|max:100',
            /* 'idcampo1' => 'required', */
        ], [
            'nombre.required' => 'El nombre es requerido.',
            'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (100).',
            /* 'idcampo1.required' => 'El idcampo1 es requerido.', */
        ]);

        if ($validacion->fails()) {
            return redirect()->route('admin.listar.rrhh')->withErrors($validacion)->withInput();
        }

        $nuevo = new TipoRRHH();
        $nuevo->trrhh_nombre = $request->input('nombre');
        $nuevo->trrhh_valor = $request->input('valor');
        $nuevo->trrhh_creado = Carbon::now()->format('Y-m-d H:i:s');
        $nuevo->trrhh_actualizado = Carbon::now()->format('Y-m-d H:i:s');
        $nuevo->trrhh_visible = 1;
        $nuevo->trrhh_nickname_mod = Session::get('admin')->usua_nickname;
        $nuevo->trrhh_rol_mod = Session::get('admin')->rous_codigo;

        $nuevo->save();

        return redirect()->back()->with('exito', 'Recurso Humano creado exitosamente');
    }

    public function eliminarRecursosHumanos(Request $request)
    {
        $eliminado = TipoRRHH::where('trrhh_codigo', $request->trrhh_codigo)->first();
        if (!$eliminado) {
            return redirect()->route('admin.listar.rrhh')->with('error', 'El Recurso Humano no se encuentra registrado en el sistema.');
        }

        $eliminado = TipoRRHH::where('trrhh_codigo', $request->trrhh_codigo)->delete();
        return redirect()->route('admin.listar.rrhh')->with('exito', 'El Recurso Humano fue eliminado correctamente.');
    }

    public function actualizarRecursosHumanos(Request $request, $trrhh_codigo)
    {
        $validacion = Validator::make($request->all(), [
            'nombre' => 'required|max:100',
            /* 'idcampo1' => 'required', */
        ], [
            'nombre.required' => 'El nombre es requerido.',
            'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (100).',
            /* 'idcampo1.required' => 'El idcampo1 es requerido.', */
        ]);

        if ($validacion->fails()) {
            return redirect()->route('admin.listar.rrhh')->withErrors($validacion)->withInput();
        }

        $editado = TipoRRHH::find($trrhh_codigo);
        if (!$editado) {
            return redirect()->route('admin.listar.rrhh')->with('error', 'El Recurso Humano no se encuentra registrado en el sistema.')->withInput();
        }

        $editado->trrhh_nombre = $request->input('nombre');
        $editado->trrhh_valor = $request->input('valor');
        $editado->trrhh_actualizado = Carbon::now()->format('Y-m-d H:i:s');
        $editado->trrhh_visible = 1;
        $editado->trrhh_nickname_mod = Session::get('admin')->usua_nickname;
        $editado->trrhh_rol_mod = Session::get('admin')->rous_codigo;
        $editado->save();

        return redirect()->back()->with('exito', 'Recurso Humano actualizado exitosamente')->withInput();;
    }

    //TODO: tipo de infraestrutura
    //--------------------------------------
    //CAMBIAR NOMBRE MODELO POR: TipoInfraestructura
    //--------------------------------------

    public function listarTipoInfraestructuras()
    {
        return view('admin.parametros.tipoinfraestructura', ['REGISTROS' => TipoInfraestructura::orderBy('tinf_codigo', 'asc')->get()]);
        /* // EN CASO DE NECESITAR OTROS DATOS AL ENRUTAR
        $REGISTROS = TipoInfraestructura::orderBy('tinf_codigo', 'asc')->get();
        $REGISTROS2 = MODELO2::orderBy('prefijojoin_codigo', 'asc')->get();

        return view('admin.parametros.tipoinfra', [
            'REGISTROS' => $REGISTROS,
            'REGISTROS2' => $REGISTROS2
        ]); */
    }

    public function crearTipoInfraestructuras(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'nombre' => 'required|max:100',
            'valor' => 'required'
            /* 'idcampo1' => 'required', */
        ], [
            'nombre.required' => 'El nombre es requerido.',
            'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (100).',
            'valor.required' => 'Es necesario agregar la valorización del tipo de infraestructura'
            /* 'idcampo1.required' => 'El idcampo1 es requerido.', */
        ]);

        if ($validacion->fails()) {
            return redirect()->route('admin.listar.tipoinfra')->withErrors($validacion)->withInput();
        }

        $nuevo = new TipoInfraestructura();
        $nuevo->tinf_nombre = $request->input('nombre');
        $nuevo->tinf_valor = $request->input('valor');
        $nuevo->tinf_creado = Carbon::now()->format('Y-m-d H:i:s');
        $nuevo->tinf_actualizado = Carbon::now()->format('Y-m-d H:i:s');
        $nuevo->tinf_visible = 1;
        $nuevo->tinf_nickname_mod = Session::get('admin')->usua_nickname;
        $nuevo->tinf_rol_mod = Session::get('admin')->rous_codigo;

        $nuevo->save();

        return redirect()->back()->with('exitoTIfrastructura', 'Tipo de infraestrutura creado exitosamente');
    }

    public function eliminarTipoInfraestructuras(Request $request)
    {
        $eliminado = TipoInfraestructura::where('tinf_codigo', $request->tinf_codigo)->first();
        if (!$eliminado) {
            return redirect()->route('admin.listar.tipoinfra')->with('error', 'El tipo de infraestrutura no se encuentra registrado en el sistema.');
        }

        $eliminado = TipoInfraestructura::where('tinf_codigo', $request->tinf_codigo)->delete();
        return redirect()->route('admin.listar.tipoinfra')->with('exito', 'El tipo de infraestrutura fue eliminado correctamente.');
    }

    public function actualizarTipoInfraestructuras(Request $request, $tinf_codigo)
    {
        $validacion = Validator::make($request->all(), [
            'nombre' => 'required|max:100',
            'valor' => 'required'
            /* 'idcampo1' => 'required', */
        ], [
            'nombre.required' => 'El nombre es requerido.',
            'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (100).',
            'valor.required' => 'Es necesario que se ingrese un valor para la infraestructura.'
            /* 'idcampo1.required' => 'El idcampo1 es requerido.', */
        ]);

        if ($validacion->fails()) {
            return redirect()->route('admin.listar.tipoinfra')->withErrors($validacion)->withInput();
        }

        $editado = TipoInfraestructura::find($tinf_codigo);
        if (!$editado) {
            return redirect()->route('admin.listar.tipoinfra')->with('error', 'El tipo de infraestrutura no se encuentra registrado en el sistema.')->withInput();
        }

        $editado->tinf_nombre = $request->input('nombre');
        $editado->tinf_valor = $request->input('valor');
        $editado->tinf_actualizado = Carbon::now()->format('Y-m-d H:i:s');
        $editado->tinf_visible = 1;
        $editado->tinf_nickname_mod = Session::get('admin')->usua_nickname;
        $editado->tinf_rol_mod = Session::get('admin')->rous_codigo;
        $editado->save();

        return redirect()->back()->with('exito', 'tipo de infraestrutura actualizado exitosamente')->withInput();;
    }

    public function listarComponentes()
    {
        return view('admin.parametros.componente', ['REGISTROS' => Componentes::orderBy('comp_codigo', 'asc')->get()]);
        /* // EN CASO DE NECESITAR OTROS DATOS AL ENRUTAR
        $REGISTROS = Componentes::orderBy('comp_codigo', 'asc')->get();
        $REGISTROS2 = MODELO2::orderBy('prefijojoin_codigo', 'asc')->get();

        return view('admin.parametros.componente', [
            'REGISTROS' => $REGISTROS,
            'REGISTROS2' => $REGISTROS2
        ]); */
    }

    public function crearComponentes(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'nombre' => 'required|max:100',
            /* 'idcampo1' => 'required', */
        ], [
            'nombre.required' => 'El nombre es requerido.',
            'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (100).',
            /* 'idcampo1.required' => 'El idcampo1 es requerido.', */
        ]);

        if ($validacion->fails()) {
            return redirect()->route('admin.listar.componente')->withErrors($validacion)->withInput();
        }

        $nuevo = new Componentes();
        $nuevo->comp_nombre = $request->input('nombre');
        $nuevo->comp_creado = Carbon::now()->format('Y-m-d H:i:s');
        $nuevo->comp_actualizado = Carbon::now()->format('Y-m-d H:i:s');
        $nuevo->comp_visible = 1;
        $nuevo->comp_nickname_mod = Session::get('admin')->usua_nickname;
        $nuevo->comp_rol_mod = Session::get('admin')->rous_codigo;

        $nuevo->save();

        return redirect()->back()->with('exito', 'componente creado exitosamente');
    }

    public function eliminarComponentes(Request $request)
    {
        $eliminado = Componentes::where('comp_codigo', $request->comp_codigo)->first();
        if (!$eliminado) {
            return redirect()->route('admin.listar.componente')->with('error', 'El componente no se encuentra registrado en el sistema.');
        }

        $eliminado = Componentes::where('comp_codigo', $request->comp_codigo)->delete();
        return redirect()->route('admin.listar.componente')->with('exito', 'El componente fue eliminado correctamente.');
    }

    public function actualizarComponentes(Request $request, $comp_codigo)
    {
        $validacion = Validator::make($request->all(), [
            'nombre' => 'required|max:100',
            /* 'idcampo1' => 'required', */
        ], [
            'nombre.required' => 'El nombre es requerido.',
            'nombre.max' => 'El nombre excede el máximo de caracteres permitidos (100).',
            /* 'idcampo1.required' => 'El idcampo1 es requerido.', */
        ]);

        if ($validacion->fails()) {
            return redirect()->route('admin.listar.componente')->withErrors($validacion)->withInput();
        }

        $editado = Componentes::find($comp_codigo);
        if (!$editado) {
            return redirect()->route('admin.listar.componente')->with('error', 'El componente no se encuentra registrado en el sistema.')->withInput();
        }

        $editado->comp_nombre = $request->input('nombre');
        $editado->comp_actualizado = Carbon::now()->format('Y-m-d H:i:s');
        $editado->comp_visible = 1;
        $editado->comp_nickname_mod = Session::get('admin')->usua_nickname;
        $editado->comp_rol_mod = Session::get('admin')->rous_codigo;
        $editado->save();

        return redirect()->back()->with('exito', 'componente actualizado exitosamente')->withInput();;
    }

    public function actualizarInvitado(Request $request, $evainv_codigo)
    {
        // Buscar al invitado por su código
        $invitado = EvaluacionInvitado::find($evainv_codigo);

        // dd($request->all());

        // Validar si el invitado existe
        if (!$invitado) {
            return redirect()->route('admin.listar.iniciativas')->with('errorSede', 'El invitado no se ha encontrado en el sistema.');
        }

        // Validar los datos enviados en el formulario
        $validatedData = $request->validate([
            'evainv_nombre' => 'required|string|max:255',
            'evainv_correo' => 'required|email|max:255',
        ], [
            'evainv_nombre.required' => 'El campo Nombre del invitado es requerido.',
            'evainv_nombre.string' => 'El campo Nombre del invitado debe ser una cadena de texto.',
            'evainv_nombre.max' => 'El campo Nombre del invitado no puede tener más de 255 caracteres.',
            'evainv_correo.required' => 'El campo Correo del invitado es requerido.',
            'evainv_correo.email' => 'El campo Correo del invitado debe ser una dirección de correo válida.',
            'evainv_correo.max' => 'El campo Correo del invitado no puede tener más de 255 caracteres.',
        ]);

        // Actualizar los datos del invitado
        $invitado->evainv_nombre = $request->input('evainv_nombre');
        $invitado->evainv_correo = $request->input('evainv_correo');

        // Guardar los cambios en la base de datos
        $invitado->save();

        // Redirigir back
        return redirect()->back()->with('exitoInvitado', 'Invitado actualizado exitosamente');
    }
}
