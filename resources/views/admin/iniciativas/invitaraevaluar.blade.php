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

                            <h4>Evaluación de la iniciativa: {{ $iniciativa[0]->inic_nombre }} - Invitar a {{$invitadoNombre}} </h4>
                            <input type="hidden" name="iniciativa_codigo" id="iniciativa_codigo"
                                value="{{ $iniciativa[0]->inic_codigo }}">

                                <div class="card-header-action">
                                    <a href="{{ route('admin.iniciativas.detalles', $iniciativa[0]->inic_codigo) }}"
                                       class="btn btn-primary mr-1 waves-effect icon-left" type="button">
                                        <i class="fas fa-angle-left"></i> Volver a la Iniciativa
                                    </a>
                                </div>
                        </div>


                        {{-- <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="tipo">
                                                <strong>Tipo de evaluador:</strong>
                                            </label>
                                            <div class="w-25">
                                                <select class="form-control select2" onchange="cambioInvitado();" name="tipo">
                                                    <option value="0" @if ($invitadoNombre == 'Estudiantes') selected @endif >Evaluador interno - Estudiante</option>
                                                    <option value="1" @if ($invitadoNombre == 'Docentes/Directivos') selected @endif >Evaluador interno - Docente/Directivo</option>
                                                    <option value="2" @if ($invitadoNombre == 'Externos') selected @endif >Evaluador externo</option>

                                                </select>
                                                <script>
                                                    function cambioInvitado() {
                                                        //obtener el valor del select
                                                        var tipo = document.getElementsByName("tipo")[0].value;
                                                        if (tipo == 0) {
                                                            var url = "{{ route('admin.iniciativa.evaluar.invitar', [$iniciativa[0]->inic_codigo, '0']) }}";
                                                        } else if (tipo == 1) {
                                                            var url = "{{ route('admin.iniciativa.evaluar.invitar', [$iniciativa[0]->inic_codigo, '1']) }}";
                                                        } else if (tipo == 2) {
                                                            var url = "{{ route('admin.iniciativa.evaluar.invitar', [$iniciativa[0]->inic_codigo, '2']) }}";
                                                        }

                                                        //cambiar url de acuerdo al tipo de evaluador
                                                        window.location.href = url;
                                                    }
                                                </script>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div> --}}

                        <div class="container-fluid">
                            <h5>Paso 3: Seleccionar evaluadores</h5>
                            <p  ><span style="color:red;">! </span>Instrucción: Tienes 3 opciones para cargar nombres y correos electrónicos de los evaluadores. Agrega nombre y correo electrónico y luego guarda con "Cargar individualmente" o "Cargar múltiple" según corresponda. En este paso sólo estás creando los contactos, en el próximo podrás enviar la encuesta.</p>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <h6>Cargar individualmente</h6>
                                                        <form method="post" action="{{ route('admin.iniciativa.evaluar.enviar.cargaIndividual') }}" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="number" hidden name="inic_codigo" value="{{$inic_codigo}}">
                                                            @if ($invitadoNombre == 'Estudiantes')
                                                                <input type="number" hidden name="tipo" value="0">
                                                            @elseif ($invitadoNombre == 'Docentes')
                                                                <input type="number" hidden name="tipo" value="1">
                                                            @elseif ($invitadoNombre == 'Directivos')
                                                                <input type="number" hidden name="tipo" value="12">
                                                            @elseif ($invitadoNombre == 'Beneficiario')
                                                                <input type="number" hidden name="tipo" value="13">
                                                            @elseif ($invitadoNombre == 'Socio comunitario')
                                                                <input type="number" hidden name="tipo" value="14">
                                                            @endif
                                                            <div class="form-group">
                                                                <label for="nombre">
                                                                    Nombre completo
                                                                </label>
                                                                <input type="text" name="nombre" class="form-control" id="nombre" required />
                                                            </div>
                                                            <div class="form-group">

                                                                <label for="email">
                                                                    &nbsp;Correo electrónico
                                                                </label>
                                                                <input type="email" name="email" class="form-control" id="email" required />
                                                            </div>
                                                            &nbsp;
                                                            <button type="submit" class="btn btn-primary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="15" height="15"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M48 96V416c0 8.8 7.2 16 16 16H384c8.8 0 16-7.2 16-16V170.5c0-4.2-1.7-8.3-4.7-11.3l33.9-33.9c12 12 18.7 28.3 18.7 45.3V416c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V96C0 60.7 28.7 32 64 32H309.5c17 0 33.3 6.7 45.3 18.7l74.5 74.5-33.9 33.9L320.8 84.7c-.3-.3-.5-.5-.8-.8V184c0 13.3-10.7 24-24 24H104c-13.3 0-24-10.7-24-24V80H64c-8.8 0-16 7.2-16 16zm80-16v80H272V80H128zm32 240a64 64 0 1 1 128 0 64 64 0 1 1 -128 0z"/></svg>
                                                                Cargar individualmente
                                                            </button>
                                                        </form>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="d-flex justify-content-between ">
                                                    <h6 class="mb-0">Cargar por Texto</h6>

                                                  </div>

                                                            <form action="{{ route('procesarTexto') }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="number" hidden name="inic_codigo" value="{{$inic_codigo}}">
                                                                @if ($invitadoNombre == 'Estudiantes')
                                                                    <input type="number" hidden name="tipo" value="0">
                                                                @elseif ($invitadoNombre == 'Docentes')
                                                                    <input type="number" hidden name="tipo" value="1">
                                                                @elseif ($invitadoNombre == 'Directivos')
                                                                    <input type="number" hidden name="tipo" value="12">
                                                                @elseif ($invitadoNombre == 'Beneficiario')
                                                                    <input type="number" hidden name="tipo" value="13">
                                                                @elseif ($invitadoNombre == 'Socio comunitario')
                                                                    <input type="number" hidden name="tipo" value="14">
                                                                @endif
                                                                <div class="form-group">

                                                                    <label for="exampleInputFile">
                                                                        Copie y pegue las dos columnas con los estudiantes y correos (<a href="https://docs.google.com/spreadsheets/d/1FUb7gqAGOO8U1HhWMODgNz38d_WTYZMJ/export">Descargar plantilla</a>)
                                                                    </label>
                                                                    <br>
                                                                    <textarea name="cargaTexto" id="cargaTexto" cols="30" rows="5" required placeholder="Nombre 1	Correo 1
                                                                    Nombre 2	Correo 2
                                                                    Nombre 3	Correo 3"></textarea>
                                                                </div>
                                                                <button type="submit" class="btn btn-primary">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="15" height="15"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M48 96V416c0 8.8 7.2 16 16 16H384c8.8 0 16-7.2 16-16V170.5c0-4.2-1.7-8.3-4.7-11.3l33.9-33.9c12 12 18.7 28.3 18.7 45.3V416c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V96C0 60.7 28.7 32 64 32H309.5c17 0 33.3 6.7 45.3 18.7l74.5 74.5-33.9 33.9L320.8 84.7c-.3-.3-.5-.5-.8-.8V184c0 13.3-10.7 24-24 24H104c-13.3 0-24-10.7-24-24V80H64c-8.8 0-16 7.2-16 16zm80-16v80H272V80H128zm32 240a64 64 0 1 1 128 0 64 64 0 1 1 -128 0z"/></svg>
                                                                    Cargar multiple
                                                                </button>
                                                            </form>
                                            </div>
                                            <div class="col-md-4">
                                                <h6>Link de invitación</h6>
                                                                <div class="form-group">

                                                                    <label for="exampleInputFile">
                                                                        Genera un link de invitación.
                                                                    </label>
                                                                </div>
                                                                {{-- <a type="button" class="btn btn-primary text-white">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="15" height="15"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M0 80C0 53.5 21.5 32 48 32h96c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V80zM64 96v64h64V96H64zM0 336c0-26.5 21.5-48 48-48h96c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V336zm64 16v64h64V352H64zM304 32h96c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H304c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48zm80 64H320v64h64V96zM256 304c0-8.8 7.2-16 16-16h64c8.8 0 16 7.2 16 16s7.2 16 16 16h32c8.8 0 16-7.2 16-16s7.2-16 16-16s16 7.2 16 16v96c0 8.8-7.2 16-16 16H368c-8.8 0-16-7.2-16-16s-7.2-16-16-16s-16 7.2-16 16v64c0 8.8-7.2 16-16 16H272c-8.8 0-16-7.2-16-16V304zM368 480a16 16 0 1 1 0-32 16 16 0 1 1 0 32zm64 0a16 16 0 1 1 0-32 16 16 0 1 1 0 32z"/></svg>
                                                                    Generar código QR
                                                                </a>

                                                                <img src="{{ route('generateQR') }}" alt="QR Code"> --}}


                                                                <button type="button" class="btn btn-success" onclick="copyToClipboard('{{ env('URL_EVALUACIONES') }}{{$evaluaciontotal->evatotal_encriptado}}/unirse')">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="15" height="15"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M208 0H332.1c12.7 0 24.9 5.1 33.9 14.1l67.9 67.9c9 9 14.1 21.2 14.1 33.9V336c0 26.5-21.5 48-48 48H208c-26.5 0-48-21.5-48-48V48c0-26.5 21.5-48 48-48zM48 128h80v64H64V448H256V416h64v48c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V176c0-26.5 21.5-48 48-48z"/></svg>
                                                                    Copiar enlace para unirse
                                                                </button>
                                                                <script>
                                                                    function copyToClipboard(text) {
                                                                        var inputc = document.body.appendChild(document.createElement("input"));
                                                                        inputc.value = text;
                                                                        inputc.focus();
                                                                        inputc.select();
                                                                        document.execCommand('copy');
                                                                        inputc.parentNode.removeChild(inputc);
                                                                    }
                                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-12">

                                                <hr>

                                                <table class="table" id="table-1">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                #
                                                            </th>
                                                            <th>
                                                                Nombre
                                                            </th>
                                                            <th>
                                                                Correo electrónico
                                                            </th>
                                                            <th>
                                                                Estado
                                                            </th>
                                                            <th>
                                                                Acciones
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $i = 1; ?>
                                                        @foreach ($invitados as $invitado)
                                                        <tr>
                                                            <td> <?php echo $i; $i++; ?> </td>
                                                            <td> {{$invitado->evainv_nombre}} </td>
                                                            <td> {{$invitado->evainv_correo}} </td>
                                                            <td>
                                                                @if ($invitado->evainv_estado == 0)
                                                                    <span class="badge badge-warning">Pendiente</span>
                                                                @elseif ($invitado->evainv_estado == 1)
                                                                    <span class="badge badge-primary">Invitado</span>
                                                                @elseif ($invitado->evainv_estado == 2)
                                                                    <span class="badge badge-success">Respondido</span>
                                                                @endif

                                                            </td>
                                                            <td>
                                                                <form action="{{route('admin.eliminar.invitacion',$invitado->evainv_codigo)}}" method="post">
                                                                    @csrf

                                                                    <button type="submit" class="btn btn-danger">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="15" height="15" data-toggle="tooltip" data-placement="top" title="Eliminar"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>
                                                                    </button>
                                                                </form>

                                                            </td>

                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <br>
                                                {{-- <div class="row">
                                                    <div class="col-md-6">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row justify-content-end mb-3">
                                                            <div>
                                                                <button type="button" class="btn btn-light" onclick="window.location.href='{{ route('admin.iniciativa.listar') }}'">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="12" height="12"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#000000" d="M267.5 440.6c9.5 7.9 22.8 9.7 34.1 4.4s18.4-16.6 18.4-29V96c0-12.4-7.2-23.7-18.4-29s-24.5-3.6-34.1 4.4l-192 160L64 241V96c0-17.7-14.3-32-32-32S0 78.3 0 96V416c0 17.7 14.3 32 32 32s32-14.3 32-32V271l11.5 9.6 192 160z"/></svg>
                                                                    Ir al listado de iniciativas
                                                                </button>
                                                            </div>

                                                            <div>

                                                                <button type="button" class="btn btn-light ml-1" onclick="window.location.href='{{route('admin.evaluar.iniciativa', $inic_codigo)}}'">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="13" height="13"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#000000" d="M192 0c-41.8 0-77.4 26.7-90.5 64H64C28.7 64 0 92.7 0 128V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V128c0-35.3-28.7-64-64-64H282.5C269.4 26.7 233.8 0 192 0zm0 64a32 32 0 1 1 0 64 32 32 0 1 1 0-64zM112 192H272c8.8 0 16 7.2 16 16s-7.2 16-16 16H112c-8.8 0-16-7.2-16-16s7.2-16 16-16z"/></svg>
                                                                    Volver a evaluaciones
                                                                </button>
                                                            </div>
                                                            <div>

                                                                @if ($invitadoNombre == 'Estudiantes')
                                                                <button type="button" class="btn btn-primary ml-1 mr-3" onclick="window.location.href = '/admin/iniciativas/{{ $iniciativa[0]->inic_codigo }}/evaluar/invitar/0/correo'">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="13" height="13"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M498.1 5.6c10.1 7 15.4 19.1 13.5 31.2l-64 416c-1.5 9.7-7.4 18.2-16 23s-18.9 5.4-28 1.6L284 427.7l-68.5 74.1c-8.9 9.7-22.9 12.9-35.2 8.1S160 493.2 160 480V396.4c0-4 1.5-7.8 4.2-10.7L331.8 202.8c5.8-6.3 5.6-16-.4-22s-15.7-6.4-22-.7L106 360.8 17.7 316.6C7.1 311.3 .3 300.7 0 288.9s5.9-22.8 16.1-28.7l448-256c10.7-6.1 23.9-5.5 34 1.4z"/></svg>
                                                                    Enviar a pendientes
                                                                </button>
                                                                @elseif ($invitadoNombre == 'Docentes/Directivos')
                                                                <button type="button" class="btn btn-primary ml-1 mr-3" onclick="window.location.href = '/admin/iniciativas/{{ $iniciativa[0]->inic_codigo }}/evaluar/invitar/1/correo'">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="13" height="13"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M498.1 5.6c10.1 7 15.4 19.1 13.5 31.2l-64 416c-1.5 9.7-7.4 18.2-16 23s-18.9 5.4-28 1.6L284 427.7l-68.5 74.1c-8.9 9.7-22.9 12.9-35.2 8.1S160 493.2 160 480V396.4c0-4 1.5-7.8 4.2-10.7L331.8 202.8c5.8-6.3 5.6-16-.4-22s-15.7-6.4-22-.7L106 360.8 17.7 316.6C7.1 311.3 .3 300.7 0 288.9s5.9-22.8 16.1-28.7l448-256c10.7-6.1 23.9-5.5 34 1.4z"/></svg>
                                                                    Enviar a pendientes
                                                                </button>
                                                                @elseif ($invitadoNombre == 'Externos')
                                                                <button type="button" class="btn btn-primary ml-1 mr-3" onclick="window.location.href = '/admin/iniciativas/{{ $iniciativa[0]->inic_codigo }}/evaluar/invitar/2/correo'">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="13" height="13"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M498.1 5.6c10.1 7 15.4 19.1 13.5 31.2l-64 416c-1.5 9.7-7.4 18.2-16 23s-18.9 5.4-28 1.6L284 427.7l-68.5 74.1c-8.9 9.7-22.9 12.9-35.2 8.1S160 493.2 160 480V396.4c0-4 1.5-7.8 4.2-10.7L331.8 202.8c5.8-6.3 5.6-16-.4-22s-15.7-6.4-22-.7L106 360.8 17.7 316.6C7.1 311.3 .3 300.7 0 288.9s5.9-22.8 16.1-28.7l448-256c10.7-6.1 23.9-5.5 34 1.4z"/></svg>
                                                                    Enviar a pendientes
                                                                </button>
                                                                 @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> --}}


                                                <div class="row mb-3">
                                                    <div class="col-xl-12 col-md-12 col-log-12">
                                                        <div class="text-right">
                                                            <strong>

                                                            </strong>
                                                            @if ($invitadoNombre == 'Estudiantes')
                                                            <a href="{{route('admin.evaluar.paso2', [$iniciativa[0]->inic_codigo,0])}}"
                                                                type="button" class="btn mr-1 waves-effect"
                                                                style="background-color:#042344; color:white"><i
                                                                    class="fas fa-chevron-left"></i>
                                                                Paso anterior</a>

                                                                <a href="{{route('admin.iniciativa.evaluar.invitar.correo', [$iniciativa[0]->inic_codigo, 0])}}"
                                                                    type="button" class="btn btn-primary mr-1 waves-effect">
                                                                    Paso siguiente <i class="fas fa-chevron-right"></i></a>

                                                            @elseif ($invitadoNombre == 'Docentes')
                                                            <a href="{{route('admin.evaluar.paso2', [$iniciativa[0]->inic_codigo,1])}}"
                                                                type="button" class="btn mr-1 waves-effect"
                                                                style="background-color:#042344; color:white"><i
                                                                    class="fas fa-chevron-left"></i>
                                                                Paso anterior</a>

                                                                <a href="{{route('admin.iniciativa.evaluar.invitar.correo', [$iniciativa[0]->inic_codigo, 1])}}"
                                                                    type="button" class="btn btn-primary mr-1 waves-effect">
                                                                    Paso siguiente <i class="fas fa-chevron-right"></i></a>

                                                            @elseif ($invitadoNombre == 'Directivos')
                                                                    <a href="{{route('admin.evaluar.paso2', [$iniciativa[0]->inic_codigo,12])}}"
                                                                        type="button" class="btn mr-1 waves-effect"
                                                                        style="background-color:#042344; color:white"><i
                                                                            class="fas fa-chevron-left"></i>
                                                                        Paso anterior</a>

                                                                <a href="{{route('admin.iniciativa.evaluar.invitar.correo', [$iniciativa[0]->inic_codigo, 12])}}"
                                                                    type="button" class="btn btn-primary mr-1 waves-effect">
                                                                    Paso siguiente <i class="fas fa-chevron-right"></i></a>

                                                            @elseif ($invitadoNombre == 'Beneficiario')
                                                                    <a href="{{route('admin.evaluar.paso2', [$iniciativa[0]->inic_codigo,13])}}"
                                                                        type="button" class="btn mr-1 waves-effect"
                                                                        style="background-color:#042344; color:white"><i
                                                                            class="fas fa-chevron-left"></i>
                                                                        Paso anterior</a>

                                                                <a href="{{route('admin.iniciativa.evaluar.invitar.correo', [$iniciativa[0]->inic_codigo, 13])}}"
                                                                    type="button" class="btn btn-primary mr-1 waves-effect">
                                                                    Paso siguiente <i class="fas fa-chevron-right"></i></a>

                                                            @elseif ($invitadoNombre == 'Socio comunitario')
                                                                    <a href="{{route('admin.evaluar.paso2', [$iniciativa[0]->inic_codigo,14])}}"
                                                                        type="button" class="btn mr-1 waves-effect"
                                                                        style="background-color:#042344; color:white"><i
                                                                            class="fas fa-chevron-left"></i>
                                                                        Paso anterior</a>

                                                                <a href="{{route('admin.iniciativa.evaluar.invitar.correo', [$iniciativa[0]->inic_codigo, 14])}}"
                                                                    type="button" class="btn btn-primary mr-1 waves-effect">
                                                                    Paso siguiente <i class="fas fa-chevron-right"></i></a>
                                                            @endif
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
        </div>

@endsection
