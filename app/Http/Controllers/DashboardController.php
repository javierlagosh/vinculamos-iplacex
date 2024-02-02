<?php

namespace App\Http\Controllers;

use App\Models\Comuna;
use App\Models\Componentes;
use App\Models\Escuelas;
use App\Models\Iniciativas;
use App\Models\IniciativasComunas;
use App\Models\IniciativasParticipantes;
use App\Models\ParticipantesInternos;
use App\Models\Programas;
use App\Models\Sedes;
use App\Models\SedesEscuelas;
use App\Models\SociosComunitarios;
use App\Models\TipoActividades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function Index()
    {
        $nIniciativas = ParticipantesInternos::select('inic_codigo')->distinct()->get();
        $nEstudiantes = ParticipantesInternos::select(DB::raw('SUM(COALESCE(pain_estudiantes_final,0)) as estudiantes'))->get();
        $nDocentes = ParticipantesInternos::select(DB::raw('SUM(COALESCE(pain_docentes_final,0)) as docentes'))->get();
        $nSocios = SociosComunitarios::all();
        $nBeneficiarios = IniciativasParticipantes::select(DB::raw('SUM(COALESCE(inpr_total_final,0)) as beneficiarios'))->get();
        $nTitulados = IniciativasParticipantes::select(DB::raw('SUM(COALESCE(inpr_total_final,0)) as titulados'))->where('soco_codigo', 15)->get();

        $comunas = IniciativasComunas::select('comu_codigo')->distinct()->get();
        $sedes = Sedes::select('sede_codigo', 'sede_nombre')->orderBy('sede_nombre', 'asc')->get();
        $escuelas = Escuelas::select('escu_codigo', 'escu_nombre')->orderBy('escu_nombre', 'asc')->get();
        $programa1_m = Programas::select(['prog_meta_estudiantes', 'prog_meta_docentes', 'prog_meta_socios', 'prog_meta_beneficiarios'])
            ->where('prog_codigo', 1)->get();

        $programa1_avance = DB::table('iniciativas_participantes')
            ->select([
                'iniciativas_participantes.inpr_codigo',
                'iniciativas_participantes.inic_codigo',
                DB::raw('SUM(COALESCE(participantes_internos.pain_docentes, 0)) as total_docentes'),
                DB::raw('SUM(COALESCE(participantes_internos.pain_estudiantes, 0)) as total_estudiantes'),
                DB::raw('(SELECT SUM(iniciativas_participantes.inpr_total) FROM iniciativas_participantes JOIN iniciativas ON iniciativas_participantes.inic_codigo = iniciativas.inic_codigo WHERE iniciativas.meca_codigo = 1 GROUP BY iniciativas_participantes.inpr_codigo) as total_beneficiarios'),
                DB::raw('COUNT(DISTINCT iniciativas_participantes.soco_codigo) as total_socios')
            ])
            ->join('iniciativas', 'iniciativas_participantes.inic_codigo', '=', 'iniciativas.inic_codigo')
            ->join('participantes_internos', 'iniciativas.inic_codigo', '=', 'participantes_internos.inic_codigo')
            ->where('iniciativas.meca_codigo', 1)
            ->groupBy('iniciativas_participantes.inpr_codigo', 'iniciativas_participantes.inic_codigo')
            ->get();

        $programa2_m = Programas::select(['prog_meta_estudiantes', 'prog_meta_docentes', 'prog_meta_socios', 'prog_meta_beneficiarios'])
            ->where('prog_codigo', 3)->get();

        $programa2_avance = DB::table('iniciativas_participantes')
            ->select([
                'iniciativas_participantes.inpr_codigo',
                'iniciativas_participantes.inic_codigo',
                DB::raw('SUM(COALESCE(participantes_internos.pain_docentes, 0)) as total_docentes'),
                DB::raw('SUM(COALESCE(participantes_internos.pain_estudiantes, 0)) as total_estudiantes'),
                DB::raw('(SELECT SUM(iniciativas_participantes.inpr_total) FROM iniciativas_participantes JOIN iniciativas ON iniciativas_participantes.inic_codigo = iniciativas.inic_codigo WHERE iniciativas.meca_codigo = 1 GROUP BY iniciativas_participantes.inpr_codigo) as total_beneficiarios'),
                DB::raw('COUNT(DISTINCT iniciativas_participantes.soco_codigo) as total_socios')
            ])
            ->join('iniciativas', 'iniciativas_participantes.inic_codigo', '=', 'iniciativas.inic_codigo')
            ->join('participantes_internos', 'iniciativas.inic_codigo', '=', 'participantes_internos.inic_codigo')
            ->where('iniciativas.meca_codigo', 3)
            ->groupBy('iniciativas_participantes.inpr_codigo', 'iniciativas_participantes.inic_codigo')
            ->get();

        $programa3_m = Programas::select(['prog_meta_estudiantes', 'prog_meta_docentes', 'prog_meta_socios', 'prog_meta_beneficiarios'])
            ->where('prog_codigo', 4)->get();

        $programa3_avance = DB::table('iniciativas_participantes')
            ->select([
                'iniciativas_participantes.inpr_codigo',
                'iniciativas_participantes.inic_codigo',
                DB::raw('SUM(COALESCE(participantes_internos.pain_docentes, 0)) as total_docentes'),
                DB::raw('SUM(COALESCE(participantes_internos.pain_estudiantes, 0)) as total_estudiantes'),
                DB::raw('(SELECT SUM(iniciativas_participantes.inpr_total) FROM iniciativas_participantes JOIN iniciativas ON iniciativas_participantes.inic_codigo = iniciativas.inic_codigo WHERE iniciativas.meca_codigo = 1 GROUP BY iniciativas_participantes.inpr_codigo) as total_beneficiarios'),
                DB::raw('COUNT(DISTINCT iniciativas_participantes.soco_codigo) as total_socios')
            ])
            ->join('iniciativas', 'iniciativas_participantes.inic_codigo', '=', 'iniciativas.inic_codigo')
            ->join('participantes_internos', 'iniciativas.inic_codigo', '=', 'participantes_internos.inic_codigo')
            ->where('iniciativas.meca_codigo', 2)
            ->groupBy('iniciativas_participantes.inpr_codigo', 'iniciativas_participantes.inic_codigo')
            ->get();

        $programa4_m = Programas::select(['prog_meta_estudiantes', 'prog_meta_docentes', 'prog_meta_socios', 'prog_meta_beneficiarios'])
            ->where('prog_codigo', 2)->get();

        $programa4_avance = DB::table('iniciativas_participantes')
            ->select([
                'iniciativas_participantes.inpr_codigo',
                'iniciativas_participantes.inic_codigo',
                DB::raw('SUM(COALESCE(participantes_internos.pain_docentes, 0)) as total_docentes'),
                DB::raw('SUM(COALESCE(participantes_internos.pain_estudiantes, 0)) as total_estudiantes'),
                DB::raw('(SELECT SUM(iniciativas_participantes.inpr_total) FROM iniciativas_participantes JOIN iniciativas ON iniciativas_participantes.inic_codigo = iniciativas.inic_codigo WHERE iniciativas.meca_codigo = 1 GROUP BY iniciativas_participantes.inpr_codigo) as total_beneficiarios'),
                DB::raw('COUNT(DISTINCT iniciativas_participantes.soco_codigo) as total_socios')
            ])
            ->join('iniciativas', 'iniciativas_participantes.inic_codigo', '=', 'iniciativas.inic_codigo')
            ->join('participantes_internos', 'iniciativas.inic_codigo', '=', 'participantes_internos.inic_codigo')
            ->where('iniciativas.meca_codigo', 4)
            ->groupBy('iniciativas_participantes.inpr_codigo', 'iniciativas_participantes.inic_codigo')
            ->get();

        $metas_total = Sedes::select(
            DB::raw('SUM(COALESCE(sede_meta_estudiantes,0)) as meta_sede_estudiantes'),
            DB::raw('SUM(COALESCE(sede_meta_docentes,0)) as meta_sede_docentes')
        )
            ->get();


        $tcomponentes = Componentes::select('comp_codigo','comp_nombre')->get();


        return view(
            'admin.dashboard',
            compact(
                'tcomponentes',
                'nIniciativas',
                'nEstudiantes',
                'nDocentes',
                'nSocios',
                'nBeneficiarios',
                'nTitulados',
                'comunas',
                'sedes',
                'escuelas',
                'programa1_m',
                'programa2_m',
                'programa3_m',
                'programa4_m',
                'metas_total',
                'programa1_avance',
                'programa2_avance',
                'programa3_avance',
                'programa4_avance'
            )
        );
    }

    public function sedesDatos(Request $request)
    {

        if ($request->sede_codigo == 'all') {
            $sede = ParticipantesInternos::select(
                DB::raw('SUM(COALESCE(pain_docentes_final,0)) as total_docentes'),
                DB::raw('SUM(COALESCE(pain_estudiantes_final,0)) as total_estudiantes'),
                DB::raw('COUNT(DISTINCT(inic_codigo)) as total_iniciativas')
            )
                ->get();

            $sede_meta = Sedes::select(
                DB::raw('SUM(COALESCE(sede_meta_estudiantes,0)) as meta_estudiantes'),
                DB::raw('SUM(COALESCE(sede_meta_docentes,0)) as meta_docentes'),
                DB::raw('SUM(COALESCE(sede_meta_iniciativas,0)) as meta_iniciativas')
            )->get();

            $sede_subgrupos_interes = ParticipantesInternos::leftjoin('iniciativas', 'iniciativas.inic_codigo', 'participantes_internos.inic_codigo')
                ->leftjoin('iniciativas_participantes', 'iniciativas_participantes.inic_codigo', 'iniciativas.inic_codigo')
                ->leftjoin('sub_grupos_interes', 'sub_grupos_interes.sugr_codigo', 'iniciativas_participantes.sugr_codigo')
                ->select(DB::raw('DISTINCT(iniciativas_participantes.inic_codigo)'), 'sub_grupos_interes.sugr_nombre')
                ->get();

            $sede_grupos_interes = ParticipantesInternos::join('iniciativas', 'iniciativas.inic_codigo', 'participantes_internos.inic_codigo')
                ->leftjoin('iniciativas_participantes', 'iniciativas_participantes.inic_codigo', 'iniciativas.inic_codigo')
                ->leftjoin('sub_grupos_interes', 'sub_grupos_interes.sugr_codigo', 'iniciativas_participantes.sugr_codigo')
                ->leftjoin('grupos_interes', 'grupos_interes.grin_codigo', 'sub_grupos_interes.grin_codigo')
                ->select(DB::raw('DISTINCT(sub_grupos_interes.sugr_codigo)'), 'grupos_interes.grin_nombre')
                ->get();

            $sede_iniciativas_estados = Iniciativas::select('inic_estado')->get();

            $sede_iniciativas_años = Iniciativas::select('inic_anho')->get();

            $sede_iniciativas_comunas = IniciativasComunas::join('comunas', 'comunas.comu_codigo', 'iniciativas_comunas.comu_codigo')
                ->select('comunas.comu_nombre')
                ->orderBy('comunas.comu_nombre')
                ->get();



            return response()->json([$sede, $sede_meta, $sede_subgrupos_interes, $sede_grupos_interes, $sede_iniciativas_estados, $sede_iniciativas_años, $sede_iniciativas_comunas]);
        } else {
            $sede = ParticipantesInternos::select(
                DB::raw('SUM(COALESCE(pain_docentes_final,0)) as total_docentes'),
                DB::raw('SUM(COALESCE(pain_estudiantes_final,0)) as total_estudiantes'),
                DB::raw('COUNT(DISTINCT(inic_codigo)) as total_iniciativas')
            )
                ->where('sede_codigo', $request->sede_codigo)
                ->get();

            $sede_meta = Sedes::select(
                DB::raw('SUM(COALESCE(sede_meta_estudiantes,0)) as meta_estudiantes'),
                DB::raw('SUM(COALESCE(sede_meta_docentes,0)) as meta_docentes'),
                DB::raw('SUM(COALESCE(sede_meta_iniciativas,0)) as meta_iniciativas')
            )
                ->where('sede_codigo', $request->sede_codigo)
                ->get();

            $sede_subgrupos_interes = ParticipantesInternos::join('iniciativas', 'iniciativas.inic_codigo', 'participantes_internos.inic_codigo')
                ->join('iniciativas_participantes', 'iniciativas_participantes.inic_codigo', 'iniciativas.inic_codigo')
                ->join('sub_grupos_interes', 'sub_grupos_interes.sugr_codigo', 'iniciativas_participantes.sugr_codigo')
                ->select(DB::raw('DISTINCT(iniciativas_participantes.inic_codigo)'), 'sub_grupos_interes.sugr_nombre')
                ->where('participantes_internos.sede_codigo', $request->sede_codigo)
                ->get();

            $sede_grupos_interes = ParticipantesInternos::join('iniciativas', 'iniciativas.inic_codigo', 'participantes_internos.inic_codigo')
                ->join('iniciativas_participantes', 'iniciativas_participantes.inic_codigo', 'iniciativas.inic_codigo')
                ->join('sub_grupos_interes', 'sub_grupos_interes.sugr_codigo', 'iniciativas_participantes.sugr_codigo')
                ->join('grupos_interes', 'grupos_interes.grin_codigo', 'sub_grupos_interes.grin_codigo')
                ->select(DB::raw('DISTINCT(sub_grupos_interes.sugr_codigo)'), 'grupos_interes.grin_nombre')
                ->where('participantes_internos.sede_codigo', $request->sede_codigo)
                ->get();

            $sede_iniciativas_estados = Iniciativas::join('participantes_internos', 'participantes_internos.inic_codigo', 'iniciativas.inic_codigo')
                ->select('inic_estado')
                ->where('participantes_internos.sede_codigo', $request->sede_codigo)
                ->get();

            $sede_iniciativas_años = Iniciativas::join('participantes_internos', 'participantes_internos.inic_codigo', 'iniciativas.inic_codigo')
                ->select('inic_anho')
                ->where('participantes_internos.sede_codigo', $request->sede_codigo)
                ->get();

            $sede_iniciativas_comunas = IniciativasComunas::join('iniciativas', 'iniciativas.inic_codigo', 'iniciativas_comunas.inic_codigo')
                ->join('participantes_internos', 'participantes_internos.inic_codigo', 'iniciativas.inic_codigo')
                ->join('comunas', 'comunas.comu_codigo', 'iniciativas_comunas.comu_codigo')
                ->select('comunas.comu_nombre')
                ->where('participantes_internos.sede_codigo', $request->sede_codigo)
                ->orderBy('comunas.comu_nombre')
                ->get();

            return response()->json([$sede, $sede_meta, $sede_subgrupos_interes, $sede_grupos_interes, $sede_iniciativas_estados, $sede_iniciativas_años, $sede_iniciativas_comunas]);
        }
    }

    //TODO: queda pendiente referenciar de otra forma los nombres de las variables paras esclarecer que se estan extrayendo lo datos de las escuelas.
    public function escuelasDatos(Request $request)
    {

        if ($request->sede_codigo == 'all' && $request->escu_codigo == 'all') {
            $sede = ParticipantesInternos::select(
                DB::raw('SUM(COALESCE(pain_docentes_final,0)) as total_docentes'),
                DB::raw('SUM(COALESCE(pain_estudiantes_final,0)) as total_estudiantes'),
                DB::raw('COUNT(DISTINCT(inic_codigo)) as total_iniciativas')
            )
                ->get();

            $sede_meta = Sedes::select(
                DB::raw('SUM(COALESCE(sede_meta_estudiantes,0)) as meta_estudiantes'),
                DB::raw('SUM(COALESCE(sede_meta_docentes,0)) as meta_docentes'),
                DB::raw('SUM(COALESCE(sede_meta_iniciativas,0)) as meta_iniciativas')
            )->get();

            $sede_subgrupos_interes = ParticipantesInternos::join('iniciativas', 'iniciativas.inic_codigo', 'participantes_internos.inic_codigo')
                ->join('iniciativas_participantes', 'iniciativas_participantes.inic_codigo', 'iniciativas.inic_codigo')
                ->join('sub_grupos_interes', 'sub_grupos_interes.sugr_codigo', 'iniciativas_participantes.sugr_codigo')
                ->select(DB::raw('DISTINCT(iniciativas_participantes.inic_codigo)'), 'sub_grupos_interes.sugr_nombre')
                ->get();

            $sede_grupos_interes = ParticipantesInternos::join('iniciativas', 'iniciativas.inic_codigo', 'participantes_internos.inic_codigo')
                ->join('iniciativas_participantes', 'iniciativas_participantes.inic_codigo', 'iniciativas.inic_codigo')
                ->join('sub_grupos_interes', 'sub_grupos_interes.sugr_codigo', 'iniciativas_participantes.sugr_codigo')
                ->join('grupos_interes', 'grupos_interes.grin_codigo', 'sub_grupos_interes.grin_codigo')
                ->select(DB::raw('DISTINCT(sub_grupos_interes.sugr_codigo)'), 'grupos_interes.grin_nombre')
                ->get();
            $sede_iniciativas_estados = Iniciativas::select('inic_estado')->get();

            $sede_iniciativas_años = Iniciativas::select('inic_anho')->get();

            $sede_iniciativas_comunas = IniciativasComunas::join('comunas', 'comunas.comu_codigo', 'iniciativas_comunas.comu_codigo')
                ->select('comunas.comu_nombre')
                ->orderBy('comunas.comu_nombre')
                ->get();

            return response()->json([$sede, $sede_meta, $sede_subgrupos_interes, $sede_grupos_interes, $sede_iniciativas_estados, $sede_iniciativas_años, $sede_iniciativas_comunas]);
        } elseif ($request->sede_codigo == 'all' && $request->escu_codigo != 'all') {
            $sede = ParticipantesInternos::select(
                DB::raw('SUM(COALESCE(pain_docentes_final,0)) as total_docentes'),
                DB::raw('SUM(COALESCE(pain_estudiantes_final,0)) as total_estudiantes'),
                DB::raw('COUNT(DISTINCT(inic_codigo)) as total_iniciativas')
            )
                ->where('escu_codigo', $request->escu_codigo)
                ->get();

            $sede_meta = Sedes::select(
                DB::raw('SUM(COALESCE(sede_meta_estudiantes,0)) as meta_estudiantes'),
                DB::raw('SUM(COALESCE(sede_meta_docentes,0)) as meta_docentes'),
                DB::raw('SUM(COALESCE(sede_meta_iniciativas,0)) as meta_iniciativas')
            )->get();

            $sede_subgrupos_interes = ParticipantesInternos::join('iniciativas', 'iniciativas.inic_codigo', 'participantes_internos.inic_codigo')
                ->join('iniciativas_participantes', 'iniciativas_participantes.inic_codigo', 'iniciativas.inic_codigo')
                ->join('sub_grupos_interes', 'sub_grupos_interes.sugr_codigo', 'iniciativas_participantes.sugr_codigo')
                ->select(DB::raw('DISTINCT(iniciativas_participantes.inic_codigo)'), 'sub_grupos_interes.sugr_nombre')
                ->where('participantes_internos.sede_codigo', $request->escu_codigo)
                ->get();

            $sede_grupos_interes = ParticipantesInternos::join('iniciativas', 'iniciativas.inic_codigo', 'participantes_internos.inic_codigo')
                ->join('iniciativas_participantes', 'iniciativas_participantes.inic_codigo', 'iniciativas.inic_codigo')
                ->join('sub_grupos_interes', 'sub_grupos_interes.sugr_codigo', 'iniciativas_participantes.sugr_codigo')
                ->join('grupos_interes', 'grupos_interes.grin_codigo', 'sub_grupos_interes.grin_codigo')
                ->select(DB::raw('DISTINCT(sub_grupos_interes.sugr_codigo)'), 'grupos_interes.grin_nombre')
                ->where('participantes_internos.sede_codigo', $request->escu_codigo)
                ->get();

            $sede_iniciativas_estados = Iniciativas::join('participantes_internos', 'participantes_internos.inic_codigo', 'iniciativas.inic_codigo')
                ->select('inic_estado')
                ->where('participantes_internos.sede_codigo', $request->escu_codigo)
                ->get();

            $sede_iniciativas_años = Iniciativas::join('participantes_internos', 'participantes_internos.inic_codigo', 'iniciativas.inic_codigo')
                ->select('inic_anho')
                ->where('participantes_internos.sede_codigo', $request->escu_codigo)
                ->get();

            $sede_iniciativas_comunas = IniciativasComunas::join('iniciativas', 'iniciativas.inic_codigo', 'iniciativas_comunas.inic_codigo')
                ->join('participantes_internos', 'participantes_internos.inic_codigo', 'iniciativas.inic_codigo')
                ->join('comunas', 'comunas.comu_codigo', 'iniciativas_comunas.comu_codigo')
                ->select('comunas.comu_nombre')
                ->where('participantes_internos.sede_codigo', $request->escu_codigo)
                ->orderBy('comunas.comu_nombre')
                ->get();
            return response()->json([$sede, $sede_meta, $sede_subgrupos_interes, $sede_grupos_interes, $sede_iniciativas_estados, $sede_iniciativas_años, $sede_iniciativas_comunas]);
        } elseif ($request->sede_codigo != 'all' && $request->escu_codigo != 'all') {
            $sede = ParticipantesInternos::select(
                DB::raw('SUM(COALESCE(pain_docentes_final,0)) as total_docentes'),
                DB::raw('SUM(COALESCE(pain_estudiantes_final,0)) as total_estudiantes'),
                DB::raw('COUNT(DISTINCT(inic_codigo)) as total_iniciativas')
            )
                ->where('sede_codigo', $request->sede_codigo)
                ->where('escu_codigo', $request->escu_codigo)
                ->get();

            $sede_meta = Sedes::select(
                DB::raw('SUM(COALESCE(sede_meta_estudiantes,0)) as meta_estudiantes'),
                DB::raw('SUM(COALESCE(sede_meta_docentes,0)) as meta_docentes'),
                DB::raw('SUM(COALESCE(sede_meta_iniciativas,0)) as meta_iniciativas')
            )
            ->where('sede_codigo', $request->sede_codigo)
            ->get();

            $sede_subgrupos_interes = ParticipantesInternos::join('iniciativas', 'iniciativas.inic_codigo', 'participantes_internos.inic_codigo')
                ->join('iniciativas_participantes', 'iniciativas_participantes.inic_codigo', 'iniciativas.inic_codigo')
                ->join('sub_grupos_interes', 'sub_grupos_interes.sugr_codigo', 'iniciativas_participantes.sugr_codigo')
                ->select(DB::raw('DISTINCT(iniciativas_participantes.inic_codigo)'), 'sub_grupos_interes.sugr_nombre')
                ->where('participantes_internos.sede_codigo', $request->sede_codigo)
                ->where('participantes_internos.sede_codigo', $request->escu_codigo)
                ->get();

            $sede_grupos_interes = ParticipantesInternos::join('iniciativas', 'iniciativas.inic_codigo', 'participantes_internos.inic_codigo')
                ->join('iniciativas_participantes', 'iniciativas_participantes.inic_codigo', 'iniciativas.inic_codigo')
                ->join('sub_grupos_interes', 'sub_grupos_interes.sugr_codigo', 'iniciativas_participantes.sugr_codigo')
                ->join('grupos_interes', 'grupos_interes.grin_codigo', 'sub_grupos_interes.grin_codigo')
                ->select(DB::raw('DISTINCT(sub_grupos_interes.sugr_codigo)'), 'grupos_interes.grin_nombre')
                ->where('participantes_internos.sede_codigo', $request->sede_codigo)
                ->where('participantes_internos.sede_codigo', $request->escu_codigo)
                ->get();

            $sede_iniciativas_estados = Iniciativas::join('participantes_internos', 'participantes_internos.inic_codigo', 'iniciativas.inic_codigo')
                ->select('inic_estado')
                ->where('participantes_internos.sede_codigo', $request->sede_codigo)
                ->where('participantes_internos.sede_codigo', $request->escu_codigo)
                ->get();

            $sede_iniciativas_años = Iniciativas::join('participantes_internos', 'participantes_internos.inic_codigo', 'iniciativas.inic_codigo')
                ->select('inic_anho')
                ->where('participantes_internos.sede_codigo', $request->sede_codigo)
                ->where('participantes_internos.sede_codigo', $request->escu_codigo)
                ->get();

            $sede_iniciativas_comunas = IniciativasComunas::join('iniciativas', 'iniciativas.inic_codigo', 'iniciativas_comunas.inic_codigo')
                ->join('participantes_internos', 'participantes_internos.inic_codigo', 'iniciativas.inic_codigo')
                ->join('comunas', 'comunas.comu_codigo', 'iniciativas_comunas.comu_codigo')
                ->select('comunas.comu_nombre')
                ->where('participantes_internos.sede_codigo', $request->escu_codigo)
                ->where('participantes_internos.sede_codigo', $request->sede_codigo)
                ->orderBy('comunas.comu_nombre')
                ->get();
            return response()->json([$sede, $sede_meta, $sede_subgrupos_interes, $sede_grupos_interes, $sede_iniciativas_estados, $sede_iniciativas_años, $sede_iniciativas_comunas]);
        }
    }

    public function escuelasBySedes(Request $request)
    {
        $escuelas = 0;
        if ($request->sede == "all") {
            $escuelas = SedesEscuelas::join('escuelas', 'escuelas.escu_codigo', 'sedes_escuelas.escu_codigo')
                ->select('escuelas.escu_nombre', 'escuelas.escu_codigo')
                ->distinct()
                ->get();
        } else {
            $escuelas = SedesEscuelas::where('sede_codigo', $request->sede)
                ->join('escuelas', 'escuelas.escu_codigo', 'sedes_escuelas.escu_codigo')
                ->select('escuelas.escu_nombre', 'escuelas.escu_codigo')
                ->distinct()
                ->get();
        }


        return response()->json($escuelas);
    }

    public function componentesDatos(Request $request)
    {
        $Datos = TipoActividades::select('tiac_nombre','tiac_codigo')->where('comp_codigo', $request->comp_codigo)->get();
        $tiacodigos = $Datos->pluck('tiac_codigo')->toArray();

        $iniciativas = [];
        $metas = [];
        foreach ($tiacodigos as $tiacodigo) {
            $metas[$tiacodigo] = DB::table('tipoactividad_metas')->where('tiac_codigo',$tiacodigo)->sum('tiacme_meta');
        }

        foreach ($tiacodigos as $tiacodigo) {
            $iniciativas[$tiacodigo] = Iniciativas::where('tiac_codigo',$tiacodigo)->select('inic_codigo')->get();
        }

        return response()->json([$Datos,$metas,$iniciativas]);
    }

}
