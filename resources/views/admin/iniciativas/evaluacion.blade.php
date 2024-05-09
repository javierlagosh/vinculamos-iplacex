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
                                                class="dropdown-item has-item" data-toggle="tooltip"
                                                data-placement="top" title="Ingresar cobertura"><i
                                                    class="fas fa-users"></i> Ingresaer cobertura</a>

                                            <a href="{{ route('admin.resultados.listado', $iniciativa[0]->inic_codigo) }}"
                                                class="dropdown-item has-item" data-toggle="tooltip"
                                                data-placement="top" title="Ingresar resultado"><i
                                                    class="fas fa-flag"></i> Ingresar resultado/s</a>
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

                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <form method="POST" action="{{route('admin.crear.evaluacion')}}">
                                        @csrf
                                        <div class="form-group">
                                            <label for="tipo">
                                                <strong>Tipo de evaluador:</strong>
                                            </label>
                                            <div class="w-50">
                                                <input type="text" hidden name="inic_codigo" id="inic_codigo"
                                                value="{{ $iniciativa[0]->inic_codigo }}">
                                                <select class="form-control select2" name="tipo" id="tipo" onchange="cambioTipo();">
                                                    <option value="" disabled selected>Seleccione...</option>
                                                    <option value="0">Evaluador interno - Estudiante</option>
                                                    <option value="1">Evaluador interno - Docente/Directivo</option>
                                                    <option value="2">Evaluador externo</option>
                                                    {{-- <option value="4">Limpiar</option> --}}
                                                </select>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary" >
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" height="15" width="15"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"/></svg>
                                            Agregar evaluación
                                        </button>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <div style="display:none;" id="EstudiantesBloque">
                            @if ($evaEstudiantesTotal == 1)
                        <hr>
                        <br>
                        <div >
                            <div class="card-header d-flex justify-content-between">
                                <h4>
                                    Propuesta evaluación vinculamos:
                                </h4>
                            </div>


                            <div class="mx-3">
                                Estimado/a,
                                En el marco de las actividades que Iplacex, hemos realizado la iniciativa denominada <strong>{{$iniciativa[0]->inic_nombre}}</strong>, en la cual le agradecemos haber participado.
                                Con el propósito de continuar mejorando nuestro trabajo, le pedimos que responda la siguiente encuesta, que nos permitirá evaluar esta actividad.
                                Saluda atentamente a usted.
                            </div>
                            <div class="w-100">
                                <div class="container bg-white w-100 p-5 rounded-bottom">
                                    <div class="row w-100">
                                      <div>
                                        <div >
                                          <div class="card-body">
                                              <div class="row">
                                                  <div class="col-12 col-md-6 col-lg-6">

                                                      <div class="card card-primary">
                                                          <div class="card-header" style="background-color: rgb(103,119,239);">
                                                              <h4 style="color:aliceblue">Conocimiento de la evaluación</h4>
                                                          </div>
                                                          <div class="card-body">

                                                              <div class="table-responsive">
                                                                  <table class="table table-striped">

                                                                      <tbody>
                                                                          <tr>
                                                                              <th>¿Sabía usted que el propósito de ésta
                                                                                  actividad
                                                                                  era?</th>
                                                                              <th>¿Sí o No?</th>
                                                                          </tr>
                                                                          <tr>
                                                                              <td>
                                                                                  {{ $iniciativa[0]->inic_descripcion }}
                                                                              </td>
                                                                              <td>
                                                                                  <div class="form-group">
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="conocimiento_1_SINO_1"
                                                                                              id="conocimiento_1_SINO_1_si"
                                                                                              value="100" checked>
                                                                                          <label class="form-check-label"
                                                                                              for="conocimiento_1_SINO_1_si">
                                                                                              SI</label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="conocimiento_1_SINO_1"
                                                                                              id="conocimiento_1_SINO_1_no"
                                                                                              value="0">
                                                                                          <label class="form-check-label"
                                                                                              for="conocimiento_1_SINO_1_no">NO</label>
                                                                                      </div>
                                                                                  </div>
                                                                              </td>

                                                                          </tr>
                                                                      </tbody>
                                                                      <tbody>
                                                                          <tr>
                                                                              <th>¿Sabía usted que los resultados esperados de
                                                                                  la
                                                                                  actividad eran?</th>
                                                                              <th>¿Sí o No?</th>
                                                                          </tr>

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
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="conocimiento_2_SINO"
                                                                                              id="conocimiento_2_SINO_si" value="100"
                                                                                              checked>
                                                                                          <label class="form-check-label"
                                                                                              for="conocimiento_2_SINO_si">
                                                                                              SI </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="conocimiento_2_SINO"
                                                                                              id="conocimiento_2_SINO_no"
                                                                                              value="0">
                                                                                          <label class="form-check-label"
                                                                                              for="conocimiento_2_SINO_no">
                                                                                              NO </label>
                                                                                      </div>
                                                                                  </div>
                                                                              </td>
                                                                          </tr>
                                                                      </tbody>

                                                                      <tbody>
                                                                          <tr>
                                                                              <th>¿Sabía usted que las contribuciones
                                                                                  esperadas
                                                                                  eran?</th>
                                                                              <th>¿Sí o No?</th>
                                                                              {{-- <th>¿En qué % cree usted que se cumplirán las contribuciones?</th> --}}
                                                                          </tr>

                                                                          <tr>
                                                                              <td>
                                                                                  <ul>
                                                                                      @foreach ($ambitos as $ambito)
                                                                                          <li>{{ $ambito->amb_nombre }}</li>
                                                                                      @endforeach
                                                                                  </ul>

                                                                              </td>
                                                                              <td>
                                                                                  <div class="form-group">
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="conocimiento_3_SINO"
                                                                                              id="conocimiento_3_SINO_si" value="100"
                                                                                              checked>
                                                                                          <label class="form-check-label"
                                                                                              for="conocimiento_3_SINO_si">
                                                                                              SI </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="conocimiento_3_SINO"
                                                                                              id="conocimiento_3_SINO_no"
                                                                                              value="0">
                                                                                          <label class="form-check-label"
                                                                                              for="conocimiento_3_SINO_no">
                                                                                              NO </label>
                                                                                      </div>
                                                                                  </div>
                                                                              </td>
                                                                          </tr>
                                                                      </tbody>
                                                                  </table>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="col-12 col-md-6 col-lg-6">
                                                      <div class="card card-primary">
                                                          <div class="card-header" style="background-color: rgb(103,119,239);">
                                                              <h4 style="color:aliceblue">Cumplimiento de la Iniciativa</h4>
                                                          </div>
                                                          <div class="card-body">
                                                              <div class="table-responsive">
                                                                  <table class="table table-striped" id="table-1">
                                                                      <tbody>
                                                                          <tr>
                                                                              <th>¿En qué % cree usted que se cumplió el
                                                                                  objetivo?
                                                                              </th>
                                                                          </tr>
                                                                          <tr>
                                                                              <td>
                                                                                  <div class="form-group">
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_1"
                                                                                              id="cumplimiento_1_0" value="0"
                                                                                              checked>
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_1_0">
                                                                                              No se cumplió </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_1"
                                                                                              id="cumplimiento_1_25" value="25">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_1_25">
                                                                                              25 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_1"
                                                                                              id="cumplimiento_1_50" value="50">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_1_50">
                                                                                              50 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_1"
                                                                                              id="cumplimiento_1_75" value="75">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_1_75">
                                                                                              75 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_1"
                                                                                              id="cumplimiento_1_100" value="100">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_1_100">
                                                                                              100 % </label>
                                                                                      </div>
                                                                                  </div>
                                                                              </td>
                                                                          </tr>
                                                                      </tbody>
                                                                      <tbody>
                                                                          <tr>
                                                                              <th>¿En qué % cree usted que se cumplió el
                                                                                  resultado
                                                                                  esperado?</th>
                                                                          </tr>
                                                                          <tr>
                                                                              <td>
                                                                                  <div class="form-group">
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_2"
                                                                                              id="cumplimiento_2_0" value="0"
                                                                                              checked>
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_2_0">
                                                                                              No se cumplió </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_2"
                                                                                              id="cumplimiento_2_25" value="25">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_2_25">
                                                                                              25 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_2"
                                                                                              id="cumplimiento_2_50" value="50">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_2_50">
                                                                                              50 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_2"
                                                                                              id="cumplimiento_2_75" value="75">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_2_75">
                                                                                              75 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_2"
                                                                                              id="cumplimiento_2_100" value="100">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_2_100">
                                                                                              100 % </label>
                                                                                      </div>
                                                                                  </div>
                                                                              </td>
                                                                          </tr>
                                                                      </tbody>

                                                                      <tbody>
                                                                          <tr>
                                                                              <th>¿En qué % cree usted que se cumplirán las
                                                                                  contribuciones?</th>
                                                                          </tr>

                                                                          <tr>
                                                                              <td>
                                                                                  <div class="form-group">
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_3"
                                                                                              id="cumplimiento_3_0" value="0"
                                                                                              checked>
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_3_0">
                                                                                              No se cumplirán </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_3"
                                                                                              id="cumplimiento_3_25" value="25">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_3_25">
                                                                                              25 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_3"
                                                                                              id="cumplimiento_3_50" value="50">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_3_50">
                                                                                              50 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_3"
                                                                                              id="cumplimiento_3_75" value="75">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_3_75">
                                                                                              75 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_3"
                                                                                              id="cumplimiento_3_100" value="100">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_3_100">
                                                                                              100 % </label>
                                                                                      </div>
                                                                                  </div>
                                                                              </td>
                                                                          </tr>
                                                                      </tbody>
                                                                  </table>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>

                                              <div class="row">
                                                  <div class="col-12 col-md-6 col-lg-6">
                                                      <div class="card card-info">
                                                          <div class="card-header" style="background-color: rgb(58,186,244);">
                                                              <h4 style="color:aliceblue">Calidad de ejecución</h4>
                                                          </div>
                                                          <div class="card-body" style="color:black;">
                                                              <label name="etiquetasEstudiante">A continuación te pedimos que
                                                                  evalúes de 0 a 3 la calidad
                                                                  en la ejecución de la actividad, según los compromisos
                                                                  asumidos por la institución, en que
                                                                  <ol>
                                                                      <li>0= no cumple.</li>
                                                                      <li>1= cumple mínimamente.</li>
                                                                      <li>2= cumple medianamente.</li>
                                                                      <li>3= cumple totalmente.</li>
                                                                  </ol>
                                                                  Si considera que algunos ítemes no estaban
                                                                  comprometidos,
                                                                  marque <b>No Aplica.</b>
                                                              </label>

                                                              <label name="etiquetasOtras">A continuación le pedimos que
                                                                  evalúe de 0 a 3 la calidad
                                                                  en la ejecución de la actividad, según los compromisos
                                                                  asumidos por la institución, en que
                                                                  <ol>
                                                                      <li>0= no cumple.</li>
                                                                      <li>1= cumple mínimamente.</li>
                                                                      <li>2= cumple medianamente.</li>
                                                                      <li>3= cumple totalmente.</li>
                                                                  </ol>
                                                                  Si considera que algunos ítemes no estaban
                                                                  comprometidos,
                                                                  marque <b>No Aplica.</b>
                                                              </label>

                                                              <div class="table-responsive">
                                                                  <table class="table table-striped">
                                                                      <tr>
                                                                          <th>Con qué nota evalúa usted la calidad en la
                                                                              ejecución de la actividad, en las siguientes
                                                                              dimensiones:</th>
                                                                          <th>Cumplimiento</th>
                                                                      </tr>
                                                                      <tbody>
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
                                                                                          <div class="form-check form-check-inline">
                                                                                              <input class="form-check-input"
                                                                                                  type="radio"
                                                                                                  name="calidad_{{ $i }}"
                                                                                                  id="calidad_{{ $i }}_0"
                                                                                                  value="0">
                                                                                              <label class="form-check-label"
                                                                                                  for="calidad_{{ $i }}_0">
                                                                                                  0 </label>
                                                                                          </div>
                                                                                          <div class="form-check form-check-inline">
                                                                                              <input class="form-check-input"
                                                                                                  type="radio"
                                                                                                  name="calidad_{{ $i }}"
                                                                                                  id="calidad_{{ $i }}_1"
                                                                                                  value="33">
                                                                                              <label class="form-check-label"
                                                                                                  for="calidad_{{ $i }}_1">
                                                                                                  1 </label>
                                                                                          </div>
                                                                                          <div class="form-check form-check-inline">
                                                                                              <input class="form-check-input"
                                                                                                  type="radio"
                                                                                                  name="calidad_{{ $i }}"
                                                                                                  id="calidad_{{ $i }}_2"
                                                                                                  value="66">
                                                                                              <label class="form-check-label"
                                                                                                  for="calidad_{{ $i }}_2">
                                                                                                  2 </label>
                                                                                          </div>
                                                                                          <div class="form-check form-check-inline">
                                                                                              <input class="form-check-input"
                                                                                                  type="radio"
                                                                                                  name="calidad_{{ $i }}"
                                                                                                  id="calidad_{{ $i }}_3"
                                                                                                  value="100">
                                                                                              <label class="form-check-label"
                                                                                                  for="calidad_{{ $i }}_3">
                                                                                                  3 </label>
                                                                                          </div>
                                                                                          <div class="form-check form-check-inline">
                                                                                              <input class="form-check-input"
                                                                                                  type="radio"
                                                                                                  name="calidad_{{ $i }}"
                                                                                                  id="calidad_{{ $i }}_NO"
                                                                                                  checked value="">
                                                                                              <label class="form-check-label"
                                                                                                  for="calidad_{{ $i }}_NO">
                                                                                                  No Aplica </label>
                                                                                          </div>
                                                                                      </div>
                                                                                  </td>
                                                                              </tr>
                                                                          @endfor
                                                                      </tbody>
                                                                  </table>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>

                                                  <div class="col-12 col-md-6 col-lg-6">
                                                      <div id="divAMostrar" name="divAMostrar">
                                                          <div class="card card-secondary">
                                                              <div class="card-header" style="background-color: rgb(55,60,97);">
                                                                  <h4 style="color:aliceblue">Competencia de estudiantes</h4>
                                                              </div>
                                                              <div class="card-body" style="color:black;">
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
                                                                      <table class="table table-striped">
                                                                          <tbody>
                                                                              <tr>
                                                                                  <th>Te sirvió la actividad para desarrollar
                                                                                      algunas
                                                                                      de las siguientes dimensiones de las
                                                                                      competencias comprometidas?</th>
                                                                                  <th>Cumplimiento</th>
                                                                              </tr>

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
                                                                                              <div class="form-check form-check-inline">
                                                                                                  <input class="form-check-input"
                                                                                                      type="radio"
                                                                                                      name="competencia_{{ $i }}"
                                                                                                      id="competencia_{{ $i }}_0"
                                                                                                      value="0">
                                                                                                  <label class="form-check-label"
                                                                                                      for="competencia_{{ $i }}_0">
                                                                                                      0 </label>
                                                                                              </div>
                                                                                              <div class="form-check form-check-inline">
                                                                                                  <input class="form-check-input"
                                                                                                      type="radio"
                                                                                                      name="competencia_{{ $i }}"
                                                                                                      id="competencia_{{ $i }}_1"
                                                                                                      value="33">
                                                                                                  <label class="form-check-label"
                                                                                                      for="competencia_{{ $i }}_1">
                                                                                                      1 </label>
                                                                                              </div>
                                                                                              <div class="form-check form-check-inline">
                                                                                                  <input class="form-check-input"
                                                                                                      type="radio"
                                                                                                      name="competencia_{{ $i }}"
                                                                                                      id="competencia_{{ $i }}_2"
                                                                                                      value="66">
                                                                                                  <label class="form-check-label"
                                                                                                      for="competencia_{{ $i }}_2">
                                                                                                      2 </label>
                                                                                              </div>
                                                                                              <div class="form-check form-check-inline">
                                                                                                  <input class="form-check-input"
                                                                                                      type="radio"
                                                                                                      name="competencia_{{ $i }}"
                                                                                                      id="competencia_{{ $i }}_3"
                                                                                                      value="100">
                                                                                                  <label class="form-check-label"
                                                                                                      for="competencia_{{ $i }}_3">
                                                                                                      3 </label>
                                                                                              </div>
                                                                                          </div>
                                                                                      </td>
                                                                                  </tr>
                                                                              @endfor
                                                                          </tbody>
                                                                      </table>
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

                              </div>

                        </div>


                        @endif

                        </div>
                        <div style="display:none;" id="DocentesBloque">
                            @if ($evaDocentesTotal == 1)
                        <hr>
                        <br>
                        <div >
                            <div class="card-header d-flex justify-content-between">
                                <h4>
                                    Propuesta evaluación vinculamos:
                                </h4>
                            </div>



                            <div class="mx-3">
                                Estimado/a,
                                En el marco de las actividades que Iplacex, hemos realizado la iniciativa denominada <strong>{{$iniciativa[0]->inic_nombre}}</strong>, en la cual le agradecemos haber participado.
                                Con el propósito de continuar mejorando nuestro trabajo, le pedimos que responda la siguiente encuesta, que nos permitirá evaluar esta actividad.
                                Saluda atentamente a usted.
                            </div>
                            <div class="w-100">
                                <div class="container bg-white w-100 p-5 rounded-bottom">
                                    <div class="row w-100">
                                      <div>
                                        <div >
                                          <div class="card-body">
                                              <div class="row">
                                                  <div class="col-12 col-md-6 col-lg-6">

                                                      <div class="card card-primary">
                                                          <div class="card-header" style="background-color: rgb(103,119,239);">
                                                              <h4 style="color:aliceblue">Conocimiento de la evaluación</h4>
                                                          </div>
                                                          <div class="card-body">

                                                              <div class="table-responsive">
                                                                  <table class="table table-striped">

                                                                      <tbody>
                                                                          <tr>
                                                                              <th>¿Sabía usted que el propósito de ésta
                                                                                  actividad
                                                                                  era?</th>
                                                                              <th>¿Sí o No?</th>
                                                                          </tr>
                                                                          <tr>
                                                                              <td>
                                                                                  {{ $iniciativa[0]->inic_descripcion }}
                                                                              </td>
                                                                              <td>
                                                                                  <div class="form-group">
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="conocimiento_1_SINO_1"
                                                                                              id="conocimiento_1_SINO_1_si"
                                                                                              value="100" checked>
                                                                                          <label class="form-check-label"
                                                                                              for="conocimiento_1_SINO_1_si">
                                                                                              SI</label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="conocimiento_1_SINO_1"
                                                                                              id="conocimiento_1_SINO_1_no"
                                                                                              value="0">
                                                                                          <label class="form-check-label"
                                                                                              for="conocimiento_1_SINO_1_no">NO</label>
                                                                                      </div>
                                                                                  </div>
                                                                              </td>

                                                                          </tr>
                                                                      </tbody>
                                                                      <tbody>
                                                                          <tr>
                                                                              <th>¿Sabía usted que los resultados esperados de
                                                                                  la
                                                                                  actividad eran?</th>
                                                                              <th>¿Sí o No?</th>
                                                                          </tr>

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
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="conocimiento_2_SINO"
                                                                                              id="conocimiento_2_SINO_si" value="100"
                                                                                              checked>
                                                                                          <label class="form-check-label"
                                                                                              for="conocimiento_2_SINO_si">
                                                                                              SI </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="conocimiento_2_SINO"
                                                                                              id="conocimiento_2_SINO_no"
                                                                                              value="0">
                                                                                          <label class="form-check-label"
                                                                                              for="conocimiento_2_SINO_no">
                                                                                              NO </label>
                                                                                      </div>
                                                                                  </div>
                                                                              </td>
                                                                          </tr>
                                                                      </tbody>

                                                                      <tbody>
                                                                          <tr>
                                                                              <th>¿Sabía usted que las contribuciones
                                                                                  esperadas
                                                                                  eran?</th>
                                                                              <th>¿Sí o No?</th>
                                                                              {{-- <th>¿En qué % cree usted que se cumplirán las contribuciones?</th> --}}
                                                                          </tr>

                                                                          <tr>
                                                                              <td>
                                                                                  <ul>
                                                                                      @foreach ($ambitos as $ambito)
                                                                                          <li>{{ $ambito->amb_nombre }}</li>
                                                                                      @endforeach
                                                                                  </ul>

                                                                              </td>
                                                                              <td>
                                                                                  <div class="form-group">
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="conocimiento_3_SINO"
                                                                                              id="conocimiento_3_SINO_si" value="100"
                                                                                              checked>
                                                                                          <label class="form-check-label"
                                                                                              for="conocimiento_3_SINO_si">
                                                                                              SI </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="conocimiento_3_SINO"
                                                                                              id="conocimiento_3_SINO_no"
                                                                                              value="0">
                                                                                          <label class="form-check-label"
                                                                                              for="conocimiento_3_SINO_no">
                                                                                              NO </label>
                                                                                      </div>
                                                                                  </div>
                                                                              </td>
                                                                          </tr>
                                                                      </tbody>
                                                                  </table>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="col-12 col-md-6 col-lg-6">
                                                      <div class="card card-primary">
                                                          <div class="card-header" style="background-color: rgb(103,119,239);">
                                                              <h4 style="color:aliceblue">Cumplimiento de la Iniciativa</h4>
                                                          </div>
                                                          <div class="card-body">
                                                              <div class="table-responsive">
                                                                  <table class="table table-striped" id="table-1">
                                                                      <tbody>
                                                                          <tr>
                                                                              <th>¿En qué % cree usted que se cumplió el
                                                                                  objetivo?
                                                                              </th>
                                                                          </tr>
                                                                          <tr>
                                                                              <td>
                                                                                  <div class="form-group">
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_1"
                                                                                              id="cumplimiento_1_0" value="0"
                                                                                              checked>
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_1_0">
                                                                                              No se cumplió </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_1"
                                                                                              id="cumplimiento_1_25" value="25">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_1_25">
                                                                                              25 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_1"
                                                                                              id="cumplimiento_1_50" value="50">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_1_50">
                                                                                              50 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_1"
                                                                                              id="cumplimiento_1_75" value="75">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_1_75">
                                                                                              75 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_1"
                                                                                              id="cumplimiento_1_100" value="100">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_1_100">
                                                                                              100 % </label>
                                                                                      </div>
                                                                                  </div>
                                                                              </td>
                                                                          </tr>
                                                                      </tbody>
                                                                      <tbody>
                                                                          <tr>
                                                                              <th>¿En qué % cree usted que se cumplió el
                                                                                  resultado
                                                                                  esperado?</th>
                                                                          </tr>
                                                                          <tr>
                                                                              <td>
                                                                                  <div class="form-group">
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_2"
                                                                                              id="cumplimiento_2_0" value="0"
                                                                                              checked>
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_2_0">
                                                                                              No se cumplió </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_2"
                                                                                              id="cumplimiento_2_25" value="25">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_2_25">
                                                                                              25 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_2"
                                                                                              id="cumplimiento_2_50" value="50">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_2_50">
                                                                                              50 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_2"
                                                                                              id="cumplimiento_2_75" value="75">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_2_75">
                                                                                              75 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_2"
                                                                                              id="cumplimiento_2_100" value="100">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_2_100">
                                                                                              100 % </label>
                                                                                      </div>
                                                                                  </div>
                                                                              </td>
                                                                          </tr>
                                                                      </tbody>

                                                                      <tbody>
                                                                          <tr>
                                                                              <th>¿En qué % cree usted que se cumplirán las
                                                                                  contribuciones?</th>
                                                                          </tr>

                                                                          <tr>
                                                                              <td>
                                                                                  <div class="form-group">
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_3"
                                                                                              id="cumplimiento_3_0" value="0"
                                                                                              checked>
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_3_0">
                                                                                              No se cumplirán </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_3"
                                                                                              id="cumplimiento_3_25" value="25">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_3_25">
                                                                                              25 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_3"
                                                                                              id="cumplimiento_3_50" value="50">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_3_50">
                                                                                              50 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_3"
                                                                                              id="cumplimiento_3_75" value="75">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_3_75">
                                                                                              75 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_3"
                                                                                              id="cumplimiento_3_100" value="100">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_3_100">
                                                                                              100 % </label>
                                                                                      </div>
                                                                                  </div>
                                                                              </td>
                                                                          </tr>
                                                                      </tbody>
                                                                  </table>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>

                                              <div class="row">
                                                  <div class="col-12 col-md-6 col-lg-6">
                                                      <div class="card card-info">
                                                          <div class="card-header" style="background-color: rgb(58,186,244);">
                                                              <h4 style="color:aliceblue">Calidad de ejecución</h4>
                                                          </div>
                                                          <div class="card-body" style="color:black;">
                                                              <label name="etiquetasEstudiante">A continuación te pedimos que
                                                                  evalúes de 0 a 3 la calidad
                                                                  en la ejecución de la actividad, según los compromisos
                                                                  asumidos por la institución, en que
                                                                  <ol>
                                                                      <li>0= no cumple.</li>
                                                                      <li>1= cumple mínimamente.</li>
                                                                      <li>2= cumple medianamente.</li>
                                                                      <li>3= cumple totalmente.</li>
                                                                  </ol>
                                                                  Si considera que algunos ítemes no estaban
                                                                  comprometidos,
                                                                  marque <b>No Aplica.</b>
                                                              </label>

                                                              <label name="etiquetasOtras">A continuación le pedimos que
                                                                  evalúe de 0 a 3 la calidad
                                                                  en la ejecución de la actividad, según los compromisos
                                                                  asumidos por la institución, en que
                                                                  <ol>
                                                                      <li>0= no cumple.</li>
                                                                      <li>1= cumple mínimamente.</li>
                                                                      <li>2= cumple medianamente.</li>
                                                                      <li>3= cumple totalmente.</li>
                                                                  </ol>
                                                                  Si considera que algunos ítemes no estaban
                                                                  comprometidos,
                                                                  marque <b>No Aplica.</b>
                                                              </label>

                                                              <div class="table-responsive">
                                                                  <table class="table table-striped">
                                                                      <tr>
                                                                          <th>Con qué nota evalúa usted la calidad en la
                                                                              ejecución de la actividad, en las siguientes
                                                                              dimensiones:</th>
                                                                          <th>Cumplimiento</th>
                                                                      </tr>
                                                                      <tbody>
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
                                                                                          <div class="form-check form-check-inline">
                                                                                              <input class="form-check-input"
                                                                                                  type="radio"
                                                                                                  name="calidad_{{ $i }}"
                                                                                                  id="calidad_{{ $i }}_0"
                                                                                                  value="0">
                                                                                              <label class="form-check-label"
                                                                                                  for="calidad_{{ $i }}_0">
                                                                                                  0 </label>
                                                                                          </div>
                                                                                          <div class="form-check form-check-inline">
                                                                                              <input class="form-check-input"
                                                                                                  type="radio"
                                                                                                  name="calidad_{{ $i }}"
                                                                                                  id="calidad_{{ $i }}_1"
                                                                                                  value="33">
                                                                                              <label class="form-check-label"
                                                                                                  for="calidad_{{ $i }}_1">
                                                                                                  1 </label>
                                                                                          </div>
                                                                                          <div class="form-check form-check-inline">
                                                                                              <input class="form-check-input"
                                                                                                  type="radio"
                                                                                                  name="calidad_{{ $i }}"
                                                                                                  id="calidad_{{ $i }}_2"
                                                                                                  value="66">
                                                                                              <label class="form-check-label"
                                                                                                  for="calidad_{{ $i }}_2">
                                                                                                  2 </label>
                                                                                          </div>
                                                                                          <div class="form-check form-check-inline">
                                                                                              <input class="form-check-input"
                                                                                                  type="radio"
                                                                                                  name="calidad_{{ $i }}"
                                                                                                  id="calidad_{{ $i }}_3"
                                                                                                  value="100">
                                                                                              <label class="form-check-label"
                                                                                                  for="calidad_{{ $i }}_3">
                                                                                                  3 </label>
                                                                                          </div>
                                                                                          <div class="form-check form-check-inline">
                                                                                              <input class="form-check-input"
                                                                                                  type="radio"
                                                                                                  name="calidad_{{ $i }}"
                                                                                                  id="calidad_{{ $i }}_NO"
                                                                                                  checked value="">
                                                                                              <label class="form-check-label"
                                                                                                  for="calidad_{{ $i }}_NO">
                                                                                                  No Aplica </label>
                                                                                          </div>
                                                                                      </div>
                                                                                  </td>
                                                                              </tr>
                                                                          @endfor
                                                                      </tbody>
                                                                  </table>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>

                                                  <div class="col-12 col-md-6 col-lg-6">
                                                      <div id="divAMostrar" name="divAMostrar">
                                                          <div class="card card-secondary">
                                                              <div class="card-header" style="background-color: rgb(55,60,97);">
                                                                  <h4 style="color:aliceblue">Competencia de estudiantes</h4>
                                                              </div>
                                                              <div class="card-body" style="color:black;">
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
                                                                      <table class="table table-striped">
                                                                          <tbody>
                                                                              <tr>
                                                                                  <th>Te sirvió la actividad para desarrollar
                                                                                      algunas
                                                                                      de las siguientes dimensiones de las
                                                                                      competencias comprometidas?</th>
                                                                                  <th>Cumplimiento</th>
                                                                              </tr>

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
                                                                                              <div class="form-check form-check-inline">
                                                                                                  <input class="form-check-input"
                                                                                                      type="radio"
                                                                                                      name="competencia_{{ $i }}"
                                                                                                      id="competencia_{{ $i }}_0"
                                                                                                      value="0">
                                                                                                  <label class="form-check-label"
                                                                                                      for="competencia_{{ $i }}_0">
                                                                                                      0 </label>
                                                                                              </div>
                                                                                              <div class="form-check form-check-inline">
                                                                                                  <input class="form-check-input"
                                                                                                      type="radio"
                                                                                                      name="competencia_{{ $i }}"
                                                                                                      id="competencia_{{ $i }}_1"
                                                                                                      value="33">
                                                                                                  <label class="form-check-label"
                                                                                                      for="competencia_{{ $i }}_1">
                                                                                                      1 </label>
                                                                                              </div>
                                                                                              <div class="form-check form-check-inline">
                                                                                                  <input class="form-check-input"
                                                                                                      type="radio"
                                                                                                      name="competencia_{{ $i }}"
                                                                                                      id="competencia_{{ $i }}_2"
                                                                                                      value="66">
                                                                                                  <label class="form-check-label"
                                                                                                      for="competencia_{{ $i }}_2">
                                                                                                      2 </label>
                                                                                              </div>
                                                                                              <div class="form-check form-check-inline">
                                                                                                  <input class="form-check-input"
                                                                                                      type="radio"
                                                                                                      name="competencia_{{ $i }}"
                                                                                                      id="competencia_{{ $i }}_3"
                                                                                                      value="100">
                                                                                                  <label class="form-check-label"
                                                                                                      for="competencia_{{ $i }}_3">
                                                                                                      3 </label>
                                                                                              </div>
                                                                                          </div>
                                                                                      </td>
                                                                                  </tr>
                                                                              @endfor
                                                                          </tbody>
                                                                      </table>
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

                              </div>

                        </div>


                        @endif

                        </div>
                        <div style="display:none;" id="ExternosBloque">
                            @if ($evaExternosTotal == 1)
                        <hr>
                        <br>
                        <div >
                            <div class="card-header d-flex justify-content-between">
                                <h4>
                                    Propuesta evaluación vinculamos:
                                </h4>
                            </div>
                            <div class="mx-3">
                                Estimado/a,
                                En el marco de las actividades que el Iplacex, hemos realizado la iniciativa denominada <strong>{{$iniciativa[0]->inic_nombre}}</strong>, en la cual le agradecemos haber participado.
                                Con el propósito de continuar mejorando nuestro trabajo, le pedimos que responda la siguiente encuesta, que nos permitirá evaluar esta actividad.
                                Saluda atentamente a usted.
                            </div>
                            <div class="w-100">
                                <div class="container bg-white w-100 p-5 rounded-bottom">
                                    <div class="row w-100">
                                      <div>
                                        <div >
                                          <div class="card-body">
                                              <div class="row">
                                                  <div class="col-12 col-md-6 col-lg-6">

                                                      <div class="card card-primary">
                                                          <div class="card-header" style="background-color: rgb(103,119,239);">
                                                              <h4 style="color:aliceblue">Conocimiento de la evaluación</h4>
                                                          </div>
                                                          <div class="card-body">

                                                              <div class="table-responsive">
                                                                  <table class="table table-striped">

                                                                      <tbody>
                                                                          <tr>
                                                                              <th>¿Sabía usted que el propósito de ésta
                                                                                  actividad
                                                                                  era?</th>
                                                                              <th>¿Sí o No?</th>
                                                                          </tr>
                                                                          <tr>
                                                                              <td>
                                                                                  {{ $iniciativa[0]->inic_descripcion }}
                                                                              </td>
                                                                              <td>
                                                                                  <div class="form-group">
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="conocimiento_1_SINO_1"
                                                                                              id="conocimiento_1_SINO_1_si"
                                                                                              value="100" checked>
                                                                                          <label class="form-check-label"
                                                                                              for="conocimiento_1_SINO_1_si">
                                                                                              SI</label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="conocimiento_1_SINO_1"
                                                                                              id="conocimiento_1_SINO_1_no"
                                                                                              value="0">
                                                                                          <label class="form-check-label"
                                                                                              for="conocimiento_1_SINO_1_no">NO</label>
                                                                                      </div>
                                                                                  </div>
                                                                              </td>

                                                                          </tr>
                                                                      </tbody>
                                                                      <tbody>
                                                                          <tr>
                                                                              <th>¿Sabía usted que los resultados esperados de
                                                                                  la
                                                                                  actividad eran?</th>
                                                                              <th>¿Sí o No?</th>
                                                                          </tr>

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
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="conocimiento_2_SINO"
                                                                                              id="conocimiento_2_SINO_si" value="100"
                                                                                              checked>
                                                                                          <label class="form-check-label"
                                                                                              for="conocimiento_2_SINO_si">
                                                                                              SI </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="conocimiento_2_SINO"
                                                                                              id="conocimiento_2_SINO_no"
                                                                                              value="0">
                                                                                          <label class="form-check-label"
                                                                                              for="conocimiento_2_SINO_no">
                                                                                              NO </label>
                                                                                      </div>
                                                                                  </div>
                                                                              </td>
                                                                          </tr>
                                                                      </tbody>

                                                                      <tbody>
                                                                          <tr>
                                                                              <th>¿Sabía usted que las contribuciones
                                                                                  esperadas
                                                                                  eran?</th>
                                                                              <th>¿Sí o No?</th>
                                                                              {{-- <th>¿En qué % cree usted que se cumplirán las contribuciones?</th> --}}
                                                                          </tr>

                                                                          <tr>
                                                                              <td>
                                                                                  <ul>
                                                                                      @foreach ($ambitos as $ambito)
                                                                                          <li>{{ $ambito->amb_nombre }}</li>
                                                                                      @endforeach
                                                                                  </ul>

                                                                              </td>
                                                                              <td>
                                                                                  <div class="form-group">
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="conocimiento_3_SINO"
                                                                                              id="conocimiento_3_SINO_si" value="100"
                                                                                              checked>
                                                                                          <label class="form-check-label"
                                                                                              for="conocimiento_3_SINO_si">
                                                                                              SI </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="conocimiento_3_SINO"
                                                                                              id="conocimiento_3_SINO_no"
                                                                                              value="0">
                                                                                          <label class="form-check-label"
                                                                                              for="conocimiento_3_SINO_no">
                                                                                              NO </label>
                                                                                      </div>
                                                                                  </div>
                                                                              </td>
                                                                          </tr>
                                                                      </tbody>
                                                                  </table>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="col-12 col-md-6 col-lg-6">
                                                      <div class="card card-primary">
                                                          <div class="card-header" style="background-color: rgb(103,119,239);">
                                                              <h4 style="color:aliceblue">Cumplimiento de la Iniciativa</h4>
                                                          </div>
                                                          <div class="card-body">
                                                              <div class="table-responsive">
                                                                  <table class="table table-striped" id="table-1">
                                                                      <tbody>
                                                                          <tr>
                                                                              <th>¿En qué % cree usted que se cumplió el
                                                                                  objetivo?
                                                                              </th>
                                                                          </tr>
                                                                          <tr>
                                                                              <td>
                                                                                  <div class="form-group">
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_1"
                                                                                              id="cumplimiento_1_0" value="0"
                                                                                              checked>
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_1_0">
                                                                                              No se cumplió </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_1"
                                                                                              id="cumplimiento_1_25" value="25">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_1_25">
                                                                                              25 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_1"
                                                                                              id="cumplimiento_1_50" value="50">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_1_50">
                                                                                              50 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_1"
                                                                                              id="cumplimiento_1_75" value="75">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_1_75">
                                                                                              75 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_1"
                                                                                              id="cumplimiento_1_100" value="100">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_1_100">
                                                                                              100 % </label>
                                                                                      </div>
                                                                                  </div>
                                                                              </td>
                                                                          </tr>
                                                                      </tbody>
                                                                      <tbody>
                                                                          <tr>
                                                                              <th>¿En qué % cree usted que se cumplió el
                                                                                  resultado
                                                                                  esperado?</th>
                                                                          </tr>
                                                                          <tr>
                                                                              <td>
                                                                                  <div class="form-group">
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_2"
                                                                                              id="cumplimiento_2_0" value="0"
                                                                                              checked>
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_2_0">
                                                                                              No se cumplió </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_2"
                                                                                              id="cumplimiento_2_25" value="25">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_2_25">
                                                                                              25 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_2"
                                                                                              id="cumplimiento_2_50" value="50">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_2_50">
                                                                                              50 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_2"
                                                                                              id="cumplimiento_2_75" value="75">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_2_75">
                                                                                              75 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_2"
                                                                                              id="cumplimiento_2_100" value="100">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_2_100">
                                                                                              100 % </label>
                                                                                      </div>
                                                                                  </div>
                                                                              </td>
                                                                          </tr>
                                                                      </tbody>

                                                                      <tbody>
                                                                          <tr>
                                                                              <th>¿En qué % cree usted que se cumplirán las
                                                                                  contribuciones?</th>
                                                                          </tr>

                                                                          <tr>
                                                                              <td>
                                                                                  <div class="form-group">
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_3"
                                                                                              id="cumplimiento_3_0" value="0"
                                                                                              checked>
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_3_0">
                                                                                              No se cumplirán </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_3"
                                                                                              id="cumplimiento_3_25" value="25">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_3_25">
                                                                                              25 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_3"
                                                                                              id="cumplimiento_3_50" value="50">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_3_50">
                                                                                              50 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_3"
                                                                                              id="cumplimiento_3_75" value="75">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_3_75">
                                                                                              75 % </label>
                                                                                      </div>
                                                                                      <div class="form-check form-check-inline">
                                                                                          <input class="form-check-input" type="radio"
                                                                                              name="cumplimiento_3"
                                                                                              id="cumplimiento_3_100" value="100">
                                                                                          <label class="form-check-label"
                                                                                              for="cumplimiento_3_100">
                                                                                              100 % </label>
                                                                                      </div>
                                                                                  </div>
                                                                              </td>
                                                                          </tr>
                                                                      </tbody>
                                                                  </table>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>

                                              <div class="row">
                                                  <div class="col-12 col-md-6 col-lg-6">
                                                      <div class="card card-info">
                                                          <div class="card-header" style="background-color: rgb(58,186,244);">
                                                              <h4 style="color:aliceblue">Calidad de ejecución</h4>
                                                          </div>
                                                          <div class="card-body" style="color:black;">
                                                              <label name="etiquetasEstudiante">A continuación te pedimos que
                                                                  evalúes de 0 a 3 la calidad
                                                                  en la ejecución de la actividad, según los compromisos
                                                                  asumidos por la institución, en que
                                                                  <ol>
                                                                      <li>0= no cumple.</li>
                                                                      <li>1= cumple mínimamente.</li>
                                                                      <li>2= cumple medianamente.</li>
                                                                      <li>3= cumple totalmente.</li>
                                                                  </ol>
                                                                  Si considera que algunos ítemes no estaban
                                                                  comprometidos,
                                                                  marque <b>No Aplica.</b>
                                                              </label>

                                                              <label name="etiquetasOtras">A continuación le pedimos que
                                                                  evalúe de 0 a 3 la calidad
                                                                  en la ejecución de la actividad, según los compromisos
                                                                  asumidos por la institución, en que
                                                                  <ol>
                                                                      <li>0= no cumple.</li>
                                                                      <li>1= cumple mínimamente.</li>
                                                                      <li>2= cumple medianamente.</li>
                                                                      <li>3= cumple totalmente.</li>
                                                                  </ol>
                                                                  Si considera que algunos ítemes no estaban
                                                                  comprometidos,
                                                                  marque <b>No Aplica.</b>
                                                              </label>

                                                              <div class="table-responsive">
                                                                  <table class="table table-striped">
                                                                      <tr>
                                                                          <th>Con qué nota evalúa usted la calidad en la
                                                                              ejecución de la actividad, en las siguientes
                                                                              dimensiones:</th>
                                                                          <th>Cumplimiento</th>
                                                                      </tr>
                                                                      <tbody>
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
                                                                                          <div class="form-check form-check-inline">
                                                                                              <input class="form-check-input"
                                                                                                  type="radio"
                                                                                                  name="calidad_{{ $i }}"
                                                                                                  id="calidad_{{ $i }}_0"
                                                                                                  value="0">
                                                                                              <label class="form-check-label"
                                                                                                  for="calidad_{{ $i }}_0">
                                                                                                  0 </label>
                                                                                          </div>
                                                                                          <div class="form-check form-check-inline">
                                                                                              <input class="form-check-input"
                                                                                                  type="radio"
                                                                                                  name="calidad_{{ $i }}"
                                                                                                  id="calidad_{{ $i }}_1"
                                                                                                  value="33">
                                                                                              <label class="form-check-label"
                                                                                                  for="calidad_{{ $i }}_1">
                                                                                                  1 </label>
                                                                                          </div>
                                                                                          <div class="form-check form-check-inline">
                                                                                              <input class="form-check-input"
                                                                                                  type="radio"
                                                                                                  name="calidad_{{ $i }}"
                                                                                                  id="calidad_{{ $i }}_2"
                                                                                                  value="66">
                                                                                              <label class="form-check-label"
                                                                                                  for="calidad_{{ $i }}_2">
                                                                                                  2 </label>
                                                                                          </div>
                                                                                          <div class="form-check form-check-inline">
                                                                                              <input class="form-check-input"
                                                                                                  type="radio"
                                                                                                  name="calidad_{{ $i }}"
                                                                                                  id="calidad_{{ $i }}_3"
                                                                                                  value="100">
                                                                                              <label class="form-check-label"
                                                                                                  for="calidad_{{ $i }}_3">
                                                                                                  3 </label>
                                                                                          </div>
                                                                                          <div class="form-check form-check-inline">
                                                                                              <input class="form-check-input"
                                                                                                  type="radio"
                                                                                                  name="calidad_{{ $i }}"
                                                                                                  id="calidad_{{ $i }}_NO"
                                                                                                  checked value="">
                                                                                              <label class="form-check-label"
                                                                                                  for="calidad_{{ $i }}_NO">
                                                                                                  No Aplica </label>
                                                                                          </div>
                                                                                      </div>
                                                                                  </td>
                                                                              </tr>
                                                                          @endfor
                                                                      </tbody>
                                                                  </table>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>

                                                  <div class="col-12 col-md-6 col-lg-6">
                                                      <div id="divAMostrar" name="divAMostrar">
                                                          <div class="card card-secondary">
                                                              <div class="card-header" style="background-color: rgb(55,60,97);">
                                                                  <h4 style="color:aliceblue">Competencia de estudiantes</h4>
                                                              </div>
                                                              <div class="card-body" style="color:black;">
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
                                                                      <table class="table table-striped">
                                                                          <tbody>
                                                                              <tr>
                                                                                  <th>Te sirvió la actividad para desarrollar
                                                                                      algunas
                                                                                      de las siguientes dimensiones de las
                                                                                      competencias comprometidas?</th>
                                                                                  <th>Cumplimiento</th>
                                                                              </tr>

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
                                                                                              <div class="form-check form-check-inline">
                                                                                                  <input class="form-check-input"
                                                                                                      type="radio"
                                                                                                      name="competencia_{{ $i }}"
                                                                                                      id="competencia_{{ $i }}_0"
                                                                                                      value="0">
                                                                                                  <label class="form-check-label"
                                                                                                      for="competencia_{{ $i }}_0">
                                                                                                      0 </label>
                                                                                              </div>
                                                                                              <div class="form-check form-check-inline">
                                                                                                  <input class="form-check-input"
                                                                                                      type="radio"
                                                                                                      name="competencia_{{ $i }}"
                                                                                                      id="competencia_{{ $i }}_1"
                                                                                                      value="33">
                                                                                                  <label class="form-check-label"
                                                                                                      for="competencia_{{ $i }}_1">
                                                                                                      1 </label>
                                                                                              </div>
                                                                                              <div class="form-check form-check-inline">
                                                                                                  <input class="form-check-input"
                                                                                                      type="radio"
                                                                                                      name="competencia_{{ $i }}"
                                                                                                      id="competencia_{{ $i }}_2"
                                                                                                      value="66">
                                                                                                  <label class="form-check-label"
                                                                                                      for="competencia_{{ $i }}_2">
                                                                                                      2 </label>
                                                                                              </div>
                                                                                              <div class="form-check form-check-inline">
                                                                                                  <input class="form-check-input"
                                                                                                      type="radio"
                                                                                                      name="competencia_{{ $i }}"
                                                                                                      id="competencia_{{ $i }}_3"
                                                                                                      value="100">
                                                                                                  <label class="form-check-label"
                                                                                                      for="competencia_{{ $i }}_3">
                                                                                                      3 </label>
                                                                                              </div>
                                                                                          </div>
                                                                                      </td>
                                                                                  </tr>
                                                                              @endfor
                                                                          </tbody>
                                                                      </table>
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

                              </div>

                        </div>


                        @endif

                        </div>


                        <div class="container-fluid mb-3" style="display: none;" id="botonesdeAbajo">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row justify-content-end mb-3">
                                                <div >

                                                    <button type="button" class="btn btn-light " onclick="window.location.href='{{ route('admin.iniciativa.listar') }}'">
                                                        Ir al listado
                                                    </button>
                                                </div>
                                                <div>

                                                    <button type="button" class="btn btn-primary ml-1" onclick="redireccionarEvaluadores();">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="15" height="15"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192h42.7c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0H21.3C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7h42.7C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3H405.3zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352H378.7C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7H154.7c-14.7 0-26.7-11.9-26.7-26.7z"/></svg>
                                                        Ver evaluadores
                                                    </button>
                                                </div>
                                                <div >

                                                    <button type="button" class="btn btn-primary ml-1" onclick="redireccionarResultados();">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="15" height="15"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M32 32c17.7 0 32 14.3 32 32V400c0 8.8 7.2 16 16 16H480c17.7 0 32 14.3 32 32s-14.3 32-32 32H80c-44.2 0-80-35.8-80-80V64C0 46.3 14.3 32 32 32zM160 224c17.7 0 32 14.3 32 32v64c0 17.7-14.3 32-32 32s-32-14.3-32-32V256c0-17.7 14.3-32 32-32zm128-64V320c0 17.7-14.3 32-32 32s-32-14.3-32-32V160c0-17.7 14.3-32 32-32s32 14.3 32 32zm64 32c17.7 0 32 14.3 32 32v96c0 17.7-14.3 32-32 32s-32-14.3-32-32V224c0-17.7 14.3-32 32-32zM480 96V320c0 17.7-14.3 32-32 32s-32-14.3-32-32V96c0-17.7 14.3-32 32-32s32 14.3 32 32z"/></svg>
                                                        Ver resultados
                                                    </button>
                                                </div>
                                                <div >

                                                    <form action="{{route('admin.eliminar.evaluacion.iniciativa')}}" method="post">
                                                        {{-- <form action="redirect('hola')"></form> --}}
                                                        @csrf
                                                        <input  type="number" hidden name="inic_codigo" id="inic_codigo" value="{{$iniciativa[0]->inic_codigo}}" required>
                                                        <input  type="number" hidden name="invitado_rol" id="invitado_rol" required>
                                                        <button type="submit" class="btn btn-light ml-1 mr-3">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="15" height="15"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#000000" d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>
                                                            Eliminar evaluación
                                                        </button>
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
        </script>
@endsection
