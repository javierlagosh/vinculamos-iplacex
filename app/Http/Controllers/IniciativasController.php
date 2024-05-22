<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Ods;
use App\Models\Pais;
use App\Models\Sedes;
use App\Models\Comuna;
use App\Models\Grupos;
use App\Models\Region;
use App\Models\Carreras;
use App\Models\Escuelas;
use App\Models\TipoRRHH;
use App\Models\Convenios;
use App\Models\Entidades;
use App\Models\MetasInic;
use App\Models\Asignaturas;
use App\Models\pivoteOds;
use App\Models\Programas;
use App\Models\Tematicas;
use App\Models\CostosRrhh;
use App\Models\IniciativasAsignaturas;
use App\Models\Dispositivos;
use App\Models\IniciativasDispositivos;
use App\Models\Mecanismos;
use App\Models\Resultados;
use App\Models\Iniciativas;
use App\Models\SedesSocios;
use App\Models\CostosDinero;
use Illuminate\Http\Request;
use App\Models\DescMetasInic;
use App\Models\SedesEscuelas;
use App\Models\FundamentoInic;
use App\Models\IniciativasPais;
use App\Models\TipoActividades;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\SubGruposInteres;
use App\Models\IniciativasGrupos;
use App\Models\IniciativasComunas;
use App\Models\SociosComunitarios;
use Illuminate\Support\Facades\DB;
use App\Models\IniciativasRegiones;
use App\Models\TipoInfraestructura;
use App\Models\IniciativasTematicas;
use App\Models\ProgramasActividades;
use Illuminate\Support\Facades\File;
use App\Models\CostosInfraestructura;
use App\Models\IniciativasEvidencias;
use App\Models\MecanismosActividades;
use App\Models\ParticipantesInternos;
use Illuminate\Support\Facades\Session;
use App\Models\IniciativasParticipantes;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\GruposInteres;
use App\Models\CentroSimulacion;
use App\Models\IniciativasCentroSimulacion;
use App\Models\Ambitos;
use App\Models\AmbitoTiac;
use App\Models\IniciativasAmbitos;
use App\Models\SubUnidades;
//evaluacion
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use App\Models\Evaluacion;
use App\Models\EvaluacionTotal;
use App\Models\EvaluacionInvitado;
use Illuminate\Support\HtmlString;

class IniciativasController extends Controller
{
    public function listarIniciativas(Request $request)
    {
        $iniciativas = Iniciativas::join('mecanismos', 'mecanismos.meca_codigo', 'iniciativas.meca_codigo')
            ->leftjoin('tipo_actividades', 'tipo_actividades.tiac_codigo', 'iniciativas.tiac_codigo')
            ->leftjoin('componentes', 'componentes.comp_codigo', 'tipo_actividades.comp_codigo')
            ->leftjoin('participantes_internos', 'participantes_internos.inic_codigo', 'iniciativas.inic_codigo')
            ->leftjoin('dispositivo', 'dispositivo.id', 'iniciativas.dispositivo_id')
            ->leftjoin('sedes', 'sedes.sede_codigo', 'participantes_internos.sede_codigo')
            ->leftjoin('carreras', 'carreras.care_codigo', 'participantes_internos.care_codigo')
            ->leftjoin('escuelas', 'escuelas.escu_codigo', 'participantes_internos.escu_codigo')
            ->select(
                'iniciativas.inic_codigo',
                'iniciativas.inic_nombre',
                'iniciativas.inic_estado',
                'iniciativas.inic_anho',
                'iniciativas.meca_codigo',
                'mecanismos.meca_nombre',
                'dispositivo.nombre as dispositivo',
                'componentes.comp_nombre',
                DB::raw('GROUP_CONCAT(DISTINCT sedes.sede_nombre SEPARATOR " / ") as sedes'),
                // DB::raw('GROUP_CONCAT(DISTINCT escuelas.escu_nombre SEPARATOR "/ ") as escuelas'),
                // DB::raw('GROUP_CONCAT(DISTINCT carreras.care_nombre SEPARATOR ", ") as carreras'),
                DB::raw('DATE_FORMAT(iniciativas.inic_creado, "%d/%m/%Y") as inic_creado')
            )
            ->groupBy('iniciativas.meca_codigo','iniciativas.inic_codigo', 'componentes.comp_nombre', 'iniciativas.inic_nombre', 'iniciativas.inic_estado', 'iniciativas.inic_anho', 'mecanismos.meca_nombre', 'inic_creado', 'dispositivo.nombre') // Agregamos inic_creado al GROUP BY
            ->orderBy('inic_creado', 'desc'); // Ordenar por fecha de creación formateada en orden descendente
        // ->where('iniciativas.inic_anho','2023')

        if ($request->sede != 'all' && $request->sede != null) {
            $iniciativas = $iniciativas->where('sedes.sede_codigo', $request->sede);
        }

        if ($request->mecanismo != 'all' && $request->mecanismo != null) {
            $iniciativas = $iniciativas->where('mecanismos.meca_codigo', $request->mecanismo);
        }

        if ($request->anho != 'all' && $request->anho != null) {
            $iniciativas = $iniciativas->where('iniciativas.inic_anho', $request->anho);
        }

        if ($request->escuela == null && $request->mecanismo == null && $request->anho == null) {
            $iniciativas = $iniciativas->where('iniciativas.inic_anho', '2024');
        }

        $iniciativas = $iniciativas->get();


        $sedes = Sedes::select('sede_codigo', 'sede_nombre')->orderBy('sede_nombre', 'asc')->get();
        // $carreras = Carreras::select('care_codigo', 'care_nombre')->orderBy('care_nombre', 'asc')->get();
        // $componentes = DB::table('componentes')->select('comp_codigo', 'comp_nombre')->orderBy('comp_nombre', 'asc')->get();
        $mecanismos = Mecanismos::select('meca_codigo','meca_nombre')->get();
        $anhos = Iniciativas::select('inic_anho')->distinct('inic_anho')->orderBy('inic_anho', 'asc')->get();

        return view('admin.iniciativas.listar', compact('iniciativas', 'mecanismos', 'anhos', 'sedes'));
    }


    public function completarCobertura($inic_codigo)
    {
        $resuVerificar = ParticipantesInternos::where('inic_codigo', $inic_codigo)->count();
        if ($resuVerificar == 0)
            return redirect()->back()->with('errorIniciativa', 'La iniciativa no posee resultados esperados.');

        $inicObtener = Iniciativas::where('inic_codigo', $inic_codigo)->first();
        $resuObtener = DB::table('participantes_internos')
            ->select(
                'participantes_internos.pain_codigo',
                'sedes.sede_nombre',
                'escuelas.escu_nombre',
                'escuelas.escu_codigo',
                'carreras.care_nombre',
                'carreras.care_codigo',
                'participantes_internos.pain_docentes',
                'participantes_internos.pain_docentes_final',
                'participantes_internos.pain_estudiantes',
                'participantes_internos.pain_estudiantes_final',
                'participantes_internos.pain_funcionarios',
                'participantes_internos.pain_funcionarios_final',
                'participantes_internos.pain_total'
            )
            ->join('carreras', 'participantes_internos.care_codigo', '=', 'carreras.care_codigo')
            ->join('escuelas', 'carreras.escu_codigo', '=', 'escuelas.escu_codigo')
            ->join('sedes', 'sedes.sede_codigo', '=', 'participantes_internos.sede_codigo')
            ->where('participantes_internos.inic_codigo', $inic_codigo)
            ->get();
        $participantes = Iniciativas::join('iniciativas_participantes', 'iniciativas_participantes.inic_codigo', 'iniciativas.inic_codigo')
            ->join('sub_grupos_interes', 'sub_grupos_interes.sugr_codigo', 'iniciativas_participantes.sugr_codigo')
            ->join('socios_comunitarios', 'socios_comunitarios.soco_codigo', 'iniciativas_participantes.soco_codigo')
            ->select(
                'sub_grupos_interes.sugr_nombre',
                'sub_grupos_interes.sugr_codigo',
                'socios_comunitarios.soco_codigo',
                'socios_comunitarios.soco_nombre_socio',
                'iniciativas.inic_codigo',
                'iniciativas.inic_nombre',
                'iniciativas_participantes.inpr_codigo',
                'iniciativas_participantes.inpr_total',
                'iniciativas_participantes.inpr_total_final',
            )
            ->where('iniciativas.inic_codigo', $inic_codigo)
            ->get();

        return view('admin.iniciativas.coberturas', [
            'iniciativa' => $inicObtener,
            'resultados' => $resuObtener,
            'participantes' => $participantes
        ]);
    }


    public function actualizarCobertura(Request $request, $inic_codigo)
    {
        $docentes_final = $request->input('docentes_final');
        $estudiantes_final = $request->input('estudiantes_final');
        $funcionarios_final = $request->input('funcionarios_final');
        // dd($docentes_final, $estudiantes_final);

        foreach ($docentes_final as $pain_codigo => $docentes_final_value) {
            // Obtener el resultado correspondiente según el $pain_codigo
            $resultado = ParticipantesInternos::where('pain_codigo', $pain_codigo)
                ->where('inic_codigo', $inic_codigo)
                ->first();

            if ($resultado) {
                // Actualizar los valores en la base de datos
                $resultado->pain_docentes_final = $docentes_final_value;
                $resultado->pain_estudiantes_final = $estudiantes_final[$pain_codigo];
                $resultado->pain_funcionarios_final = $funcionarios_final[$pain_codigo];
                $resultado->save();
            }
        }

        return redirect()->route('admin.cobertura.index', $inic_codigo)
            ->with('exitoInterno', 'Participacion interna actualizada correctamente.');
    }

    public function actualizarCoberturaEx(Request $request, $inic_codigo)
    {
        $participantes_final = $request->input('participantes');
        // dd($participantes_final);

        foreach ($participantes_final as $inpr_codigo => $participantes_final_value) {
            // Obtener el resultado correspondiente según el $inpr_codigo
            $resultado = IniciativasParticipantes::where('inpr_codigo', $inpr_codigo)
                ->where('inic_codigo', $inic_codigo)
                ->first();

            if ($resultado) {
                // Actualizar los valores en la base de datos
                $resultado->inpr_total_final = $participantes_final_value;
                // dd($resultado->inpr_total_final = $participantes_final_value);
                $resultado->save();
            }
        }

        return redirect()->back()->with('exitoExterno', 'Participantes externos actualizados correctamente.');
    }

    public function updateState(Request $request)
    {
        $iniciativaId = $request->inic_codigo;
        $state = $request->state;

        $iniciativa = Iniciativas::findOrFail($iniciativaId);
        $iniciativa->update([
            'inic_estado' => $state,
        ]);


        // Respuesta de éxito

        return redirect('/admin/iniciativas/listar')->with('success', 'Estado actualizado correctamente');
    }

    public function mostrarPDF($inic_codigo)
    {
        $iniciativa = Iniciativas::leftjoin('convenios', 'convenios.conv_codigo', '=', 'iniciativas.conv_codigo')
            ->join('tipo_actividades', 'tipo_actividades.tiac_codigo', '=', 'iniciativas.tiac_codigo')
            ->join('mecanismos', 'mecanismos.meca_codigo', '=', 'iniciativas.meca_codigo')
            ->select(
                'iniciativas.inic_codigo',
                'iniciativas.inic_nombre',
                'iniciativas.inic_descripcion',
                'iniciativas.inic_anho',
                'iniciativas.inic_estado',
                'mecanismos.meca_nombre',
                'tipo_actividades.tiac_nombre',
            )
            ->where('iniciativas.inic_codigo', $inic_codigo)
            ->first();
        //TODO: FIXEAR PARA QUE MUESTRE LOS ODS CORRESPONDIENTES Y NO REPETIDOS
        $odsValues = PivoteOds::join('ods', 'pivote_ods.id_ods', '=', 'ods.id_ods')
            ->join('metas_inic', 'metas_inic.inic_codigo', '=', 'pivote_ods.inic_codigo')
            ->where('pivote_ods.inic_codigo', '=', $inic_codigo)
            ->select('pivote_ods.inic_codigo', 'pivote_ods.id_ods', 'ods.nombre_ods', 'metas_inic.desc_meta', 'metas_inic.fundamento')
            ->orderBy('pivote_ods.id_ods') // Ordenar por la columna id_ods
            ->get()
            ->unique('id_ods');
        // dd($odsValues);

        //Con la inic_codigo obtener el fundamento de la tabla fundamento_inic
        // $fundamentos = FundamentoInic::select('fund_ods')->where('inic_codigo', $inic_codigo)->get();

        //Con la inic_codigo obtener las metas de la tabla metas_inic
        $metas = MetasInic::select('*')->where('inic_codigo', $inic_codigo)->get();
        //Con la inic_codigo obtener las metas de la tabla metas_inic
        // $metas = MetasInic::where('inic_codigo', $inic_codigo)
        // ->orderByRaw('CAST(meta_ods AS DECIMAL(10,2)) ASC')
        // ->get();


        $pdf = Pdf::loadView('admin.iniciativas.pdf', compact('iniciativa', 'inic_codigo', 'odsValues', 'metas'));

        return $pdf->stream();

    }

    public function mostrarDetalles($inic_codigo)
    {
        //Obtener las id para los ODS registrados en la tabla pivote_ods
        $ods = pivoteOds::select('id_ods')->where('inic_codigo', $inic_codigo)->get();
        //Con la ID obtener desde la tabla ODS, el nombre del ods que corresponde
        // $ods = Ods::select('nombre_ods')->whereIn('id_ods', $ods)->get();

        // dd($ods);
        $dispositivos = Dispositivos::join('iniciativas', 'dispositivo.id', 'iniciativas.dispositivo_id')
            ->where('inic_codigo', $inic_codigo)
            ->get()
            ->first();


        $iniciativas_asignaturas = IniciativasAsignaturas::join('asignaturas', 'asignaturas.id', 'iniciativas_asignaturas.asignatura_id')
            ->where('inic_codigo', $inic_codigo)
            ->get();

        $impactosInternos = IniciativasAmbitos::join('ambito', 'iniciativas_ambitos.amb_codigo', 'ambito.amb_codigo')
            ->where('iniciativas_ambitos.inic_codigo', $inic_codigo)
            ->where('ambito.amb_descripcion', 'Impacto Interno')
            ->get();


        $impactosExternos = IniciativasAmbitos::join('ambito', 'iniciativas_ambitos.amb_codigo', 'ambito.amb_codigo')
            ->where('iniciativas_ambitos.inic_codigo', $inic_codigo)
            ->where('ambito.amb_descripcion', 'Impacto Externo')
            ->get();

        $iniciativa = Iniciativas::leftjoin('convenios', 'convenios.conv_codigo', '=', 'iniciativas.conv_codigo')
            ->leftjoin('tipo_actividades', 'tipo_actividades.tiac_codigo', '=', 'iniciativas.tiac_codigo')
            ->leftjoin('mecanismos', 'mecanismos.meca_codigo', '=', 'iniciativas.meca_codigo')
            ->leftjoin('sub_grupos_interes', 'sub_grupos_interes.sugr_codigo', '=', 'iniciativas.sugr_codigo')
            ->select(
                'iniciativas.inic_codigo',
                'iniciativas.inic_nombre',
                'iniciativas.inic_descripcion',
                'iniciativas.inic_anho',
                'iniciativas.inic_estado',
                'mecanismos.meca_nombre',
                'tipo_actividades.tiac_nombre',
                'convenios.conv_nombre',
                'iniciativas.inic_brecha',
                'iniciativas.inic_diagnostico',
                'sub_grupos_interes.sugr_nombre',
                'iniciativas.inic_macrozona',
                'iniciativas.inic_bimestre',
                'iniciativas.inic_desde',
                'iniciativas.inic_hasta',
                'iniciativas.inic_escuela_ejecutora',
            )
            ->where('iniciativas.inic_codigo', $inic_codigo)
            ->first();

            $escuelaEjecutora = Escuelas::where('escu_codigo', $iniciativa->inic_escuela_ejecutora)->first();
            $escuelaEjecutora = $escuelaEjecutora->escu_nombre ?? "No especificado";


        // return $iniciativa;
        $participantes = ParticipantesInternos::join('carreras', 'carreras.care_codigo', 'participantes_internos.care_codigo')
            ->join('escuelas', 'escuelas.escu_codigo', 'participantes_internos.escu_codigo')
            ->select(
                'participantes_internos.inic_codigo',
                'participantes_internos.pain_docentes',
                'participantes_internos.pain_docentes_final',
                'participantes_internos.pain_estudiantes',
                'participantes_internos.pain_estudiantes_final',
                'carreras.care_nombre',
                'escuelas.escu_nombre'
            )
            ->where('participantes_internos.inic_codigo', $inic_codigo)
            ->get();

        $ubicaciones = IniciativasComunas::join('comunas', 'comunas.comu_codigo', 'iniciativas_comunas.comu_codigo')
            ->join('regiones', 'regiones.regi_codigo', 'comunas.regi_codigo')
            ->select(
                'iniciativas_comunas.inic_codigo',
                'regiones.regi_codigo',
                'regiones.regi_nombre',
                DB::raw('GROUP_CONCAT(comunas.comu_nombre SEPARATOR ", ") as comunas')
            )
            ->groupBy('iniciativas_comunas.inic_codigo', 'regiones.regi_nombre', 'regiones.regi_codigo')
            ->where('iniciativas_comunas.inic_codigo', $inic_codigo)
            ->get();

        $grupos = IniciativasGrupos::join('grupos', 'grupos.grup_codigo', 'iniciativas_grupos.grup_codigo')
            // ->select(DB::raw('GROUP_CONCAT(grupos.grup_nombre SEPARATOR ", " ) as grupos'))
            // ->groupBy('iniciativas_grupos.inic_codigo')
            ->where('iniciativas_grupos.inic_codigo', $inic_codigo)->get();

        $tematicas = IniciativasTematicas::join('tematicas', 'tematicas.tema_codigo', 'iniciativas_tematicas.tema_codigo')
            ->where('inic_codigo', $inic_codigo)->get();

        $participantes_externos = IniciativasParticipantes::join('sub_grupos_interes', 'sub_grupos_interes.sugr_codigo', 'iniciativas_participantes.sugr_codigo')
            ->join('socios_comunitarios', 'socios_comunitarios.soco_codigo', 'iniciativas_participantes.soco_codigo')
            ->join('grupos_interes', 'grupos_interes.grin_codigo', 'sub_grupos_interes.grin_codigo')
            ->where('iniciativas_participantes.inic_codigo', $inic_codigo)->get();

        $entidadesRecursos = Entidades::select('enti_codigo', 'enti_nombre')->get();
        $costosDinero = CostosDinero::select(DB::raw('IFNULL(SUM(codi_valorizacion), 0) AS codi_valorizacion'))->where('inic_codigo', $inic_codigo)->first();
        $costosInfraestructura = CostosInfraestructura::select(DB::raw('IFNULL(SUM(coin_valorizacion), 0) AS coin_valorizacion'))->where('inic_codigo', $inic_codigo)->first();
        $costosRrhh = CostosRrhh::select(DB::raw('IFNULL(SUM(corh_valorizacion), 0) AS corh_valorizacion'))->where('inic_codigo', $inic_codigo)->first();

        $codiListar = CostosDinero::select('enti_codigo', DB::raw('IFNULL(SUM(codi_valorizacion), 0) AS suma_dinero'))->where('inic_codigo', $inic_codigo)->groupBy('enti_codigo')->get();
        $coinListar = CostosInfraestructura::select('enti_codigo', 'costos_infraestructura.tinf_codigo', 'tinf_nombre', DB::raw('IFNULL(SUM(coin_valorizacion), 0) AS suma_infraestructura'))
            ->join('tipo_infraestructura', 'tipo_infraestructura.tinf_codigo', '=', 'costos_infraestructura.tinf_codigo')
            ->where('inic_codigo', $inic_codigo)
            ->groupBy('enti_codigo', 'costos_infraestructura.tinf_codigo', 'tinf_nombre')
            ->get();

        $corhListar = CostosRrhh::select('enti_codigo', 'costos_rrhh.trrhh_codigo', 'trrhh_nombre', DB::raw('IFNULL(SUM(corh_valorizacion), 0) AS suma_rrhh'))
            ->join('tipo_rrhh', 'tipo_rrhh.trrhh_codigo', '=', 'costos_rrhh.trrhh_codigo')
            ->where('inic_codigo', $inic_codigo)
            ->groupBy('enti_codigo', 'costos_rrhh.trrhh_codigo', 'trrhh_nombre')
            ->get();

        // return $costosDinero;
        // return $iniciativa;

        return view('admin.iniciativas.mostrar', [
            'iniciativa' => $iniciativa,
            'ubicaciones' => $ubicaciones,
            'grupos' => $grupos,
            'tematicas' => $tematicas,
            'externos' => $participantes_externos,
            'internos' => $participantes,
            'dinero' => $costosDinero,
            'infraestructura' => $costosInfraestructura,
            'rrhh' => $costosRrhh,
            'recursoDinero' => $codiListar,
            'recursoInfraestructura' => $coinListar,
            'recursoRrhh' => $corhListar,
            'entidades' => $entidadesRecursos,
            'ods_array' => $ods,
            'escuelaEjecutora' => $escuelaEjecutora,
            'iniciativas_asignaturas' => $iniciativas_asignaturas,
            'dispositivos' => $dispositivos,
            'impactosInternos' => $impactosInternos,
            'impactosExternos' => $impactosExternos
        ]);
    }

    public function listarEvidencia($inic_codigo)
    {
        $inicVerificar = Iniciativas::where('inic_codigo', $inic_codigo)->first();
        if (!$inicVerificar)
            return redirect()->route('admin.iniciativa.listar')->with('errorIniciativa', 'La iniciativa no se encuentra registrada en el sistema.');

        $inevListar = IniciativasEvidencias::where(['inic_codigo' => $inic_codigo])->get();
        return view('admin.iniciativas.evidencias', [
            'iniciativas' => $inicVerificar,
            'evidencias' => $inevListar
        ]);
    }

    public function actualizarResultados(Request $request, $inic_codigo)
    {
        $resultados_final = $request->input('resultados');
        // dd($resultadosfinal);

        foreach ($resultados_final as $resu_codigo => $resultados_final_value) {
            // Obtener el resultado correspondiente según el $inpr_codigo
            $resultado = Resultados::where('resu_codigo', $resu_codigo)
                ->where('inic_codigo', $inic_codigo)
                ->first();

            if ($resultado) {
                // Actualizar los valores en la base de datos
                $resultado->resu_cuantificacion_final = $resultados_final_value;
                // dd($resultado->inpr_total_final = $participantes_final_value);
                $resultado->save();
            }
        }

        return redirect()->back()->with('exitoExterno', 'Resultados actualizados correctamente.');
    }

    public function guardarEvidencia(Request $request, $inic_codigo)
    {

        $inicVerificar = Iniciativas::where('inic_codigo', $inic_codigo)->first();
        if (!$inicVerificar)
            return redirect()->route('admin.iniciativa.listar')->with('errorIniciativa', 'La iniciativa no se encuentra registrada en el sistema.');

        $validarEntradas = Validator::make(
            $request->all(),
            [
                'inev_nombre' => 'required|max:50',
                // 'inev_descripcion' => 'required|max:500',
                'inev_archivo' => 'required|max:10000',
            ],
            [
                'inev_nombre.required' => 'El nombre de la evidencia es requerido.',
                'inev_nombre.max' => 'El nombre de la evidencia excede el máximo de caracteres permitidos (50).',
                // 'inev_descripcion.required' => 'La descripción de la evidencia es requerida.',
                // 'inev_descripcion.max' => 'La descripción de la evidencia excede el máximo de caracteres permitidos (500).',
                'inev_archivo.required' => 'El archivo de la evidencia es requerido.',
                'inev_archivo.mimes' => 'El tipo de archivo no está permitido, intente con un formato de archivo tradicional.',
                'inev_archivo.max' => 'El archivo excede el tamaño máximo permitido (10 MB).'
            ]
        );
        if ($validarEntradas->fails())
            return redirect()->route('admin.evidencias.listar', $inic_codigo)->with('errorValidacion', $validarEntradas->errors()->first());

        $inevGuardar = IniciativasEvidencias::insertGetId([
            'inic_codigo' => $inic_codigo,
            'inev_nombre' => $request->inev_nombre,
            // 'inev_tipo' => $request->inev_tipo,
            // Todo: nuevo campo a la BD
            'inev_descripcion' => $request->inev_descripcion,
            'inev_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'inev_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'inev_rol_mod' => Session::get('admin')->rous_codigo,
            'inev_nickname_mod' => Session::get('admin')->usua_nickname
        ]);
        if (!$inevGuardar)
            redirect()->back()->with('errorEvidencia', 'Ocurrió un error al registrar la evidencia, intente más tarde.');

        $archivo = $request->file('inev_archivo');
        $rutaEvidencia = 'files/evidencias/' . $inevGuardar;
        if (File::exists(public_path($rutaEvidencia)))
            File::delete(public_path($rutaEvidencia));
        $moverArchivo = $archivo->move(public_path('files/evidencias'), $inevGuardar);
        if (!$moverArchivo) {
            IniciativasEvidencias::where('inev_codigo', $inevGuardar)->delete();
            return redirect()->back()->with('errorEvidencia', 'Ocurrió un error al registrar la evidencia, intente más tarde.');
        }

        $inevActualizar = IniciativasEvidencias::where('inev_codigo', $inevGuardar)->update([
            'inev_ruta' => 'files/evidencias/' . $inevGuardar,
            'inev_mime' => $archivo->getClientMimeType(),
            'inev_nombre_origen' => $archivo->getClientOriginalName(),
            'inev_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'inev_rol_mod' => Session::get('admin')->rous_codigo,
            'inev_nickname_mod' => Session::get('admin')->usua_nickname
        ]);
        if (!$inevActualizar)
            return redirect()->back()->with('errorEvidencia', 'Ocurrió un error al registrar la evidencia, intente más tarde.');
        return redirect()->route('admin.evidencias.listar', $inic_codigo)->with('exitoEvidencia', 'La evidencia fue registrada correctamente.');

    }

    public function actualizarEvidencia(Request $request, $inev_codigo)
    {
        try {
            $evidencia = IniciativasEvidencias::where('inev_codigo', $inev_codigo)->first();
            if (!$evidencia)
                return redirect()->back()->with('errorEvidencia', 'La evidencia no se encuentra registrada o vigente en el sistema.');

            $validarEntradas = Validator::make(
                $request->all(),
                [
                    'inev_nombre_edit' => 'required|max:50',
                    // 'inev_descripcion_edit' => 'required|max:500',
                ],
                [
                    'inev_nombre_edit.required' => 'El nombre de la evidencia es requerido.',
                    'inev_nombre_edit.max' => 'El nombre de la evidencia excede el máximo de caracteres permitidos (50).',
                    // 'inev_descripcion_edit.required' => 'La descripción de la evidencia es requerida.',
                    // 'inev_descripcion_edit.max' => 'La descripción de la evidencia excede el máximo de caracteres permitidos (500).'
                ]
            );
            if ($validarEntradas->fails())
                return redirect()->route('admin.evidencias.listar', $evidencia->inic_codigo)->with('errorValidacion', $validarEntradas->errors()->first());

            $inevActualizar = IniciativasEvidencias::where('inev_codigo', $inev_codigo)->update([
                'inev_nombre' => $request->inev_nombre_edit,
                'inev_descripcion' => $request->inev_descripcion_edit,
                // 'inev_tipo' => $request->inev_tipo_edit,
                'inev_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                'inev_rol_mod' => Session::get('admin')->rous_codigo,
                'inev_nickname_mod' => Session::get('admin')->usua_nickname
            ]);
            if (!$inevActualizar)
                return redirect()->back()->with('errorEvidencia', 'Ocurrió un error al actualizar la evidencia, intente más tarde.');
            return redirect()->route('admin.evidencias.listar', $evidencia->inic_codigo)->with('exitoEvidencia', 'La evidencia fue actualizada correctamente.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorEvidencia', 'Ocurrió un problema al actualizar la evidencia, intente más tarde.');
        }
    }


    public function descargarEvidencia($inev_codigo)
    {
        try {
            $evidencia = IniciativasEvidencias::where('inev_codigo', $inev_codigo)->first();
            if (!$evidencia)
                return redirect()->back()->with('errorEvidencia', 'La evidencia no se encuentra registrada o vigente en el sistema.');

            $archivo = public_path($evidencia->inev_ruta);
            $cabeceras = array(
                'Content-Type: ' . $evidencia->inev_mime,
                'Cache-Control: no-cache, no-store, must-revalidate',
                'Pragma: no-cache'
            );
            return Response::download($archivo, $evidencia->inev_nombre_origen, $cabeceras);
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorEvidencia', 'Ocurrió un problema al descargar la evidencia, intente más tarde.');
        }
    }

    public function eliminarEvidencia($inev_codigo)
    {
        try {
            $evidencia = IniciativasEvidencias::where('inev_codigo', $inev_codigo)->first();
            if (!$evidencia)
                return redirect()->back()->with('errorEvidencia', 'La evidencia no se encuentra registrada o vigente en el sistema.');

            if (File::exists(public_path($evidencia->inev_ruta)))
                File::delete(public_path($evidencia->inev_ruta));
            $inevEliminar = IniciativasEvidencias::where('inev_codigo', $inev_codigo)->delete();
            if (!$inevEliminar)
                return redirect()->back()->with('errorEvidencia', 'Ocurrió un error al eliminar la evidencia, intente más tarde.');
            return redirect()->route('admin.evidencias.listar', $evidencia->inic_codigo)->with('exitoEvidencia', 'La evidencia fue eliminada correctamente.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorEvidencia', 'Ocurrió un problema al eliminar la evidencia, intente más tarde.');
        }
    }

    public function crearPaso1()
    {

        $iniciativa = Iniciativas::all();
        $mecanismo = Mecanismos::all();
        $tipoActividad = TipoActividades::all();
        $convenios = Convenios::all();
        $programas = Programas::all();
        $paises = Pais::all();
        $regiones = Region::all();
        $escuelas = Escuelas::all();
        $sedes = Sedes::all();
        $centro_simulacion = CentroSimulacion::all();
        $comunas = Comuna::all();
        $carreras = Carreras::all();
        $asignaturas = Asignaturas::all();
        $dispositivos = Dispositivos::all();
        $subgrupos = SubUnidades::all();

        $impactosInternos = Ambitos::where('amb_descripcion', 'Impacto Interno')->get();
        $impactosExternos = Ambitos::where('amb_descripcion', 'Impacto Externo')->get();



        return view('admin.iniciativas.paso1', [
            'editar' => false,
            //para saber si se esta editando o creando una nueva iniciativa
            'iniciativa' => $iniciativa,
            'mecanismo' => $mecanismo,
            'tipoActividad' => $tipoActividad,
            'convenios' => $convenios,
            'dispositivos' => $dispositivos,
            'programas' => $programas,
            'paises' => $paises,
            'regiones' => $regiones,
            'sedes' => $sedes,
            'escuelas' => $escuelas,
            'comunas' => $comunas,
            'carreras' => $carreras,
            'asignaturas' => $asignaturas,
            'subgrupos' => $subgrupos,
            'centro_simulacion' => $centro_simulacion,
            'impactosInternos' => $impactosInternos,
            'impactosExternos' => $impactosExternos
        ]);
    }

    public function verificarPaso1(Request $request)
    {



        //TODO: LAS ASIGNATURAS SE DEBERIAN GUARDAR EN UNA NUEVA COLUMNA DE PARTICIPANTES_INTERNOS
        //PREGUNTAR COMO VA LA COSA PORQUE PAIN DOCENTES Y PAIN ESTUDIANTES SON POR CARRERA Y NO POR ASIGNATURA POR LO QUE UNA CARRERA TIENE MÁS DOCENTES Y ESTUDIANTES QUE UNA ASIGNATURA
        $request->validate([
            'nombre' => 'required|max:255',
            'inic_formato' => 'required',
            'description' => 'required',
            'carreras' => 'required',
            'escuelas' => 'required',
            'mecanismos' => 'required',
            'tactividad' => 'required',
            'convenio' => 'required',
            'territorio' => 'required',
            // 'pais' => 'required'
        ], [
            'nombre.required' => 'El nombre de la iniciativa es requerido.',
            'nombre.max' => 'El nombre de la iniciativa no puede superar los 250 carácteres.',
            /* 'anho.required' => 'Es necesario ingresar un año para la iniciativa.',
            'inic_formato.required' => 'Es necesario que seleccione un formato para la iniciativa.',
            'description.required' => 'La Descripción es requerida.',
            'carreras.required' => 'Es necesario que seleccione al menos una Carrera en donde se ejecutará la iniciativa.',
            'escuelas.required' => 'Es necesario que seleccione al menos una Escuela en donde se ejecutará la iniciativa.',
            'tactividad.required' => 'Es necesario que seleccione un tipo de actividad para la iniciativa.',
            'mecanismos.required' => 'Es necesario que seleccione un programa.', */
            /* 'convenio.required' => 'Es necesario que escoja un convenio para asociar la iniciativa.', */
            /* 'territorio.required' => 'Especifique si la iniciativa es a nivel nacional o internacional.',
            'pais.required' => 'Seleccione el país en donde se ejecutará la iniciativa.' */
        ]);
        $anho = Carbon::parse($request->desde)->format('Y');
        $inicCrear = Iniciativas::insertGetId([
            'inic_nombre' => $request->nombre,
            'inic_anho' => $anho,
            'inic_desde' => $request->desde,
            'inic_hasta' => $request->hasta,
            'inic_responsable' => $request->inic_responsable,
            'inic_bimestre' => $request->inic_bimestre,
            'inic_escuela_ejecutora' => $request->inic_escuela_ejecutora,
            'inic_asignaturas' => $request->inic_asignaturas,
            'dispositivo_id' => $request->dispositivo_id,
            'inic_macrozona' => $request->inic_macrozona,
            'sugr_codigo' => $request->sugr_codigo,
            'inic_formato' => $request->inic_formato,
            'inic_brecha' => $request->brecha,
            'inic_diagnostico' => $request->diagnostico,
            'inic_descripcion' => $request->description,
            'conv_codigo' => $request->convenio,
            'meca_codigo' => $request->mecanismos,
            'tiac_codigo' => $request->tactividad,
            'inic_territorio' => $request->territorio,
            'inic_visible' => 1,
            'inic_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'inic_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'inic_nickname_mod' => Session::get('admin')->usua_nickname,
            'inic_rol_mod' => Session::get('admin')->rous_codigo,
        ]);

        if (!$inicCrear)
            return redirect()->back()->with('errorPaso1', 'Ocurrió un error durante el registro de los datos de la iniciativa, intente más tarde.')->withInput();

        $inic_codigo = $inicCrear;

        // $asignaturas = $request->input('asignaturas', []);
        // if (empty($asignaturas))
        // {
        //     //
        // }else{
        //     foreach ($asignaturas as $asignatura) {
        //             $IniciativasAsignaturas = new IniciativasAsignaturas();
        //             $IniciativasAsignaturas->inic_codigo = $inic_codigo;
        //             $IniciativasAsignaturas->asignatura_id = $asignatura;
        //             $IniciativasAsignaturas->save();
        //     }
        // }

        $impactosInternos = $request->input('impactosInternos', []);

        if (empty($impactosInternos))
        {
            //
        }else{
            foreach ($impactosInternos as $impactosInterno) {
                    $IniciativasAmbitos = new IniciativasAmbitos();
                    $IniciativasAmbitos->inic_codigo = $inic_codigo;
                    $IniciativasAmbitos->amb_codigo = $impactosInterno;
                    $IniciativasAmbitos->save();
            }
        }

        $impactosExternos = $request->input('impactosExternos', []);
        if (empty($impactosExternos))
        {
            //
        }else{
            foreach ($impactosExternos as $impactosExterno) {
                    $IniciativasAmbitos2 = new IniciativasAmbitos();
                    $IniciativasAmbitos2->inic_codigo = $inic_codigo;
                    $IniciativasAmbitos2->amb_codigo = $impactosExterno;
                    $IniciativasAmbitos2->save();
            }
        }

        $centro_simulacion = $request->input('centro_simulacion', []);
        if (empty($centro_simulacion))
        {
            //
        }else{
            foreach ($centro_simulacion as $cs) {
                    $IniciativasCentroSimulacion = new IniciativasCentroSimulacion();
                    $IniciativasCentroSimulacion->inic_codigo = $inic_codigo;
                    $IniciativasCentroSimulacion->cs_codigo = $cs;
                    $IniciativasCentroSimulacion->save();
            }
        }

        IniciativasPais::create([
            'inic_codigo' => $inic_codigo,
            'pais_codigo' => $request->pais,
            'pain_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'pain_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'pais_nickname_mod' => Session::get('admin')->usua_nickname,
            'pain_rol_mod' => Session::get('admin')->rous_codigo,
        ]);

        $regi = [];
        $regiones = $request->input('region', []);

        foreach ($regiones as $region) {
            array_push(
                $regi,
                [
                    'inic_codigo' => $inic_codigo,
                    'regi_codigo' => $region,
                    'rein_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                    'rein_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                    'rein_nickname_rol' => Session::get('admin')->usua_nickname,
                    'rein_rol_mod' => Session::get('admin')->rous_codigo,
                ]
            );
        }

        $regiCrear = IniciativasRegiones::insert($regi);

        if (!$regiCrear) {
            IniciativasRegiones::where('inic_codigo', $inic_codigo)->delete();
            return redirect()->back()->with('regiError', 'Ocurrió un error durante el registro de las regiones, intente más tarde.')->withInput();
        }

        $comu = [];
        $comunas = $request->input('comuna', []);

        foreach ($comunas as $comuna) {
            array_push($comu, [
                'inic_codigo' => $inic_codigo,
                'comu_codigo' => $comuna,
                'coin_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                'coin_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                'coin_nickname_mod' => Session::get('admin')->usua_nickname,
                'coin_rol_mod' => Session::get('admin')->rous_codigo,
            ]);
        }

        $comuCrear = IniciativasComunas::insert($comu);


        if (!$comuCrear) {
            IniciativasComunas::where('inic_codigo', $inic_codigo)->delete();
            return redirect()->back()->with('comuError', 'Ocurrió un error durante el registro de las comunas, intente más tarde.')->withInput();
        }

        $pain = [];
        $sedes = $request->input('sedes', []);
        $escuelas = $request->input('escuelas', []);
        $carreras = $request->input('carreras', []);

        foreach ($sedes as $sede) {
            foreach ($escuelas as $escuela) {
                foreach ($carreras as $carrera) {
                    $sede_escuela = SedesEscuelas::where('sede_codigo', $sede)
                        ->where('escu_codigo', $escuela)
                        ->exists();

                    $escuela_carrera = Carreras::where('escu_codigo', $escuela)
                        ->where('care_codigo', $carrera)->exists();
                    $escuela_sede = ParticipantesInternos::where([
                        'sede_codigo' => $sede,
                        'escu_codigo' => $escuela,
                        'care_codigo' => $carrera,
                        'inic_codigo' => $inic_codigo
                    ])->exists();

                    if ($sede_escuela && !$escuela_sede && $escuela_carrera) {
                        array_push($pain, [
                            'inic_codigo' => $inic_codigo,
                            'sede_codigo' => $sede,
                            'escu_codigo' => $escuela,
                            'care_codigo' => $carrera,
                        ]);
                    }
                }
            }
        }

        try {
            $odsValues = $request->ods_values ?? [];
        $odsMetasValues = $request->ods_metas_values ?? [];
        $odsMetasDescValues = $request->ods_metas_desc_values ?? [];
        $fundamentoOds = $request->ods_fundamentos_values ?? [];

        //Eliminar valores nulos de los arreglo
        $odsValues = array_filter($odsValues, function ($value) {
            return $value !== null;
        });

        $odsMetasValues = array_filter($odsMetasValues, function ($value) {
            return $value !== null;
        });

        $odsMetasDescValues = array_filter($odsMetasDescValues, function ($value) {
            return $value !== null;
        });

        $fundamentoOds = array_filter($fundamentoOds, function ($value) {
            return $value !== null;
        });

        // Eliminar duplicados de $fundamentoOds
        $fundamentoOds = array_unique($fundamentoOds);

        // dd($request->all());

        foreach ($odsValues as $ods) {
            $idOds = Ods::where('id_ods', $ods)->value('id_ods');
            PivoteOds::create([
                'inic_codigo' => $inic_codigo,
                'id_ods' => $idOds,
            ]);
        }
        //contar total de elementos en el arreglo de fundamentoOds
        $totalFundamentos = count($fundamentoOds);


        $fundamentoOds = array_values($fundamentoOds);

        for ($i=0; $i < 100; $i++) {
            try {
                $fundamentosNew = explode('.', ($fundamentoOds[$i]));
                break;
            } catch (\Throwable $th) {
                //
            }
        }

        try {
            $fundamentosNew = array_map('trim', $fundamentosNew);
            //quitar elemento si es ""
            foreach ($fundamentosNew as $key => $value) {
                if ($value == "") {
                    unset($fundamentosNew[$key]);
                }

            $fundamentosNew = array_values($fundamentosNew);
        }
        } catch (\Throwable $th) {
            //
        }

        //indexar todos los arreglos para las metas
        $odsMetasValues = array_values($odsMetasValues);
        $odsMetasDescValues = array_values($odsMetasDescValues);


        //TODO: QUE LOS FUNDAMENTOS SE GUARDEN EN LA DB (CREA UNA COLUMNA EN metas_inic LLAMADA 'fundamento' varchar(4096))
        for ($i = 0; $i < count($odsMetasValues); $i++) {
            MetasInic::create([
                'inic_codigo' => $inic_codigo,
                'meta_ods' => $odsMetasValues[$i],
                'desc_meta' => $odsMetasDescValues[$i],
                'fundamento' => $fundamentosNew[$i],
            ]);
        }
        } catch (\Throwable $th) {
            $errorODS = 'Ocurrió un error durante el registro de los ODS, intente más tarde.';
        }

        // foreach ($fundamentoOds as $fundamentoValue){
        //     FundamentoInic::create([
        //         'inic_codigo' => $inic_codigo,
        //         'fund_ods' => $fundamentoValue
        //     ]);
        // }

        $painCrear = ParticipantesInternos::insert($pain);
        if (!$painCrear) {
            ParticipantesInternos::where('inic_codigo', $inic_codigo)->delete();
            return redirect()->back()->with('errorPaso1', 'Ocurrió un error durante el registro de las unidades, intente más tarde.')->withInput();
        }
        if (isset($errorODS)) {
            return redirect()->route('admin.editar.paso2', $inic_codigo)->with('exitoPaso1', 'Los datos de la iniciativa se registraron correctamente, Lamentablemente ocurrió un error al registrar los ODS, por favor intente nuevamente...');
        }
        return redirect()->route('admin.editar.paso2', $inic_codigo)->with('exitoPaso1', 'Los datos de la iniciativa se registraron correctamente');
    }

    public function saveODS(Request $request, $inic_codigo)
    {
        //Guardar en la tabla pivote_ods, los ods seleccionados en el arreglo, junto al $inic_codigo
        $odsValues = $request->ods_values ?? [];
        //Eliminar valores nulos del arreglo
        $odsValues = array_filter($odsValues, function ($value) {
            return $value !== null;
        });

        foreach ($odsValues as $ods) {
            $idOds = Ods::where('id_ods', $ods)->value('id_ods');
            PivoteOds::create([
                'inic_codigo' => $inic_codigo,
                'id_ods' => $idOds,
            ]);
        }

        return redirect()->route('admin.iniciativas.detalles', $inic_codigo);
    }

    public function mostrarOds($inic_codigo)
    {
        //Obtener las id para los ODS registrados en la tabla pivote_ods
        $ods = pivoteOds::select('id_ods')->where('inic_codigo', $inic_codigo)->get();
        //Con la ID obtener desde la tabla ODS, el nombre del ods que corresponde
        $odsValues = Ods::select('id_ods', 'nombre_ods')->whereIn('id_ods', $ods)->get();

        //Con la inic_codigo obtener el fundamento de la tabla fundamento_inic
        // $fundamentos = FundamentoInic::select('fund_ods')->where('inic_codigo', $inic_codigo)->get();

        //Con la inic_codigo obtener las metas de la tabla metas_inic
        $metas = MetasInic::where('inic_codigo', $inic_codigo)
            ->orderByRaw('CAST(meta_ods AS DECIMAL(10,2)) ASC')
            ->get();
        // dd($metas);



        // dd($metas);
        return view('admin.iniciativas.agendaods', [
            'iniciativa' => $inic_codigo,
            'odsValues' => $odsValues,
            // 'fundamentos' => $fundamentos,
            'metas' => $metas,
        ]);
    }

    public function editarPaso1($inic_codigo)
    {
        $iniciativa = Iniciativas::where('inic_codigo', $inic_codigo)->first();
        $asignaturas = Asignaturas::all();

        $iniciativaData = Iniciativas::join('tipo_actividades', 'tipo_actividades.tiac_codigo', '=', 'iniciativas.tiac_codigo')
            ->where('inic_codigo', $inic_codigo)
            ->get();

        $sedes = Sedes::all();
        $tipoActividad = TipoActividades::all();
        $convenios = Convenios::all();
        // $programas = Programas::all();
        $mecanismos = MecanismosActividades::join('mecanismos', 'mecanismos.meca_codigo', 'mecanismos_actividades.meca_codigo')
            ->join('tipo_actividades', 'tipo_actividades.tiac_codigo', 'mecanismos_actividades.tiac_codigo')
            ->select('tipo_actividades.tiac_codigo', 'tipo_actividades.tiac_nombre', 'mecanismos.meca_codigo','mecanismos.meca_nombre')
            ->where('tipo_actividades.tiac_codigo', $iniciativaData[0]->tiac_codigo)
            ->distinct()
            ->get();
        $paises = Pais::all();
        $regiones = Region::all();
        $comunas = Comuna::all();
        $escuelas = Escuelas::all();
        $carreras = Carreras::all();
        $sedeSec = ParticipantesInternos::select('sede_codigo')->where('inic_codigo', $inic_codigo)->get();
        $escuSec = ParticipantesInternos::select('escu_codigo')->where('inic_codigo', $inic_codigo)->get();
        $careSec = ParticipantesInternos::select('care_codigo')->where('inic_codigo', $inic_codigo)->get();
        $iniciativaPais = IniciativasPais::where('inic_codigo', $inic_codigo)->get();
        $iniciativaRegion = IniciativasRegiones::select('regi_codigo')->where('inic_codigo', $inic_codigo)->get();
        $iniciativaComuna = IniciativasComunas::select('comu_codigo')->where('inic_codigo', $inic_codigo)->get();
        $centro_simulacion = CentroSimulacion::all();

        $sedeSecCod = $sedeSec->pluck('sede_codigo')->toArray();
        // $asignaturaSecCod2 = IniciativasAsignaturas::select('asignatura_id')->where('inic_codigo', $inic_codigo)->get();
        // $asignaturaSecCod = $asignaturaSecCod2->pluck('asignatura_id')->toArray();
        $csSecCod = $centro_simulacion->pluck('cs_codigo')->toArray();

        $impactosInternosSec = [];
        $impactosInternosSec2 = IniciativasAmbitos::select('iniciativas_ambitos.amb_codigo')->where('iniciativas_ambitos.inic_codigo', $inic_codigo)
        ->leftjoin('ambito', 'ambito.amb_codigo', 'iniciativas_ambitos.amb_codigo')
        ->where('ambito.amb_descripcion', 'Impacto Interno')
        ->get();
        foreach ($impactosInternosSec2 as $key => $value) {
            $impactosInternosSec[$key] = $value->amb_codigo;
        }

        $impactosExternosSec2 = IniciativasAmbitos::select('iniciativas_ambitos.amb_codigo')->where('iniciativas_ambitos.inic_codigo', $inic_codigo)
        ->leftjoin('ambito', 'ambito.amb_codigo', 'iniciativas_ambitos.amb_codigo')
        ->where('ambito.amb_descripcion', 'Impacto Externo')
        ->get();
        $impactosExternosSec = [];
        foreach ($impactosExternosSec2 as $key => $value) {
            $impactosExternosSec[$key] = $value->amb_codigo;
        }
        $escuSecCod = $escuSec->pluck('escu_codigo')->toArray();
        $careSecCod = $careSec->pluck('care_codigo')->toArray();
        $regiSec = $iniciativaRegion->pluck('regi_codigo')->toArray();
        $comuSec = $iniciativaComuna->pluck('comu_codigo')->toArray();
        $subgrupos = SubUnidades::all();

        // dd($iniciativaData);
        $impactosInternos = Ambitos::where('amb_descripcion', 'Impacto Interno')->get();
        $impactosExternos = Ambitos::where('amb_descripcion', 'Impacto Externo')->get();

        $odsData = DB::table('pivote_ods')
            ->join('ods', 'pivote_ods.id_ods', '=', 'ods.id_ods')
            ->where('pivote_ods.inic_codigo', $inic_codigo)
            ->select(
                'ods.id_ods',
                'ods.nombre_ods'
            )
            ->get();

        $metasData = DB::table('pivote_ods')
            ->join('metas_inic', 'pivote_ods.inic_codigo', '=', 'metas_inic.inic_codigo')
            ->where('pivote_ods.inic_codigo', $inic_codigo)
            ->select(
                'metas_inic.inic_codigo',
                'metas_inic.meta_ods',
                'metas_inic.desc_meta',
                'metas_inic.fundamento'
            )
            ->groupBy('metas_inic.inic_codigo', 'metas_inic.meta_ods', 'metas_inic.desc_meta', 'metas_inic.fundamento')
            ->orderByRaw('CAST(meta_ods AS DECIMAL(10,2)) ASC')
            ->get();

        // dd($metasData);
        $dispositivos = Dispositivos::all();

        return view('admin.iniciativas.paso1', [
            'editar' => true,
            //para que se muestre el boton de editar en el formulario
            'iniciativa' => $iniciativa,
            'iniciativaData' => $iniciativaData[0],
            'iniciativaPais' => $iniciativaPais,
            'tipoActividad' => $tipoActividad,
            'iniciativaRegion' => $regiSec,
            'iniciativaComuna' => $comuSec,
            'sedes' => $sedes,
            'comunas' => $comunas,
            'convenios' => $convenios,
            'mecanismo' => $mecanismos,
            //'asignaturas' => $asignaturas,
            'paises' => $paises,
            'regiones' => $regiones,
            'escuelas' => $escuelas,
            'sedeSec' => $sedeSecCod,
            'dispositivos' => $dispositivos,
            //'asignaturaSec' => $asignaturaSecCod,
            'escuSec' => $escuSecCod,
            'careSec' => $careSecCod,
            'csSec' => $csSecCod,
            'carreras' => $carreras,
            'ods' => $odsData,
            'metas' => $metasData,
            'subgrupos' => $subgrupos,
            'centro_simulacion' => $centro_simulacion,
            'impactosInternos' => $impactosInternos,
            'impactosExternos' => $impactosExternos,
            'impactosExternosSec' => $impactosExternosSec,
            'impactosInternosSec' => $impactosInternosSec,

        ]);

    }

    public function actualizarPaso1(Request $request, $inic_codigo)
    {
        $request->validate([
            'nombre' => 'required|max:255',
            /* 'anho' => 'required',
            'inic_formato' => 'required',
            'description' => 'required',
            'carreras' => 'required',
            'escuelas' => 'required',
            'mecanismos' => 'required',
            'tactividad' => 'required', */
            /* 'convenio' => 'required', */
            /* 'territorio' => 'required',
            'pais' => 'required' */
        ], [
            'nombre.required' => 'El nombre de la iniciativa es requerido.',
            'nombre.max' => 'El nombre de la iniciativa no puede superar los 250 carácteres.',
            /* 'anho.required' => 'Es necesario ingresar un año para la iniciativa.',
            'inic_formato.required' => 'Es necesario que seleccione un formato para la iniciativa.',
            'description.required' => 'La Descripción es requerida.',
            'carreras.required' => 'Es necesario que seleccione al menos una Carrera en donde se ejecutará la iniciativa.',
            'escuelas.required' => 'Es necesario que seleccione al menos una Escuela en donde se ejecutará la iniciativa.',
            'mecanismos.required' => 'Es necesario que seleccione un programa.',
            'tactividad.required' => 'Es necesario que seleccione el tipo de actividad a realizar.', */
            /* 'convenio.required' => 'Es necesario que escoja un convenio para asociar la iniciativa.', */
            /* 'territorio.required' => 'Especifique si la iniciativa es a nivel nacional o internacional.',
            'pais.required' => 'Seleccione el país en donde se ejecutará la iniciativa.' */
        ]);

        //obtener el anho del request date y convertirlo a number
        $anho = Carbon::parse($request->desde)->format('Y');

        $inicActualizar = Iniciativas::where('inic_codigo', $inic_codigo)->update([
            'inic_nombre' => $request->nombre,
            'inic_anho' => $anho,
            'inic_formato' => $request->inic_formato,
            'inic_descripcion' => $request->description,
            'inic_brecha' => $request->brecha,
            'inic_diagnostico' => $request->diagnostico,
            'inic_escuela_ejecutora' => $request->inic_escuela_ejecutora,
            'dispositivo_id' => $request->dispositivo_id,
            'inic_desde' => $request->desde,
            'inic_asignaturas' => $request->inic_asignaturas,
            'inic_hasta' => $request->hasta,
            'sugr_codigo' => $request->sugr_codigo,
            'conv_codigo' => $request->convenio,
            'meca_codigo' => $request->mecanismos,
            'tiac_codigo' => $request->tactividad,
            'inic_territorio' => $request->territorio,
            'inic_visible' => 1,
            'inic_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'inic_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'inic_nickname_mod' => Session::get('admin')->usua_nickname,
            'inic_rol_mod' => Session::get('admin')->rous_codigo,
        ]);

        if (!$inicActualizar)
            return redirect()->back()->with('errorPaso1', 'Ocurrió un error durante la actualización de los datos de la iniciativa, intente más tarde.')->withInput();



        //eliminar asignaturas asociadas
        IniciativasAsignaturas::where('inic_codigo', $inic_codigo)->delete();


        // $asignaturas = $request->input('asignaturas', []);
        // if (empty($asignaturas))
        // {
        //     return redirect()->back()->with('errorPaso1', 'Es necesario que seleccione al menos una asignatura para la iniciativa.')->withInput();
        // }else{
        //     foreach ($asignaturas as $asignatura) {
        //             $IniciativasAsignaturas = new IniciativasAsignaturas();
        //             $IniciativasAsignaturas->inic_codigo = $inic_codigo;
        //             $IniciativasAsignaturas->asignatura_id = $asignatura;
        //             $IniciativasAsignaturas->save();
        //     }
        // }

        // eliminar impactos internos y externos asociados
        IniciativasAmbitos::where('inic_codigo', $inic_codigo)->delete();

        $impactosInternos = $request->input('impactosInternos', []);

        if (empty($impactosInternos))
        {
            //
        }else{
            foreach ($impactosInternos as $impactosInterno) {
                    $IniciativasAmbitos = new IniciativasAmbitos();
                    $IniciativasAmbitos->inic_codigo = $inic_codigo;
                    $IniciativasAmbitos->amb_codigo = $impactosInterno;
                    $IniciativasAmbitos->save();
            }
        }

        $impactosExternos = $request->input('impactosExternos', []);
        if (empty($impactosExternos))
        {
            //
        }else{
            foreach ($impactosExternos as $impactosExterno) {
                    $IniciativasAmbitos2 = new IniciativasAmbitos();
                    $IniciativasAmbitos2->inic_codigo = $inic_codigo;
                    $IniciativasAmbitos2->amb_codigo = $impactosExterno;
                    $IniciativasAmbitos2->save();
            }
        }
        // ParticipantesInternos::where('inic_codigo', $inic_codigo)->delete();
        // ParticipantesInternos::where('inic_codigo', $inic_codigo)->delete();
        $pain = [];
        $sedes = $request->input('sedes', []);
        $escuelas = $request->input('escuelas', []);
        $carreras = $request->input('carreras', []);




        $existentes = ParticipantesInternos::where('inic_codigo', $inic_codigo)->get();

        foreach ($existentes as $existente) {
            $sedeExistente = in_array($existente->sede_codigo, $sedes);
            $escuelaExistente = in_array($existente->escu_codigo, $escuelas);
            $carreraExistente = in_array($existente->care_codigo, $carreras);

            if (!$sedeExistente || !$escuelaExistente || !$carreraExistente) {
                ParticipantesInternos::where([
                    'inic_codigo' => $inic_codigo,
                    'sede_codigo' => $existente->sede_codigo,
                    'escu_codigo' => $existente->escu_codigo,
                    'care_codigo' => $existente->care_codigo
                ])->delete();
            }
        }
        foreach ($sedes as $sede) {
            foreach ($escuelas as $escuela) {
                foreach ($carreras as $carrera) {
                    $sede_escuela = SedesEscuelas::where('sede_codigo', $sede)
                        ->where('escu_codigo', $escuela)
                        ->exists();

                    $escuela_carrera = Carreras::where(
                        'escu_codigo',
                        $escuela
                    )->where('care_codigo', $carrera)->exists();

                    $escuela_sede = ParticipantesInternos::where([
                        'sede_codigo' => $sede,
                        'escu_codigo' => $escuela,
                        'care_codigo' => $carrera,
                        'inic_codigo' => $inic_codigo
                    ])->exists();

                    if ($sede_escuela && !$escuela_sede && $escuela_carrera) {
                        array_push($pain, [
                            'inic_codigo' => $inic_codigo,
                            'sede_codigo' => $sede,
                            'escu_codigo' => $escuela,
                            'care_codigo' => $carrera,
                        ]);
                    }
                }
            }
        }

        $painCrear = ParticipantesInternos::insert($pain);
        if (!$painCrear) {
            ParticipantesInternos::where('inic_codigo', $inic_codigo)->delete();
            return redirect()->back()->with('errorPaso1', 'Ocurrió un error durante el registro de las unidades, intente más tarde.')->withInput();
        }

        IniciativasPais::where('inic_codigo', $inic_codigo)->delete();
        IniciativasRegiones::where('inic_codigo', $inic_codigo)->delete();
        IniciativasComunas::where('inic_codigo', $inic_codigo)->delete();

        IniciativasPais::create([
            'inic_codigo' => $inic_codigo,
            'pais_codigo' => $request->pais,
            'pain_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'pain_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'pais_nickname_mod' => Session::get('admin')->usua_nickname,
            'pain_rol_mod' => Session::get('admin')->rous_codigo,
        ]);

        $regi = [];
        $regiones = $request->input('region', []);

        foreach ($regiones as $region) {
            array_push(
                $regi,
                [
                    'inic_codigo' => $inic_codigo,
                    'regi_codigo' => $region,
                    'rein_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                    'rein_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                    'rein_nickname_rol' => Session::get('admin')->usua_nickname,
                    'rein_rol_mod' => Session::get('admin')->rous_codigo,
                ]
            );
        }

        $regiCrear = IniciativasRegiones::insert($regi);

        if (!$regiCrear) {
            IniciativasRegiones::where('inic_codigo', $inic_codigo)->delete();
            return redirect()->back()->with('regiError', 'Ocurrió un error durante el registro de las regiones, intente más tarde.')->withInput();
        }

        $comu = [];
        $comunas = $request->input('comuna', []);

        foreach ($comunas as $comuna) {
            array_push($comu, [
                'inic_codigo' => $inic_codigo,
                'comu_codigo' => $comuna,
                'coin_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                'coin_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                'coin_nickname_mod' => Session::get('admin')->usua_nickname,
                'coin_rol_mod' => Session::get('admin')->rous_codigo,
            ]);
        }

        $comuCrear = IniciativasComunas::insert($comu);
        $odsValues = $request->ods_values ?? [];
        $odsMetasValues = $request->ods_metas_values ?? [];
        $odsMetasDescValues = $request->ods_metas_desc_values ?? [];
        $fundamentoOds = $request->ods_fundamentos_values ?? [];

        // Eliminar registros existentes

        $odsValues = array_filter($odsValues, function ($value) {
            return $value !== null;
        });

        $odsMetasValues = array_filter($odsMetasValues, function ($value) {
            return $value !== null;
        });

        $odsMetasDescValues = array_filter($odsMetasDescValues, function ($value) {
            return $value !== null;
        });

        $fundamentoOds = array_filter($fundamentoOds, function ($value) {
            return $value !== null;
        });

        // dd($odsMetasValues);
        //Verifica si viene en request existe el campo ods_values, ods_metas_values, ods_metas_desc_values, ods_fundamentos_values tienen valores asignados, si no los tienen no se actualizan
        if (empty($odsValues) && empty($odsMetasValues) && empty($odsMetasDescValues) && empty($fundamentoOds)) {
            // if(empty($request->ods_values) && empty($request->ods_metas_values) && empty($request->ods_metas_desc_values) && empty($request->ods_fundamentos_values)){
            // dd('estoy aca');
            return redirect()->route('admin.editar.paso2', $inic_codigo)->with('exitoPaso1', 'Los datos de la iniciativa se actualizaron correctamente');
        } else {
            // dd('estoy aqui');

            //Verifica si existen registros con el inic_codigo en la tabla pivote_ods y metas_inic, si existen los elimina

            PivoteOds::where('inic_codigo', $inic_codigo)->delete();
            MetasInic::where('inic_codigo', $inic_codigo)->delete();

            // Eliminar duplicados de $fundamentoOds
            $fundamentoOds = array_unique($fundamentoOds);


            foreach ($odsValues as $ods) {
                $idOds = Ods::where('id_ods', $ods)->value('id_ods');
                PivoteOds::create([
                    'inic_codigo' => $inic_codigo,
                    'id_ods' => $idOds,
                ]);
            }
            //contar total de elementos en el arreglo de fundamentoOds
            $totalFundamentos = count($fundamentoOds);
            try {
                $fundamentosNew = explode('.', ($fundamentoOds[0]));
            } catch (\Throwable $th) {
                try {
                    $fundamentosNew = explode('.', ($fundamentoOds[1]));
                } catch (\Throwable $th) {
                    try {
                        $fundamentosNew = explode('.', ($fundamentoOds[2]));
                    } catch (\Throwable $th) {
                        try {
                            $fundamentosNew = explode('.', ($fundamentoOds[3]));
                        } catch (\Throwable $th) {
                            try {
                                $fundamentosNew = explode('.', ($fundamentoOds[4]));
                            } catch (\Throwable $th) {
                                try {
                                    $fundamentosNew = explode('.', ($fundamentoOds[5]));
                                } catch (\Throwable $th) {
                                    dd('error, Contacte un administrador para obtener ayuda y crear la iniciativa');
                                }
                            }
                        }
                    }
                }
            }

            $fundamentosNew = array_map('trim', $fundamentosNew);



            //quitar elemento si es ""
            foreach ($fundamentosNew as $key => $value) {
                if ($value == "") {
                    unset($fundamentosNew[$key]);
                }
            }

            //indexar todos los arreglos para las metas
            $odsMetasValues = array_values($odsMetasValues);
            $odsMetasDescValues = array_values($odsMetasDescValues);
            $fundamentosNew = array_values($fundamentosNew);

            $metasExistentes = MetasInic::where('inic_codigo', $inic_codigo)->get();

            // dd( $request->all(),$odsMetasValues, $odsMetasDescValues, $fundamentosNew, $metasExistentes);
            for ($i = 0; $i < count($odsMetasValues); $i++) {
                MetasInic::create([
                    'inic_codigo' => $inic_codigo,
                    'meta_ods' => $odsMetasValues[$i],
                    'desc_meta' => $odsMetasDescValues[$i],
                    'fundamento' => $fundamentosNew[$i],
                ]);
            }
        }

        if (!$comuCrear) {
            IniciativasComunas::where('inic_codigo', $inic_codigo)->delete();
            return redirect()->back()->with('comuError', 'Ocurrió un error durante el registro de las comunas, intente más tarde.')->withInput();
        }



        return redirect()->route('admin.editar.paso2', $inic_codigo)->with('exitoPaso1', 'Los datos de la iniciativa se actualizaron correctamente');

    }


    public function editarPaso2($inic_codigo)
    {
        $iniciativaActual = Iniciativas::where('inic_codigo', $inic_codigo)->first();


        $sedes = ParticipantesInternos::where('inic_codigo', $inic_codigo)
            ->join('sedes', 'sedes.sede_codigo', '=', 'participantes_internos.sede_codigo')
            ->select('sedes.sede_codigo', 'sedes.sede_nombre')
            ->distinct()->get();

        $escuelas = ParticipantesInternos::where('inic_codigo', $inic_codigo)
            ->join('escuelas', 'escuelas.escu_codigo', '=', 'participantes_internos.escu_codigo')
            ->select('escuelas.escu_codigo', 'escuelas.escu_nombre')
            ->distinct()->get();

        $carreras = ParticipantesInternos::where('inic_codigo', $inic_codigo)
            ->join('carreras', 'carreras.care_codigo', '=', 'participantes_internos.care_codigo')
            ->select('carreras.care_codigo', 'carreras.care_nombre')
            ->distinct()->get();

        $subGrupos = SubGruposInteres::all();
        $grupos = Grupos::all();
        $gruposIni = IniciativasGrupos::select('grup_codigo')->where('inic_codigo', $inic_codigo)->get();
        $socios = SociosComunitarios::all();
        $escuelasTotales = Escuelas::all();
        $carrerasTotales = Carreras::all();
        $grupos = GruposInteres::orderBy('grin_codigo', 'asc')->get();
        $subgrupos = SubGruposInteres::all();

        $grupoIniCod = [];

        $tematicas = Tematicas::all();
        $tematicasIni = IniciativasTematicas::select('tema_codigo')->where('inic_codigo', $inic_codigo)->get();
        $temaIniCod = [];

        foreach ($gruposIni as $registro) {
            array_push($grupoIniCod, $registro->grup_codigo);
        }
        foreach ($tematicasIni as $registro) {
            array_push($temaIniCod, $registro->tema_codigo);
        }

        // return $grupoIniCod;

        return view('admin.iniciativas.paso2', [
            'iniciativa' => $iniciativaActual,
            'subgrupos' => $subGrupos,
            'grupos' => $grupos,
            'tematicas' => $tematicas,
            'sedes' => $sedes,
            'escuelas' => $escuelas,
            'carreras' => $carreras,
            'gruposSec' => $grupoIniCod,
            'tematicasSec' => $temaIniCod,
            'escuelasTotales' => $escuelasTotales,
            'carrerasTotales' => $carrerasTotales,
            'socios' => $socios,
            'grupos' => $grupos,
            'subgrupos' => $subgrupos

        ]);

    }

    public function verificarPaso2(Request $request, $inic_codigo)
    {
        $ingr = [];
        $inte = [];
        $grupos = $request->input('grupos', []);
        $tematicas = $request->input('tematicas', []);

        IniciativasGrupos::where('inic_codigo', $inic_codigo)->delete();
        IniciativasTematicas::where('inic_codigo', $inic_codigo)->delete();

        foreach ($grupos as $grupo) {
            array_push($ingr, [
                'inic_codigo' => $inic_codigo,
                'grup_codigo' => $grupo,
                'ingr_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                'ingr_nickname_mod' => Session::get('admin')->usua_nickname,
                'ingr_rol_mod' => Session::get('admin')->rous_codigo,
            ]);
        }

        foreach ($tematicas as $tematica) {
            array_push($inte, [
                'inic_codigo' => $inic_codigo,
                'tema_codigo' => $tematica,
                'inte_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                'inte_nickname_mod' => Session::get('admin')->usua_nickname,
                'inte_rol_mod' => Session::get('admin')->rous_codigo,
            ]);
        }

        //todo:falta hacer validaciones
        IniciativasGrupos::insert($ingr);
        IniciativasTematicas::insert($inte);

        return redirect()->route('admin.iniciativa.listar')->with('exitoIniciativa', 'La iniciativa se registró correctamente');
    }

    public function listadoResultados($inic_codigo)
    {
        $resuVerificar = Resultados::where('inic_codigo', $inic_codigo)->count();
        // return $resuVerificar;

        if ($resuVerificar == 0)
            return redirect()->back()->with('errorIniciativa', 'La iniciativa no posee resultados esperados.');

        $inicObtener = Iniciativas::where('inic_codigo', $inic_codigo)->first();

        $participantes = Resultados::where('inic_codigo', $inic_codigo)->get();

        return view('admin.iniciativas.resultados', ['iniciativa' => $inicObtener, 'participantes' => $participantes]);
    }

    public function eliminarIniciativas(Request $request)
    {
        $iniciativa = Iniciativas::where('inic_codigo', $request->inic_codigo)->first();

        if (!$iniciativa) {
            return redirect()->route('admin.iniciativa.listar')->with('errorIniciativa', 'La iniciativa no se encuentra registrada en el sistema.');
        }
        Resultados::where('inic_codigo', $request->inic_codigo)->delete();
        IniciativasComunas::where('inic_codigo', $request->inic_codigo)->delete();
        IniciativasGrupos::where('inic_codigo', $request->inic_codigo)->delete();
        IniciativasPais::where('inic_codigo', $request->inic_codigo)->delete();
        IniciativasParticipantes::where('inic_codigo', $request->inic_codigo)->delete();
        IniciativasRegiones::where('inic_codigo', $request->inic_codigo)->delete();
        IniciativasTematicas::where('inic_codigo', $request->inic_codigo)->delete();
        ParticipantesInternos::where('inic_codigo', $request->inic_codigo)->delete();
        IniciativasEvidencias::where('inic_codigo', $request->inic_codigo)->delete();
        Iniciativas::where('inic_codigo', $request->inic_codigo)->delete();


        return redirect()->route('admin.iniciativa.listar')->with('exitoIniciativa', 'La iniciativa fue eliminada correctamente.');
    }


    public function guardarSocioComunitario(Request $request)
    {
        $validacion = $request->validate([
            'nombre' => 'required',
            'nombrec' => 'required',
            'subgrupo' => 'required',
            'sedesT' => 'required',
        ], [
            'nombre.required' => 'El nombre del socio es un parametro requerido.',
            'nombrec.required' => 'El nombre de la contraparte es un parámetro requerido.',
            'subgrupo.required' => 'El socio tiene que formar parte de un subgrupo.',
            'sudesT.required' => 'Es necesario que seleccione al menos una sede a la cual este asociada el socio comunitario.',

        ]);

        if (!$validacion) {

            return redirect()->back()->withErrors($validacion)->withInput();
        }

        $socoCrear = SociosComunitarios::insertGetId([
            'soco_nombre_socio' => $request->nombre,
            'soco_nombre_contraparte' => $request->nombrec,
            'soco_telefono_contraparte' => $request->telefono,
            'soco_email_contraparte' => $request->emailc,
            'sugr_codigo' => $request->subgrupo
        ]);

        if (!$socoCrear) {
            return redirect()->back()->with('socoError', 'Ocurrió un error al ingresar al socio, intente más tarde.')->withInput();
        }

        $soco_codigo = $socoCrear;

        $seso = [];
        $sedes = $request->input('sedesT', []);

        foreach ($sedes as $sede) {
            array_push($seso, [
                'sede_codigo' => $sede,
                'soco_codigo' => $soco_codigo,
                'seso_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                'seso_nickname_mod' => Session::get('admin')->usua_nickname,
                'seso_rol_mod' => Session::get('admin')->rous_codigo,
            ]);
        }

        $sesoCrear = SedesSocios::insert($seso);
        if (!$sesoCrear) {
            SedesSocios::where('soco_codigo', $soco_codigo)->delete();
            return redirect()->back()->with('socoError', 'Ocurrió un error durante el registro de las sedes, intente más tarde.')->withInput();
        }

        return redirect()->back()->with('socoExito', 'Se agregó el socio comunitario correctamente.')->withInput();
    }


    public function escuelasBySedesPaso2(Request $request)
    {
        $escuelas = ParticipantesInternos::where(['sede_codigo' => $request->sedes, 'inic_codigo' => $request->inic_codigo])
            ->join('escuelas', 'escuelas.escu_codigo', '=', 'participantes_internos.escu_codigo')
            ->get();
        return response()->json($escuelas);
    }

    public function agregarExternos(Request $request)
    {
        if (Session::has('admin')) {
            $rolePrefix = 'admin';
        } elseif (Session::has('digitador')) {
            $rolePrefix = 'digitador';
        } elseif (Session::has('observador')) {
            $rolePrefix = 'observador';
        } elseif (Session::has('supervisor')) {
            $rolePrefix = 'supervisor';
        }


        $validar = IniciativasParticipantes::where(
            [
                "inic_codigo" => $request->inic_codigo,
                "soco_codigo" => $request->soco_codigo
            ]
        )->first();

        $sugr_codigo = SociosComunitarios::where('soco_codigo', $request->soco_codigo)->value('sugr_codigo');
        if (!$validar) {
            $externosCrear = IniciativasParticipantes::insertGetId([
                'inic_codigo' => $request->inic_codigo,
                'soco_codigo' => $request->soco_codigo,
                'sugr_codigo' => $sugr_codigo,
                'inpr_total' => $request->inpr_total,
                'inpr_creado' => Carbon::now('America/Santiago')->format('Y-m-d H:i:s'),
                'inpr_actualizado' => Carbon::now('America/Santiago')->format('Y-m-d H:i:s'),
                'inpr_nickname_mod' => Session::get($rolePrefix)->usua_nickname,
                'inpr_rol_mod' => Session::get($rolePrefix)->rous_codigo,
            ]);

        } else {

            IniciativasParticipantes::where(
                [
                    "inic_codigo" => $request->inic_codigo,
                    "sugr_codigo" => $request->sugr_codigo,
                    "soco_codigo" => $request->soco_codigo
                ]
            )
                ->update([
                    'inpr_total' => $request->inpr_total,
                    'inpr_actualizado' => Carbon::now('America/Santiago')->format('Y-m-d H:i:s'),
                    'inpr_nickname_mod' => Session::get($rolePrefix)->usua_nickname,
                    'inpr_rol_mod' => Session::get($rolePrefix)->rous_codigo,
                ]);

        }

        $externos = IniciativasParticipantes::join('sub_grupos_interes', 'sub_grupos_interes.sugr_codigo', '=', 'iniciativas_participantes.sugr_codigo')
            ->join('socios_comunitarios', 'socios_comunitarios.soco_codigo', '=', 'iniciativas_participantes.soco_codigo')
            ->where('iniciativas_participantes.inic_codigo', $request->inic_codigo)
            ->get();

        //todo:falta hacer validación



        return json_encode(["estado" => true, "resultado" => $externos]);
    }

    public function listarExternos(Request $request)
    {
        $externos = IniciativasParticipantes::join('sub_grupos_interes', 'sub_grupos_interes.sugr_codigo', '=', 'iniciativas_participantes.sugr_codigo')
            ->join('socios_comunitarios', 'socios_comunitarios.soco_codigo', '=', 'iniciativas_participantes.soco_codigo')
            ->where('iniciativas_participantes.inic_codigo', $request->inic_codigo)
            ->get();

        return json_encode(["estado" => true, "resultado" => $externos]);
    }

    public function eliminarExterno(Request $request)
    {
        $externo = IniciativasParticipantes::where(['inic_codigo' => $request->inic_codigo, 'sugr_codigo' => $request->sugr_codigo, 'soco_codigo' => $request->soco_codigo])->first();

        if (!$externo) {
            return json_encode(['estado' => false, 'resultado' => 'El socio o subgrupo no estan asociados a las iniciativa']);
        }

        $externoEliminar = IniciativasParticipantes::where(['inic_codigo' => $request->inic_codigo, 'sugr_codigo' => $request->sugr_codigo, 'soco_codigo' => $request->soco_codigo])->delete();
        if (!$externoEliminar) {
            return json_encode(['estado' => false, 'resultado' => 'Ocurrio un error al eliminar el registro seleccionado']);
        }

        return json_encode(['estado' => true, 'resultado' => 'El registro se elimino correctamente']);
    }
    public function listarInternos(Request $request)
    {

        $internos = ParticipantesInternos::join('carreras', 'carreras.care_codigo', '=', 'participantes_internos.care_codigo')
            ->join('escuelas', 'escuelas.escu_codigo', '=', 'participantes_internos.escu_codigo')
            ->join('sedes', 'sedes.sede_codigo', '=', 'participantes_internos.sede_codigo')
            ->where('inic_codigo', $request->inic_codigo)
            ->get();
        return json_encode(["estado" => true, "resultado" => $internos]);
    }

    public function actualizarInternos(Request $request)
    {
        $actualizarInternos = ParticipantesInternos::where(
            [
                'inic_codigo' => $request->inic_codigo,
                'sede_codigo' => $request->sede_codigo,
                'escu_codigo' => $request->escu_codigo,
                'care_codigo' => $request->care_codigo
            ]
        )->update([
                    'pain_docentes' => $request->pain_docentes,
                    'pain_estudiantes' => $request->pain_estudiantes,
                    'pain_funcionarios' => $request->pain_funcionarios,
                    'pain_total' => $request->pain_total
                ]);

        $internos = ParticipantesInternos::join('carreras', 'carreras.care_codigo', '=', 'participantes_internos.care_codigo')
            ->join('escuelas', 'escuelas.escu_codigo', '=', 'participantes_internos.escu_codigo')
            ->join('sedes', 'sedes.sede_codigo', '=', 'participantes_internos.sede_codigo')
            ->where('inic_codigo', $request->inic_codigo)
            ->get();
        return json_encode(["estado" => true, "resultado" => $internos, "internos" => $actualizarInternos]);
    }

    public function escuelasBySede(Request $request)
    {

        $sedeIds = $request->input('sedes', []);
        $escuelas = SedesEscuelas::whereIn('sede_codigo', $sedeIds)
            ->join('escuelas', 'escuelas.escu_codigo', '=', 'sedes_escuelas.escu_codigo')
            ->select('escuelas.escu_nombre', 'escuelas.escu_codigo')
            ->distinct()
            ->get();

        return response()->json($escuelas);
    }

    public function escuelasBySedes(Request $request)
    {
        $sedesIds = $request->input('sedes', []);
        if (empty($sedesIds)) {
            $escuelas = Escuelas::select('escuelas.escu_nombre', 'escuelas.escu_codigo')
            ->distinct()
            ->get();
            return response()->json($escuelas);
        }
        $escuelas = Escuelas::join('sedes_escuelas', 'sedes_escuelas.escu_codigo', '=', 'escuelas.escu_codigo')
            ->whereIn('sedes_escuelas.sede_codigo', $sedesIds)
            ->select('escuelas.escu_nombre', 'escuelas.escu_codigo')
            ->distinct()
            ->get();
        return response()->json($escuelas);
    }

    public function comunasByRegiones(Request $request)
    {

        $regionesIds = $request->input('regiones', []);
        $comunas = Comuna::whereIn('regi_codigo', $regionesIds)
            ->select('comunas.comu_nombre', 'comunas.comu_codigo')
            ->get();

        return response()->json($comunas);
    }

    public function regionesByMacrozonas(Request $request)
    {
        $macrozonaJson = $request->all('macrozona');
        $macrozonaNombre = $macrozonaJson['macrozona'];
        $regiones = Region::where('regi_macrozona', $macrozonaNombre)
        ->select('regiones.regi_nombre', 'regiones.regi_codigo')
        ->get();

        return response()->json($regiones);
    }
    public function DispositivoByInstrumento(Request $request)
    {
        $instrumento = $request->input('tactividad');

       try {
        $dispositivos = Dispositivos::where('tiac_codigo', $instrumento)
        ->select('dispositivo.id', 'dispositivo.nombre')
        ->get();
       } catch (\Throwable $th) {
        return response()->json(['error' => 'No se encontraron dispositivos asociados a este instrumento']);
       }

        return response()->json($dispositivos);
    }

    public function ImpactoInternoByInstrumento(Request $request)
    {
        $instrumento = $request->input('tactividad');

       try {
        $impactosInternos = Ambitos::join('ambito_tiac', 'ambito_tiac.amb_codigo', '=', 'ambito.amb_codigo')
        ->leftjoin('tipo_actividades', 'tipo_actividades.tiac_codigo', '=', 'ambito_tiac.tiac_codigo')
        ->select('ambito.amb_codigo', 'ambito.amb_nombre')
        ->where('ambito.amb_descripcion', 'Impacto Interno')
        ->where('ambito_tiac.tiac_codigo', $instrumento)
        ->get();
       } catch (\Throwable $th) {
        return response()->json(['error' => 'No se encontraron impactos internos asociados a este instrumento']);
       }

        return response()->json($impactosInternos);

    }

    public function ImpactoExternoByInstrumento(Request $request)
    {
        $instrumento = $request->input('tactividad');

       try {
        $impactosExternos = Ambitos::join('ambito_tiac', 'ambito_tiac.amb_codigo', '=', 'ambito.amb_codigo')
        ->leftjoin('tipo_actividades', 'tipo_actividades.tiac_codigo', '=', 'ambito_tiac.tiac_codigo')
        ->select('ambito.amb_codigo', 'ambito.amb_nombre')
        ->where('ambito.amb_descripcion', 'Impacto Externo')
        ->where('ambito_tiac.tiac_codigo', $instrumento)
        ->get();
       } catch (\Throwable $th) {
        return response()->json(['error' => 'No se encontraron impactos internos asociados a este instrumento']);
       }

        return response()->json($impactosExternos);

    }



    public function sociosBySubgrupos(Request $request)
    {

        $socio = SociosComunitarios::where('sugr_codigo', $request->sugr_codigo)->get();
        return response()->json($socio);
    }

    public function actividadesByMecanismos(Request $request)
    {
        // $actividades = DB::table('mecanismos_actividades')
        //     ->join('tipo_actividades', 'tipo_actividades.tiac_codigo', '=', 'mecanismos_actividades.tiac_codigo')
        //     ->where('mecanismos_actividades.prog_codigo', '=', $request->programa)
        //     ->select('tipo_actividades.*')
        //     ->get();
        $actividades = MecanismosActividades::join('tipo_actividades', 'tipo_actividades.tiac_codigo', '=', 'mecanismos_actividades.tiac_codigo')
            ->where('mecanismos_actividades.meca_codigo', '=', $request->mecanismo)
            ->get();
        return response()->json($actividades);
    }

    public function mecanismoByActividades(Request $request)
    {
        $mecanismos = MecanismosActividades::select('mecanismos.meca_codigo', 'mecanismos.meca_nombre')
            ->join('mecanismos', 'mecanismos.meca_codigo', '=', 'mecanismos_actividades.meca_codigo')
            ->where('mecanismos_actividades.tiac_codigo', '=', $request->actividad)
            ->get();
        return response()->json($mecanismos);
    }

    public function paisByTerritorio(Request $request)
    {
        $pais = '';
        if ($request->pais == 'nacional') {
            $pais = Pais::where('pais_codigo', 1)->get();
        } else {
            $pais = Pais::where('pais_codigo', '!=', 1)->get();
        }
        return response()->json($pais);
    }





    // FUNCIONES PARA EL PASO 3
    public function editarPaso3($inic_codigo)
    {
        $iniciativa = Iniciativas::where('inic_codigo', $inic_codigo)->first();
        // $inicEditar = Iniciativas::where('inic_codigo', $inic_codigo)->first();
        // $listarRegiones = Regiones::select('regi_codigo', 'regi_nombre')->orderBy('regi_codigo')->get();
        // $listarParticipantes = DB::table('participantes')
        //     ->select('inic_codigo', 'participantes.sube_codigo', 'sube_nombre')
        //     ->join('subentornos', 'subentornos.sube_codigo', '=', 'participantes.sube_codigo')
        //     ->where('inic_codigo', $inic_codigo)
        //     ->orderBy('part_creado', 'asc')
        //     ->get();
        return view('admin.iniciativas.paso3', [
            'iniciativa' => $iniciativa
        ]);
    }

    public function guardarDinero(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            [
                'iniciativa' => 'exists:iniciativas,inic_codigo',
                'entidad' => 'exists:entidades,enti_codigo'
            ],
            [
                'iniciativa.exists' => 'La iniciativa no se encuentra registrada.',
                'entidad.exists' => 'La entidad no se encuentra registrada.'
            ]
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $codiVerificar = CostosDinero::where(
            [
                'inic_codigo' => $request->iniciativa,
                'enti_codigo' => $request->entidad
            ]
        )->first();
        if (!$codiVerificar) {
            if($request->tipoaporteempresa === "vcmescuela"){
                $codiGuardar = CostosDinero::create([
                    'inic_codigo' => $request->iniciativa,
                    'enti_codigo' => $request->entidad,
                    'codi_valorizacion_vcm_escuela' => $request->valorizacion,
                    'codi_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                    'codi_nickname_mod' => Session::get('admin')->usua_nickname,
                    'codi_rol_mod' => Session::get('admin')->rous_codigo
                ]);
            }elseif($request->tipoaporteempresa === "externo"){
                $codiGuardar = CostosDinero::create([
                    'inic_codigo' => $request->iniciativa,
                    'enti_codigo' => 2,
                    'codi_valorizacion' => $request->valorizacion,
                    'codi_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                    'codi_nickname_mod' => Session::get('admin')->usua_nickname,
                    'codi_rol_mod' => Session::get('admin')->rous_codigo
                ]);
            }elseif($request->tipoaporteempresa === "vcmsede"){
                $codiGuardar = CostosDinero::create([
                    'inic_codigo' => $request->iniciativa,
                    'enti_codigo' => $request->entidad,
                    'codi_valorizacion_vcm_sede' => $request->valorizacion,
                    'codi_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                    'codi_nickname_mod' => Session::get('admin')->usua_nickname,
                    'codi_rol_mod' => Session::get('admin')->rous_codigo
                ]);
            }elseif($request->tipoaporteempresa === "vra"){
                $codiGuardar = CostosDinero::create([
                    'inic_codigo' => $request->iniciativa,
                    'enti_codigo' => $request->entidad,
                    'codi_valorizacion_vra' => $request->valorizacion,
                    'codi_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                    'codi_nickname_mod' => Session::get('admin')->usua_nickname,
                    'codi_rol_mod' => Session::get('admin')->rous_codigo
                ]);
            }elseif($request->tipoaporteempresa === "primero"){
                $codiGuardar = CostosDinero::create([
                    'inic_codigo' => $request->iniciativa,
                    'enti_codigo' => $request->entidad,
                    'codi_valorizacion' => $request->valorizacion,
                    'codi_creado' => Carbon::now()->format('Y-m-d H:i:s'),
                    'codi_nickname_mod' => Session::get('admin')->usua_nickname,
                    'codi_rol_mod' => Session::get('admin')->rous_codigo
                ]);
            }

        } else {

            if($request->tipoaporteempresa === "vcmescuela"){
                $codiGuardar = CostosDinero::where(
                    [
                        'inic_codigo' => $request->iniciativa,
                        'enti_codigo' => $request->entidad
                    ]
                )->update([
                            'codi_valorizacion_vcm_escuela' => $request->valorizacion,
                            'codi_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                            'codi_nickname_mod' => Session::get('admin')->usua_nickname,
                            'codi_rol_mod' => Session::get('admin')->rous_codigo
                        ]);
            }elseif($request->tipoaporteempresa === "vcmsede"){
                $codiGuardar = CostosDinero::where(
                    [
                        'inic_codigo' => $request->iniciativa,
                        'enti_codigo' => $request->entidad
                    ]
                )->update([
                            'codi_valorizacion_vcm_sede' => $request->valorizacion,
                            'codi_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                            'codi_nickname_mod' => Session::get('admin')->usua_nickname,
                            'codi_rol_mod' => Session::get('admin')->rous_codigo
                        ]);
            }elseif($request->tipoaporteempresa === "vra"){
                $codiGuardar = CostosDinero::where(
                    [
                        'inic_codigo' => $request->iniciativa,
                        'enti_codigo' => $request->entidad
                    ]
                )->update([
                            'codi_valorizacion_vra' => $request->valorizacion,
                            'codi_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                            'codi_nickname_mod' => Session::get('admin')->usua_nickname,
                            'codi_rol_mod' => Session::get('admin')->rous_codigo
                        ]);
            }elseif($request->tipoaporteempresa === "primero"){
                $codiGuardar = CostosDinero::where(
                    [
                        'inic_codigo' => $request->iniciativa,
                        'enti_codigo' => $request->entidad
                    ]
                )->update([
                            'codi_valorizacion' => $request->valorizacion,
                            'codi_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
                            'codi_nickname_mod' => Session::get('admin')->usua_nickname,
                            'codi_rol_mod' => Session::get('admin')->rous_codigo
                        ]);
            }
        }

        if (!$codiGuardar)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al guardar el recurso, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'El recurso fue guardado correctamente.']);
    }
    public function consultarDinero(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            ['iniciativa' => 'exists:iniciativas,inic_codigo'],
            ['iniciativa.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $codiListar = CostosDinero::select(
            'enti_codigo',
            DB::raw('COALESCE(SUM(codi_valorizacion), 0) AS suma_dinero'),
            DB::raw('COALESCE(SUM(codi_valorizacion_vcm_sede), 0) AS suma_dinero_vcm_sede'),
            DB::raw('COALESCE(SUM(codi_valorizacion_vcm_escuela), 0) AS suma_dinero_vcm_escuela'),
            DB::raw('COALESCE(SUM(codi_valorizacion_vra), 0) AS suma_dinero_vra')
        )->where('inic_codigo', $request->iniciativa)
            ->groupBy('enti_codigo')
            ->get();
        return json_encode(['estado' => true, 'resultado' => $codiListar]);
    }


    public function guardarResultado(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            [
                'iniciativa' => 'exists:iniciativas,inic_codigo',
                'cantidad' => 'required|integer|min:1',
                'nombre' => 'required|max:100'
            ],
            [
                'iniciativa.exists' => 'La iniciativa no se encuentra registrada.',
                'cantidad.required' => 'La cuantificación es requerida.',
                'cantidad.integer' => 'La cuantificación debe ser un número entero.',
                'cantidad.min' => 'La cuantificación debe ser un número mayor o igual que uno.',
                'nombre.required' => 'Nombre del resultado es requerido.',
                'nombre.max' => 'Nombre del resultado excede el máximo de caracteres permitidos (100).'
            ]
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $resuGuardar = Resultados::create([
            'inic_codigo' => $request->inic_codigo,
            'resu_nombre' => $request->nombre,
            'resu_cuantificacion_inicial' => $request->cantidad,
            'resu_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'resu_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'resu_visible' => 1,
            'resu_nickname_mod' => Session::get('admin')->usua_nickname,
            'resu_rol_mod' => Session::get('admin')->rous_codigo
        ]);
        if (!$resuGuardar)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al guardar el resultado esperado, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'El resultado esperado fue registrado correctamente.']);
    }

    public function listarResultados(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            ['iniciativa' => 'exists:iniciativas,inic_codigo'],
            ['iniciativa.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $resuListar = Resultados::join('iniciativas', 'iniciativas.inic_codigo', '=', 'resultados.inic_codigo')
            ->select('resu_codigo', 'resultados.inic_codigo', 'resu_nombre', 'resu_cuantificacion_inicial')
            ->where('resultados.inic_codigo', $request->iniciativa)
            ->orderBy('resu_creado', 'asc')
            ->get();
        if (sizeof($resuListar) == 0)
            return json_encode(['estado' => false, 'resultado' => '']);
        return json_encode(['estado' => true, 'resultado' => $resuListar]);
    }

    public function eliminarResultado(Request $request)
    {
        $resuVerificar = Resultados::where(['inic_codigo' => $request->inic_codigo, 'resu_codigo' => $request->resu_codigo])->first();
        if (!$resuVerificar)
            return json_encode(['estado' => false, 'resultado' => 'El resultado esperado no se encuentra asociado a la iniciativa.']);

        $resuEliminar = Resultados::where(['inic_codigo' => $request->inic_codigo, 'resu_codigo' => $request->resu_codigo])->delete();
        if (!$resuEliminar)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al eliminar el resultado esperado, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'El resultado esperado fue eliminado correctamente.']);
    }
    public function buscarTipoInfra(Request $request)
    {
        $tiinConsultar = TipoInfraestructura::select(
            'tinf_codigo',
            'tinf_valor'
        )
            ->where('tinf_codigo', $request->tipoinfra)
            ->first();
        return json_encode($tiinConsultar);
    }

    public function listarTipoInfra()
    {
        $tiinListar = TipoInfraestructura::select(
            'tinf_codigo',
            'tinf_nombre',
            'tinf_valor',
        )
            ->where('tinf_vigente', 'S')->get();
        return json_encode($tiinListar);
    }

    public function guardarInfraestructura(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            [
                'iniciativa' => 'exists:iniciativas,inic_codigo',
                'entidad' => 'exists:entidades,enti_codigo',
                'tipoinfra' => 'exists:tipo_infraestructura,tinf_codigo',
                'horas' => 'required|integer|min:0'
            ],
            [
                'iniciativa.exists' => 'La iniciativa no se encuentra registrada.',
                'entidad.exists' => 'La entidad no se encuentra registrada.',
                'tipoinfra.exists' => 'El tipo de infraestructura no se encuentra registrado.',
                'horas.required' => 'La cantidad de horas es requerida.',
                'horas.integer' => 'La cantidad de horas debe ser un número entero.',
                'horas.min' => 'La cantidad de horas debe ser un número mayor o igual que cero.'
            ]
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $coinVerificar = CostosInfraestructura::where(
            [
                'inic_codigo' => $request->iniciativa,
                'enti_codigo' => $request->entidad,
                'tinf_codigo' => $request->tipoinfra
            ]
        )->first();

        if ($coinVerificar)
            return json_encode(['estado' => false, 'resultado' => 'La infraestructura ya se encuentra asociada a la entidad.']);

        $tiinConsultar = TipoInfraestructura::select('tinf_valor')->where('tinf_codigo', $request->tipoinfra)->first();
        $coinGuardar = CostosInfraestructura::create([
            'inic_codigo' => $request->iniciativa,
            'enti_codigo' => $request->entidad,
            'tinf_codigo' => $request->tipoinfra,
            'coin_horas' => $request->horas,
            'coin_cantidad' => $request->cantidad,
            'coin_valorizacion' => $request->horas * $tiinConsultar->tinf_valor * $request->cantidad,
            'coin_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'coin_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'coin_vigente' => 'S',
            'coin_nickname_mod' => Session::get('admin')->usua_nickname,
            'coin_rol_mod' => Session::get('admin')->rous_codigo
        ]);
        if (!$coinGuardar)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al guardar la infraestructura, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'La infraestructura fue guardada correctamente.']);
    }

    public function listarInfraestructura(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            ['iniciativa' => 'exists:iniciativas,inic_codigo'],
            ['iniciativa.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $coinListar = DB::table('costos_infraestructura')
            ->select('inic_codigo', 'enti_codigo', 'costos_infraestructura.tinf_codigo', 'tinf_nombre', 'coin_horas', 'coin_cantidad', 'coin_valorizacion')
            ->join('tipo_infraestructura', 'tipo_infraestructura.tinf_codigo', '=', 'costos_infraestructura.tinf_codigo')
            ->where('inic_codigo', $request->iniciativa)
            ->orderBy('coin_creado', 'asc')
            ->get();
        if (sizeof($coinListar) == 0)
            return json_encode(['estado' => false, 'resultado' => '']);
        return json_encode(['estado' => true, 'resultado' => $coinListar]);
    }

    public function eliminarInfraestructura(Request $request)
    {
        $coinVerificar = CostosInfraestructura::where(
            [
                'inic_codigo' => $request->iniciativa,
                'enti_codigo' => $request->entidad,
                'tinf_codigo' => $request->tipoinfra
            ]
        )->first();
        if (!$coinVerificar)
            return json_encode(['estado' => false, 'resultado' => 'La infraestructura no se encuentra asociada a la iniciativa y entidad.']);

        $coinEliminar = CostosInfraestructura::where(['inic_codigo' => $request->iniciativa, 'enti_codigo' => $request->entidad, 'tinf_codigo' => $request->tipoinfra])->delete();
        if (!$coinEliminar)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al eliminar la infraestructura, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'La infraestructura fue eliminada correctamente.']);
    }


    public function consultarInfraestructura(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            ['iniciativa' => 'exists:iniciativas,inic_codigo'],
            ['iniciativa.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $coinListar = CostosInfraestructura::select('enti_codigo', DB::raw('COALESCE(SUM(coin_valorizacion), 0) AS suma_infraestructura'))->where('inic_codigo', $request->iniciativa)->groupBy('enti_codigo')->get();
        return json_encode(['estado' => true, 'resultado' => $coinListar]);
    }


    public function listarRecursos(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            ['iniciativa' => 'exists:iniciativas,inic_codigo'],
            ['iniciativa.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $codiListar = CostosDinero::select('enti_codigo', DB::raw('COALESCE(SUM(codi_valorizacion), 0) AS suma_dinero'))->where('inic_codigo', $request->iniciativa)->groupBy('enti_codigo')->get();
        //$coesListar = CostosEspecies::select('enti_codigo', DB::raw('COALESCE(SUM(coes_valorizacion), 0) AS suma_especies'))->where('inic_codigo', $request->iniciativa)->groupBy('enti_codigo')->get();
        $coinListar = CostosInfraestructura::select('enti_codigo', DB::raw('COALESCE(SUM(coin_valorizacion), 0) AS suma_infraestructura'))->where('inic_codigo', $request->iniciativa)->groupBy('enti_codigo')->get();
        $corhListar = CostosRrhh::select('enti_codigo', DB::raw('COALESCE(SUM(corh_valorizacion), 0) AS suma_rrhh'))->where('inic_codigo', $request->iniciativa)->groupBy('enti_codigo')->get();
        $resultado = ['dinero' => $codiListar, 'infraestructura' => $coinListar, 'rrhh' => $corhListar];
        return json_encode(['estado' => true, 'resultado' => $resultado]);
    }

    public function listarTipoRrhh()
    {
        $tirhListar = TipoRrhh::select('trrhh_codigo', 'trrhh_nombre')->where('trrhh_visible', 1)->get();
        return json_encode($tirhListar);
    }
    public function buscarTipoRrhh(Request $request)
    {
        $tirhConsultar = TipoRRHH::select('trrhh_codigo', 'trrhh_valor')->where('trrhh_codigo', $request->tiporrhh)->first();
        return json_encode($tirhConsultar);
    }
    public function listarRrhh(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            ['iniciativa' => 'exists:iniciativas,inic_codigo'],
            ['iniciativa.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $corhListar = DB::table('costos_rrhh')
            ->select('inic_codigo', 'enti_codigo', 'costos_rrhh.trrhh_codigo', 'trrhh_nombre', 'corh_horas', 'corh_cantidad', 'corh_valorizacion')
            ->join('tipo_rrhh', 'tipo_rrhh.trrhh_codigo', '=', 'costos_rrhh.trrhh_codigo')
            ->where('inic_codigo', $request->iniciativa)
            ->orderBy('corh_creado', 'asc')
            ->get();
        if (sizeof($corhListar) == 0)
            return json_encode(['estado' => false, 'resultado' => '']);
        return json_encode(['estado' => true, 'resultado' => $corhListar]);
    }

    public function guardarRrhh(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            [
                'iniciativa' => 'exists:iniciativas,inic_codigo',
                'entidad' => 'exists:entidades,enti_codigo',
                'tiporrhh' => 'exists:tipo_rrhh,trrhh_codigo',
                'horas' => 'required|integer|min:0'
            ],
            [
                'iniciativa.exists' => 'La iniciativa no se encuentra registrada.',
                'entidad.exists' => 'La entidad no se encuentra registrada.',
                'tiporrhh.exists' => 'El tipo de recurso humano no se encuentra registrado.',
                'horas.required' => 'La cantidad de horas es requerida.',
                'horas.integer' => 'La cantidad de horas debe ser un número entero.',
                'horas.min' => 'La cantidad de horas debe ser un número mayor o igual que cero.'
            ]
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $corhVerificar = CostosRrhh::where(
            [
                'inic_codigo' => $request->iniciativa,
                'enti_codigo' => $request->entidad,
                'trrhh_codigo' => $request->tiporrhh,
            ]
        )->first();

        if ($corhVerificar)
            return json_encode(['estado' => false, 'resultado' => 'El recurso humano ya se encuentra asociado a la entidad.']);

        $tirhConsultar = TipoRrhh::select('trrhh_valor')->where('trrhh_codigo', $request->tiporrhh)->first();

        $corhGuardar = CostosRrhh::create([
            'inic_codigo' => $request->iniciativa,
            'trrhh_codigo' => $request->tiporrhh,
            'enti_codigo' => $request->entidad,
            'corh_cantidad' => $request->cantidad,
            'corh_horas' => $request->horas,
            'corh_valorizacion' => $request->horas * $tirhConsultar->trrhh_valor * $request->cantidad,
            'corh_creado' => Carbon::now()->format('Y-m-d H:i:s'),
            'corh_actualizado' => Carbon::now()->format('Y-m-d H:i:s'),
            'corh_vigente' => 1,
            'corh_nickname_mod' => Session::get('admin')->usua_nickname,
            'corh_rol_mod' => Session::get('admin')->rous_codigo
        ]);
        if (!$corhGuardar)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al guardar el recurso humano, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'El recurso humano fue guardado correctamente.']);
    }

    public function eliminarRRHH(Request $request)
    {
        $coinVerificar = CostosRrhh::where(
            [
                'inic_codigo' => $request->iniciativa,
                'enti_codigo' => $request->entidad,
                'trrhh_codigo' => $request->tiporrhh
            ]
        )->first();
        if (!$coinVerificar)
            return json_encode(['estado' => false, 'resultado' => 'La infraestructura no se encuentra asociada a la iniciativa y entidad.']);

        $coinEliminar = CostosRrhh::where(['inic_codigo' => $request->iniciativa, 'enti_codigo' => $request->entidad, 'trrhh_codigo' => $request->tiporrhh])->delete();
        if (!$coinEliminar)
            return json_encode(['estado' => false, 'resultado' => 'Ocurrió un error al eliminar la infraestructura, intente más tarde.']);
        return json_encode(['estado' => true, 'resultado' => 'El RRHH fue eliminado correctamente.']);
    }

    public function consultarRrhh(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            ['iniciativa' => 'exists:iniciativas,inic_codigo'],
            ['iniciativa.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $corhListar = CostosRrhh::select('enti_codigo', DB::raw('COALESCE(SUM(corh_valorizacion), 0) AS suma_rrhh'))->where('inic_codigo', $request->iniciativa)->groupBy('enti_codigo')->get();
        return json_encode(['estado' => true, 'resultado' => $corhListar]);
    }

    // TODO: Evaluación de iniciativa
    public function evaluarIniciativa($inic_codigo)
    {
        $iniciativa = Iniciativas::where('inic_codigo', $inic_codigo)->get();
        $resultados = Resultados::where('inic_codigo', $inic_codigo)->get();
        $evaluaciones = Evaluacion::where('inic_codigo', $inic_codigo)->get();

        $evaluacion_estudiantes = EvaluacionTotal::where('evaluacion_total.evatotal_tipo', 0)
            ->where('evaluacion_total.inic_codigo', $inic_codigo)
            ->join('evaluacion_invitado', 'evaluacion_total.evatotal_codigo', '=', 'evaluacion_invitado.evatotal_codigo')
            ->get();

        $evaluacion_docentes = EvaluacionTotal::where('evaluacion_total.evatotal_tipo', 1)
            ->where('evaluacion_total.inic_codigo', $inic_codigo)
            ->join('evaluacion_invitado', 'evaluacion_total.evatotal_codigo', '=', 'evaluacion_invitado.evatotal_codigo')
            ->get();

        $evaluacion_externos = EvaluacionTotal::where('evaluacion_total.evatotal_tipo', 2)
            ->where('evaluacion_total.inic_codigo', $inic_codigo)
            ->join('evaluacion_invitado', 'evaluacion_total.evatotal_codigo', '=', 'evaluacion_invitado.evatotal_codigo')
            ->get();

        $evatipoestudiantes = EvaluacionTotal::where('evaluacion_total.evatotal_tipo', 0)
            ->where('evaluacion_total.inic_codigo', $inic_codigo)
            ->get();
        $evatipodocentes = EvaluacionTotal::where('evaluacion_total.evatotal_tipo', 1)
            ->where('evaluacion_total.inic_codigo', $inic_codigo)
            ->get();
        $evatipoexternos = EvaluacionTotal::where('evaluacion_total.evatotal_tipo', 2)
            ->where('evaluacion_total.inic_codigo', $inic_codigo)
            ->get();

        $evaluaciontotal = EvaluacionTotal::where('inic_codigo', $inic_codigo)->get();


        $evaEstudiantesTotal = count(EvaluacionTotal::where('evaluacion_total.evatotal_tipo', 0)->where('evaluacion_total.inic_codigo', $inic_codigo)->get());
        $evaDocentesTotal = count(EvaluacionTotal::where('evaluacion_total.evatotal_tipo', 1)->where('evaluacion_total.inic_codigo', $inic_codigo)->get());
        $evaExternosTotal = count(EvaluacionTotal::where('evaluacion_total.evatotal_tipo', 2)->where('evaluacion_total.inic_codigo', $inic_codigo)->get());
        $mecanismo = Iniciativas::join('mecanismos', 'mecanismos.meca_codigo', 'iniciativas.meca_codigo')
            ->select('mecanismos.meca_nombre', 'iniciativas.inic_codigo')
            ->where('iniciativas.inic_codigo', $inic_codigo)
            ->get();

        // return $mecanismo[0]->meca_nombre;
        $ambitos = Programas::join('programas_contribuciones', 'programas_contribuciones.prog_codigo', 'programas.prog_codigo')
            ->join('ambito', 'ambito.amb_codigo', 'programas_contribuciones.amb_codigo')
            ->select('ambito.amb_nombre')
            ->where('programas.prog_nombre', '$mecanismo[0]->meca_nombre')
            ->get();

        return view('admin.iniciativas.evaluacion', compact(
            'iniciativa',
            'resultados',
            'ambitos',
            'evaluaciones',
            'evaluaciontotal',
            'evaluacion_estudiantes',
            'evaluacion_docentes',
            'evaluacion_externos',
            'evatipoestudiantes',
            'evatipodocentes',
            'evatipoexternos',
            'evaEstudiantesTotal',
            'evaDocentesTotal',
            'evaExternosTotal',
        )
        );
    }
    public function evaluarIniciativaInvitar($inic_codigo)
    {
        $evaluacion_estudiantes = EvaluacionTotal::where('evaluacion_total.evatotal_tipo', 0)
            ->where('evaluacion_total.inic_codigo', $inic_codigo)
            ->join('evaluacion_invitado', 'evaluacion_total.evatotal_codigo', '=', 'evaluacion_invitado.evatotal_codigo')
            ->get();
        $evaluacion_docentes = EvaluacionTotal::where('evaluacion_total.evatotal_tipo', 1)
            ->where('evaluacion_total.inic_codigo', $inic_codigo)
            ->join('evaluacion_invitado', 'evaluacion_total.evatotal_codigo', '=', 'evaluacion_invitado.evatotal_codigo')
            ->get();
        $evaluacion_externos = EvaluacionTotal::where('evaluacion_total.evatotal_tipo', 2)
            ->where('evaluacion_total.inic_codigo', $inic_codigo)
            ->join('evaluacion_invitado', 'evaluacion_total.evatotal_codigo', '=', 'evaluacion_invitado.evatotal_codigo')
            ->get();

        $iniciativa = Iniciativas::where('inic_codigo', $inic_codigo)->get();

        return view('admin.iniciativas.invitar', compact('evaluacion_estudiantes', 'evaluacion_docentes', 'evaluacion_externos', 'inic_codigo', 'iniciativa'));
    }
    public function invitarEvaluacion(Request $request)
    {

        $evaluacion_estudiantes = EvaluacionTotal::where('evaluacion_total.evatotal_tipo', 0)
            ->where('evaluacion_total.inic_codigo', $request->inic_codigo)
            ->where('evaluacion_total.evatotal_tipo', 0)
            ->get();

        $evaluacion_docentes = EvaluacionTotal::where('evaluacion_total.evatotal_tipo', 1)
            ->where('evaluacion_total.inic_codigo', $request->inic_codigo)
            ->where('evaluacion_total.evatotal_tipo', 1)
            ->get();

        $evaluacion_externos = EvaluacionTotal::where('evaluacion_total.evatotal_tipo', 2)
            ->where('evaluacion_total.inic_codigo', $request->inic_codigo)
            ->where('evaluacion_total.evatotal_tipo', 2)
            ->get();


        $evaluacionInvitado = EvaluacionInvitado::where('evainv_correo', $request->correo)
            ->where('evaluacion_invitado.inic_codigo', $request->inic_codigo)->get();

        $existe = 0;
        foreach ($evaluacionInvitado as $eval) {
            if ($eval->evainv_estado == 0) {
                $existe = 1;
            }
        }
        if ($existe == 1) {
            return redirect()->back()->with('error', '¡Ya has sido invitado a responder la encuesta!');
        } else {


            $evaluacionInvitado = new EvaluacionInvitado();
            $evaluacionInvitado->evainv_correo = $request->correo;
            $evaluacionInvitado->evainv_nombre = $request->nombre;
            $evaluacionInvitado->inic_codigo = $request->inic_codigo;
            $evaluacionInvitado->evainv_estado = 0;


            if ($request->tipo == 0) {
                $evaluacionInvitado->evatotal_tipo = $request->tipo;
                $evaluacionInvitado->evatotal_codigo = $evaluacion_estudiantes[0]->evatotal_codigo;
            } elseif ($request->tipo == 1) {
                $evaluacionInvitado->evatotal_tipo = $request->tipo;
                $evaluacionInvitado->evatotal_codigo = $evaluacion_docentes[0]->evatotal_codigo;
            } elseif ($request->tipo == 2) {
                $evaluacionInvitado->evatotal_tipo = $request->tipo;
                $evaluacionInvitado->evatotal_codigo = $evaluacion_externos[0]->evatotal_codigo;
            }

            $evaluacionInvitado->save();
            return redirect()->back()->with('exito', '¡Invitación enviada correctamente!');
        }
    }
    public function crearEvaluacion(Request $request)
    {

        $evaluaciontotal = EvaluacionTotal::where('inic_codigo', $request->inic_codigo)->get();
        $iniciativa_nombre = Iniciativas::where('inic_codigo', $request->inic_codigo)->get();

        $nombre = $iniciativa_nombre[0]->inic_nombre;

        $existe = 0;
        foreach ($evaluaciontotal as $eval) {
            if ($eval->evatotal_tipo == $request->tipo) {
                $existe = 1;
            }
        }

        if ($existe != 1) {
            $evaluacion_total = new EvaluacionTotal();
            $evaluacion_total->inic_codigo = $request->inic_codigo;
            $evaluacion_total->evatotal_tipo = $request->tipo;
            $evaluacion_total->evatotal_encriptado = md5($nombre . $request->tipo);
            $evaluacion_total->save();
            return redirect()->route('admin.evaluar.iniciativa', ['inic_codigo' => $request->inic_codigo])->with('exito', 'Se ha creado una evaluación de este tipo.');
            ;
        } else {
            return redirect()->route('admin.evaluar.iniciativa', ['inic_codigo' => $request->inic_codigo])->with('error', 'Ya existe una evaluación de este tipo.');
        }



    }
    public function eliminarEvaluacionInciativa(Request $request)
    {
        $evaluacionTotal = EvaluacionTotal::where('inic_codigo', $request->inic_codigo)
        ->where('evatotal_tipo', $request->invitado_rol)
        ->get()
        ->first();
        $evatotal_codigo = $evaluacionTotal->evatotal_codigo;

        try {
            $evaluacion = Evaluacion::where('evatotal_codigo', $evatotal_codigo)->get();
            foreach ($evaluacion as $eval) {
                $eval->delete();
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        $evaluacionInvitado = EvaluacionInvitado::where('evatotal_codigo', $evatotal_codigo)->get();
        foreach ($evaluacionInvitado as $eval) {
            $eval->delete();
        }

        $evaluacionTotal->delete();

        return redirect()->route('admin.evaluar.iniciativa', ['inic_codigo' => $request->inic_codigo])->with('exito', 'Se ha eliminado la evaluación de este tipo.');
    }
    public function eliminarInvitadoEvaluacion(Request $request, $evainv_codigo)
    {
        $evaluacionInvitado = EvaluacionInvitado::where('evainv_codigo', $evainv_codigo)->first();
        $evaluacionInvitado->delete();
        return redirect()->back()->with('exito', '¡Invitación al estudiante eliminada correctamente!');
    }
    public function eliminarInvitadoEvaluacionDocente(Request $request, $evainv_codigo)
    {
        $evaluacionInvitado = EvaluacionInvitado::where('evainv_codigo', $evainv_codigo)->first();
        $evaluacionInvitado->delete();
        return redirect()->back()->with('exito', '¡Invitación al docente/directivo eliminada correctamente!');
    }
    public function eliminarInvitadoEvaluacionExterno(Request $request, $evainv_codigo)
    {
        $evaluacionInvitado = EvaluacionInvitado::where('evainv_codigo', $evainv_codigo)->first();
        $evaluacionInvitado->delete();
        return redirect()->back()->with('exito', '¡Invitación al externo eliminada correctamente!');
    }
    public function cargaIndividualEvaluacion(Request $request)
    {
        $evaluacionTotal = EvaluacionTotal::where('evaluacion_total.evatotal_tipo', $request->tipo)
            ->where('evaluacion_total.inic_codigo', $request->inic_codigo)
            ->get()
            ->first();

        $evainvitado = new EvaluacionInvitado();
        $evainvitado->evainv_nombre = $request->nombre;
        $evainvitado->evainv_correo = $request->email;
        $evainvitado->inic_codigo = $request->inic_codigo;
        $evainvitado->evainv_estado = 0;
        $evainvitado->evatotal_tipo = $request->tipo;
        $evainvitado->evatotal_codigo = $evaluacionTotal->evatotal_codigo;
        $evainvitado->save();

        return redirect()->back()->with('exito', '¡El correo electrónico se ha agregado correctamente!');

    }
    public function procesarTexto(Request $request) {
        try {
            $evaluacionTotal = EvaluacionTotal::where('evaluacion_total.evatotal_tipo', $request->tipo)
            ->where('evaluacion_total.inic_codigo', $request->inic_codigo)
            ->get()
            ->first();

        $informacion = $request->input('cargaTexto');
        //separar por \r\n
        $informacion = explode("\r\n", $informacion);
        $ccUsuarios = 0;
        foreach ($informacion as $valor) {
            try {
                $infoarray = explode("\t", $valor);
                $evainvitado = new EvaluacionInvitado();
                $evainvitado->evainv_nombre = $infoarray[0];
                $evainvitado->evainv_correo = $infoarray[1];
            } catch (\Throwable $th) {
                $infoarray = explode(" ", $valor);
                $evainvitado = new EvaluacionInvitado();
                $evainvitado->evainv_nombre = $infoarray[0];
                $evainvitado->evainv_correo = $infoarray[1];
            }
            $evainvitado->inic_codigo = $request->inic_codigo;
            $evainvitado->evainv_estado = 0;
            $evainvitado->evatotal_tipo = $request->tipo;
            $evainvitado->evatotal_codigo = $evaluacionTotal->evatotal_codigo;
            $evainvitado->save();
            $ccUsuarios++;
        }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', '¡No se han podido cargar los usuarios, por favor verifique el formato del texto!');
        }


        return redirect()->back()->with('exito', '¡Se han cargado '.$ccUsuarios.' usuarios correctamente!');

    }
    public function verEvaluacion($inic_codigo, $invitado)
    {
        //si invitado no es un numero o no es 0, 1 o 2
        if (!is_numeric($invitado) || $invitado < 0 || $invitado > 2) {
            return redirect()->back();
        }elseif($invitado == 0){
            $invitadoNombre = 'Estudiantes';
        }elseif($invitado == 1){
            $invitadoNombre = 'Docentes/Directivos';
        }elseif($invitado == 2){
            $invitadoNombre = 'Externos';
        }
        $evaluacion = Evaluacion::where('evaluacion.inic_codigo', $inic_codigo)
        ->join('evaluacion_total', 'evaluacion_total.evatotal_codigo', 'evaluacion.evatotal_codigo')
        ->where('evaluacion_total.evatotal_tipo', $invitado)->get();


        $iniciativa = Iniciativas::where('inic_codigo', $inic_codigo)->get();

        $totalEvaluadores = count($evaluacion);
        $conocimiento_1 = 0;
        $conocimiento_2 = 0;
        $conocimiento_3 = 0;
        $cumplimiento_1 = 0;
        $cumplimiento_2 = 0;
        $cumplimiento_3 = 0;
        $calidad_1 = 0;
        $calidad_2 = 0;
        $calidad_3 = 0;
        $calidad_4 = 0;
        $competencia_1 = 0;
        $competencia_2 = 0;
        $competencia_3 = 0;
        foreach ($evaluacion as $eval) {
            $conocimiento_1 = $conocimiento_1 + $eval->eval_conocimiento_1;
            $conocimiento_2 = $conocimiento_2 + $eval->eval_conocimiento_2;
            $conocimiento_3 = $conocimiento_3 + $eval->eval_conocimiento_3;
            $cumplimiento_1 = $cumplimiento_1 + $eval->eval_cumplimiento_1;
            $cumplimiento_2 = $cumplimiento_2 + $eval->eval_cumplimiento_2;
            $cumplimiento_3 = $cumplimiento_3 + $eval->eval_cumplimiento_3;
            $calidad_1 = $calidad_1 + $eval->eval_calidad_1;
            $calidad_2 = $calidad_2 + $eval->eval_calidad_2;
            $calidad_3 = $calidad_3 + $eval->eval_calidad_3;
            $calidad_4 = $calidad_4 + $eval->eval_calidad_4;
            $competencia_1 = $competencia_1 + $eval->eval_competencia_1;
            $competencia_2 = $competencia_2 + $eval->eval_competencia_2;
            $competencia_3 = $competencia_3 + $eval->eval_competencia_3;
        }
        if($totalEvaluadores != 0){
            $conocimiento_1 = $conocimiento_1 / $totalEvaluadores;
            $conocimiento_2 = $conocimiento_2 / $totalEvaluadores;
            $conocimiento_3 = $conocimiento_3 / $totalEvaluadores;
            $cumplimiento_1 = $cumplimiento_1 / $totalEvaluadores;
            $cumplimiento_2 = $cumplimiento_2 / $totalEvaluadores;
            $cumplimiento_3 = $cumplimiento_3 / $totalEvaluadores;
            $calidad_1 = $calidad_1 / $totalEvaluadores;
            $calidad_2 = $calidad_2 / $totalEvaluadores;
            $calidad_3 = $calidad_3 / $totalEvaluadores;
            $calidad_4 = $calidad_4 / $totalEvaluadores;
            $competencia_1 = $competencia_1 / $totalEvaluadores;
            $competencia_2 = $competencia_2 / $totalEvaluadores;
            $competencia_3 = $competencia_3 / $totalEvaluadores;
        }else{
            $conocimiento_1 = 0;
            $conocimiento_2 = 0;
            $conocimiento_3 = 0;
            $cumplimiento_1 = 0;
            $cumplimiento_2 = 0;
            $cumplimiento_3 = 0;
            $calidad_1 = 0;
            $calidad_2 = 0;
            $calidad_3 = 0;
            $calidad_4 = 0;
            $competencia_1 = 0;
            $competencia_2 = 0;
            $competencia_3 = 0;
        }


        return view('admin.iniciativas.resultados-evaluacion',
        ['iniciativa' => $iniciativa,
        'invitado' => $invitado,
        'invitadoNombre' => $invitadoNombre,
        'evaluacion' => $evaluacion,
        'conocimiento_1' => $conocimiento_1,
        'conocimiento_2' => $conocimiento_2,
        'conocimiento_3' => $conocimiento_3,
        'cumplimiento_1' => $cumplimiento_1,
        'cumplimiento_2' => $cumplimiento_2,
        'cumplimiento_3' => $cumplimiento_3,
        'calidad_1' => $calidad_1,
        'calidad_2' => $calidad_2,
        'calidad_3' => $calidad_3,
        'calidad_4' => $calidad_4,
        'competencia_1' => $competencia_1,
        'competencia_2' => $competencia_2,
        'competencia_3' => $competencia_3,
        'totalEvaluadores' => $totalEvaluadores

         ]);
    }

    public function evaluarIniciativa2($inic_codigo)
    {
        $iniciativa = Iniciativas::where('inic_codigo', $inic_codigo)->get();
        $resultados = Resultados::where('inic_codigo', $inic_codigo)->get();

        $mecanismo = Iniciativas::join('mecanismos', 'mecanismos.meca_codigo', 'iniciativas.meca_codigo')
            ->select('mecanismos.meca_nombre', 'iniciativas.inic_codigo')
            ->where('iniciativas.inic_codigo', $inic_codigo)
            ->get();

        // return $mecanismo[0]->meca_nombre;
        $ambitos = Programas::join('programas_contribuciones', 'programas_contribuciones.prog_codigo', 'programas.prog_codigo')
            ->join('ambito', 'ambito.amb_codigo', 'programas_contribuciones.amb_codigo')
            ->select('ambito.amb_nombre')
            ->where('prog_nombre', $mecanismo[0]->meca_nombre)
            ->get();
        // return $ambitos;
        return view('admin.iniciativas.evaluacion', compact('iniciativa', 'resultados', 'ambitos'))->with('exito', "Evaluación ingresada correctamente.");
    }

    public function listarEvaluaciones(Request $request)
    {
        $evaluaciones = Evaluacion::where('inic_codigo', $request->inic_codigo)->get();
        return json_encode(["estado" => true, "resultado" => $evaluaciones]);
    }

    public function eliminarEvaluacion(Request $request)
    {
        # Return Vista
        $iniciativa = Iniciativas::where('inic_codigo', $request->inic_codigo)->get();
        $resultados = Resultados::where('inic_codigo', $request->inic_codigo)->get();
        $evaluaciones = Evaluacion::where('inic_codigo', $request->inic_codigo)->get();

        $mecanismo = Iniciativas::join('mecanismos', 'mecanismos.meca_codigo', 'iniciativas.meca_codigo')
            ->select('mecanismos.meca_nombre', 'iniciativas.inic_codigo')
            ->where('iniciativas.inic_codigo', $request->inic_codigo)
            ->get();

        // return $mecanismo[0]->meca_nombre;
        $ambitos = Programas::join('programas_contribuciones', 'programas_contribuciones.prog_codigo', 'programas.prog_codigo')
            ->join('ambito', 'ambito.amb_codigo', 'programas_contribuciones.amb_codigo')
            ->select('ambito.amb_nombre')
            ->where('prog_nombre', $mecanismo[0]->meca_nombre)
            ->get();

        #####################################################################################
        $eval = Evaluacion::where('eval_codigo', $request->eval_codigo)->get();

        if (!$eval) {
            return view('admin.iniciativas.evaluacion', compact('iniciativa', 'resultados', 'ambitos', 'evaluaciones'))->with('error', 'La evaluación no se encuentra registrada en el sistema.');
        }

        $eval = Evaluacion::where('eval_codigo', $request->eval_codigo)->delete();
        $evaluaciones = Evaluacion::where('inic_codigo', $request->inic_codigo)->get();
        return view('admin.iniciativas.evaluacion', compact('iniciativa', 'resultados', 'ambitos', 'evaluaciones'))->with('exito', "Evaluación eliminada correctamente.");
    }

    // TODO: Calculo Evaluación
    public function guardarEvaluacion(Request $request)
    {
        dd('hola');
        $ponderado_1 = 0;
        $ponderado_2 = 0;
        $ponderado_3 = 0;
        $ponderado_4 = 0;
        $ponderado_final = 0;

        if ($request->tipo_data == 1) {
            $ponderado_1 = 0.15;
            $ponderado_2 = 0.30;
            $ponderado_3 = 0.15;
            $ponderado_4 = 0.40;
            $ponderado_final = 0.7;
        }

        if ($request->tipo_data == 2) {
            $ponderado_1 = 0.15;
            $ponderado_2 = 0.30;
            $ponderado_3 = 0.15;
            $ponderado_4 = 0.40;
            $ponderado_final = 0.3;
        }

        if ($request->tipo_data == 3) {
            $ponderado_1 = 0.20;
            $ponderado_2 = 0.50;
            $ponderado_3 = 0.30;
            $ponderado_final = 1;
            /* $ponderado_4 = 0; */
        }

        $puntaje_conocimiento = ($request->conocimiento_1_data + $request->conocimiento_2_data + $request->conocimiento_3_data) / 3 * $ponderado_1;
        $puntaje_cumplimiento = ($request->cumplimiento_1_data + $request->cumplimiento_2_data + $request->cumplimiento_3_data) / 3 * $ponderado_2;

        # VER SI APLICA: es para solo considerar los que no tenga NO APLICA marcado
        $count = 0; # Para dividir en los puntos que si aplica
        $aux1 = $request->calidad_1_data;
        $aux2 = $request->calidad_2_data;
        $aux3 = $request->calidad_3_data;
        $aux4 = $request->calidad_4_data;
        if ($aux1 != "") {
            $count = $count + 1;
        } else {
            $aux1 = 0;
        }
        if ($aux2 != "") {
            $count = $count + 1;
        } else {
            $aux2 = 0;
        }
        if ($aux3 != "") {
            $count = $count + 1;
        } else {
            $aux3 = 0;
        }
        if ($aux4 != "") {
            $count = $count + 1;
        } else {
            $aux4 = 0;
        }

        $puntaje_calidad = ($aux1 + $aux2 + $aux3 + $aux4) / $count * $ponderado_3;


        if ($request->tipo_data == 1 || $request->tipo_data == 2) {
            $puntaje_competencia = ($request->competencia_1_data + $request->competencia_2_data + $request->competencia_3_data) / 3 * $ponderado_4;
        } else {
            $puntaje_competencia = 0;
        }

        $puntaje = ($puntaje_conocimiento + $puntaje_cumplimiento + $puntaje_calidad + $puntaje_competencia) * $ponderado_final;

        $nuevo = new Evaluacion();
        $nuevo->inic_codigo = $request->iniciativa_codigo;
        $nuevo->eval_evaluador = $request->tipo_data;
        $nuevo->eval_conocimiento_1 = $request->conocimiento_1_data;
        $nuevo->eval_conocimiento_2 = $request->conocimiento_2_data;
        $nuevo->eval_conocimiento_3 = $request->conocimiento_3_data;
        $nuevo->eval_cumplimiento_1 = $request->cumplimiento_1_data;
        $nuevo->eval_cumplimiento_2 = $request->cumplimiento_2_data;
        $nuevo->eval_cumplimiento_3 = $request->cumplimiento_3_data;
        $nuevo->eval_calidad_1 = $request->calidad_1_data;
        $nuevo->eval_calidad_2 = $request->calidad_2_data;
        $nuevo->eval_calidad_3 = $request->calidad_3_data;
        $nuevo->eval_calidad_4 = $request->calidad_4_data;
        $nuevo->eval_competencia_1 = $request->competencia_1_data;
        $nuevo->eval_competencia_2 = $request->competencia_2_data;
        $nuevo->eval_competencia_3 = $request->competencia_3_data;
        $nuevo->eval_puntaje = $puntaje;

        $nuevo->eval_creado = Carbon::now('America/Santiago')->format('Y-m-d H:i:s');
        $nuevo->eval_actualizado = Carbon::now('America/Santiago')->format('Y-m-d H:i:s');
        $nuevo->eval_vigente = 1;
        $nuevo->eval_nickname_mod = Session::get('admin')->usua_nickname;
        $nuevo->eval_rol_mod = Session::get('admin')->rous_codigo;

        $nuevo->save();

        /* # PARA RETORNAR AL LISTADO
        return view('admin.iniciativas.redireccion', ['inic_codigo' => $request->iniciativa_codigo]); */
        # PARA RETORNAR AL LISTADO
        return json_encode(['estado' => true, 'resultado' => 'La evaluación fue ingresada correctamente.']);
    }
    public function iniciativaEvaluarInvitar($inic_codigo, $invitado)
    {
        //si invitado no es un numero o no es 0, 1 o 2
        if (!is_numeric($invitado) || $invitado < 0 || $invitado > 2) {
            return redirect()->back();
        }elseif($invitado == 0){
            $invitadoNombre = 'Estudiantes';
        }elseif($invitado == 1){
            $invitadoNombre = 'Docentes/Directivos';
        }elseif($invitado == 2){
            $invitadoNombre = 'Externos';
        }
        $evaluacion = EvaluacionTotal::where('evaluacion_total.evatotal_tipo', $invitado)
        ->where('evaluacion_total.inic_codigo', $inic_codigo)
        ->join('evaluacion_invitado', 'evaluacion_total.evatotal_codigo', '=', 'evaluacion_invitado.evatotal_codigo')
        ->get();

        $evaluaciontotal = EvaluacionTotal::where('inic_codigo', $inic_codigo)
        ->where('evatotal_tipo', $invitado)
        ->first();

        $invitados = EvaluacionInvitado::where('inic_codigo', $inic_codigo)
        ->where('evatotal_tipo', $invitado)
        ->get();

        $iniciativa = Iniciativas::where('inic_codigo', $inic_codigo)->get();

        return view('admin.iniciativas.invitaraevaluar', compact('evaluacion', 'inic_codigo', 'iniciativa', 'invitadoNombre', 'invitados', 'evaluaciontotal'));
    }
    public function iniciativaEvaluarInvitarCorreo($inic_codigo, $invitado)
    {
        //return $invitado;
        $invitado_rol = $invitado;
        //si invitado no es un numero o no es 0, 1 o 2
        if (!is_numeric($invitado) || $invitado < 0 || $invitado > 2) {
            return redirect()->back();
        }elseif($invitado == 0){
            $invitadoNombre = 'Estudiantes';
        }elseif($invitado == 1){
            $invitadoNombre = 'Docentes/Directivos';
        }elseif($invitado == 2){
            $invitadoNombre = 'Externos';
        }
        $evaluacion = EvaluacionTotal::where('evaluacion_total.evatotal_tipo', $invitado)
        ->where('evaluacion_total.inic_codigo', $inic_codigo)
        ->join('evaluacion_invitado', 'evaluacion_total.evatotal_codigo', '=', 'evaluacion_invitado.evatotal_codigo')
        ->get();

        $invitados = EvaluacionInvitado::where('inic_codigo', $inic_codigo)
        ->where('evatotal_tipo', $invitado)
        ->get();
        $destinatarios = "";
        foreach ($invitados as $invitado) {
            $destinatarios = $destinatarios . $invitado->evainv_correo . ', ';
        }
        //quitar la ultima coma
        $destinatarios = substr($destinatarios, 0, -2);

        $evaluaciontotal = EvaluacionTotal::where('inic_codigo', $inic_codigo)
        ->where('evatotal_tipo', $invitado_rol)
        ->first();


        $iniciativa = Iniciativas::where('inic_codigo', $inic_codigo)->get();

        return view('admin.iniciativas.correoinvitacion', compact('evaluacion', 'inic_codigo', 'iniciativa', 'invitadoNombre', 'invitados', 'destinatarios', 'evaluaciontotal', 'invitado_rol', 'iniciativa'));
    }
    public function iniciativaEvaluarEnviarCorreo(Request $request)
{
    // Obtener el nombre de la iniciativa
    $iniciativa = Iniciativas::where('inic_codigo', $request->iniciativa_codigo)->first();
    if (!$iniciativa) {
        return "La iniciativa no fue encontrada";
    }
    $iniciativaNombre = $iniciativa->inic_nombre;

    // Obtener los destinatarios y el mensaje del formulario
    $destinatarios = $request->destinatarios;
    $mensaje = $request->mensaje;
    $html = new HtmlString($mensaje);
    // Verificar si el campo destinatarios está presente y no está vacío
    if (!empty($destinatarios)) {
        $destinatarios = explode(',', $destinatarios);

        foreach ($destinatarios as $destinatario) {
            // Validar cada dirección de correo electrónico antes de enviar el correo
            $email = trim($destinatario);
            $invitado = EvaluacionInvitado::where('evainv_correo', $email)
            ->where('evaluacion_invitado.inic_codigo', $request->iniciativa_codigo)
            ->first();
            if ($invitado) {
                if ($invitado->evainv_estado == 0) {

                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        Mail::to($email)->send(new ContactFormMail($email, 'test', 'test', 'test', 'test', $html));
                        $invitado->evainv_estado = 1;
                        $invitado->save();
                    } else {
                        // Si la dirección de correo electrónico no es válida, manejar el error adecuadamente
                        return redirect()->back()->with('error', '¡"La dirección de correo electrónico $email no es válida"!');
                    }
                }else{
                    // pass
                }

            }

        }

        return redirect()->back()->with('exito', '¡El correo electrónico fue enviado correctamente a los pendientes!');
    } else {
        return "No se proporcionaron destinatarios de correo electrónico";
    }
}

    // TODO: Calculo Evaluación
    public function guardarEvaluacion2(Request $request)
    {

        $nuevo = new Evaluacion();
        $nuevo->inic_codigo = $request->iniciativa_codigo;
        $nuevo->eval_evaluador = $request->tipo_data;
        $nuevo->eval_puntaje = $request->puntaje;

        $nuevo->eval_creado = Carbon::now('America/Santiago')->format('Y-m-d H:i:s');
        $nuevo->eval_actualizado = Carbon::now('America/Santiago')->format('Y-m-d H:i:s');
        $nuevo->eval_vigente = 1;
        $nuevo->eval_nickname_mod = Session::get('admin')->usua_nickname;
        $nuevo->eval_rol_mod = Session::get('admin')->rous_codigo;

        $nuevo->save();

        # PARA RETORNAR AL LISTADO
        return json_encode(['estado' => true, 'resultado' => 'La evaluación fue ingresada correctamente.']);
    }

    //TODO: INVI
    public function datosIndice(Request $request)
    {
        $validacion = Validator::make(
            $request->all(),
            ['iniciativa' => 'exists:iniciativas,inic_codigo'],
            ['iniciativa.exists' => 'La iniciativa no se encuentra registrada.']
        );
        if ($validacion->fails())
            return json_encode(['estado' => false, 'resultado' => $validacion->errors()->first()]);

        $mecanismoDato = Iniciativas::join('mecanismos', 'mecanismos.meca_codigo', 'iniciativas.meca_codigo')
            ->select('mecanismos.meca_nombre', 'iniciativas.inic_codigo')
            ->where('iniciativas.inic_codigo', $request->iniciativa)
            ->get();

        $frecuenciaDato = Iniciativas::leftJoin('programas', 'programas.prog_codigo', '=', 'iniciativas.prog_codigo')
            ->select(
                'iniciativas.inic_codigo',
                'iniciativas.prog_codigo',
                'programas.prog_descripcion'
            )
            ->where('iniciativas.inic_codigo', $request->iniciativa)
            ->get();


        $resultados2 = DB::table('resultados')
            ->select(
                DB::raw('SUM(resu_cuantificacion_inicial) as suma_inicial'),
                DB::raw('SUM(resu_cuantificacion_final) as suma_final')
            )
            ->where('inic_codigo', $request->iniciativa)
            ->get();

        $coberturaDato = DB::table('participantes_internos')
            ->select(
                DB::raw('SUM(IFNULL(pain_docentes, 0)) as total_docentes'),
                DB::raw('SUM(IFNULL(pain_estudiantes, 0)) as total_estudiantes'),
                DB::raw('SUM(IFNULL(pain_funcionarios, 0)) as total_funcionarios'),
                DB::raw('SUM(IFNULL(pain_docentes_final, 0)) as total_docentes_final'),
                DB::raw('SUM(IFNULL(pain_estudiantes_final, 0)) as total_estudiantes_final'),
                DB::raw('SUM(IFNULL(pain_funcionarios_final, 0)) as total_funcionarios_final')
            )
            ->where('inic_codigo', $request->iniciativa)
            ->get();

        $coberturaDatoExt = DB::table('iniciativas_participantes')
            ->select(
                DB::raw('SUM(IFNULL(inpr_total, 0)) as total_externos'),
                DB::raw('SUM(IFNULL(inpr_total_final, 0)) as total_externos_final')
            )
            ->where('inic_codigo', $request->iniciativa)
            ->get();

        // $cobertura_externa =


        $evalDatos = Evaluacion::select('inic_codigo', DB::raw('COUNT(*) as total_evaluaciones'), DB::raw('SUM(eval_puntaje) as suma_evaluaciones'))
            ->groupBy('inic_codigo')
            ->get()
            ->where('inic_codigo', $request->iniciativa)->first();

        return json_encode([
            'resultado' => [
                'mecanismo' => $mecanismoDato,
                'frecuencia' => $frecuenciaDato,
                'cobertura' => $coberturaDato,
                'cobertura2' => $coberturaDatoExt,
                'resultados2' => $resultados2,
                'evaluacion' => $evalDatos
            ]
        ]);
    }

    /* public function actualizarIndice(Request $request) {
        try {
            Iniciativas::where('inic_codigo', $request->inic_codigo)->update([
                'inic_inrel' => $request->inic_inrel,
                'inic_rut_mod' => Session::get('admin')->usua_rut,
                'inic_rol_mod' => Session::get('admin')->rous_codigo
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    } */

    public function AutoInvitacionEvaluacion($evatotal_encriptado)
    {
        $evaluacion = EvaluacionTotal::where('evatotal_encriptado', $evatotal_encriptado)->get();
        $tipo = $evaluacion[0]->evatotal_tipo;

        $inic_codigo = $evaluacion[0]->inic_codigo;
        $evatotal_codigo = $evaluacion[0]->evatotal_codigo;

        $iniciativa = Iniciativas::where('inic_codigo', $inic_codigo)->get()->first();

        return view('invitarseaevaluar', compact('tipo', 'evatotal_encriptado', 'inic_codigo', 'iniciativa', 'evatotal_codigo'));
    }
    public function guardarInvitacion(Request $request)
    {
        //si ya fue invitado
        $evaluacionInvitado = EvaluacionInvitado::where('evainv_correo', $request->email)
            ->where('inic_codigo', $request->inic_codigo)
            ->where('evatotal_tipo', $request->tipo)
            ->get();
        $existe = 0;
        foreach ($evaluacionInvitado as $eval) {
            if ($eval->evainv_estado == 0) {
                $existe = 1;
            }
        }
        if ($existe == 1) {
            return redirect()->back()->with('error', '¡Ya has sido invitado a responder la encuesta!');
        }

        $evaluacionInvitado = new EvaluacionInvitado();
        $evaluacionInvitado->evainv_nombre = $request->nombre;
        $evaluacionInvitado->evainv_correo = $request->email;
        $evaluacionInvitado->inic_codigo = $request->inic_codigo;
        $evaluacionInvitado->evainv_estado = 0;
        $evaluacionInvitado->evatotal_tipo = $request->tipo;
        $evaluacionInvitado->evatotal_codigo = $request->evatotal_codigo;
        $evaluacionInvitado->save();
        return redirect()->back()->with('exito', 'Solicitud enviada correctamente!');
    }

    public function evaluaEstudiante($evatotal_encriptado)
    {
        $evaluacion = EvaluacionTotal::where('evatotal_encriptado', $evatotal_encriptado)->get();
        $tipo = $evaluacion[0]->evatotal_tipo;

        $inic_codigo = $evaluacion[0]->inic_codigo;


        $iniciativa = Iniciativas::where('inic_codigo', $inic_codigo)->get();
        $evaluaciones = Evaluacion::where('inic_codigo', $inic_codigo)->get();
        $resultados = Resultados::where('inic_codigo', $inic_codigo)->get();
        $ambitos = Programas::join('programas_contribuciones', 'programas_contribuciones.prog_codigo', 'programas.prog_codigo')
            ->join('ambito', 'ambito.amb_codigo', 'programas_contribuciones.amb_codigo')
            ->select('ambito.amb_nombre')
            ->where('prog_nombre', $iniciativa[0]->inic_nombre)
            ->get();

        return view('evaestudiantes', compact('iniciativa', 'evaluaciones', 'inic_codigo', 'resultados', 'ambitos', 'tipo'));

    }

    public function guardarEvaluacionEstudiante(Request $request)
    {

        $evaluacionInvitado = EvaluacionTotal::join('evaluacion_invitado', 'evaluacion_total.evatotal_codigo', '=', 'evaluacion_invitado.evatotal_codigo')
            ->where('evaluacion_total.evatotal_tipo', $request->tipo)
            ->where('evaluacion_invitado.inic_codigo', $request->inic_codigo)
            ->get();
        $existe = 0;
        foreach ($evaluacionInvitado as $eval) {
            if ($eval->evainv_correo == $request->correo) {
                $existe = 1;
            }
        }


        if ($existe = 1) {
            $evaluacionInvitadoExistente = EvaluacionInvitado::join('evaluacion_total', 'evaluacion_total.evatotal_codigo', '=', 'evaluacion_invitado.evatotal_codigo')
                ->where('evaluacion_total.evatotal_tipo', $request->tipo)
                ->where('evaluacion_invitado.evainv_correo', $request->correo)
                ->where('evaluacion_total.inic_codigo', $request->inic_codigo)
                ->first();

            try {

                if ($evaluacionInvitadoExistente->evainv_estado == 2) {
                    return redirect()->back()->with('error', '¡Ya has respondido está encuesta!');
                } elseif ($evaluacionInvitadoExistente->evainv_estado == 1 || $evaluacionInvitadoExistente->evainv_estado == 0) {
                    $ponderado_1 = 0;
                    $ponderado_2 = 0;
                    $ponderado_3 = 0;
                    $ponderado_4 = 0;
                    $ponderado_final = 0;

                    if ($request->tipo == 0) {
                        $ponderado_1 = 0.15;
                        $ponderado_2 = 0.30;
                        $ponderado_3 = 0.15;
                        $ponderado_4 = 0.40;
                        $ponderado_final = 0.7;
                    }

                    if ($request->tipo == 1) {
                        $ponderado_1 = 0.15;
                        $ponderado_2 = 0.30;
                        $ponderado_3 = 0.15;
                        $ponderado_4 = 0.40;
                        $ponderado_final = 0.3;
                    }

                    if ($request->tipo == 2) {
                        $ponderado_1 = 0.20;
                        $ponderado_2 = 0.50;
                        $ponderado_3 = 0.30;
                        $ponderado_final = 1;
                        /* $ponderado_4 = 0; */
                    }

                    $puntaje_conocimiento = ($request->conocimiento_1_SINO_1 + $request->conocimiento_2_SINO + $request->conocimiento_3_SINO) / 3 * $ponderado_1;
                    $puntaje_cumplimiento = ($request->cumplimiento_1 + $request->cumplimiento_2 + $request->cumplimiento_3) / 3 * $ponderado_2;

                    # VER SI APLICA: es para solo considerar los que no tenga NO APLICA marcado
                    $count = 0; # Para dividir en los puntos que si aplica
                    $aux1 = $request->calidad_1;
                    $aux2 = $request->calidad_2;
                    $aux3 = $request->calidad_3;
                    $aux4 = $request->calidad_4;
                    if ($aux1 != "") {
                        $count = $count + 1;
                    } else {
                        $aux1 = 0;
                    }
                    if ($aux2 != "") {
                        $count = $count + 1;
                    } else {
                        $aux2 = 0;
                    }
                    if ($aux3 != "") {
                        $count = $count + 1;
                    } else {
                        $aux3 = 0;
                    }
                    if ($aux4 != "") {
                        $count = $count + 1;
                    } else {
                        $aux4 = 0;
                    }

                    try {
                        $puntaje_calidad = ($aux1 + $aux2 + $aux3 + $aux4) / $count * $ponderado_3;
                    } catch (\Throwable $th) {
                        $puntaje_calidad = 0;
                    }


                    if ($request->tipo_data == 1 || $request->tipo_data == 2) {
                        $puntaje_competencia = ($request->competencia_1 + $request->competencia_2 + $request->competencia_3) / 3 * $ponderado_4;
                    } else {
                        $puntaje_competencia = 0;
                    }

                    $puntaje = ($puntaje_conocimiento + $puntaje_cumplimiento + $puntaje_calidad + $puntaje_competencia) * $ponderado_final;
                    $nuevo = new Evaluacion();
                    $nuevo->inic_codigo = $request->inic_codigo;
                    $nuevo->eval_email = $request->correo;
                    $nuevo->eval_evaluador = 1;
                    $nuevo->evatotal_codigo = $evaluacionInvitadoExistente->evatotal_codigo;
                    $nuevo->eval_conocimiento_1 = $request->conocimiento_1_SINO_1;
                    $nuevo->eval_conocimiento_2 = $request->conocimiento_2_SINO;
                    $nuevo->eval_conocimiento_3 = $request->conocimiento_3_SINO;
                    $nuevo->eval_cumplimiento_1 = $request->cumplimiento_1;
                    $nuevo->eval_cumplimiento_2 = $request->cumplimiento_2;
                    $nuevo->eval_cumplimiento_3 = $request->cumplimiento_3;
                    $nuevo->eval_calidad_1 = $request->calidad_1;
                    $nuevo->eval_calidad_2 = $request->calidad_2;
                    $nuevo->eval_calidad_3 = $request->calidad_3;
                    $nuevo->eval_calidad_4 = $request->calidad_4;
                    $nuevo->eval_competencia_1 = $request->competencia_1;
                    $nuevo->eval_competencia_2 = $request->competencia_2;
                    $nuevo->eval_competencia_3 = $request->competencia_3;
                    $nuevo->eval_puntaje = $puntaje;

                    $nuevo->eval_creado = Carbon::now('America/Santiago')->format('Y-m-d H:i:s');
                    $nuevo->eval_actualizado = Carbon::now('America/Santiago')->format('Y-m-d H:i:s');
                    $nuevo->eval_vigente = 1;
                    $nuevo->eval_nickname_mod = 'invitado';
                    $nuevo->eval_rol_mod = 0;

                    $nuevo->save();



                    $evaluacionInvitadoExistente->evainv_estado = 2;
                    $evaluacionInvitadoExistente->save();


                    return redirect()->back()->with('exito', 'Evaluación ingresada correctamente.');
                } else {
                    return redirect()->back()->with('error', '¡No has sido invitado a responder está encuesta!');
                }
            } catch (\Throwable $th) {
                return redirect()->back()->with('error', 'El correo proporcionado no ha sido invitado a responder está encuesta.');

            }
        } else {
            return redirect()->back()->with('error', 'El correo proporcionado no ha sido invitado a responder está encuesta.');
        }



    }

    public function sendEmailEstudiante(Request $request)
    {
        $evaluacion = EvaluacionTotal::where('evaluacion_total.evatotal_tipo', $request->tipo2)
            ->where('evaluacion_total.inic_codigo', $request->inic_codigo)
            ->join('evaluacion_invitado', 'evaluacion_total.evatotal_codigo', '=', 'evaluacion_invitado.evatotal_codigo')
            ->get();
        $iniciativa = Iniciativas::where('inic_codigo', $request->inic_codigo)->get();
        $nombre_iniciativa = $iniciativa[0]->inic_nombre;


        foreach ($evaluacion as $eval) {
            if ($eval->evainv_estado == 0) {
                $correo = $eval->evainv_correo;
                $nombre = $eval->evainv_nombre;
                $encriptacion = $eval->evatotal_encriptado;
                $tipo = $eval->evatotal_tipo;
                Mail::to($correo)->send(new ContactFormMail($correo, $nombre, $encriptacion, $tipo, $nombre_iniciativa));

                $evaluacionInvitado = EvaluacionInvitado::where('evainv_correo', $correo)
                    ->where('evatotal_codigo', $eval->evatotal_codigo)
                    ->get();
                $evaluacionInvitado[0]->evainv_estado = 2;
                $evaluacionInvitado[0]->save();
            }
        }

        return redirect()->back()->with('success', 'El correo electrónico ha sido enviado correctamente.');
    }



}
