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
<div class="modal fade" id="modalEliminaEvaluacion" tabindex="-1" role="dialog" aria-labelledby="modalEliminar"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <form action="{{ route('admin.eliminar.todas.las.evaluaciones') }} " method="POST">
                    @method('DELETE')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEliminar">Cambiando modalidad de evaluación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-ban text-danger" style="font-size: 50px; color"></i>
                        <h6 class="mt-2">Para cambiar de modalidad de evaluación, todas las evaluaciones relacionadas a la iniciativa <span style="color:black;">dejarán de existir dentro del sistema</span>. <br> ¿Desea continuar de
                            todos
                            modos?</h6>
                        <input  id="inic_codigo" hidden name="inic_codigo" value="">
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="submit" class="btn btn-primary">Continuar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">

                        <div class="col-3"></div>
                    </div>
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6 alert-container" id="exito_ingresar" style="display: none;">
                            <div class="alert alert-success show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>Evaluación ingresada correctamente</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 alert-container" id="exito_crear" style="display: none;">
                            <div class="alert alert-success show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>Evaluación creada correctamente</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            @if (Session::has('exito'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exito') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                            @if (Session::has('error'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('error') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                        <div class="col-3"></div>
                        <div class="col-6 alert-container" id="error">

                        </div>
                        <div class="col-3"></div>
                    </div>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">

                            <h4>Evaluación de la iniciativa: {{ $iniciativa[0]->inic_nombre }} </h4>


                            <div class="card-header-action">
                                <div class="dropdown d-inline">

                                    <button class="btn btn-info dropdown-toggle" id="dropdownMenuButton2"
                                        data-toggle="dropdown">Iniciativas</button>

                                    <div class="dropdown-menu dropright">
                                        <a href="{{ route('admin.editar.paso1', $iniciativa[0]->inic_codigo) }}"
                                            class="dropdown-item has-item" data-toggle="tooltip" data-placement="top"
                                            title="Editar iniciativa"><i class="fas fa-edit"></i> Editar
                                            Iniciativa</a>

                                        <a href="{{ route('admin.iniciativas.detalles', $iniciativa[0]->inic_codigo) }}"
                                            class="dropdown-item has-item" data-toggle="tooltip" data-placement="top"
                                            title="Ver detalles de la iniciativa"><i class="fas fa-eye"></i> Mostrar
                                            detalles</a>
                                        {{-- <a href="javascript:void(0)" class="btn btn-icon btn-info icon-left"
                                            data-toggle="tooltip" data-placement="top" title="Calcular INVI"
                                            onclick="calcularIndice({{ $iniciativa[0]->inic_codigo }})"><i
                                                class="fas fa-tachometer-alt"></i>INVI</a> --}}

                                        <a href="{{ route('admin.evidencias.listar', $iniciativa[0]->inic_codigo) }}"
                                            class="dropdown-item has-item" data-toggle="tooltip" data-placement="top"
                                            title="Adjuntar evidencia"><i class="fas fa-paperclip"></i> Adjuntar
                                            evidencias</a>
                                    </div>

                                    <div class="dropdown d-inline">

                                        <button class="btn btn-success dropdown-toggle" id="dropdownMenuButton2"
                                            data-toggle="dropdown"><i class="fas fa-plus-circle"></i> Ingresar</button>

                                            <div class="dropdown-menu dropright">
                                                <a href="{{ route('admin.cobertura.index', $iniciativa[0]->inic_codigo) }}"
                                                    class="dropdown-item has-icon" data-toggle="tooltip" data-placement="top"
                                                    title="Ingresar cobertura"><i class="fas fa-users"></i> Ingresar cobertura</a>

                                                <a href="{{ route('admin.resultados.listado', $iniciativa[0]->inic_codigo) }}"
                                                    class="dropdown-item has-icon" data-toggle="tooltip" data-placement="top"
                                                    title="Ingresar resultado"><i class="fas fa-flag"></i>Ingresar resultado/s</a>
                                                    <a href="{{ route('admin.evidencias.listar', $iniciativa[0]->inic_codigo) }}"
                                                        class="dropdown-item has-item" data-toggle="tooltip" data-placement="top"
                                                        title="Adjuntar evidencia"><i class="fas fa-paperclip"></i> Ingresar
                                                        evidencias</a>
                                                    <a href="{{ route('admin.evaluar.iniciativa', $iniciativa[0]->inic_codigo) }}" class="dropdown-item has-icon"><i
                                                        class="fas fa-file-signature"></i>Ingresar evaluación</a>


                                            </div>
                                    </div>



                                    {{-- <a href="{{ route($role . '.evaluar.iniciativa', $iniciativa[0]->inic_codigo) }}"
                                            class="btn btn-icon btn-success icon-left" data-toggle="tooltip"
                                            data-placement="top" title="Evaluar iniciativa"><i
                                                class="fas fa-file-signature"></i>Evaluar</a> --}}

                                    <a href="{{ route('admin.iniciativa.listar') }}"
                                        class="btn btn-primary mr-1 waves-effect icon-left" type="button">
                                        <i class="fas fa-angle-left"></i> Volver a listado
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($evaluacionManualPredeterminada == 0)
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-lg-6">
                                    <h5>Ingresar evaluación </h5>

                                    <table class="table table-bordered text-center">
                                        <thead>
                                            <tr>
                                                <th>Evaluador Interno</th>
                                                <th>Puntaje</th>
                                                <th>Resultado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="width:33%;">Estudiantes</td>
                                                <td>
                                                    <form action="{{route('admin.guardar.evaluacion.manual')}}" method="post">
                                                        @method('POST')
                                                        @csrf
                                                        <input type="number" hidden name="inic_codigo" value="{{$iniciativa[0]->inic_codigo}}">
                                                        <input type="number" hidden name="eval_evaluador" value="0">
                                                        <div class="d-flex flex-column flex-md-row w-100">
                                                            <input id="evaestudiantes" type="number" value="{{$evaluacion_estudiantes->eval_puntaje  ?? 0}}" name="puntaje" min="0" max="100" class="form-control mr-md-2 mb-2 mb-md-0" style="flex: 0 0 80%;" placeholder="Ingresa Puntaje">
                                                            <button type="submit" class="btn btn-primary" style="flex: 0 0 20%;"><i class="fas fa-save"></i></button>
                                                          </div>

                                                    </form>
                                                </td>
                                                <td rowspan="3" class="align-middle">
                                                    <br>
                                                    <h3 id="resultados-internos">0</h3>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Docentes</td>
                                                <td>
                                                    <form action="{{route('admin.guardar.evaluacion.manual')}}" method="post">
                                                        @method('POST')
                                                        @csrf
                                                        <input type="number" hidden name="inic_codigo" value="{{$iniciativa[0]->inic_codigo}}">
                                                        <input type="number" hidden name="eval_evaluador" value="1">
                                                        <div class="d-flex flex-column flex-md-row w-100">
                                                            <input id="evadocentes" type="number" value="{{$evaluacion_docentes->eval_puntaje  ?? 0}}" name="puntaje" min="0" max="100" class="form-control mr-md-2 mb-2 mb-md-0" style="flex: 0 0 80%;" placeholder="Ingresa Puntaje">
                                                            <button type="submit" class="btn btn-primary" style="flex: 0 0 20%;"><i class="fas fa-save"></i></button>
                                                          </div>

                                                    </form>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Directivos/Funcionarios</td>
                                                <td>
                                                    <form action="{{route('admin.guardar.evaluacion.manual')}}" method="post">
                                                        @method('POST')
                                                        @csrf
                                                        <input type="number" hidden name="inic_codigo" value="{{$iniciativa[0]->inic_codigo}}">
                                                        <input type="number" hidden name="eval_evaluador" value="12">
                                                        <div class="d-flex flex-column flex-md-row w-100">
                                                            <input id="evadirectivos" type="number" value="{{$evaluacion_directivos->eval_puntaje  ?? 0}}" name="puntaje" min="0" max="100" class="form-control mr-md-2 mb-2 mb-md-0" style="flex: 0 0 80%;" placeholder="Ingresa Puntaje">
                                                            <button type="submit" class="btn btn-primary" style="flex: 0 0 20%;"><i class="fas fa-save"></i></button>
                                                          </div>

                                                    </form>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <table class="table table-bordered text-center">
                                        <thead>
                                            <tr>
                                                <th style="width:33%;">Evaluador Externo</th>
                                                <th>Puntaje</th>
                                                <th>Resultado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Beneficiarios</td>
                                                <td>
                                                    <form action="{{route('admin.guardar.evaluacion.manual')}}" method="post">
                                                        @method('POST')
                                                        @csrf
                                                        <input type="number" hidden name="inic_codigo" value="{{$iniciativa[0]->inic_codigo}}">
                                                        <input type="number" hidden name="eval_evaluador" value="13">
                                                        <div class="d-flex flex-column flex-md-row w-100">
                                                            <input id="evabeneficiarios" type="number" value="{{$evaluacion_beneficiarios->eval_puntaje  ?? 0}}" name="puntaje" min="0" max="100" class="form-control mr-md-2 mb-2 mb-md-0" style="flex: 0 0 80%;" placeholder="Ingresa Puntaje">
                                                            <button type="submit" class="btn btn-primary" style="flex: 0 0 20%;"><i class="fas fa-save"></i></button>
                                                          </div>

                                                    </form>
                                                </td>
                                                <td rowspan="3" class="align-middle">
                                                    <br>
                                                    <h3 id="resultados-externos">0</h3>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Socios comunitarios</td>
                                                <td>
                                                    <form action="{{route('admin.guardar.evaluacion.manual')}}" method="post">
                                                        @method('POST')
                                                        @csrf
                                                        <input type="number" hidden name="inic_codigo" value="{{$iniciativa[0]->inic_codigo}}">
                                                        <input type="number" hidden name="eval_evaluador" value="14">
                                                        <div class="d-flex flex-column flex-md-row w-100">
                                                            <input id="evasocios" type="number" value="{{$evaluacion_socios->eval_puntaje  ?? 0}}" name="puntaje" min="0" max="100" class="form-control mr-md-2 mb-2 mb-md-0" style="flex: 0 0 80%;" placeholder="Ingresa Puntaje">
                                                            <button type="submit" class="btn btn-primary" style="flex: 0 0 20%;"><i class="fas fa-save"></i></button>
                                                          </div>

                                                    </form>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <script>
                                        function sumarValores() {
                                            // Obtener los valores de los inputs
                                            const evaEstudiantes = parseFloat(document.getElementById('evaestudiantes').value) || 0;
                                            const evaDocentes = parseFloat(document.getElementById('evadocentes').value) || 0;
                                            const evaDirectivos = parseFloat(document.getElementById('evadirectivos').value) || 0;

                                            const evabeneficiarios = parseFloat(document.getElementById('evabeneficiarios').value) || 0;
                                            const evasocios = parseFloat(document.getElementById('evasocios').value) || 0;

                                            // Contadores para valores válidos
                                            let contadorInternos = 0;
                                            let contadorExternos = 0;

                                            // Sumar los valores solo si son mayores que 0 y contar cuántos son válidos
                                            const sumaTotalInternos = (evaEstudiantes > 0 ? (contadorInternos++, evaEstudiantes) : 0) +
                                                                    (evaDocentes > 0 ? (contadorInternos++, evaDocentes) : 0) +
                                                                    (evaDirectivos > 0 ? (contadorInternos++, evaDirectivos) : 0);

                                            const sumaTotalExternos = (evabeneficiarios > 0 ? (contadorExternos++, evabeneficiarios) : 0) +
                                                                    (evasocios > 0 ? (contadorExternos++, evasocios) : 0);

                                            // Evitar divisiones por cero
                                            const PromedioInternos = contadorInternos > 0 ? sumaTotalInternos / contadorInternos : 0;
                                            const PromedioExternos = contadorExternos > 0 ? sumaTotalExternos / contadorExternos : 0;

                                            // Verificar si los valores no son válidos (NaN) o si están fuera de rango
                                            if (isNaN(PromedioInternos)) {
                                                document.getElementById('resultados-internos').innerText = 0;
                                                return;
                                            }
                                            if (isNaN(PromedioExternos)) {
                                                document.getElementById('resultados-externos').innerText = 0;
                                                return;
                                            }

                                            // Limitar el resultado máximo a 100
                                            const PromedioInternosAplicado = Math.min(PromedioInternos, 100).toFixed(2);
                                            const PromedioExternosAplicado = Math.min(PromedioExternos, 100).toFixed(2);

                                            // Actualizar el resultado en el h3
                                            document.getElementById('resultados-internos').innerText = PromedioInternosAplicado;
                                            document.getElementById('resultados-externos').innerText = PromedioExternosAplicado;
                                        }

                                        // Llamar a la función cuando la página se carga
                                        window.onload = sumarValores;

                                    </script>

                                </div>
                                <div class="col-xl-6 col-md-6 col-lg-6">

                                    <h5>Crear evaluación</h5>
                                    <table class="table table-bordered text-center">
                                        <thead>
                                            <tr>
                                                <th>Seleccione un tipo de evaluador</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <form method="POST" action="{{route('admin.crear.evaluacion')}}">
                                                        @method('POST')
                                                        @csrf
                                                        <div class="form-group">

                                                            <div class="w-100 mt-2">
                                                                <input type="text" hidden name="inic_codigo" id="inic_codigo"
                                                                value="{{ $iniciativa[0]->inic_codigo }}">
                                                                <select class="form-control" name="tipo" id="tipo" onchange="cambiodeTipo();" required>
                                                                    <option value="" disabled selected>Seleccione...</option>
                                                                    <option value="" disabled>--- Evaluador Interno ---</option>
                                                                    <option value="0">Evaluador interno - Estudiante</option>
                                                                    <option value="1">Evaluador interno - Docente</option>
                                                                    <option value="12">Evaluador interno - Directivo</option>
                                                                    <option value="" disabled>--- Evaluador Externo ---</option>
                                                                    <option value="13">Evaluador Externo - Beneficiario</option>
                                                                    <option value="14">Evaluador Externo - Socio comunitario</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <button type="submit" id="botonEnviar" class="btn btn-primary w-100">
                                                            Paso Siguiente &nbsp;<i class="fas fa-chevron-right"></i>
                                                        </button>

                                                    </form>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>



                                        <script>
                                            function cambiodeTipo(){
                                                var tipo = document.getElementById('tipo').value;
                                                if(tipo == 0){
                                                    document.getElementById('verResultadosButton').href = '/admin/iniciativas/{{$iniciativa[0]->inic_codigo}}/evaluacion/resultados/0';
                                                    document.getElementById('agregarParticipantesButton').href = '/admin/iniciativas/{{$iniciativa[0]->inic_codigo}}/evaluar/invitar/0';
                                                }else if(tipo == 1){
                                                    document.getElementById('verResultadosButton').href = '/admin/iniciativas/{{$iniciativa[0]->inic_codigo}}/evaluacion/resultados/1';
                                                    document.getElementById('agregarParticipantesButton').href = '/admin/iniciativas/{{$iniciativa[0]->inic_codigo}}/evaluar/invitar/1';
                                                }else if(tipo == 12){
                                                    document.getElementById('verResultadosButton').href = '/admin/iniciativas/{{$iniciativa[0]->inic_codigo}}/evaluacion/resultados/12';
                                                    document.getElementById('agregarParticipantesButton').href = '/admin/iniciativas/{{$iniciativa[0]->inic_codigo}}/evaluar/invitar/12';
                                                }else if(tipo == 13){
                                                    document.getElementById('verResultadosButton').href = '/admin/iniciativas/{{$iniciativa[0]->inic_codigo}}/evaluacion/resultados/13';
                                                    document.getElementById('agregarParticipantesButton').href = '/admin/iniciativas/{{$iniciativa[0]->inic_codigo}}/evaluar/invitar/13';
                                                }else if(tipo == 14){
                                                    document.getElementById('verResultadosButton').href = '/admin/iniciativas/{{$iniciativa[0]->inic_codigo}}/evaluacion/resultados/14';
                                                    document.getElementById('agregarParticipantesButton').href = '/admin/iniciativas/{{$iniciativa[0]->inic_codigo}}/evaluar/invitar/14';
                                                }
                                            }
                                        </script>



                                        <script>
                                            //funcion donde si cambia el tipo de evaluador se muestra el boton de enviar
                                            onchange = function() {
                                                document.getElementById('botonEnviar').hidden = false;
                                            }

                                        </script>


                                </div>
                            </div>

                            @elseif($evaluacionManualPredeterminada == 1)
                            <div class="d-flex justify-content-center">
                            <div class="col-xl-6 col-md-6 col-lg-6">
                                <h5>Ingresar evaluación </h5>

                                <table class="table table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th>Evaluador Interno</th>
                                            <th>Puntaje</th>
                                            <th>Resultado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="width:33%;">Estudiantes</td>
                                            <td>
                                                <form action="{{route('admin.guardar.evaluacion.manual')}}" method="post">
                                                    @method('POST')
                                                    @csrf
                                                    <input type="number" hidden name="inic_codigo" value="{{$iniciativa[0]->inic_codigo}}">
                                                    <input type="number" hidden name="eval_evaluador" value="0">
                                                    <div class="d-flex flex-column flex-md-row w-100">
                                                        <input id="evaestudiantes" type="number" value="{{$evaluacion_estudiantes->eval_puntaje  ?? 0}}" name="puntaje" min="0" max="100" class="form-control mr-md-2 mb-2 mb-md-0" style="flex: 0 0 80%;" placeholder="Ingresa Puntaje">
                                                        <button type="submit" class="btn btn-primary" style="flex: 0 0 20%;"><i class="fas fa-save"></i></button>
                                                      </div>

                                                </form>
                                            </td>
                                            <td rowspan="3" class="align-middle">Resultados Internos
                                                <br>
                                                <h3 id="resultados-internos">0</h3>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Docentes</td>
                                            <td>
                                                <form action="{{route('admin.guardar.evaluacion.manual')}}" method="post">
                                                    @method('POST')
                                                    @csrf
                                                    <input type="number" hidden name="inic_codigo" value="{{$iniciativa[0]->inic_codigo}}">
                                                    <input type="number" hidden name="eval_evaluador" value="1">
                                                    <div class="d-flex flex-column flex-md-row w-100">
                                                        <input id="evadocentes" type="number" value="{{$evaluacion_docentes->eval_puntaje  ?? 0}}" name="puntaje" min="0" max="100" class="form-control mr-md-2 mb-2 mb-md-0" style="flex: 0 0 80%;" placeholder="Ingresa Puntaje">
                                                        <button type="submit" class="btn btn-primary" style="flex: 0 0 20%;"><i class="fas fa-save"></i></button>
                                                      </div>

                                                </form>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Directivos/Funcionarios</td>
                                            <td>
                                                <form action="{{route('admin.guardar.evaluacion.manual')}}" method="post">
                                                    @method('POST')
                                                    @csrf
                                                    <input type="number" hidden name="inic_codigo" value="{{$iniciativa[0]->inic_codigo}}">
                                                    <input type="number" hidden name="eval_evaluador" value="12">
                                                    <div class="d-flex flex-column flex-md-row w-100">
                                                        <input id="evadirectivos" type="number" value="{{$evaluacion_directivos->eval_puntaje  ?? 0}}" name="puntaje" min="0" max="100" class="form-control mr-md-2 mb-2 mb-md-0" style="flex: 0 0 80%;" placeholder="Ingresa Puntaje">
                                                        <button type="submit" class="btn btn-primary" style="flex: 0 0 20%;"><i class="fas fa-save"></i></button>
                                                      </div>

                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table class="table table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th style="width:33%;">Evaluador Externo</th>
                                            <th>Puntaje</th>
                                            <th>Resultado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Beneficiarios</td>
                                            <td>
                                                <form action="{{route('admin.guardar.evaluacion.manual')}}" method="post">
                                                    @method('POST')
                                                    @csrf
                                                    <input type="number" hidden name="inic_codigo" value="{{$iniciativa[0]->inic_codigo}}">
                                                    <input type="number" hidden name="eval_evaluador" value="13">
                                                    <div class="d-flex flex-column flex-md-row w-100">
                                                        <input id="evabeneficiarios" type="number" value="{{$evaluacion_beneficiarios->eval_puntaje  ?? 0}}" name="puntaje" min="0" max="100" class="form-control mr-md-2 mb-2 mb-md-0" style="flex: 0 0 80%;" placeholder="Ingresa Puntaje">
                                                        <button type="submit" class="btn btn-primary" style="flex: 0 0 20%;"><i class="fas fa-save"></i></button>
                                                      </div>

                                                </form>
                                            </td>
                                            <td rowspan="3" class="align-middle">
                                                <br>
                                                <h3 id="resultados-externos">0</h3>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Socios comunitarios</td>
                                            <td>
                                                <form action="{{route('admin.guardar.evaluacion.manual')}}" method="post">
                                                    @method('POST')
                                                    @csrf
                                                    <input type="number" hidden name="inic_codigo" value="{{$iniciativa[0]->inic_codigo}}">
                                                    <input type="number" hidden name="eval_evaluador" value="14">
                                                    <div class="d-flex flex-column flex-md-row w-100">
                                                        <input id="evasocios" type="number" value="{{$evaluacion_socios->eval_puntaje  ?? 0}}" name="puntaje" min="0" max="100" class="form-control mr-md-2 mb-2 mb-md-0" style="flex: 0 0 80%;" placeholder="Ingresa Puntaje">
                                                        <button type="submit" class="btn btn-primary" style="flex: 0 0 20%;"><i class="fas fa-save"></i></button>
                                                      </div>

                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <script>
                                    function sumarValores() {
                                        // Obtener los valores de los inputs
                                        const evaEstudiantes = parseFloat(document.getElementById('evaestudiantes').value) || 0;
                                        const evaDocentes = parseFloat(document.getElementById('evadocentes').value) || 0;
                                        const evaDirectivos = parseFloat(document.getElementById('evadirectivos').value) || 0;

                                        const evabeneficiarios = parseFloat(document.getElementById('evabeneficiarios').value) || 0;
                                        const evasocios = parseFloat(document.getElementById('evasocios').value) || 0;

                                        // Contadores para valores válidos
                                        let contadorInternos = 0;
                                        let contadorExternos = 0;

                                        // Sumar los valores solo si son mayores que 0 y contar cuántos son válidos
                                        const sumaTotalInternos = (evaEstudiantes > 0 ? (contadorInternos++, evaEstudiantes) : 0) +
                                                                (evaDocentes > 0 ? (contadorInternos++, evaDocentes) : 0) +
                                                                (evaDirectivos > 0 ? (contadorInternos++, evaDirectivos) : 0);

                                        const sumaTotalExternos = (evabeneficiarios > 0 ? (contadorExternos++, evabeneficiarios) : 0) +
                                                                (evasocios > 0 ? (contadorExternos++, evasocios) : 0);

                                        // Evitar divisiones por cero
                                        const PromedioInternos = contadorInternos > 0 ? sumaTotalInternos / contadorInternos : 0;
                                        const PromedioExternos = contadorExternos > 0 ? sumaTotalExternos / contadorExternos : 0;

                                        // Verificar si los valores no son válidos (NaN) o si están fuera de rango
                                        if (isNaN(PromedioInternos)) {
                                            document.getElementById('resultados-internos').innerText = 0;
                                            return;
                                        }
                                        if (isNaN(PromedioExternos)) {
                                            document.getElementById('resultados-externos').innerText = 0;
                                            return;
                                        }

                                        // Limitar el resultado máximo a 100
                                        const PromedioInternosAplicado = Math.min(PromedioInternos, 100).toFixed(2);
                                        const PromedioExternosAplicado = Math.min(PromedioExternos, 100).toFixed(2);

                                        // Actualizar el resultado en el h3
                                        document.getElementById('resultados-internos').innerText = PromedioInternosAplicado;
                                        document.getElementById('resultados-externos').innerText = PromedioExternosAplicado;
                                    }

                                    // Llamar a la función cuando la página se carga
                                    window.onload = sumarValores;

                                </script>

                            <div class="d-flex justify-content-center mb-5">
                                <button class="btn btn-danger" onclick="eliminarEvaluaciones({{$iniciativa[0]->inic_codigo}});"> Cambiar tipo de evaluación</button>
                            </div>
                            </div>
                            </div>

                            @elseif($evaluacionManualPredeterminada == 2)
                            <div class="col-xl-12 col-md-12 col-lg-12">
                                <h5>Crear evaluación</h5>
                                <div class="d-flex justify-content-center mb-3">
                                    <form method="POST" action="{{route('admin.crear.evaluacion')}}">
                                        @method('POST')
                                        @csrf
                                        <div class="form-group">
                                            <label for="tipo">
                                                <strong>Seleccione un tipo de evaluador:</strong>
                                            </label>
                                            <div class="w-100">
                                                <input type="text" hidden name="inic_codigo" id="inic_codigo"
                                                value="{{ $iniciativa[0]->inic_codigo }}">
                                                <select class="form-control" name="tipo" id="tipo" onchange="cambiodeTipo();" required>
                                                    <option value="" disabled selected>Seleccione...</option>
                                                    <option value="" disabled>--- Evaluador Interno ---</option>
                                                    <option value="0">Evaluador interno - Estudiante</option>
                                                    <option value="1">Evaluador interno - Docente</option>
                                                    <option value="12">Evaluador interno - Directivo</option>
                                                    <option value="" disabled>--- Evaluador Externo ---</option>
                                                    <option value="13">Evaluador Externo - Beneficiario</option>
                                                    <option value="14">Evaluador Externo - Socio comunitario</option>

                                                </select>
                                            </div>
                                        </div>
                                        <button type="submit" id="botonEnviar" class="btn btn-primary w-100">
                                            Paso Siguiente &nbsp;<i class="fas fa-chevron-right"></i>
                                        </button>
                                        <div>
                                            <a href="" hidden id="verResultadosButton" class="btn btn-primary w-100 mt-2">Ver resultados</a>
                                            <a href="" hidden id="agregarParticipantesButton" class="btn btn-primary w-100 mt-2">Agregar participantes</a>

                                        </div>
                                    </form>


                                    <script>
                                        function cambiodeTipo(){
                                            var tipo = document.getElementById('tipo').value;
                                            if(tipo == 0){
                                                //mostrar botones
                                                document.getElementById('verResultadosButton').hidden = false;
                                                document.getElementById('agregarParticipantesButton').hidden = false;
                                                document.getElementById('verResultadosButton').href = '/admin/iniciativas/{{$iniciativa[0]->inic_codigo}}/evaluacion/resultados/0';
                                                document.getElementById('agregarParticipantesButton').href = '/admin/iniciativas/{{$iniciativa[0]->inic_codigo}}/evaluar/invitar/0';
                                            }else if(tipo == 1){
                                                document.getElementById('verResultadosButton').hidden = false;
                                                document.getElementById('agregarParticipantesButton').hidden = false;
                                                document.getElementById('verResultadosButton').href = '/admin/iniciativas/{{$iniciativa[0]->inic_codigo}}/evaluacion/resultados/1';
                                                document.getElementById('agregarParticipantesButton').href = '/admin/iniciativas/{{$iniciativa[0]->inic_codigo}}/evaluar/invitar/1';
                                            }else if(tipo == 12){
                                                document.getElementById('verResultadosButton').hidden = false;
                                                document.getElementById('agregarParticipantesButton').hidden = false;
                                                document.getElementById('verResultadosButton').href = '/admin/iniciativas/{{$iniciativa[0]->inic_codigo}}/evaluacion/resultados/12';
                                                document.getElementById('agregarParticipantesButton').href = '/admin/iniciativas/{{$iniciativa[0]->inic_codigo}}/evaluar/invitar/12';
                                            }else if(tipo == 13){
                                                document.getElementById('verResultadosButton').hidden = false;
                                                document.getElementById('agregarParticipantesButton').hidden = false;
                                                document.getElementById('verResultadosButton').href = '/admin/iniciativas/{{$iniciativa[0]->inic_codigo}}/evaluacion/resultados/13';
                                                document.getElementById('agregarParticipantesButton').href = '/admin/iniciativas/{{$iniciativa[0]->inic_codigo}}/evaluar/invitar/13';
                                            }else if(tipo == 14){
                                                document.getElementById('verResultadosButton').hidden = false;
                                                document.getElementById('agregarParticipantesButton').hidden = false;
                                                document.getElementById('verResultadosButton').href = '/admin/iniciativas/{{$iniciativa[0]->inic_codigo}}/evaluacion/resultados/14';
                                                document.getElementById('agregarParticipantesButton').href = '/admin/iniciativas/{{$iniciativa[0]->inic_codigo}}/evaluar/invitar/14';
                                            }
                                        }
                                    </script>



                                    <script>
                                        //funcion donde si cambia el tipo de evaluador se muestra el boton de enviar
                                        onchange = function() {
                                            document.getElementById('botonEnviar').hidden = false;
                                        }

                                    </script>


                                </div>

                                <div class="d-flex justify-content-center mb-5">
                                    <button class="btn btn-danger" onclick="eliminarEvaluaciones({{$iniciativa[0]->inic_codigo}});">Cambiar tipo de evaluación</button>
                                </div>
                            </div>

                            @endif




                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function cambioTipo() {
                var tipo = document.getElementById('tipo').value;
                document.getElementById('invitado_rol').value = document.getElementById('tipo').value;

                if (tipo == 0) {
                    document.getElementById('botonesdeAbajo').style.display = 'block';
                    document.getElementById('EstudiantesBloque').style.display = 'block';
                    document.getElementById('DocentesBloque').style.display = 'none';
                    document.getElementById('ExternosBloque').style.display = 'none';
                } else if (tipo == 1) {
                    document.getElementById('EstudiantesBloque').style.display = 'none';
                    document.getElementById('botonesdeAbajo').style.display = 'block';
                    document.getElementById('DocentesBloque').style.display = 'block';
                    document.getElementById('ExternosBloque').style.display = 'none';
                } else if (tipo == 2) {
                    document.getElementById('EstudiantesBloque').style.display = 'none';
                    document.getElementById('botonesdeAbajo').style.display = 'block';
                    document.getElementById('DocentesBloque').style.display = 'none';
                    document.getElementById('ExternosBloque').style.display = 'block';
                } else{
                    document.getElementById('EstudiantesBloque').style.display = 'none';
                    document.getElementById('botonesdeAbajo').style.display = 'block';
                    document.getElementById('DocentesBloque').style.display = 'none';
                    document.getElementById('ExternosBloque').style.display = 'none';
                }

            }
            function redireccionarEvaluadores() {
                evaluador = document.getElementById('tipo').value;
                if(evaluador == ""){
                    alert('Debe seleccionar un tipo de evaluador');
                    return;
                }
                console.log('inic_codigo: {{$iniciativa[0]->inic_codigo}}' );
                window.location.href = '/admin/iniciativas/'+{{$iniciativa[0]->inic_codigo}}+'/evaluar/invitar/'+evaluador;
            }

            function redireccionarResultados() {
                evaluador = document.getElementById('tipo').value;
                if(evaluador == ""){
                    alert('Debe seleccionar un tipo de evaluador');
                    return;
                }
                console.log('inic_codigo: {{$iniciativa[0]->inic_codigo}}' );
                window.location.href = '/admin/iniciativas/'+{{$iniciativa[0]->inic_codigo}}+'/evaluacion/resultados/'+evaluador;
            }

            function eliminarEvaluaciones(inic_codigo) {
            $('#inic_codigo').val(inic_codigo);
            $('#modalEliminaEvaluacion').modal('show');
        }
        </script>




@endsection
