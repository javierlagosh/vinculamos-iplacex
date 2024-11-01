@if (Session::has('admin'))
    @php
        $role = 'admin';
    @endphp
@elseif (Session::has('digitador'))
    @php
        $role = 'digitador';
    @endphp
@elseif (Session::has('observador'))
    @php
        $role = 'observador';
    @endphp
@elseif (Session::has('supervisor'))
    @php
        $role = 'supervisor';
    @endphp
@endif

@extends('admin.panel')

@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Evaluacion de la iniciativa N° {{ $iniciativa[0]->inic_codigo }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3 col-md-3 col-lg-3">
                                    <label>Tipo de evaluador</label>
                                    <select class="form-control" name="tipo" id="tipo">
                                        <option value="" disabled selected>Seleccione...</option>
                                        <option value="estudiante">Evaluador interno - Estudiante</option>
                                        <option value="docente">Evaluador interno - Docente/Directivo</option>
                                        <option value="externo">Evaluador externo</option>
                                    </select>
                                </div>
                            </div>
                            <div id="divAMostrar" style="display: none;">
                                {{-- EVALUACION ESTUDIANTE --}}
                                <div id="form-estudiantes" style="margin-top: 25px">
                                    <div class="row clearfix">

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="card">
                                                <div class="card-header" id="tituloEstudiante" style="display: none;">
                                                    <h4>Evaluación interna - Estudiante</h4>
                                                </div>
                                                <div class="card-header" id="tituloDocente" style="display: none;">
                                                    <h4>Evaluación interna - Docente/Directivo</h4>
                                                </div>
                                                <div class="card-header" id="tituloExterno" style="display: none;">
                                                    <h4>Evaluación externa</h4>
                                                </div>
                                                <div class="card-body">
                                                    <form id="wizard_with_validation" method="POST" action="{{route('admin.guardar.evaluacion')}}">
                                                        @csrf
                                                        {{-- ----------- --}}
                                                        {{-- PRIMER PASO --}}
                                                        {{-- ----------- --}}
                                                        <h3>CONOCIMIENTO DE LA INICIATIVA</h3>
                                                        <fieldset>
                                                            <div class="table-responsive">
                                                                <table class="table table-striped" id="table-1">
                                                                    <thead>
                                                                    </thead>
                                                                    <tbody id="tabla-Conocimiento-1">
                                                                        <tr>
                                                                            <th>¿Sabía usted que el propósito de ésta
                                                                                actividad
                                                                                era?</th>
                                                                            <th>¿Sí o No?</th>
                                                                            {{-- <th>¿En qué % cree usted que se cumplió el objetivo? --}}
                                                                            </th>
                                                                        </tr>
                                                                        {{-- ----------------------------- --}}
                                                                        {{-- RECORRER PROPOSITOS ESPERADOS --}}
                                                                        {{-- ----------------------------- --}}

                                                                        <tr>
                                                                            <td>
                                                                                {{ $iniciativa[0]->inic_descripcion }}
                                                                            </td>
                                                                            <td>
                                                                                <div class="form-group">
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            name="estu_conocimiento_1_SINO_1"
                                                                                            id="estu_conocimiento_1_SINO_1_si"
                                                                                            value="SI" checked>
                                                                                        <label class="form-check-label"
                                                                                            for="estu_conocimiento_1_SINO_1_si">
                                                                                            SI </label>
                                                                                    </div>
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            name="estu_conocimiento_1_SINO_1"
                                                                                            id="estu_conocimiento_1_SINO_1_no"
                                                                                            value="NO">>
                                                                                        <label class="form-check-label"
                                                                                            for="estu_conocimiento_1_SINO_1_no">
                                                                                            NO </label>
                                                                                    </div>
                                                                                </div>
                                                                            </td>

                                                                        </tr>

                                                                    </tbody>
                                                                    <tbody id="tabla-Conocimiento-2">
                                                                        <tr>
                                                                            <th>¿Sabía usted que los resultados esperados de
                                                                                la
                                                                                actividad eran?</th>
                                                                            <th>¿Sí o No?</th>
                                                                            {{-- <th>¿En qué % cree usted que se cumplió el resultado esperado?</th> --}}
                                                                        </tr>

                                                                        {{-- ----------------------------- --}}
                                                                        {{-- RECORRER RESULTADOS ESPERADOS --}}
                                                                        {{-- ----------------------------- --}}
                                                                        <tr>
                                                                            <td>
                                                                                <ul>
                                                                                    @foreach ($resultados as $resultado)
                                                                                        <li>{{ $resultado->resu_cuantificacion_inicial }}
                                                                                            x {{ $resultado->resu_nombre }}
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            </td>
                                                                            <td>
                                                                                <div class="form-group">
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            name="estu_conocimiento_2_SINO"
                                                                                            id="estu_conocimiento_2_SINO_si"
                                                                                            value="SI" checked>
                                                                                        <label class="form-check-label"
                                                                                            for="estu_conocimiento_2_SINO_si">
                                                                                            SI </label>
                                                                                    </div>
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            name="estu_conocimiento_2_SINO"
                                                                                            id="estu_conocimiento_2_SINO_no"
                                                                                            value="NO">
                                                                                        <label class="form-check-label"
                                                                                            for="estu_conocimiento_2_SINO_no">
                                                                                            NO </label>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                    <tbody id="tabla-Conocimiento-3">
                                                                        <tr>
                                                                            <th>¿Sabía usted que las contribuciones
                                                                                esperadas
                                                                                eran?</th>
                                                                            <th>¿Sí o No?</th>
                                                                            {{-- <th>¿En qué % cree usted que se cumplirán las contribuciones?</th> --}}
                                                                        </tr>

                                                                        {{-- ----------------------------- --}}
                                                                        {{-- RECORRER RESULTADOS ESPERADOS --}}
                                                                        {{-- ----------------------------- --}}
                                                                            <tr>
                                                                                <td>
                                                                                    <ul>
                                                                                        @foreach ($ambitos as $ambito)
                                                                                            <li>{{$ambito->amb_nombre}}</li>
                                                                                        @endforeach
                                                                                    </ul>

                                                                                </td>
                                                                                <td>
                                                                                    <div class="form-group">
                                                                                        <div
                                                                                            class="form-check form-check-inline">
                                                                                            <input class="form-check-input"
                                                                                                type="radio"
                                                                                                name="estu_conocimiento_3_SINO"
                                                                                                id="estu_conocimiento_3_SINO_si"
                                                                                                checked>
                                                                                            <label class="form-check-label"
                                                                                                for="estu_conocimiento_3_SINO_si">
                                                                                                SI </label>
                                                                                        </div>
                                                                                        <div
                                                                                            class="form-check form-check-inline">
                                                                                            <input class="form-check-input"
                                                                                                type="radio"
                                                                                                name="estu_conocimiento_3_SINO"
                                                                                                id="estu_conocimiento_3_SINO_no">
                                                                                            <label class="form-check-label"
                                                                                                for="estu_conocimiento_3_SINO_no">
                                                                                                NO </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>


                                                        </fieldset>

                                                        {{-- ----------------- --}}
                                                        {{-- Cumplimiento PASO --}}
                                                        {{-- ----------------- --}}

                                                        <h3>CUMPLIMIENTO DE LA INICIATIVA</h3>
                                                        <fieldset>
                                                            <div class="table-responsive">
                                                                <table class="table table-striped" id="table-1">
                                                                    <thead>
                                                                    </thead>
                                                                    <tbody id="tabla-Conocimiento-1">
                                                                        <tr>
                                                                            {{-- <th>Objetivo</th> --}}
                                                                            <th>¿En qué % cree usted que se cumplió el
                                                                                objetivo?
                                                                            </th>
                                                                        </tr>
                                                                        {{-- ----------------------------- --}}
                                                                        {{-- RECORRER PROPOSITOS ESPERADOS --}}
                                                                        {{-- ----------------------------- --}}
                                                                        <tr>

                                                                            <td>
                                                                                <div class="form-group">
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            name="est_cumplimiento_1"
                                                                                            id="est_cumplimiento_1_0"
                                                                                            value="0" checked>
                                                                                        <label class="form-check-label"
                                                                                            for="est_cumplimiento_1_0">
                                                                                            No se cumplió </label>
                                                                                    </div>
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            name="est_cumplimiento_1"
                                                                                            id="est_cumplimiento_1_25"
                                                                                            value="25">
                                                                                        <label class="form-check-label"
                                                                                            for="est_cumplimiento_1_25">
                                                                                            25 % </label>
                                                                                    </div>
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            name="est_cumplimiento_1"
                                                                                            id="est_cumplimiento_1_50"
                                                                                            value="50">
                                                                                        <label class="form-check-label"
                                                                                            for="est_cumplimiento_1_50">
                                                                                            50 % </label>
                                                                                    </div>
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            name="est_cumplimiento_1"
                                                                                            id="est_cumplimiento_1_75"
                                                                                            value="75">
                                                                                        <label class="form-check-label"
                                                                                            for="est_cumplimiento_1_75">
                                                                                            75 % </label>
                                                                                    </div>
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            name="est_cumplimiento_1"
                                                                                            id="est_cumplimiento_1_100"
                                                                                            value="100">
                                                                                        <label class="form-check-label"
                                                                                            for="est_cumplimiento_1_100">
                                                                                            100 % </label>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                    <tbody id="tabla-Conocimiento-2">
                                                                        <tr>
                                                                            {{-- <th>Objetivo</th> --}}
                                                                            <th>¿En qué % cree usted que se cumplió el
                                                                                resultado
                                                                                esperado?</th>
                                                                        </tr>

                                                                        {{-- ----------------------------- --}}
                                                                        {{-- RECORRER RESULTADOS ESPERADOS --}}
                                                                        {{-- ----------------------------- --}}
                                                                        <tr>
                                                                            <td>
                                                                                <div class="form-group">
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            name="est_cumplimiento_2"
                                                                                            id="est_cumplimiento_2_0"
                                                                                            value="0" checked>
                                                                                        <label class="form-check-label"
                                                                                            for="est_cumplimiento_2_0">
                                                                                            No se cumplió </label>
                                                                                    </div>
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            name="est_cumplimiento_2"
                                                                                            id="est_cumplimiento_2_25"
                                                                                            value="25">
                                                                                        <label class="form-check-label"
                                                                                            for="est_cumplimiento_2_25">
                                                                                            25 % </label>
                                                                                    </div>
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            name="est_cumplimiento_2"
                                                                                            id="est_cumplimiento_2_50"
                                                                                            value="50">
                                                                                        <label class="form-check-label"
                                                                                            for="est_cumplimiento_2_50">
                                                                                            50 % </label>
                                                                                    </div>
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            name="est_cumplimiento_2"
                                                                                            id="est_cumplimiento_2_75"
                                                                                            value="75">
                                                                                        <label class="form-check-label"
                                                                                            for="est_cumplimiento_2_75">
                                                                                            75 % </label>
                                                                                    </div>
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            name="est_cumplimiento_2"
                                                                                            id="est_cumplimiento_2_100"
                                                                                            value="100">
                                                                                        <label class="form-check-label"
                                                                                            for="est_cumplimiento_2_100">
                                                                                            100 % </label>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                    <tbody id="tabla-Conocimiento-3">
                                                                        <tr>
                                                                            {{-- <th>Objetivo</th> --}}
                                                                            <th>¿En qué % cree usted que se cumplirán las
                                                                                contribuciones?</th>
                                                                        </tr>

                                                                        {{-- ----------------------------- --}}
                                                                        {{-- RECORRER RESULTADOS ESPERADOS --}}
                                                                        {{-- ----------------------------- --}}
                                                                        <tr>

                                                                            <td>
                                                                                <div class="form-group">
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            name="est_cumplimiento_3"
                                                                                            id="est_cumplimiento_3_0"
                                                                                            value="0" checked>
                                                                                        <label class="form-check-label"
                                                                                            for="est_cumplimiento_3_0">
                                                                                            No se cumplirán </label>
                                                                                    </div>
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            name="est_cumplimiento_3"
                                                                                            id="est_cumplimiento_3_25"
                                                                                            value="25">
                                                                                        <label class="form-check-label"
                                                                                            for="est_cumplimiento_3_25">
                                                                                            25 % </label>
                                                                                    </div>
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            name="est_cumplimiento_3"
                                                                                            id="est_cumplimiento_3_50"
                                                                                            value="50">
                                                                                        <label class="form-check-label"
                                                                                            for="est_cumplimiento_3_50">
                                                                                            50 % </label>
                                                                                    </div>
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            name="est_cumplimiento_3"
                                                                                            id="est_cumplimiento_3_75"
                                                                                            value="75">
                                                                                        <label class="form-check-label"
                                                                                            for="est_cumplimiento_3_75">
                                                                                            75 % </label>
                                                                                    </div>
                                                                                    <div
                                                                                        class="form-check form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                            type="radio"
                                                                                            name="est_cumplimiento_3"
                                                                                            id="est_cumplimiento_3_100"
                                                                                            value="100">
                                                                                        <label class="form-check-label"
                                                                                            for="est_cumplimiento_3_100">
                                                                                            100 % </label>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </fieldset>

                                                        {{-- ------------ --}}
                                                        {{-- SEGUNDO PASO --}}
                                                        {{-- ------------ --}}

                                                        <h3>CALIDAD DE LA EJECUCIÓN</h3>
                                                        <fieldset>
                                                            <label name="etiquetasEstudiante">A continuación te pedimos que
                                                                evalúes de 0 a 3 la calidad
                                                                en la ejecución de la actividad, según los compromisos
                                                                asumidos por la institución, en que
                                                                <br>
                                                                <br>0= no cumple.
                                                                <br>1= cumple mínimamente.
                                                                <br>2= cumple medianamente.
                                                                <br>3= cumple totalmente.
                                                                <br>
                                                                <br>Si considera que algunos ítemes no estaban
                                                                comprometidos,
                                                                marque <b>No Aplica.</b> </label>

                                                            <label name="etiquetasOtras">A continuación le pedimos que
                                                                evalúe de 0 a 3 la calidad
                                                                en la ejecución de la actividad, según los compromisos
                                                                asumidos por la institución, en que
                                                                <br>
                                                                <br>0= no cumple.
                                                                <br>1= cumple mínimamente.
                                                                <br>2= cumple medianamente.
                                                                <br>3= cumple totalmente.
                                                                <br>
                                                                <br>Si considera que algunos ítemes no estaban
                                                                comprometidos,
                                                                marque <b>No Aplica.</b> </label>
                                                            <div class="table-responsive">
                                                                <table class="table table-striped" id="table-1">
                                                                    <thead>
                                                                    </thead>
                                                                    <tbody id="tabla-calidad-1">
                                                                        <tr>
                                                                            <th>Con qué nota evalúa usted la calidad en la
                                                                                ejecución de la actividad, en las siguientes
                                                                                dimensiones:</th>
                                                                            <th>Cumplimiento</th>
                                                                        </tr>
                                                                        @for ($i = 1; $i <= 4; $i++)
                                                                            <tr>
                                                                                @if ($i === 1)
                                                                                    <td>
                                                                                        Plazo y horarios
                                                                                    </td>
                                                                                @endif
                                                                                @if ($i === 2)
                                                                                    <td>
                                                                                        Equipamiento y/o infraestructura
                                                                                    </td>
                                                                                @endif
                                                                                @if ($i === 3)
                                                                                    <td>
                                                                                        Conexión Digital y/ logística
                                                                                    </td>
                                                                                @endif
                                                                                @if ($i === 4)
                                                                                    <td>
                                                                                        Presentación y/o desarrollo de la
                                                                                        actividad
                                                                                    </td>
                                                                                @endif
                                                                                <td>
                                                                                    <div class="form-group">
                                                                                        <div
                                                                                            class="form-check form-check-inline">
                                                                                            <input class="form-check-input"
                                                                                                type="radio"
                                                                                                name="est_calidad_{{ $i }}"
                                                                                                id="est_calidad_{{ $i }}_0">
                                                                                            <label class="form-check-label"
                                                                                                for="est_calidad_{{ $i }}_0">
                                                                                                0 </label>
                                                                                        </div>
                                                                                        <div
                                                                                            class="form-check form-check-inline">
                                                                                            <input class="form-check-input"
                                                                                                type="radio"
                                                                                                name="est_calidad_{{ $i }}"
                                                                                                id="est_calidad_{{ $i }}_1">
                                                                                            <label class="form-check-label"
                                                                                                for="est_calidad_{{ $i }}_1">
                                                                                                1 </label>
                                                                                        </div>
                                                                                        <div
                                                                                            class="form-check form-check-inline">
                                                                                            <input class="form-check-input"
                                                                                                type="radio"
                                                                                                name="est_calidad_{{ $i }}"
                                                                                                id="est_calidad_{{ $i }}_2">
                                                                                            <label class="form-check-label"
                                                                                                for="est_calidad_{{ $i }}_2">
                                                                                                2 </label>
                                                                                        </div>
                                                                                        <div
                                                                                            class="form-check form-check-inline">
                                                                                            <input class="form-check-input"
                                                                                                type="radio"
                                                                                                name="est_calidad_{{ $i }}"
                                                                                                id="est_calidad_{{ $i }}_3">
                                                                                            <label class="form-check-label"
                                                                                                for="est_calidad_{{ $i }}_3">
                                                                                                3 </label>
                                                                                        </div>
                                                                                        <div
                                                                                            class="form-check form-check-inline">
                                                                                            <input class="form-check-input"
                                                                                                type="radio"
                                                                                                name="est_calidad_{{ $i }}"
                                                                                                id="est_calidad_{{ $i }}_NO"
                                                                                                checked>
                                                                                            <label class="form-check-label"
                                                                                                for="est_calidad_{{ $i }}_NO">
                                                                                                No Aplica </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @endfor
                                                                    </tbody>
                                                                </table>
                                                            </div>


                                                        </fieldset>

                                                        {{-- ----------- --}}
                                                        {{-- TERCER PASO --}}
                                                        {{-- ----------- --}}

                                                        <h3>COMPETENCIA DE ESTUDIANTES</h3>
                                                        <fieldset>
                                                            <label name="etiquetasEstudiante">Ahora, siguiendo la misma
                                                                escala de 0 a 3, te pedimos que evalúes si te sirvió la
                                                                actividad para desarrollar
                                                                algunas de las siguientes dimensiones de las competencias
                                                                comprometidas.</label>

                                                            <label name="etiquetasOtras"> Ahora, siguiendo la misma escala
                                                                de 0 a 3, le pedimos que evalúe si la actividad le sirvió a
                                                                los estudiantes para
                                                                desarrollar algunas de las siguientes dimensiones de las
                                                                competencias comprometidas.</label>
                                                            <div class="table-responsive">
                                                                <table class="table table-striped" id="table-1">
                                                                    <thead>
                                                                    </thead>
                                                                    <tbody id="tabla-competencia-1">
                                                                        <tr>
                                                                            <th>Te sirvió la actividad para desarrollar
                                                                                algunas
                                                                                de las siguientes dimensiones de las
                                                                                competencias comprometidas?</th>
                                                                            <th>Cumplimiento</th>
                                                                        </tr>

                                                                        {{-- ----------------------------- --}}
                                                                        {{-- RECORRER RESULTADOS ESPERADOS --}}
                                                                        {{-- ----------------------------- --}}
                                                                        @for ($i = 1; $i <= 3; $i++)
                                                                            <tr>
                                                                                @if ($i === 1)
                                                                                    <td>
                                                                                        Capacidad para ejecutar las
                                                                                        actividades.
                                                                                    </td>
                                                                                @endif
                                                                                @if ($i === 2)
                                                                                    <td>
                                                                                        Actitud positiva para ejecutar
                                                                                        actividades.
                                                                                    </td>
                                                                                @endif
                                                                                @if ($i === 3)
                                                                                    <td>
                                                                                        Habilidad para resolver problemas.
                                                                                    </td>
                                                                                @endif
                                                                                <td>
                                                                                    <div class="form-group">
                                                                                        <div
                                                                                            class="form-check form-check-inline">
                                                                                            <input class="form-check-input"
                                                                                                type="radio"
                                                                                                name="est_competencia_{{ $i }}"
                                                                                                id="est_competencia_{{ $i }}_0"
                                                                                                checked>
                                                                                            <label class="form-check-label"
                                                                                                for="est_competencia_{{ $i }}_0">
                                                                                                0 </label>
                                                                                        </div>
                                                                                        <div
                                                                                            class="form-check form-check-inline">
                                                                                            <input class="form-check-input"
                                                                                                type="radio"
                                                                                                name="est_competencia_{{ $i }}"
                                                                                                id="est_competencia_{{ $i }}_1">
                                                                                            <label class="form-check-label"
                                                                                                for="est_competencia_{{ $i }}_1">
                                                                                                1 </label>
                                                                                        </div>
                                                                                        <div
                                                                                            class="form-check form-check-inline">
                                                                                            <input class="form-check-input"
                                                                                                type="radio"
                                                                                                name="est_competencia_{{ $i }}"
                                                                                                id="est_competencia_{{ $i }}_2">
                                                                                            <label class="form-check-label"
                                                                                                for="est_competencia_{{ $i }}_2">
                                                                                                2 </label>
                                                                                        </div>
                                                                                        <div
                                                                                            class="form-check form-check-inline">
                                                                                            <input class="form-check-input"
                                                                                                type="radio"
                                                                                                name="est_competencia_{{ $i }}"
                                                                                                id="est_competencia_{{ $i }}_3">
                                                                                            <label class="form-check-label"
                                                                                                for="est_competencia_{{ $i }}_3">
                                                                                                3 </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @endfor
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-12 col-md-12 col-lg-12">
                                                                    <div class="text-right">
                                                                        <button type="submit" class="btn btn-primary mr-1 waves-effect"><i
                                                                                class="fas fa-save"></i> Enviar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        const selectTipo = document.getElementById('tipo');
        const etiquetasEstudiante = document.getElementsByName('etiquetasEstudiante');
        const etiquetasOtras = document.getElementsByName('etiquetasOtras');
        const divAMostrar = document.getElementById('divAMostrar');
        const tituloEstudiante = document.getElementById('tituloEstudiante');
        const tituloDocente = document.getElementById('tituloDocente');
        const tituloExterno = document.getElementById('tituloExterno');
        const botonfinalizar =  document.getElementById('finish');
        
        selectTipo.addEventListener('change', function() {
            // Muestra el div cuando se seleccione cualquier opción
            divAMostrar.style.display = 'block';
        });

        selectTipo.addEventListener('change', function() {
            const tipoSeleccionado = selectTipo.value;

            tituloEstudiante.style.display = 'none';
            tituloDocente.style.display = 'none';
            tituloExterno.style.display = 'none';

            // Oculta todas las etiquetas primero
            ocultarEtiquetas(etiquetasEstudiante);
            ocultarEtiquetas(etiquetasOtras);

            // Muestra las etiquetas correspondientes al tipo seleccionado
            if (tipoSeleccionado === 'estudiante') {
                mostrarEtiquetas(etiquetasEstudiante);
            } else {
                mostrarEtiquetas(etiquetasOtras);
            }

            tituloEstudiante.style.display = 'none';
            tituloDocente.style.display = 'none';
            tituloExterno.style.display = 'none';

            // Mostrar el título correspondiente al tipo seleccionado
            if (tipoSeleccionado === 'estudiante') {
                tituloEstudiante.style.display = 'block';
            } else if (tipoSeleccionado === 'docente') {
                tituloDocente.style.display = 'block';
            } else if (tipoSeleccionado === 'externo') {
                tituloExterno.style.display = 'block';
            }
        });

        function ocultarEtiquetas(etiquetas) {
            for (let i = 0; i < etiquetas.length; i++) {
                etiquetas[i].style.display = 'none';
            }
        }

        function mostrarEtiquetas(etiquetas) {
            for (let i = 0; i < etiquetas.length; i++) {
                etiquetas[i].style.display = 'block';
            }
        }
    </script>
@endsection
