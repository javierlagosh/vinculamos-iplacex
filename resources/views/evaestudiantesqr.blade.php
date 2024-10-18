<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  <title>Encuesta de @if ($tipo == 0) Estudiante @elseif ($tipo == 1) Docente @elseif ($tipo == 12) Directivo @elseif ($tipo == 13) Beneficiario @elseif ($tipo == 14) Socio comunitario @endif</title>
</head>
<body style="background: #6777ef;">
  <div class="mt-5 w-100">
    <div class="container bg-white w-100 p-5 rounded mb-3">
        <div class="center-block">
            <img  style="display: block; margin-left: auto; margin-right: auto;" width="300px" alt="Logo" src="{{ asset('/img/logos/logo_vg_color.png') }}" class="header-logo">

            <hr style="margin-left: 20%; margin-right: 20%;
            border: 0;
            background-color: white;
            width: 60%;
            border-top: 1px solid rgba(0, 0, 0, 0.1);" />
            <h3 class="mb-4 text-center">Encuesta de Actividad</h3>
            @if (Session::has('exito'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exito') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
            @elseif (Session::has('error'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('error') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
            @else

            <p class="">Por favor, haznos saber qué opinas acerca de la iniciativa
                <strong>"{{$iniciativa[0]->inic_nombre}}"</strong>
              </p>
            <form action="{{ route('evaluacion.guardar.desde.qr') }}" method="POST">
                @csrf
                <div class=" mb-3">
                    <h5>Contacto</h5>
                    <input type="text" hidden name="inic_codigo" id="inic_codigo" value="{{$inic_codigo}}">
                    <label style="text-align: center;" id="email-label" for="email">Por favor ingresa tu correo electrónico :</label>
                    <input class="form form-control" type="email" name="correo"  id="correo" class="form-control" placeholder="ejemplo@gmail.com" required>
                    <input type="number" name="tipo" hidden value="{{$tipo}}">
                  </div>
                <!-- Sección Objetivo -->
                <section class="mb-5">
                    <h5>Objetivo</h5>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">¿Sabía usted que el propósito u objetivo de ésta actividad era?</label>
                        <label class="form-label">{{ $iniciativa[0]->inic_descripcion }}</label>
                        <div class="d-flex">
                            <div class="form-check me-3 mr-3 mr-3">
                                <input class="form-check-input" type="radio" name="conocimientoObjetivo" id="siConociaObjetivo" value="100">
                                <label class="form-check-label" for="siConociaObjetivo">Sí</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="conocimientoObjetivo" id="noConociaObjetivo" value="0">
                                <label class="form-check-label" for="noConociaObjetivo">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">¿En qué % cree usted que se cumplió el propósito u objetivo?</label>
                        <div class="d-flex flex-wrap">
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="cumplimientoObjetivo" id="noCumplioObjetivo" value="0">
                                <label class="form-check-label" for="noCumplioObjetivo">No se cumplió</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="cumplimientoObjetivo" id="cumplio25Objetivo" value="25">
                                <label class="form-check-label" for="cumplio25Objetivo">25%</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="cumplimientoObjetivo" id="cumplio50Objetivo" value="50">
                                <label class="form-check-label" for="cumplio50Objetivo">50%</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="cumplimientoObjetivo" id="cumplio75Objetivo" value="75">
                                <label class="form-check-label" for="cumplio75Objetivo">75%</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cumplimientoObjetivo" id="cumplio100Objetivo" value="100">
                                <label class="form-check-label" for="cumplio100Objetivo">100%</label>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Sección Resultado -->
                <section class="mb-5">
                    <h5>Resultados</h5>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">¿Sabía usted que @if (count($resultados) > 1) los resultados esperados @else el resultado esperado @endif de la actividad @if (count($resultados) > 1) eran @else era @endif?</label></label>
                        @if (count($resultados) > 0)
                                            <ul>
                                                @foreach ($resultados as $resultado)
                                                    <li>{{ $resultado->resu_cuantificacion_inicial }}
                                                        x {{ $resultado->resu_nombre }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                            @else
                                            <p>No hay resultados esperados</p>
                                            @endif
                        <div class="d-flex">
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="conocimientoResultado" id="siConociaResultado" value="100">
                                <label class="form-check-label" for="siConociaResultado">Sí</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="conocimientoResultado" id="noConociaResultado" value="0">
                                <label class="form-check-label" for="noConociaResultado">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">¿En qué % cree usted que se cumplió el resultado?</label>
                        <div class="d-flex flex-wrap">
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="cumplimientoResultado" id="noCumplioResultado" value="0">
                                <label class="form-check-label" for="noCumplioResultado">No se cumplió</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="cumplimientoResultado" id="cumplio25Resultado" value="25">
                                <label class="form-check-label" for="cumplio25Resultado">25%</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="cumplimientoResultado" id="cumplio50Resultado" value="50">
                                <label class="form-check-label" for="cumplio50Resultado">50%</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="cumplimientoResultado" id="cumplio75Resultado" value="75">
                                <label class="form-check-label" for="cumplio75Resultado">75%</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="cumplimientoResultado" id="cumplio100Resultado" value="100">
                                <label class="form-check-label" for="cumplio100Resultado">100%</label>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Sección Calidad -->
                <section class="mb-5">
                    <h5>Calidad</h5>
                    <p class="mb-3">A continuación te pedimos que evalúes de 0 a 3 la calidad en la ejecución de la actividad, según los compromisos asumidos por la institución, en que:</p>
                    <ul class="mb-3">
                        <li>0 = no cumple</li>
                        <li>1 = cumple mínimamente</li>
                        <li>2 = cumple medianamente</li>
                        <li>3 = cumple totalmente</li>
                    </ul>
                    {{-- <p class="mb-3">Si considera que algunos ítemes no estaban comprometidos, marque No Aplica.</p> --}}
                    <p class="mb-3">Con qué nota evalúa usted la calidad en la ejecución de la actividad, en las siguientes dimensiones:</p>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Plazo y horarios (Inicio y término)</label>
                        <div class="d-flex flex-wrap">
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="calidad_plazos" id="plazos0" value="0">
                                <label class="form-check-label" for="plazos0">0</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="calidad_plazos" id="plazos1" value="33">
                                <label class="form-check-label" for="plazos1">1</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="calidad_plazos" id="plazos2" value="67">
                                <label class="form-check-label" for="plazos2">2</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="calidad_plazos" id="plazos3" value="100">
                                <label class="form-check-label" for="plazos3">3</label>
                            </div>
                            {{-- <div class="form-check">
                                <input class="form-check-input" type="radio" name="calidad_plazos" id="plazosNA" value="999">
                                <label class="form-check-label" for="plazosNA">No aplica</label>
                            </div> --}}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Equipamiento y/o infraestructura (Lugar, mobiliario, equipos)</label>
                        <div class="d-flex flex-wrap">
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="calidad_equipamiento" id="equipamiento0" value="0">
                                <label class="form-check-label" for="equipamiento0">0</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="calidad_equipamiento" id="equipamiento1" value="33">
                                <label class="form-check-label" for="equipamiento1">1</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="calidad_equipamiento" id="equipamiento2" value="67">
                                <label class="form-check-label" for="equipamiento2">2</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="calidad_equipamiento" id="equipamiento3" value="100">
                                <label class="form-check-label" for="equipamiento3">3</label>
                            </div>
                            {{-- <div class="form-check">
                                <input class="form-check-input" type="radio" name="calidad_equipamiento" id="equipamientoNA" value="999">
                                <label class="form-check-label" for="equipamientoNA">No aplica</label>
                            </div> --}}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Logística (Coordinaciones, conexión web)</label>
                        <div class="d-flex flex-wrap">
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="calidad_logistica" id="logistica0" value="0">
                                <label class="form-check-label" for="logistica0">0</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="calidad_logistica" id="logistica1" value="33">
                                <label class="form-check-label" for="logistica1">1</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="calidad_logistica" id="logistica2" value="67">
                                <label class="form-check-label" for="logistica2">2</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="calidad_logistica" id="logistica3" value="100">
                                <label class="form-check-label" for="logistica3">3</label>
                            </div>
                            {{-- <div class="form-check">
                                <input class="form-check-input" type="radio" name="calidad_logistica" id="logisticaNA" value="999">
                                <label class="form-check-label" for="logisticaNA">No aplica</label>
                            </div> --}}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Presentaciones (Charla, presentación, relatoría)</label>
                        <div class="d-flex flex-wrap">
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="calidad_presentaciones" id="presentaciones0" value="0">
                                <label class="form-check-label" for="presentaciones0">0</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="calidad_presentaciones" id="presentaciones1" value="33">
                                <label class="form-check-label" for="presentaciones1">1</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="calidad_presentaciones" id="presentaciones2" value="67">
                                <label class="form-check-label" for="presentaciones2">2</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="calidad_presentaciones" id="presentaciones3" value="100">
                                <label class="form-check-label" for="presentaciones3">3</label>
                            </div>
                            {{-- <div class="form-check">
                                <input class="form-check-input" type="radio" name="calidad_presentaciones" id="presentacionesNA" value="999">
                                <label class="form-check-label" for="presentacionesNA">No aplica</label>
                            </div> --}}
                        </div>
                    </div>
                </section>
                {{-- Sección de estudiantes --}}
                @if ($tipo == 0)
                <section class="mb-5">
                    <h5>Competencia de estudiantes</h5>
                    <p class="mb-3">Ahora, siguiendo la misma escala de 0 a 3, te pedimos que evalúes si te sirvió la actividad para desarrollar algunas de las siguientes dimensiones de las competencias comprometidas.</p>
                    <ul class="mb-3">
                        <li>0 = no cumple</li>
                        <li>1 = cumple mínimamente</li>
                        <li>2 = cumple medianamente</li>
                        <li>3 = cumple totalmente</li>
                    </ul>

                    <p class="mb-3">Con qué nota evalúa usted la calidad en la ejecución de la actividad, en las siguientes dimensiones:</p>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Capacidad para ejecutar las actividades.</label>
                        <div class="d-flex flex-wrap">
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="estudiantes_ejecutar" id="estudiantes_ejecutar0" value="0">
                                <label class="form-check-label" for="estudiantes_ejecutar0">0</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="estudiantes_ejecutar" id="estudiantes_ejecutar1" value="33">
                                <label class="form-check-label" for="estudiantes_ejecutar1">1</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="estudiantes_ejecutar" id="estudiantes_ejecutar2" value="67">
                                <label class="form-check-label" for="estudiantes_ejecutar2">2</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="estudiantes_ejecutar" id="estudiantes_ejecutar3" value="100">
                                <label class="form-check-label" for="estudiantes_ejecutar3">3</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Actitud positiva para ejecutar actividades.</label>
                        <div class="d-flex flex-wrap">
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="estudiantes_positividad" id="estudiantes_positividad0" value="0">
                                <label class="form-check-label" for="estudiantes_positividad0">0</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="estudiantes_positividad" id="estudiantes_positividad1" value="33">
                                <label class="form-check-label" for="estudiantes_positividad1">1</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="estudiantes_positividad" id="estudiantes_positividad2" value="67">
                                <label class="form-check-label" for="estudiantes_positividad2">2</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="estudiantes_positividad" id="estudiantes_positividad3" value="100">
                                <label class="form-check-label" for="estudiantes_positividad3">3</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Habilidad para resolver problemas.</label>
                        <div class="d-flex flex-wrap">
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="estudiantes_resolucion" id="estudiantes_resolucion0" value="0">
                                <label class="form-check-label" for="estudiantes_resolucion0">0</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="estudiantes_resolucion" id="estudiantes_resolucion1" value="33">
                                <label class="form-check-label" for="estudiantes_resolucion1">1</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="estudiantes_resolucion" id="estudiantes_resolucion2" value="67">
                                <label class="form-check-label" for="estudiantes_resolucion2">2</label>
                            </div>
                            <div class="form-check me-3 mr-3">
                                <input class="form-check-input" type="radio" name="estudiantes_resolucion" id="estudiantes_resolucion3" value="100">
                                <label class="form-check-label" for="estudiantes_resolucion3">3</label>
                            </div>
                        </div>
                    </div>


                </section>
                @endif

                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        @endif


        </div>



    </div>





  </div>
</body>
</html>
