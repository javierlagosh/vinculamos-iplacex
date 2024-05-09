<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  
  <title>Encuesta de @if ($tipo == 0) Estudiante @elseif ($tipo == 1) Docente/Directivo @elseif ($tipo == 2) Agente externo @endif</title>
</head>
<body style="background: #6777ef;">
  <div class="mt-5 w-100">
    
    <div class="container bg-white w-100 p-5 rounded-bottom">
        <div class="center-block">
            <img  style="display: block; margin-left: auto; margin-right: auto;" width="300px" alt="Logo" src="{{ asset('/img/logos/logo_vg_color.png') }}" class="header-logo">
            
            <hr style="margin-left: 20%; margin-right: 20%;
            border: 0;
            background-color: white;
            width: 60%;
            border-top: 1px solid rgba(0, 0, 0, 0.1);" />
      <h1 class="text-center">Encuesta para el @if ($tipo == 0) Estudiante @elseif ($tipo == 1) Docente/Directivo @elseif ($tipo == 2) Agente externo @endif</h1>
      <p class="text-center">Por favor, haznos saber qué opinas acerca la iniciativa llamada
        <strong>"{{$iniciativa[0]->inic_nombre}}"</strong>
      </p>
    </div>
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
        <form action="{{ route('evaluacion.guardar.estudiante') }}" method="POST">
            @csrf
        <div class="row w-100">
          
          <br>
          <div> 
            <div >
              <div class="card-body">
                <div class="col-md-6 "> 
                    <input type="text" hidden name="inic_codigo" id="inic_codigo" value="{{$inic_codigo}}">
                    <label style="text-align: center;" id="email-label" for="email">Correo Electrónico:</label>
                    <input class="form form-control" type="email" name="correo"  id="correo" class="form-control" placeholder="hola@ejemplo.com" required>
                    <input type="number" name="tipo" hidden value="{{$tipo}}">
                  </div>
                  <br>
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
                              <div class="card-body">
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
                                  <div class="card-body">
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
                  <div class="row">
                      <div class="col-12 col-md-12 col-lg-12">
                          <div class="card">
                              <button id="submit" type="submit" class="fas fa-check btn btn-primary">Enviar respuestas</button>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          </div>
        </div>
        <div class="row">
          
        </div>    
      </form>
    </div>
  </div>
</body>
</html>
