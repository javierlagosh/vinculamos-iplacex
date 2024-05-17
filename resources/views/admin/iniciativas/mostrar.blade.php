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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-3"></div>
                        <div class="col-xl-6">
                            @if (Session::has('errorIniciativa'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorIniciativa') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-xl-3"></div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Información de la iniciativa</h4>
                            <div class="card-header-action">
                                {{-- <div class="dropdown d-inline">
                                    <button class="btn btn-primary dropdown-toggle" id="dropdownMenuButton2"
                                        data-toggle="dropdown">Iniciativa</button>
                                    <div class="dropdown-menu dropright">

                                        <a href="{{ route('admin.cobertura.index', $iniciativa->inic_codigo) }}"
                                            class="dropdown-item has-icon"><i class="fas fa-users"></i>Ingresar
                                            cobertura</a>
                                        <a href="" class="dropdown-item has-icon"><i class="fas fa-flag"></i>Ingresar
                                            resultados</a>
                                        <a href="" class="dropdown-item has-icon"><i
                                                class="fas fa-file-signature"></i>Ingresar evaluación</a>
                                    </div>
                                </div> --}}


                                <div class="dropdown d-inline">

                                    <button class="btn btn-info dropdown-toggle" id="dropdownMenuButton2"
                                        data-toggle="dropdown"> Iniciativas</button>
                                    <div class="dropdown-menu dropright">


                                        <a href="javascript:void(0)" class="dropdown-item has-icon"
                                            data-toggle="tooltip" data-placement="top" title="Calcular INVI"
                                            onclick="calcularIndice({{ $iniciativa->inic_codigo }})"><i
                                                class="fas fa-tachometer-alt"></i> INVI</a>

                                        <a href="{{ route('admin.editar.paso1', $iniciativa->inic_codigo) }}"
                                            class="dropdown-item has-icon" data-toggle="tooltip"
                                            data-placement="top" title="Editar iniciativa"><i class="fas fa-edit"></i>
                                            Editar
                                            Iniciativa</a>

                                        <a href="{{ route('admin.evidencias.listar', $iniciativa->inic_codigo) }}"
                                            class="dropdown-item has-icon" data-toggle="tooltip"
                                            data-placement="top" title="Adjuntar evidencia"><i class="fas fa-paperclip"></i>
                                            Adjuntar evidencia</a>

                                        <a href="javascript:void(0)" class="dropdown-item has-icon" data-toggle="tooltip"
                                            onclick="eliminarIniciativa({{ $iniciativa->inic_codigo }})"
                                            data-placement="top" title="Eliminar iniciativa"><i class="fas fa-trash"></i>
                                            Eliminar</a>
                                    </div>
                                </div>


                                        @if ($ods_array->isEmpty())
                                        @else
                                        <div class="dropdown d-inline">

                                            <button class="btn btn-warning dropdown-toggle" id="dropdownMenuButton2"
                                                data-toggle="dropdown"> Agenda 2030</button>
                                            <div class="dropdown-menu dropright">
                                            <a href="{{ route('admin.iniciativas.agendaods', $iniciativa->inic_codigo) }}"
                                                class="dropdown-item has-icon" data-toggle="tooltip"
                                                data-placement="top" title="Agenda 2030"><i
                                                    class="fas  fa-star-half"></i> Contribucion externa</a>

                                            <a href="{{ route('admin.iniciativas.pdf', $iniciativa->inic_codigo) }}"
                                                class="dropdown-item has-icon" data-toggle="tooltip"
                                                data-placement="top" title="Agenda 2030"><i
                                                    class="fas  fa-file-pdf"></i> Generar
                                                pdf con ODS</a>
                                            </div>
                                        </div>
                                        @endif

                                <div class="dropdown d-inline">

                                    <button class="btn btn-success dropdown-toggle" id="dropdownMenuButton2"
                                        data-toggle="dropdown"> <i class="fas fa-plus-circle"></i> Ingresar</button>
                                    <div class="dropdown-menu dropright">
                                        <a href="{{ route('admin.cobertura.index', $iniciativa->inic_codigo) }}"
                                            class="dropdown-item has-icon" data-toggle="tooltip" data-placement="top"
                                            title="Ingresar cobertura"><i class="fas fa-users"></i> Ingresar cobertura</a>

                                        <a href="{{ route('admin.resultados.listado', $iniciativa->inic_codigo) }}"
                                            class="dropdown-item has-icon" data-toggle="tooltip" data-placement="top"
                                            title="Ingresar resultado"><i class="fas fa-flag"></i> Ingresar resultado/s</a>

                                        <a href="{{ route($role . '.evaluar.iniciativa', $iniciativa->inic_codigo) }}"
                                            class="dropdown-item has-icon" data-toggle="tooltip" data-placement="top"
                                            title="Evaluar iniciativa"><i class="fas fa-file-signature"></i> Evaluar iniciativa</a>
                                    </div>
                                </div>

                                <div class="dropdown d-inline">

                                    <button class="btn btn-primary dropdown-toggle" id="dropdownMenuButton2"
                                        data-toggle="dropdown">Estados</button>
                                    <div class="dropdown-menu dropright">
                                        <form method="POST"
                                            action="{{ route('admin.iniciativas.updateState', ['inic_codigo' => $iniciativa->inic_codigo]) }}">
                                            @csrf
                                            <input type="hidden" name="state" value="3">
                                            <a href="javascript:void(0);" onclick="this.closest('form').submit();"
                                                class="dropdown-item has-icon" style="display: flex; align-items: center;">
                                                <i class="fas fa-check" style="margin-right: 8px;"></i> Aprobar
                                                iniciativa
                                            </a>
                                        </form>

                                        <form method="POST"
                                            action="{{ route('admin.iniciativas.updateState', ['inic_codigo' => $iniciativa->inic_codigo]) }}">
                                            @csrf
                                            <input type="hidden" name="state" value="2">
                                            <a href="javascript:void(0);" onclick="this.closest('form').submit();"
                                                class="dropdown-item has-icon" style="display: flex; align-items: center;">
                                                <i class="fas fa-cog" style="margin-right: 8px;"></i> En ejecución
                                            </a>
                                        </form>

                                        <form action="#">

                                        </form>

                                        <form method="POST"
                                            action="{{ route('admin.iniciativas.updateState', ['inic_codigo' => $iniciativa->inic_codigo]) }}">
                                            @csrf
                                            <input type="hidden" name="state" value="4">
                                            <a href="javascript:void(0);" onclick="this.closest('form').submit();"
                                                class="dropdown-item has-icon" style="display: flex; align-items: center;">
                                                <i class="fas fa-info-circle" style="margin-right: 8px;"></i> Falta
                                                información
                                            </a>
                                        </form>

                                        <form method="POST"
                                            action="{{ route('admin.iniciativas.updateState', ['inic_codigo' => $iniciativa->inic_codigo]) }}">
                                            @csrf
                                            <input type="hidden" name="state" value="5">
                                            <a href="javascript:void(0);" onclick="this.closest('form').submit();"
                                                class="dropdown-item has-icon"
                                                style="display: flex; align-items: center;">
                                                <i class="fas fa-lock" style="margin-right: 8px;"></i> Cerrar iniciativa
                                            </a>
                                        </form>

                                        <form method="POST"
                                            action="{{ route('admin.iniciativas.updateState', ['inic_codigo' => $iniciativa->inic_codigo]) }}">
                                            @csrf
                                            <input type="hidden" name="state" value="6">
                                            <a href="javascript:void(0);" onclick="this.closest('form').submit();"
                                                class="dropdown-item has-icon"
                                                style="display: flex; align-items: center;">
                                                <i class="fas fa-times" style="margin-right: 8px;"></i> Finalizar
                                                Iniciativa
                                            </a>
                                        </form>



                                    </div>
                                </div>

                                {{-- <a href="{{ route('admin.iniciativa.listar') }}" data-toggle="tooltip" data-placemet="top"
                                    type="button" class="btn btn-primary" title="Ir a iniciativas">
                                    <i class="fas fa-backward"></i>
                                </a> --}}

                                {{-- <a href="" type="button" data-toggle="tooltip" class="btn btn-primary"
                                    data-placemet="top" title="Adjuntar evidencia">
                                    <i class="fas fa-paperclip"></i>
                                </a> --}}

                                {{-- <a href="{{ route('admin.editar.paso1', $iniciativa->inic_codigo) }}" type="button"
                                    data-toggle="tooltip" class="btn btn-warning" data-placemet="top"
                                    title="Editar iniciativa">
                                    <i class="fas fa-edit"></i>
                                </a> --}}
                                {{-- <a href="{{ route('admin.iniciativas.detalles', $iniciativa->inic_codigo) }}"
                                    class="btn btn-icon btn-warning icon-left" data-toggle="tooltip"
                                    data-placement="top" title="Ver detalles de la iniciativa"><i
                                        class="fas fa-eye"></i>Ver detalle</a> --}}





                                {{-- Comprueba si no tiene ods asociado --}}







                                {{-- <a href="javascript:void(0)" class="dropdown-item has-icon"
                                    onclick="eliminarIniciativa({{ $iniciativa->inic_codigo }})" data-toggle="tooltip"
                                    data-placement="top" title="Eliminar">Eliminar Iniciativa<i
                                        class="fas fa-trash"></i></a> --}}
                                <a href="{{ route('admin.iniciativa.listar') }}"
                                    class="btn btn-primary mr-1 waves-effect icon-left" type="button">
                                    <i class="fas fa-angle-left"></i> Volver a listado
                                </a>

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-strip table-md">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <strong>Nombre de la iniciativa</strong>
                                                </td>
                                                <td>
                                                    {{ $iniciativa->inic_nombre }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <strong>Escuela ejecutora</strong>
                                                </td>
                                                <td>
                                                    {{ $escuelaEjecutora ?? 'No registrado' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <strong>Desde</strong>
                                                </td>
                                                <td>
                                                    {{ $iniciativa->inic_desde ?? 'No registrado' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>Hasta</strong>
                                                </td>
                                                <td>
                                                    {{ $iniciativa->inic_hasta ?? 'No registrado' }}
                                                </td>
                                            </tr>
                                            <tr hidden>
                                                <td>
                                                    <strong>Bimestre</strong>
                                                </td>
                                                <td>
                                                    {{ $iniciativa->inic_bimestre ?? 'No registrado' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <strong>Brecha</strong>
                                                </td>
                                                <td>
                                                    {{ $iniciativa->inic_brecha ?? 'No registrado' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <strong>Diagnóstico</strong>
                                                </td>
                                                <td>
                                                    {{ $iniciativa->inic_diagnostico ?? 'No registrado' }}
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>
                                                    <strong>Descripción</strong>
                                                </td>
                                                <td>
                                                    {{ $iniciativa->inic_descripcion }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><strong>Mecanismo</strong></td>
                                                <td>{{ $iniciativa->meca_nombre }}</td>
                                            </tr>

                                            <tr>
                                                <td><strong>Instrumento</strong></td>
                                                <td>
                                                    {{ $iniciativa->tiac_nombre }}
                                                </td>

                                            </tr>

                                            <tr>
                                                <td><strong>Convenio</strong></td>
                                                <td>{{ $iniciativa->conv_nombre }}</td>
                                            </tr>
                                            <tr>

                                                {{-- {{json_encode($ods_array)}} --}}
                                                    <td><strong>ODS</strong></td>

                                                <td>
                                                    @forelse ($ods_array as $ods)
                                                        <!-- Código para mostrar ODS -->
                                                        <img src="https://cftpucv.vinculamosvm02.cl/vinculamos_v5_cftpucv/app/img/ods-{{ $ods->id_ods }}.png"
                                                            alt="Ods {{ $ods->id_ods }}"
                                                            style="width: 100px; height: 100px;">

                                                        {{-- <div style="display: inline-block; margin: 0; padding: 0;">
                                                <td>
                                                        <img src="https://cftpucv.vinculamosvm02.cl/vinculamos_v5_cftpucv/app/img/ods-{{$ods->id_ods}}.png" alt="Ods {{ $ods->id_ods }}" style="width: 100px; height: 100px;">
                                                </td>
                                                </div> --}}
                                                        {{-- @if ($ods_array->isEmpty()) --}}
                                                    @empty
                                                        <p>Está iniciativa no cuenta con ODS asociados.</p> <a href="{{route('admin.editar.paso1', $iniciativa->inic_codigo)}}">Asociar aquí.</a>
                                                        {{-- <!-- Agrega el campo oculto para almacenar la descripción de la iniciativa -->
                                                    <input type="hidden" id="descripcion_iniciativa" value="{{ $iniciativa->inic_descripcion }}">

                                                    <!-- Agrega el botón "Evaluar ODS" -->
                                                    <button id="send-button" class="btn btn-primary mr-1 text-white mt-2">Asociar ODS</button>
                                                    <div class="mt-3" id="fotosods"></div>
                                                    {{-- <div type="hidden" id="ods-values"></div> --}}
                                                        {{-- <form action="{{ route('admin.iniciativas.odsGuardar', ['inic_codigo' => $iniciativa->inic_codigo]) }}" method="POST">
                                                        @csrf
                                                        <button id="confirmar-ods-button" class="btn btn-success mt-2" style="display: none;">Confirmar ODS</button>
                                                        <input type="hidden" name="ods_values[]" id="ods-hidden-field" value="">
                                                    </form> --}}


                                                        <!-- Agrega los elementos donde se mostrarán las imágenes y valores de ODS -->

                                                        <!-- Script JavaScript -->
                                                        {{-- <script defer>
                                                    $(document).ready(function() {
                                                        $('#send-button').click(function(e) {
                                                            e.preventDefault(); // Previene el comportamiento predeterminado del formulario
                                                            enviarMensaje();
                                                        });

                                                        $('#user-input').keydown(function(event) {
                                                            if (event.keyCode === 13) {
                                                                event.preventDefault();
                                                                enviarMensaje();
                                                            }
                                                        });

                                                        function enviarMensaje() {
                                                            var userInput = $('#descripcion_iniciativa').val().toLowerCase();
                                                            console.log(userInput);

                                                            var inicCodigo = $('#confirmar-ods-button').data('inic-codigo');

                                                            // Mostrar el mensaje del usuario en la derecha
                                                            $('#chat-messages').append(`<div>${userInput}</div>`);

                                                            // Enviar el mensaje al servidor
                                                            $.ajax({
                                                                url: '{{ route("admin.chat.sendMessage") }}',
                                                                type: 'POST',
                                                                data: {
                                                                    '_token': '{{ csrf_token() }}',
                                                                    'message': userInput
                                                                },
                                                                success: function(response) {
                                                                    try {

                                                                        var ods = response.ods;
                                                                        // ods a array
                                                                        var odsArray = ods.split(',');
                                                                        // if(agregarOds){
                                                                        //     odsArray.push('4')
                                                                        // }
                                                                        console.log(odsArray);

                                                                        // Obtener el div donde se agregarán las fotos
                                                                        var fotosDiv = document.getElementById("fotosods");

                                                                        // Limpiar el contenido actual del div
                                                                        fotosDiv.innerHTML = '';

                                                                        // Iterar sobre el arreglo
                                                                        odsArray.forEach(function(numero) {
                                                                            // Crear un elemento de imagen
                                                                            var img = document.createElement("img");

                                                                            // Establecer el src con el número correspondiente
                                                                            img.src = `https://cftpucv.vinculamosvm02.cl/vinculamos_v5_cftpucv/app/img/ods-${numero.toString().trim()}.png`;

                                                                            // Establecer el ancho y alto de la imagen
                                                                            img.width = 150;
                                                                            img.height = 150;

                                                                            // Establecer estilo para mostrar las imágenes en línea
                                                                            img.style.display = "inline-block";
                                                                            img.style.marginRight = "10px"; // Ajusta el margen entre las imágenes

                                                                            // Agregar la imagen al div
                                                                            fotosDiv.appendChild(img);

                                                                            // Agregar el valor de la ODS al campo oculto
                                                                            var odsHiddenInput = document.createElement("input");
                                                                            odsHiddenInput.type = "hidden";
                                                                            odsHiddenInput.name = "ods_values[]";
                                                                            odsHiddenInput.value = numero.trim();
                                                                            document.getElementById("ods-hidden-field").appendChild(odsHiddenInput);
                                                                        });
                                                                        $('#confirmar-ods-button').show();
                                                                        $('#send-button').hide();

                                                                        // $('#ods-input').value(odsArray.join(','))
                                                                    } catch (error) {
                                                                        console.error('Error al procesar la respuesta del servidor:', error);
                                                                    }
                                                                }
                                                            });
                                                        }
                                                    });
                                                    </script> --}}
                                                    @endforelse
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>Dispositivo</strong>
                                                </td>
                                                <td>
                                                    {{ $dispositivos->nombre ?? 'No registrado' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>Asignatura(s)</strong>
                                                </td>
                                                <td>
                                                    <ul>
                                                        @if ($iniciativas_asignaturas->isEmpty())
                                                            <li>No hay asignaturas registradas</li>
                                                        @else
                                                        @foreach ($iniciativas_asignaturas as $asignatura)
                                                            <li>{{ $asignatura->nombre }}</li>
                                                        @endforeach
                                                        @endif

                                                    </ul>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <strong>Impacto(s) interno(s)</strong>
                                                </td>
                                                <td>
                                                    <ul>
                                                        @if ($impactosInternos->isEmpty())
                                                            <li>No hay asignaturas registradas</li>
                                                        @else
                                                        @foreach ($impactosInternos as $impactosInterno)
                                                            <li>{{ $impactosInterno->amb_nombre }}</li>
                                                        @endforeach
                                                        @endif

                                                    </ul>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <strong>Impacto(s) Externos(s)</strong>
                                                </td>
                                                <td>
                                                    <ul>
                                                        @if ($impactosExternos->isEmpty())
                                                            <li>No hay asignaturas registradas</li>
                                                        @else
                                                        @foreach ($impactosExternos as $impactosExterno)
                                                            <li>{{ $impactosExterno->amb_nombre }}</li>
                                                        @endforeach
                                                        @endif

                                                    </ul>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>Macrozona</strong>
                                                </td>
                                                <td>
                                                    {{ $iniciativa->inic_macrozona ?? 'No registrado' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Ubicaciones</strong></td>
                                                <td>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-sm">
                                                            <thead>
                                                                <th>Región</th>
                                                                <th>Comunas</th>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($ubicaciones as $ubicacion)
                                                                    <tr style="background-color: inherit;">
                                                                        <td>{{ $ubicacion->regi_nombre }}</td>
                                                                        <td>{{ $ubicacion->comunas }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>

                                            {{-- <tr> --}}
                                            {{-- Todo: incluir el caso en el que no existan grupos implicados --}}
                                            {{-- <td><strong>Grupos incluidos</strong></td>
                                                <td>
                                                    <ol>
                                                    @foreach ($grupos as $grupo)
                                                    <li>{{$grupo->grup_nombre}}</li>
                                                    @endforeach
                                                    </ol>
                                                </td>
                                            </tr> --}}

                                            {{-- <tr> --}}
                                            {{-- Todo: incluir el caso en el que no existan grupos implicados --}}
                                            {{-- <td><strong>Grupos y temáticas <br> relacionadas</strong></td>
                                                <td>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-sm small">
                                                            <thead>
                                                                <th>Grupos</th>
                                                                <th>Temáticas</th>
                                                            </thead>
                                                            <tbody>
                                                                <td>
                                                                    <ol>
                                                                        @foreach ($grupos as $grupo)
                                                                            <li>{{ $grupo->grup_nombre }}</li>
                                                                        @endforeach
                                                                    </ol>
                                                                </td>
                                                                <td>
                                                                    <ol>
                                                                        @foreach ($tematicas as $tematica)
                                                                            <li>{{ $tematica->tema_nombre }}</li>
                                                                        @endforeach
                                                                    </ol>
                                                                </td>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td> --}}
                                            {{-- </tr> --}}

                                            <tr>
                                                <td><strong>Participantes externos</strong></td>
                                                <td>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-sm ">
                                                            <thead>
                                                                <th>Grupos</th>
                                                                <th>Subgrupos</th>
                                                                <th>Nombre del socio</th>
                                                                <th>Beneficiarios</th>
                                                                <th>Beneficiarios final</th>
                                                            </thead>

                                                            <tbody>
                                                                @foreach ($externos as $externo)
                                                                    <tr>
                                                                        <td>{{ $externo->grin_nombre }}</td>
                                                                        <td>{{ $externo->sugr_nombre }}</td>
                                                                        <td>{{ $externo->soco_nombre_socio }}</td>
                                                                        <td>{{ $externo->inpr_total }}</td>
                                                                        <td>{{ $externo->inpr_total_final }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><strong>Participantes internos</strong></td>
                                                <td>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-sm">
                                                            <thead>
                                                                <th>Áreas</th>
                                                                <th>Carreras</th>
                                                                <th>Docentes</th>
                                                                <th>Docentes final</th>
                                                                <th>Estudiantes</th>
                                                                <th>Estudiantes final</th>
                                                            </thead>

                                                            <tbody>
                                                                @foreach ($internos as $interno)
                                                                    <tr>
                                                                        <td>{{ $interno->escu_nombre }}</td>
                                                                        <td>{{ $interno->care_nombre }}</td>
                                                                        <td>
                                                                            @if ($interno->pain_docentes != null)
                                                                                {{ $interno->pain_docentes }}
                                                                            @else
                                                                                No registrado
                                                                            @endif
                                                                        </td>

                                                                        <td>
                                                                            @if ($interno->pain_docentes_final != null)
                                                                                {{ $interno->pain_docentes_final }}
                                                                            @else
                                                                                No registrado
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if ($interno->pain_estudiantes != null)
                                                                                {{ $interno->pain_estudiantes }}
                                                                            @else
                                                                                No registrado
                                                                            @endif
                                                                        </td>

                                                                        <td>
                                                                            @if ($interno->pain_estudiantes_final != null)
                                                                                {{ $interno->pain_estudiantes_final }}
                                                                            @else
                                                                                No registrado
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><strong>Total de recursos invertidos</strong></td>
                                                <td>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-sm">
                                                            <thead>
                                                                <th></th>
                                                                <th>Dinero</th>
                                                                <th>Infraestructura</th>
                                                                <th>Recursos humanos</th>
                                                            </thead>

                                                            <tbody>
                                                                @php
                                                                    $totalDinero = 0;
                                                                    $totalInfraestructura = 0;
                                                                    $totalRrhh = 0;
                                                                @endphp
                                                                @foreach ($entidades as $entidad)
                                                                    @php
                                                                        $entidadDinero = 0;
                                                                        $entidadInfraestructura = 0;
                                                                        $entidadRrhh = 0;
                                                                    @endphp

                                                                    <tr>
                                                                        <td>{{ $entidad->enti_nombre }}</td>
                                                                        <td>
                                                                            @if (sizeof($recursoDinero) == 0)
                                                                                $0
                                                                            @else
                                                                                @foreach ($recursoDinero as $dinero)
                                                                                    @if ($entidad->enti_codigo == $dinero->enti_codigo)
                                                                                        @php
                                                                                            $entidadDinero = $dinero->suma_dinero;
                                                                                        @endphp
                                                                                        ${{ number_format($dinero->suma_dinero, 0, ',', '.') }}
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if (sizeof($recursoInfraestructura) == 0)
                                                                                $0
                                                                            @else
                                                                                @foreach ($recursoInfraestructura as $infraestructura)
                                                                                    @if ($entidad->enti_codigo == $infraestructura->enti_codigo)
                                                                                        @php
                                                                                            $entidadInfraestructura = $infraestructura->suma_infraestructura;
                                                                                        @endphp
                                                                                        ${{ number_format($infraestructura->suma_infraestructura, 0, ',', '.') }}
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if (sizeof($recursoRrhh) == 0)
                                                                                $0
                                                                            @else
                                                                                @foreach ($recursoRrhh as $rrhh)
                                                                                    @if ($entidad->enti_codigo == $rrhh->enti_codigo)
                                                                                        @php
                                                                                            $entidadRrhh = $rrhh->suma_rrhh;
                                                                                        @endphp
                                                                                        ${{ number_format($rrhh->suma_rrhh, 0, ',', '.') }}
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    @php
                                                                        $totalDinero += $entidadDinero;
                                                                        $totalInfraestructura += $entidadInfraestructura;
                                                                        $totalRrhh += $entidadRrhh;
                                                                    @endphp
                                                                @endforeach
                                                                <tr>
                                                                    <td>Total General</td>
                                                                    <td>${{ number_format($totalDinero, 0, ',', '.') }}
                                                                    </td>
                                                                    <td>${{ number_format($totalInfraestructura, 0, ',', '.') }}
                                                                    </td>
                                                                    <td>${{ number_format($totalRrhh, 0, ',', '.') }}
                                                                    </td>
                                                                </tr>

                                                            </tbody>
                                                        </table>
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
            </div>
        </div>
    </section>
    <div class="modal fade" id="modalINVI" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Índice de vinculación INVI</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md" id="table-1"
                            style="border-top: 1px ghostwhite solid;">
                            <tbody>
                                <tr>
                                    <td><strong>Mecanismo</strong></td>
                                    <td id="mecanismo-nombre"></td>
                                    <td id="mecanismo-puntaje"></td>
                                </tr>
                                <tr>
                                    <td><strong>Frecuencia</strong></td>
                                    <td id="frecuencia-nombre"></td>
                                    <td id="frecuencia-puntaje"></td>
                                </tr>
                                <tr>
                                    <td><strong>Resultados</strong></td>
                                    <td id="resultados-nombre"></td>
                                    <td id="resultados-puntaje"></td>
                                </tr>
                                <tr>
                                    <td><strong>Cobertura</strong></td>
                                    <td id="cobertura-nombre"></td>
                                    <td id="cobertura-puntaje"></td>
                                </tr>
                                <tr>
                                    <td><strong>Evaluación</strong></td>
                                    <td id="evaluacion-nombre"></td>
                                    <td id="evaluacion-puntaje"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <h6>Índice de vinculación INVI</h6>
                                    </td>
                                    <td id="valor-indice"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalEliminaIniciativa" tabindex="-1" role="dialog" aria-labelledby="modalEliminar"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.iniciativa.eliminar') }} " method="POST">
                    @method('DELETE')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEliminar">Eliminar Iniciativa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-ban text-danger" style="font-size: 50px; color"></i>
                        <h6 class="mt-2">La iniciativa dejará de existir dentro del sistema. <br> ¿Desea continuar de
                            todos
                            modos?</h6>
                        <input type="hidden" id="inic_codigo" name="inic_codigo" value="">
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="submit" class="btn btn-primary">Continuar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('/js/admin/iniciativas/INVI.js') }}"></script>
    <script>
        function eliminarIniciativa(inic_codigo) {
            $('#inic_codigo').val(inic_codigo);
            $('#modalEliminaIniciativa').modal('show');
        }
    </script>
@endsection
