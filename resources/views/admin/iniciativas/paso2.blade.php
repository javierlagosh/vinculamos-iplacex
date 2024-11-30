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
                <div class="col-xl-3"></div>
                <div class="col-xl-6">
                    @if (Session::has('exitoPaso1'))
                        <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                            <div class="alert-body">
                                <strong>{{ Session::get('exitoPaso1') }}</strong>
                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                            </div>
                        </div>
                    @endif
                    @if (Session::has('ExisteSocio'))
                        <div class="alert alert-warning alert-dismissible show fade mb-4 text-center">
                            <div class="alert-body">
                                <strong>{{ Session::get('ExisteSocio') }}</strong>
                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                            </div>
                        </div>
                    @endif

                    @if (Session::has('errorPaso2'))
                        <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                            <div class="alert-body">
                                <strong>{{ Session::get('errorPaso2') }}</strong>
                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                            </div>
                        </div>
                    @endif

                    @if (Session::has('socoError'))
                        <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                            <div class="alert-body">
                                <strong>{{ Session::get('socoError') }}</strong>
                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                            </div>
                        </div>
                    @endif
                    @if (Session::has('socoExito'))
                        <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                            <div class="alert-body">
                                <strong>{{ Session::get('socoExito') }}</strong>
                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h2 id="idIniciativa">{{ $iniciativa->inic_codigo }}</h2>
                            <h4>Iniciativa: {{ $iniciativa->inic_nombre }}</h4>
                            @if (isset($iniciativa))
                            <div class="card-header-action">
                                <div class="dropdown d-inline">
                                    <button class="btn btn-info dropdown-toggle" id="dropdownMenuButton2"
                                        data-toggle="dropdown"title="Iniciativa">
                                        Iniciativa</button>
                                    <div class="dropdown-menu dropright">
                                        <a href="{{ route($role . '.iniciativas.detalles', $iniciativa->inic_codigo) }}"
                                            class="dropdown-item has-icon" data-toggle="tooltip" data-placement="top"
                                            title="Ver detalles de la iniciativa"><i class="fas fa-eye"></i> Ver
                                            detalle</a>

                                        {{-- <a href="{{ route('admin.editar.paso1', $iniciativa->inic_codigo) }}"
                                                            class="btn btn-icon btn-primary icon-left" data-toggle="tooltip"
                                                            data-placement="top" title="Editar iniciativa"><i
                                                                class="fas fa-edit"></i>Editar Iniciativa</a> --}}

                                        {{-- <a href="javascript:void(0)" class="dropdown-item has-icon"
                                            data-toggle="tooltip" data-placement="top" title="Calcular INVI"
                                            onclick="calcularIndice({{ $iniciativa->inic_codigo }})"><i
                                                class="fas fa-tachometer-alt"></i> INVI</a> --}}

                                        {{-- <a href="{{ route($role . '.evidencias.listar', $iniciativa->inic_codigo) }}"
                                            class="dropdown-item has-icon" data-toggle="tooltip" data-placement="top"
                                            title="Adjuntar evidencia"><i class="fas fa-paperclip"></i> Adjuntar
                                            evidencia</a> --}}
                                    </div>
                                </div>
                                <div class="dropdown d-inline">
                                    <button class="btn btn-success dropdown-toggle" id="dropdownMenuButton2"
                                        data-toggle="dropdown"title="ingresar">
                                        <i class="fas fa-plus-circle"></i> Ingresar</button>
                                        <div class="dropdown-menu dropright">
                                            <a href="{{ route('admin.cobertura.index', $iniciativa->inic_codigo) }}"
                                                class="dropdown-item has-icon" data-toggle="tooltip" data-placement="top"
                                                title="Ingresar cobertura"><i class="fas fa-users"></i> Ingresar cobertura</a>

                                            <a href="{{ route('admin.resultados.listado', $iniciativa->inic_codigo) }}"
                                                class="dropdown-item has-icon" data-toggle="tooltip" data-placement="top"
                                                title="Ingresar resultado"><i class="fas fa-flag"></i>Ingresar resultado/s</a>
                                                <a href="{{ route('admin.evidencias.listar', $iniciativa->inic_codigo) }}"
                                                    class="dropdown-item has-item" data-toggle="tooltip" data-placement="top"
                                                    title="Adjuntar evidencia"><i class="fas fa-paperclip"></i> Ingresar
                                                    evidencias</a>
                                                <a href="{{ route('admin.evaluar.iniciativa', $iniciativa->inic_codigo) }}" class="dropdown-item has-icon"><i
                                                    class="fas fa-file-signature"></i>Ingresar evaluación</a>


                                        </div>
                                </div>
                                <a href="{{ route($role . '.iniciativa.listar') }}"
                                    class="btn btn-primary mr-1 waves-effect icon-left" type="button">
                                    <i class="fas fa-angle-left"></i> Volver a listado
                                </a>
                            </div>
                            @endif
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-lg-6">
                                    <h5>Sección 2 - Participantes externos</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-2 col-md-2 col-lg-3" hidden>
                                    <div class="form-group">
                                        <label style="font-size: 110%">Subgrupos</label> <label for=""
                                            style="color: red;">*</label>
                                        <select class="form-control select2" id="subgrupo" name="subgrupo"
                                            style="width: 100%">
                                            <option value="">Seleccione...</option>
                                            @forelse ($subgrupos as $subgrupo)
                                                <option value="{{ $subgrupo->sugr_codigo }}">{{ $subgrupo->sugr_nombre }}
                                                </option>
                                            @empty
                                                <option value="-1">No existen registros</option>
                                            @endforelse
                                        </select>

                                        @if ($errors->has('subgrupo'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close"
                                                        data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('subgrupo') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xl-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label style="font-size: 110%">Socio/a comunitario</label> <label for=""
                                            style="color: red;">*</label>
                                        <select class="form-control select2" id="socio" name="socio"
                                            style="width: 100%">
                                            <option value="">Seleccione...</option>
                                            @forelse ($socios as $socio)
                                                <option value="{{ $socio->soco_codigo }}">{{ $socio->soco_nombre_socio }}
                                                </option>
                                            @empty
                                                <option value="-1">No existen registros</option>
                                            @endforelse
                                        </select>

                                        @if ($errors->has('socio'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close"
                                                        data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('socio') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xl-2 col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label style="font-size: 110%">Personas beneficiarias</label>
                                        <input type="number" class="form-control" id="npersonas" name="npersonas"
                                            value="{{ old('npersonas') }}">

                                        @if ($errors->has('npersonas'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close"
                                                        data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('npersonas') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xl-2 col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label style="font-size: 110%">Acciones</label>
                                        <div class="d-flex">
                                            <div >
                                                <button class="btn btn-primary waves-effect"
                                                onclick="AgregarParticipantesExternos()"><i class="fas fa-plus"></i>
                                                Agregar</button>
                                            </div>
                                            &nbsp;
                                            <div >
                                                <button type="button" class="btn btn-success" data-toggle="modal"
                                            data-target="#modalCrearSocio"><i class="fas fa-plus"></i> Nuevo socio/a
                                            comunitario/a</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                    </div>


                            </div>
                            <div class="row">
                                <div class="col-xl-2"></div>
                                <div class="col-xl-8">
                                    <div class="card">
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-bored table-md">
                                                    <thead>
                                                        <th>Subgrupo</th>
                                                        <th>Socio Comunitario</th>
                                                        <th>Personas beneficiarias</th>
                                                        <th>Acción</th>
                                                    </thead>
                                                    <tbody id="body-tabla-externos">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php
                                $tipo = session('tipo', 'crear'); // 'crear' es el valor por defecto si no se encuentra en la sesión
                            @endphp
                            @php
                                $tipo = session('tipo', 'editar')
                            @endphp
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="d-flex justify-content-start" style="margin-left: 25px;">
                                        <div class="mb-2">
                                            <!-- Botón para abrir el modal de OK de Sección 2 -->
                                            {{-- @dd($motivosSeccion2) --}}
                                            <!-- Botón para abrir el modal de Falta Información de Sección 2 -->
                                            {{-- TODOOCULTAR: CAMBIAR DE OCULTAR A EDITAR -> $tipo === "editar" --}}
                                            @if ($tipo === "editar")
                                                @if(Session::has('admin') or Session::has('digitador'))
                                                    @if($estadoIniciativa->isNotEmpty())
                                                        {{-- Filtramos la colección para obtener los registros de la sección 2 --}}
                                                        @php
                                                            $motivosSeccion2 = $estadoIniciativa->where('seccion', 2);
                                                            $motivosFaltaInfoSeccion2 = $motivosSeccion2->where('estado', 0)->count(); // Contar motivos no validados
                                                            $motivosCorregidosSeccion2 = $motivosSeccion2->where('estado', 2)->count();
                                                            $estadoSeccion2 = $motivosSeccion2->first(); // Obtener el primer registro de la sección
                                                        @endphp

                                                        {{-- Si existe un registro para la sección 2 --}}
                                                        @if($estadoSeccion2)
                                                            {{-- Si el estado de la sección 2 es 1, muestra el botón Validado --}}
                                                            @if($estadoSeccion2->estado == 1)
                                                                <div>
                                                                    <button type="button" class="btn btn-success mr-1" id="btnFaltaInfoSeccion2">Validado</button>
                                                                </div>
                                                            @else
                                                                <div>
                                                                    @if($motivosFaltaInfoSeccion2 > 0)
                                                                        <button type="button" class="btn btn-warning mr-1" id="btnFaltaInfoSeccion2">
                                                                            Falta Información ({{ $motivosFaltaInfoSeccion2 }})
                                                                        </button>
                                                                    @elseif($motivosCorregidosSeccion2>=1)
                                                                        <button type="button" class="btn btn-warning mr-1" id="btnFaltaInfoSeccion2">
                                                                            Resueltos ({{ $motivosCorregidosSeccion2 }})
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        @else
                                                            {{-- Si no existe un registro para la sección 2, mostramos el botón Falta Información --}}
                                                            <div class="d-flex align-items-center mb-4">
                                                                <button type="button" class="btn btn-warning mr-2" id="btnFaltaInfoSeccion2">Falta Información</button>
                                                                @if (Session::has('admin'))
                                                                    @if ($motivosSeccion2->where('seccion', 3)->isEmpty())
                                                                        <form action="{{ url('/admin/iniciativas/'.$inic_codigo.'/seccion/2/ok') }}" method="POST">
                                                                            @csrf
                                                                            <button type="submit" class="btn" style="background-color: #28a745; color: white;">Validar Sección</button>
                                                                        </form>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        @endif
                                                    @else
                                                        {{-- Si no hay motivos registrados para la sección --}}
                                                        <div class="d-flex align-items-center mb-4">
                                                            <button type="button" class="btn btn-warning mr-2" id="btnFaltaInfoSeccion2">Falta Información</button>
                                                            @if (Session::has('admin'))
                                                                @if ($estadoIniciativa->where('seccion', 2)->isEmpty())
                                                                    <form action="{{ url('/admin/iniciativas/'.$inic_codigo.'/seccion/2/ok') }}" method="POST">
                                                                        @csrf
                                                                        <button type="submit" class="btn" style="background-color: #28a745; color: white;">Validar Sección</button>
                                                                    </form>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal Falta Información Sección 2 -->
                            @if($tipo === 'editar')
                            <style>
                                .modal-body {
                                    max-height: 50vh; /* Limitar la altura máxima del contenido al 80% de la altura de la pantalla */
                                    overflow-y: auto; /* Habilitar el scroll vertical */
                                    padding: 1rem; /* Asegurar un espacio interno adecuado */
                                }

                                .modal {
                                    justify-content: center;
                                    align-items: center;
                                }

                                .modal-dialog {
                                    max-width: 90%; /* Ajustar el ancho del modal */
                                    margin: auto; /* Centrar el modal */
                                }
                                .modal-open {
                                    overflow: hidden;
                                }
                            </style>
                            <div class="modal fade" id="modalFaltaInfoSeccion2" tabindex="-1" role="dialog" aria-labelledby="modalFaltaInfoSeccion2Label" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered custom-modal" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalFaltaInfoSeccion2Label">Falta Información - Sección 2</h5>
                                            <!-- Botón de cerrar modal -->
                                            <button type="button" class="close" id="closeModalFaltaInfoSeccion2" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div>
                                                @if ($tipo==="editar")
                                                    @if($estadoIniciativa->isNotEmpty())
                                                        @foreach($estadoIniciativa as $motivo)
                                                            @if($motivo->seccion == 2)
                                                                @if ($motivo->estado === 0)
                                                                    <div class="card mb-2 w-100" style="max-width: 20rem; padding: 0.5rem; margin-top: 15px; background-color: #6c757d; color: white;">
                                                                        <div class="card-body p-2">
                                                                            <h5 class="card-title" style="font-size: 1.25rem;">Estado: Pendiente</h5>
                                                                            <p class="card-text" style="font-size: 1rem;">
                                                                                Comentario: {{ $motivo->motivo }}
                                                                            </p>
                                                                            <p class="card-meta" style="font-size: 0.875rem; margin-top: 0.5rem;">
                                                                                Comentario ingresado por: <strong>{{ $motivo->usua_nickname }}</strong><br>
                                                                                Fecha de registro: <em>{{ $motivo->fecha_registro }}</em>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                @elseif ($motivo->estado === 1)
                                                                    <div class="card mb-2 w-100" style="max-width: 20rem; padding: 0.5rem; margin-top: 15px; background-color: #007219; color: white;">
                                                                        <div class="card-body p-2">
                                                                            <h5 class="card-title" style="font-size: 1.25rem;">Estado: Aprobado</h5>
                                                                            <p class="card-text" style="font-size: 1rem;">
                                                                                Comentario: {{ $motivo->motivo }}
                                                                            </p>
                                                                            @if($motivo->usua_nickname_corrector!=null or $motivo->usua_nickname_corrector != '')
                                                                                <p class="card-meta" style="font-size: 0.875rem; margin-top: 0.3rem;">
                                                                                    Comentario ingresado por: <strong>{{ $motivo->usua_nickname }}</strong><br>
                                                                                    Fecha de registro: <em>{{ $motivo->fecha_registro }}</em>
                                                                                </p>
                                                                                <p class="card-meta" style="font-size: 0.875rem; margin-top: 0.3rem">
                                                                                    Corregido por: <strong>{{ $motivo->usua_nickname_corrector }}</strong><br>
                                                                                    Fecha de corregido: <em>{{ $motivo->fecha_correccion }}</em>
                                                                                </p>
                                                                                <p class="card-meta" style="font-size: 0.875rem; margin-top: 0.3rem;">
                                                                                    Validado por: <strong>{{ $motivo->usua_nickname_validador }}</strong><br>
                                                                                    Fecha de validación: <em>{{ $motivo->fecha_validacion }}</em>
                                                                                </p>
                                                                            @else
                                                                                <p class="card-meta" style="font-size: 0.875rem; margin-top: 0.3rem;">
                                                                                    Comentario ingresado por: <strong>{{ $motivo->usua_nickname }}</strong><br>
                                                                                    Fecha de validación: <em>{{ $motivo->fecha_validacion }}</em>
                                                                                </p>
                                                                                <p class="card-meta" style="font-size: 0.875rem; margin-top: 0.3rem">
                                                                                    Validado por: <strong>{{ $motivo->usua_nickname_validador }}</strong><br>
                                                                                    Fecha de validación: <em>{{ $motivo->fecha_validacion }}</em>
                                                                                </p>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @elseif ($motivo->estado === 2)
                                                                    <div class="card mb-2 w-100" style="max-width: 20rem; padding: 0.5rem; margin-top: 15px; background-color: #708a00; color: white;">
                                                                        <div class="card-body p-2">
                                                                            <h5 class="card-title" style="font-size: 1.25rem;">Estado: Corregido</h5>
                                                                            <p class="card-text" style="font-size: 1.2rem;">
                                                                                Comentario: {{ $motivo->motivo }}
                                                                            </p>
                                                                            <p class="card-meta" style="font-size: 0.875rem; margin-top: 0.5rem;">
                                                                                Comentario ingresado por: <strong>{{ $motivo->usua_nickname }}</strong><br>
                                                                                Fecha de registro: <em>{{ $motivo->fecha_registro }}</em>
                                                                            </p>
                                                                            <p class="card-meta" style="font-size: 0.875rem; margin-top: 0.3rem;">
                                                                                Corregido por: <strong>{{ $motivo->usua_nickname_corrector }}</strong><br>
                                                                                Fecha de corrección: <em>{{ $motivo->fecha_correccion }}</em>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endif
                                            </div>
                                            @if (!$estadoIniciativa->filter(fn($item) => $item->seccion == 2)->contains('estado', 1))
                                                @if(Session::has('admin'))
                                                    <p>Por favor, ingresa el motivo de la falta de información:</p>
                                                    <form id="formFaltaInfoSeccion2" action="{{ url('/admin/iniciativas/'.$inic_codigo.'/seccion/2/falta-info') }}" method="POST">
                                                        @csrf
                                                        <textarea name="motivo" class="form-control auto-expand" placeholder="Motivo de la falta de información" rows="3" required></textarea>
                                                    </div>
                                                    <div class="modal-footer bg-whitesmoke br" style="display: flex; justify-content: center; gap: 10px;">
                                                    <!-- Botón "Guardar Falta Información" con color naranjo y ocupando todo el ancho -->
                                                    <button type="submit" class="btn h-100" style="background-color: #FFA500; color: white;">Guardar falta información</button>
                                                    </form>
                                                    <!-- Botón "Validar Sección" con color verde y ocupando todo el ancho -->
                                                    @if (!$estadoIniciativa->where('seccion', 2)->isEmpty())
                                                        <form action="{{ url('/admin/iniciativas/'.$inic_codigo.'/seccion/2/ok') }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn h-100" style="background-color: #28a745; color: white;">Validar Sección</button>
                                                        </form>
                                                    @endif
                                                @elseif (Session::has('digitador'))
                                                    @if($estadoIniciativa->isNotEmpty() &&
                                                        $estadoIniciativa->filter(fn($item) => $item->seccion == 2)->isNotEmpty() &&
                                                        !$estadoIniciativa->filter(fn($item) => $item->seccion == 2)->every(fn($item) => $item->estado === 2))
                                                        <form action="{{ url('/admin/iniciativas/'.$inic_codigo.'/seccion/2/corregido') }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn h-200" style="background-color: #28a745; color: white; width: -webkit-fill-available;">Comentarios Resueltos</button>
                                                        </form>
                                                    @else
                                                        <p class="text-center" style="margin-top:2rem;">No hay comentarios por corregir.</p>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <!-- Fondo oscuro para cuando el modal está abierto -->
                            <div id="modalBackdrop" class="modal-backdrop"></div>

                            <!-- Estilos personalizados para los modales y el fondo oscuro -->
                            <style>
                            .custom-modal {
                                max-width: 400px;
                                height: 500px;
                            }
                            .modal-backdrop {
                                position: fixed;
                                top: 0;
                                left: 0;
                                width: 100%;
                                height: 100%;
                                background-color: rgba(0, 0, 0, 0); /* Fondo transparente inicialmente */
                                opacity: 0;
                                transition: opacity 0.4s ease-in-out;
                                z-index: 1040; /* Aseguramos que el fondo esté por debajo del modal */
                                display: none; /* Inicialmente oculto */
                            }
                            .modal-backdrop.show {
                                display: block; /* Mostrar cuando se activa */
                                opacity: 0.5; /* Nivel de opacidad deseado */
                            }
                            .fade {
                                opacity: 0;
                                transition: opacity 0.4s ease-in-out;
                            }
                            .fade.show {
                                opacity: 1;
                            }

                            /* Personaliza el fondo opaco del modal */
                            .modal-backdrop {
                                background-color: rgba(0, 0, 0, 0.7); /* Cambia la opacidad */
                                transition: opacity 0.3s ease-in-out; /* Añade una transición suave */
                            }

                            /* Asegúrate de que el modal tenga la transición suave también */
                            .modal.fade .modal-dialog {
                                transition: transform 0.3s ease-out;
                            }
                            </style>

                            <!-- Script para manejar los modales con animación y fondo oscuro -->
                            <script>
                            document.addEventListener("input", function(event) {
                                if (event.target.classList.contains("auto-expand")) {
                                    const textarea = event.target;
                                    textarea.style.height = "auto";
                                    textarea.style.height = (textarea.scrollHeight) + "px";
                                }
                            });

                            function showModal(modalId) {
                                const modal = document.getElementById(modalId);
                                const backdrop = document.getElementById("modalBackdrop");

                                // Mostrar el fondo oscuro con transición
                                backdrop.classList.add("show");

                                // Mostrar el modal con transición
                                modal.style.display = "block";
                                setTimeout(() => {
                                    modal.classList.add("show");
                                }, 10);
                                document.body.classList.add("modal-open");

                                // Cerrar al hacer clic fuera del modal
                                modal.addEventListener("click", function(event) {
                                    if (event.target === modal) {
                                        hideModal(modalId);
                                    }
                                });
                            }

                            function hideModal(modalId) {
                                const modal = document.getElementById(modalId);
                                const backdrop = document.getElementById("modalBackdrop");

                                // Ocultar el modal
                                modal.classList.remove("show");
                                setTimeout(() => {
                                    modal.style.display = "none";
                                    document.body.classList.remove("modal-open");
                                }, 400);

                                // Ocultar el fondo oscuro con transición
                                backdrop.classList.remove("show");
                            }

                            // Verificar si el botón existe antes de añadir los event listeners
                            const btnFaltaInfoSeccion2 = document.getElementById("btnFaltaInfoSeccion2");
                            const closeModalFaltaInfoSeccion2 = document.getElementById("closeModalFaltaInfoSeccion2");
                            const cancelModalFaltaInfoSeccion2 = document.getElementById("cancelModalFaltaInfoSeccion2");

                            if (btnFaltaInfoSeccion2) {
                                btnFaltaInfoSeccion2.addEventListener("click", function() {
                                    showModal("modalFaltaInfoSeccion2");
                                });
                            }

                            if (closeModalFaltaInfoSeccion2) {
                                closeModalFaltaInfoSeccion2.addEventListener("click", function() {
                                    hideModal("modalFaltaInfoSeccion2");
                                });
                            }

                            if (cancelModalFaltaInfoSeccion2) {
                                cancelModalFaltaInfoSeccion2.addEventListener("click", function() {
                                    hideModal("modalFaltaInfoSeccion2");
                                });
                            }
                            </script>

                            <div class="row p-4">
                                <div class="col-xl-6 col-md-6 col-lg-6">
                                    <h5>Sección 3 - Participantes internos</h5>
                                </div>
                            </div>
                            <div class="row p-4">
                                <div class="col-xl-2 col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label style="font-size: 110%">Sedes</label> <label for=""
                                            style="color: red;">*</label>
                                        <select class="form-control select2" id="sedes" name="sedes"
                                            style="width: 100%">
                                            <option value="" selected disabled>Seleccione...</option>
                                            @forelse ($sedes as $sede)
                                                <option value="{{ $sede->sede_codigo }}">
                                                    {{ $sede->sede_nombre }}
                                                </option>
                                            @empty
                                                <option value="-1">No existen registros</option>
                                            @endforelse

                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label style="font-size: 110%">Unidad ejecutora</label> <label for=""
                                            style="color: red;">*</label>
                                        <select class="form-control select2" id="escuelas" name="escuelas"
                                            style="width: 100%">
                                            <option value="" selected disabled>Seleccione...</option>
                                            @forelse ($escuelas as $escuela)
                                                <option value="{{ $escuela->escu_codigo }}">
                                                    {{ $escuela->escu_nombre }}
                                                </option>
                                            @empty
                                                <option value="-1">No existen registros</option>
                                            @endforelse

                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-2 div-col-md-2 col-lg-3">
                                    <div class="form-group">
                                        <label style="font-size: 110%">Carreras</label> <label for=""
                                            style="color: red;">*</label>
                                        <select class="form-control select2" id="carreras" name="carreras"
                                            style="width: 100%">
                                            <option value="" disabled selected>Seleccione...</option>
                                            @forelse ($carreras as $carrera)
                                                <option value="{{ $carrera->care_codigo }}">
                                                    {{ $carrera->care_nombre }}
                                                </option>
                                            @empty
                                                <option value="-1">No existen registros</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label style="font-size: 110%">Estudiantes</label> <label for=""
                                            style="color: red;">*</label>
                                        <input type="number" class="form-control" id="nestudiantes"
                                            name="nestudiantes">

                                        @if ($errors->has('nestudiantes'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close"
                                                        data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('nestudiantes') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xl-2 col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label style="font-size: 110%">Docentes</label> <label for=""
                                            style="color: red;">*</label>
                                        <input type="number" class="form-control" id="ndocentes" name="ndocentes">

                                        @if ($errors->has('ndocentes'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close"
                                                        data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('ndocentes') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xl-2 col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label style="font-size: 110%">Funcionarios/as</label> <label for=""
                                            style="color: red;">*</label>
                                        <input type="number" class="form-control" id="nfuncionarios"
                                            name="nfuncionarios">

                                        @if ($errors->has('nfuncionarios'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close"
                                                        data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('nfuncionarios') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="text-align: ">
                                <div class="col-xl-4"></div>
                                <div class="col-xl-4">

                                    <button onclick="modificar()" class="btn btn-primary mr-1 waves-effect"><i
                                            class="fas fa-plus"></i> Agregar
                                    </button>

                                </div>
                                <div class="col-4"></div>
                            </div>

                            <div class="row" style="margin-top:75px">
                                <div class="col-xl-2"></div>
                                <div class="col-xl-8">
                                    <div class="card">
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-bored table-md">
                                                    <thead>
                                                        <th>Sedes</th>
                                                        <th>Unidades ejecutoras</th>
                                                        <th>Carreras</th>
                                                        <th>Estudiantes</th>
                                                        <th>Docentes</th>
                                                        <th>Funcionarios/as</th>
                                                        {{-- <th>Total</th> --}}
                                                    </thead>
                                                    <tbody id="body-tabla-internos">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($tipo === "editar")
                                @if(Session::has('admin') or Session::has('digitador'))
                                    @if($estadoIniciativa->isNotEmpty())
                                        {{-- Filtramos la colección para obtener los registros de la sección 2 --}}
                                        @php
                                            $motivosSeccion3 = $estadoIniciativa->where('seccion', 3);
                                            $motivosFaltaInfoSeccion3 = $motivosSeccion3->where('estado',0)->count(); // Contar motivos no validados
                                            $motivosCorregidosSeccion3 = $motivosSeccion3->where('estado',2)->count();
                                            $estadoSeccion3 = $motivosSeccion3->first(); // Obtener el primer registro de la sección
                                        @endphp
                                        {{-- @dd($estadoSeccion3) --}}
                                        {{-- Si existe un registro para la sección 3 --}}
                                        @if($estadoSeccion3)
                                            {{-- Si el estado de la sección 3 es 1, muestra el botón Validado --}}
                                            @if($estadoSeccion3->estado == 1)
                                                <div class="d-flex align-items-center mb-4" style="margin-left: 25px;">
                                                    <button type="button" class="btn btn-success mr-1 mb-4" id="btnFaltaInfoSeccion3">Validado</button>
                                                </div>
                                            @else
                                                <div class="d-flex align-items-center mb-4" style="margin-left: 25px;">
                                                    @if ($motivosFaltaInfoSeccion3 > 0)
                                                        <button type="button" class="btn btn-warning mr-1 mb-4" id="btnFaltaInfoSeccion3">
                                                            Falta Información ({{ $motivosFaltaInfoSeccion3 }})
                                                        </button>
                                                    @elseif($motivosCorregidosSeccion3 >= 1)
                                                        <button type="button" class="btn btn-warning mr-1 mb-4" id="btnFaltaInfoSeccion3">
                                                            Resueltos ({{ $motivosCorregidosSeccion3 }})
                                                        </button>
                                                    @endif
                                                </div>
                                            @endif
                                        @else
                                            {{-- Si no existe un registro para la sección 2, mostramos el botón Falta Información --}}
                                            <div class="d-flex align-items-center mb-4" style="margin-left: 25px;">
                                                <button type="button" class="btn btn-warning mr-2" id="btnFaltaInfoSeccion3">Falta Información</button>
                                                @if (Session::has('admin'))
                                                    @if ($motivosSeccion3->where('seccion', 3)->isEmpty())
                                                        <form action="{{ url('/admin/iniciativas/'.$inic_codigo.'/seccion/3/ok') }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn" style="background-color: #28a745; color: white;">Validar Sección</button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif
                                    @else
                                        {{-- Si no hay motivos registrados para la sección --}}
                                        <div class="d-flex align-items-center mb-4" style="margin-left: 25px;">
                                            <button type="button" class="btn btn-warning mr-2" id="btnFaltaInfoSeccion3">Falta Información</button>
                                            @if (Session::has('admin'))
                                                @if ($estadoIniciativa->where('seccion', 3)->isEmpty())
                                                    <form action="{{ url('/admin/iniciativas/'.$inic_codigo.'/seccion/3/ok') }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn" style="background-color: #28a745; color: white;">Validar Sección</button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                @endif
                            @endif

                            <!-- Modal Falta Información Sección 3 -->
                            @if($tipo === 'editar')
                                <style>
                                    .modal-body {
                                        max-height: 50vh; /* Limitar la altura máxima del contenido al 80% de la altura de la pantalla */
                                        overflow-y: auto; /* Habilitar el scroll vertical */
                                        padding: 1rem; /* Asegurar un espacio interno adecuado */
                                    }

                                    .modal {
                                        justify-content: center;
                                        align-items: center;
                                    }

                                    .modal-dialog {
                                        max-width: 90%; /* Ajustar el ancho del modal */
                                        margin: auto; /* Centrar el modal */
                                    }
                                    .modal-open {
                                        overflow: hidden;
                                    }
                                    .custom-modal {
                                        max-width: 400px;
                                        height: 500px;
                                    }
                                    .modal-backdrop {
                                        position: fixed;
                                        top: 0;
                                        left: 0;
                                        width: 100%;
                                        height: 100%;
                                        background-color: rgba(0, 0, 0, 0); /* Fondo transparente inicialmente */
                                        opacity: 0;
                                        transition: opacity 0.4s ease-in-out;
                                        z-index: 1040; /* Aseguramos que el fondo esté por debajo del modal */
                                        display: none; /* Inicialmente oculto */
                                    }
                                    .modal-backdrop.show {
                                        display: block; /* Mostrar cuando se activa */
                                        opacity: 0.5; /* Nivel de opacidad deseado */
                                    }
                                    .fade {
                                        opacity: 0;
                                        transition: opacity 0.4s ease-in-out;
                                    }
                                    .fade.show {
                                        opacity: 1;
                                    }

                                    /* Personaliza el fondo opaco del modal */
                                    .modal-backdrop {
                                        background-color: rgba(0, 0, 0, 0.7); /* Cambia la opacidad */
                                        transition: opacity 0.3s ease-in-out; /* Añade una transición suave */
                                    }

                                    /* Asegúrate de que el modal tenga la transición suave también */
                                    .modal.fade .modal-dialog {
                                        transition: transform 0.3s ease-out;
                                    }

                                </style>
                            <div class="modal fade" id="modalFaltaInfoSeccion3" tabindex="-1" role="dialog" aria-labelledby="modalFaltaInfoSeccion3Label" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered custom-modal" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalFaltaInfoSeccion3Label">Falta Información - Sección 3</h5>
                                            <!-- Botón de cerrar modal -->
                                            <button type="button" class="close" id="closeModalFaltaInfoSeccion3" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div>
                                                @if ($tipo ==="editar")
                                                    @if($estadoIniciativa->isNotEmpty())
                                                        @foreach($estadoIniciativa as $motivo)
                                                            @if ($motivo->seccion == 3)
                                                                @if ($motivo->estado === 0)
                                                                    <div class="card mb-2" style="max-width: 20rem; padding: 0.5rem; margin-top: 15px; background-color: #6c757d; color: white;">
                                                                        <div class="card-body p-2">
                                                                            <h5 class="card-title" style="font-size: 1.25rem;">Estado: Pendiente</h5>
                                                                            <p class="card-text" style="font-size: 1rem;">
                                                                                Comentario: {{ $motivo->motivo }}
                                                                            </p>
                                                                            <p class="card-meta" style="font-size: 0.875rem; margin-top: 0.5rem;">
                                                                                Comentario ingresado por: <strong>{{ $motivo->usua_nickname }}</strong><br>
                                                                                Fecha de registro: <em>{{ $motivo->fecha_registro }}</em>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                @elseif ($motivo->estado === 1)
                                                                    <div class="card mb-2" style="max-width: 20rem; padding: 0.5rem; margin-top: 15px; background-color: #007219; color: white;">
                                                                        <div class="card-body p-2">
                                                                            <h5 class="card-title" style="font-size: 1.25rem;">Estado: Aprobado</h5>
                                                                            <p class="card-text" style="font-size: 1rem;">
                                                                                Comentario: {{ $motivo->motivo }}
                                                                            </p>
                                                                            @if($motivo->usua_nickname_corrector!=null or $motivo->usua_nickname_corrector != '')
                                                                                <p class="card-meta" style="font-size: 0.875rem; margin-top: 0.3rem;">
                                                                                    Comentario ingresado por: <strong>{{ $motivo->usua_nickname }}</strong><br>
                                                                                    Fecha de registro: <em>{{ $motivo->fecha_registro }}</em>
                                                                                </p>
                                                                                <p class="card-meta" style="font-size: 0.875rem; margin-top: 0.3rem">
                                                                                    Corregido por: <strong>{{ $motivo->usua_nickname_corrector }}</strong><br>
                                                                                    Fecha de validación: <em>{{ $motivo->fecha_correccion }}</em>
                                                                                </p>
                                                                                <p class="card-meta" style="font-size: 0.875rem; margin-top: 0.3rem;">
                                                                                    Validado por: <strong>{{ $motivo->usua_nickname_validador }}</strong><br>
                                                                                    Fecha de validación: <em>{{ $motivo->fecha_validacion }}</em>
                                                                                </p>
                                                                            @else
                                                                                <p class="card-meta" style="font-size: 0.875rem; margin-top: 0.3rem;">
                                                                                    Comentario ingresado por: <strong>{{ $motivo->usua_nickname }}</strong><br>
                                                                                    Fecha de validación: <em>{{ $motivo->fecha_validacion }}</em>
                                                                                </p>
                                                                                <p class="card-meta" style="font-size: 0.875rem; margin-top: 0.3rem">
                                                                                    Validado por: <strong>{{ $motivo->usua_nickname_validador }}</strong><br>
                                                                                    Fecha de validación: <em>{{ $motivo->fecha_validacion }}</em>
                                                                                </p>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @elseif ($motivo->estado === 2)
                                                                    <div class="card mb-2" style="max-width: 20rem; padding: 0.5rem; margin-top: 15px; background-color: #708a00; color: white;">
                                                                        <div class="card-body p-2">
                                                                            <h5 class="card-title" style="font-size: 1.25rem;">Estado: Corregido</h5>
                                                                            <p class="card-text" style="font-size: 1rem;">
                                                                                Comentario: {{ $motivo->motivo }}
                                                                            </p>
                                                                            <p class="card-meta" style="font-size: 0.875rem; margin-top: 0.5rem;">
                                                                                Comentario ingresado por: <strong>{{ $motivo->usua_nickname }}</strong><br>
                                                                                Fecha de registro: <em>{{ $motivo->fecha_registro }}</em>
                                                                            </p>
                                                                            <p class="card-meta" style="font-size: 0.875rem; margin-top: 0.3rem;">
                                                                                Corregido por: <strong>{{ $motivo->usua_nickname_corrector }}</strong><br>
                                                                                Fecha de corrección: <em>{{ $motivo->fecha_correccion }}</em>
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endif
                                            </div>
                                            @if (!$estadoIniciativa->filter(fn($item) => $item->seccion == 3)->contains('estado', 1))
                                                @if (Session::has('admin'))
                                                    <p>Por favor, ingresa el motivo de la falta de información:</p>
                                                    <form id="formFaltaInfoSeccion3" action="{{ url('/admin/iniciativas/'.$inic_codigo.'/seccion/3/falta-info') }}" method="POST">
                                                        @csrf
                                                        <textarea name="motivo" class="form-control auto-expand" placeholder="Motivo de la falta de información" rows="3" required></textarea>
                                                    </div>
                                                    <div class="modal-footer bg-whitesmoke br" style="display: flex; justify-content: center; gap: 10px;">
                                                    <!-- Botón "Guardar Falta Información" con color naranjo y ocupando todo el ancho -->
                                                    <button type="submit" class="btn h-100" style="background-color: #FFA500; color: white;">Guardar falta información</button>
                                                    </form>
                                                    <!-- Botón "Validar Sección" con color verde y ocupando todo el ancho -->
                                                    @if (!$estadoIniciativa->where('seccion', 3)->isEmpty())
                                                        <form action="{{ url('/admin/iniciativas/'.$inic_codigo.'/seccion/3/ok') }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn h-100" style="background-color: #28a745; color: white;">Validar Sección</button>
                                                        </form>
                                                    @endif
                                                @elseif (Session::has('digitador'))
                                                    @if($estadoIniciativa->isNotEmpty() &&
                                                        $estadoIniciativa->filter(fn($item) => $item->seccion == 3)->isNotEmpty() &&
                                                        !$estadoIniciativa->filter(fn($item) => $item->seccion == 3)->every(fn($item) => $item->estado === 2))
                                                        <form action="{{ url('/admin/iniciativas/'.$inic_codigo.'/seccion/3/corregido') }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn h-200" style="background-color: #28a745; color: white; width: -webkit-fill-available;">Comentarios Resueltos</button>
                                                        </form>
                                                    @else
                                                        <p class="text-center" style="margin-top:2rem;">No hay comentarios por corregir.</p>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- Fondo oscuro para cuando el modal está abierto -->
                        <div id="modalBackdrop" class="modal-backdrop"></div>
                        <!-- Script para manejar los modales con animación y fondo oscuro -->
                        <script>
                            document.addEventListener("input", function(event) {
                                if (event.target.classList.contains("auto-expand")) {
                                    const textarea = event.target;
                                    textarea.style.height = "auto";
                                    textarea.style.height = (textarea.scrollHeight) + "px";
                                }
                            });

                            function showModal(modalId) {
                                const modal = document.getElementById(modalId);
                                const backdrop = document.getElementById("modalBackdrop");

                                // Mostrar el fondo oscuro con transición
                                backdrop.classList.add("show");

                                // Mostrar el modal con transición
                                modal.style.display = "block";
                                setTimeout(() => {
                                    modal.classList.add("show");
                                }, 10);
                                document.body.classList.add("modal-open");

                                // Cerrar al hacer clic fuera del modal
                                modal.addEventListener("click", function(event) {
                                    if (event.target === modal) {
                                        hideModal(modalId);
                                    }
                                });
                            }

                            function hideModal(modalId) {
                                const modal = document.getElementById(modalId);
                                const backdrop = document.getElementById("modalBackdrop");

                                // Ocultar el modal
                                modal.classList.remove("show");
                                setTimeout(() => {
                                    modal.style.display = "none";
                                    document.body.classList.remove("modal-open");
                                }, 400);

                                // Ocultar el fondo oscuro con transición
                                backdrop.classList.remove("show");
                            }

                            // Verificar si el botón existe antes de añadir los event listeners
                            const btnFaltaInfoSeccion3 = document.getElementById("btnFaltaInfoSeccion3");
                            const closeModalFaltaInfoSeccion3 = document.getElementById("closeModalFaltaInfoSeccion3");
                            const cancelModalFaltaInfoSeccion3 = document.getElementById("cancelModalFaltaInfoSeccion3");

                            if (btnFaltaInfoSeccion3) {
                                btnFaltaInfoSeccion3.addEventListener("click", function() {
                                    showModal("modalFaltaInfoSeccion3");
                                });
                            }

                            if (closeModalFaltaInfoSeccion3) {
                                closeModalFaltaInfoSeccion3.addEventListener("click", function() {
                                    hideModal("modalFaltaInfoSeccion3");
                                });
                            }

                            if (cancelModalFaltaInfoSeccion3) {
                                cancelModalFaltaInfoSeccion3.addEventListener("click", function() {
                                    hideModal("modalFaltaInfoSeccion3");
                                });
                            }
                        </script>

                            <div class="row p-4">
                                <div class="col-xl-6 col-md-6 col-lg-6">
                                    <div class="col-xl-6 col-md-6 col-lg-6">
                                        <h5>Sección 4 - Resultados esperados</h5>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-3 col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <label>Cuantificación</label> <label for=""
                                                    style="color: red;">*</label>
                                                <input type="number" class="form-control" id="cuantificacion"
                                                    name="cuantificacion" autocomplete="off" min="0">
                                            </div>
                                        </div>
                                        <div class="col-xl-7 col-md-7 col-lg-7">
                                            <div class="form-group">
                                                <label>Resultado esperado</label> <label for=""
                                                    style="color: red;">*</label>
                                                <input type="text" class="form-control" id="resultado"
                                                    name="resultado" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-2 col-lg-2" style="position: relative;">
                                            <button type="button" class="btn btn-primary waves-effect"
                                                onclick="agregarResultado()"><i class="fas fa-plus"></i></button>
                                        </div>
                                        <div class="col-xl-12 col-md-12 col-lg-12 text-center" id="div-alert-resultado">
                                        </div>
                                    </div>
                                    <div class="card" id="card-tabla-resultados" style="display: none;">
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-md">
                                                    <tr>
                                                        <th>Cuantificación</th>
                                                        <th>Resultado</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                    <tbody id="body-tabla-resultados">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <input type="hidden" id="iniciativa" name="iniciativa"
                                            value="{{ $iniciativa->inic_codigo }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-12 col-log-12" style="padding: 30px;">
                                    <div class="d-flex justify-content-between">
                                            <!-- Botones a la izquierda -->
                                            {{-- TODOOCULTAR: CAMBIAR DE OCULTAR A EDITAR -> $tipo === "editar" --}}
                                            @if ($tipo === "editar")
                                                @if(Session::has('admin') or Session::has('digitador'))
                                                    @if($estadoIniciativa->isNotEmpty())
                                                        {{-- Filtramos la colección para obtener los registros de la sección 2 --}}
                                                        @php
                                                            $motivosSeccion4 = $estadoIniciativa->where('seccion', 4);
                                                            $motivosFaltaInfoSeccion4 = $motivosSeccion4->where('estado',0)->count(); // Contar motivos no validados
                                                            $motivosCorregidosSeccion4 = $motivosSeccion4->where('estado',2)->count();
                                                            $estadoSeccion4 = $motivosSeccion4->first(); // Obtener el primer registro de la sección
                                                        @endphp
                                                        {{-- @dd($estadoSeccion3) --}}
                                                        {{-- Si existe un registro para la sección 3 --}}
                                                        @if($estadoSeccion4)
                                                            {{-- Si el estado de la sección 3 es 1, muestra el botón Validado --}}
                                                            @if($estadoSeccion4->estado == 1)
                                                                <div>
                                                                    <button type="button" class="btn btn-success mr-1" id="btnFaltaInfoSeccion4">Validado</button>
                                                                </div>
                                                            @else
                                                                <div>
                                                                    @if ($motivosFaltaInfoSeccion4 > 0)
                                                                        <button type="button" class="btn btn-warning mr-1" id="btnFaltaInfoSeccion4">
                                                                            Falta Información ({{ $motivosFaltaInfoSeccion4 }})
                                                                        </button>
                                                                    @elseif($motivosCorregidosSeccion4 >= 1)
                                                                        <button type="button" class="btn btn-warning mr-1" id="btnFaltaInfoSeccion4">
                                                                            Resueltos ({{ $motivosCorregidosSeccion4 }})
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        @else
                                                            {{-- Si no existe un registro para la sección 2, mostramos el botón Falta Información --}}
                                                            <div class="d-flex align-items-center">
                                                                <button type="button" class="btn btn-warning mr-2" id="btnFaltaInfoSeccion4">Falta Información</button>
                                                                @if (Session::has('admin'))
                                                                    @if ($motivosSeccion4->where('seccion', 4)->isEmpty())
                                                                        <form action="{{ url('/admin/iniciativas/'.$inic_codigo.'/seccion/4/ok') }}" method="POST">
                                                                            @csrf
                                                                            <button type="submit" class="btn" style="background-color: #28a745; color: white;">Validar Sección</button>
                                                                        </form>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        @endif
                                                    @else
                                                        {{-- Si no hay motivos registrados para la sección --}}
                                                        <div class="d-flex align-items-center">
                                                            <button type="button" class="btn btn-warning mr-2" id="btnFaltaInfoSeccion4">Falta Información</button>
                                                            @if (Session::has('admin'))
                                                                @if ($estadoIniciativa->where('seccion', 4)->isEmpty())
                                                                    <form action="{{ url('/admin/iniciativas/'.$inic_codigo.'/seccion/4/ok') }}" method="POST">
                                                                        @csrf
                                                                        <button type="submit" class="btn" style="background-color: #28a745; color: white;">Validar Sección</button>
                                                                    </form>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif
                                            <div>

                                            </div>
                                            <div class="text-right">
                                                <strong>
                                                    <a href="{{ route('admin.editar.paso1', $iniciativa->inic_codigo) }}"
                                                        type="button" class="btn mr-1 waves-effect"
                                                        style="background-color:#042344; color:white"><i
                                                            class="fas fa-chevron-left"></i>
                                                        Paso anterior</a>
                                                </strong>
                                                @if($tipo === 'editar')
                                                    <a href="{{ route('admin.editar.paso3', $iniciativa->inic_codigo) }}"
                                                        type="button" class="btn btn-primary mr-1 waves-effect">
                                                        Paso siguiente <i class="fas fa-chevron-right"></i></a>
                                                @elseif($tipo === 'crear')
                                                    <a href="{{ route('admin.crear.paso3', $iniciativa->inic_codigo) }}"
                                                        type="button" class="btn btn-primary mr-1 waves-effect">
                                                        Paso siguiente <i class="fas fa-chevron-right"></i></a>
                                                @endif
                                            </div>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="row p-4">
                                <div class="col-xl-12 col-md-12 col-log-12">
                                    <div class="text-right">
                                        <strong>
                                            <a href="{{ route($role . '.editar.paso1', $iniciativa->inic_codigo) }}"
                                                type="button" class="btn mr-1 waves-effect"
                                                style="background-color:#042344; color:white"><i
                                                    class="fas fa-chevron-left"></i>
                                                Paso anterior</a>
                                        </strong>
                                        <a href="{{ route($role . '.editar.paso3', $iniciativa->inic_codigo) }}"
                                            type="button" class="btn btn-primary mr-1 waves-effect">
                                            Paso siguiente <i class="fas fa-chevron-right"></i></a>
                                    </div>
                                </div>
                            </div> --}}

                        </div>
                    </div>
                </div>

            </div>
    </section>

    @if($tipo === 'editar')
                <style>
                    .modal-body {
                        max-height: 50vh; /* Limitar la altura máxima del contenido al 80% de la altura de la pantalla */
                        overflow-y: auto; /* Habilitar el scroll vertical */
                        padding: 1rem; /* Asegurar un espacio interno adecuado */
                    }

                    .modal {
                        justify-content: center;
                        align-items: center;
                    }

                    .modal-dialog {
                        max-width: 90%; /* Ajustar el ancho del modal */
                        margin: auto; /* Centrar el modal */
                    }
                    .modal-open {
                        overflow: hidden;
                    }
                </style>
                <div class="modal fade" id="modalFaltaInfoSeccion4" tabindex="-1" role="dialog" aria-labelledby="modalFaltaInfoSeccion4Label" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered custom-modal" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalFaltaInfoSeccion4Label">Falta Información - Sección 4</h5>
                                <!-- Botón de cerrar modal -->
                                <button type="button" class="close" id="closeModalFaltaInfoSeccion4" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div>
                                    @if ($tipo ==="editar")
                                        @if($estadoIniciativa->isNotEmpty())
                                            @foreach($estadoIniciativa as $motivo)
                                                @if ($motivo->seccion == 4)
                                                    @if ($motivo->estado === 0)
                                                        <div class="card mb-2" style="max-width: 20rem; padding: 0.5rem; margin-top: 15px; background-color: #6c757d; color: white;">
                                                            <div class="card-body p-2">
                                                                <h5 class="card-title" style="font-size: 1.25rem;">Estado: Pendiente</h5>
                                                                <p class="card-text" style="font-size: 1rem;">
                                                                    Comentario: {{ $motivo->motivo }}
                                                                </p>
                                                                <p class="card-meta" style="font-size: 0.875rem; margin-top: 0.5rem;">
                                                                    Comentario ingresado por: <strong>{{ $motivo->usua_nickname }}</strong><br>
                                                                    Fecha de registro: <em>{{ $motivo->fecha_registro }}</em>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @elseif ($motivo->estado === 1)
                                                        <div class="card mb-2" style="max-width: 20rem; padding: 0.5rem; margin-top: 15px; background-color: #007219; color: white;">
                                                            <div class="card-body p-2">
                                                                <h5 class="card-title" style="font-size: 1.25rem;">Estado: Aprobado</h5>
                                                                <p class="card-text" style="font-size: 1rem;">
                                                                    Comentario: {{ $motivo->motivo }}
                                                                </p>
                                                                @if($motivo->usua_nickname_corrector!=null or $motivo->usua_nickname_corrector != '')
                                                                    <p class="card-meta" style="font-size: 0.875rem; margin-top: 0.3rem;">
                                                                        Comentario ingresado por: <strong>{{ $motivo->usua_nickname }}</strong><br>
                                                                        Fecha de registro: <em>{{ $motivo->fecha_registro }}</em>
                                                                    </p>
                                                                    <p class="card-meta" style="font-size: 0.875rem; margin-top: 0.3rem">
                                                                        Corregido por: <strong>{{ $motivo->usua_nickname_corrector }}</strong><br>
                                                                        Fecha de corregido: <em>{{ $motivo->fecha_correccion }}</em>
                                                                    </p>
                                                                    <p class="card-meta" style="font-size: 0.875rem; margin-top: 0.3rem;">
                                                                        Validado por: <strong>{{ $motivo->usua_nickname_validador }}</strong><br>
                                                                        Fecha de validación: <em>{{ $motivo->fecha_validacion }}</em>
                                                                    </p>
                                                                @else
                                                                    <p class="card-meta" style="font-size: 0.875rem; margin-top: 0.3rem;">
                                                                        Comentario ingresado por: <strong>{{ $motivo->usua_nickname }}</strong><br>
                                                                        Fecha de validación: <em>{{ $motivo->fecha_validacion }}</em>
                                                                    </p>
                                                                    <p class="card-meta" style="font-size: 0.875rem; margin-top: 0.3rem">
                                                                        Validado por: <strong>{{ $motivo->usua_nickname_validador }}</strong><br>
                                                                        Fecha de validación: <em>{{ $motivo->fecha_validacion }}</em>
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @elseif ($motivo->estado === 2)
                                                        <div class="card mb-2" style="max-width: 20rem; padding: 0.5rem; margin-top: 15px; background-color: #708a00; color: white;">
                                                            <div class="card-body p-2">
                                                                <h5 class="card-title" style="font-size: 1.25rem;">Estado: Corregido</h5>
                                                                <p class="card-text" style="font-size: 1rem;">
                                                                    Comentario: {{ $motivo->motivo }}
                                                                </p>
                                                                <p class="card-meta" style="font-size: 0.875rem; margin-top: 0.5rem;">
                                                                    Comentario ingresado por: <strong>{{ $motivo->usua_nickname }}</strong><br>
                                                                    Fecha de registro: <em>{{ $motivo->fecha_registro }}</em>
                                                                </p>
                                                                <p class="card-meta" style="font-size: 0.875rem; margin-top: 0.3rem;">
                                                                    Corregido por: <strong>{{ $motivo->usua_nickname_corrector }}</strong><br>
                                                                    Fecha de corrección: <em>{{ $motivo->fecha_correccion }}</em>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    @endif
                                </div>
                                @if (!$estadoIniciativa->filter(fn($item) => $item->seccion == 4)->contains('estado', 1))
                                    @if (Session::has('admin'))
                                        <p>Por favor, ingresa el motivo de la falta de información:</p>
                                        <form id="formFaltaInfoSeccion4" action="{{ url('/admin/iniciativas/'.$inic_codigo.'/seccion/4/falta-info') }}" method="POST">
                                            @csrf
                                            <textarea name="motivo" class="form-control auto-expand" placeholder="Motivo de la falta de información" rows="3" required></textarea>
                                        </div>
                                        <div class="modal-footer bg-whitesmoke br" style="display: flex; justify-content: center; gap: 10px;">
                                        <!-- Botón "Guardar Falta Información" con color naranjo y ocupando todo el ancho -->
                                        <button type="submit" class="btn h-100" style="background-color: #FFA500; color: white;">Guardar falta información</button>
                                        </form>
                                        <!-- Botón "Validar Sección" con color verde y ocupando todo el ancho -->
                                        @if (!$estadoIniciativa->where('seccion', 4)->isEmpty())
                                            <form action="{{ url('/admin/iniciativas/'.$inic_codigo.'/seccion/4/ok') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn h-100" style="background-color: #28a745; color: white;">Validar Sección</button>
                                            </form>
                                        @endif
                                    @elseif (Session::has('digitador'))
                                        @if($estadoIniciativa->isNotEmpty() &&
                                            $estadoIniciativa->filter(fn($item) => $item->seccion == 4)->isNotEmpty() &&
                                            !$estadoIniciativa->filter(fn($item) => $item->seccion == 4)->every(fn($item) => $item->estado === 2))
                                            <form action="{{ url('/admin/iniciativas/'.$inic_codigo.'/seccion/4/corregido') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn h-200" style="background-color: #28a745; color: white; width: -webkit-fill-available;">Comentarios Resueltos</button>
                                            </form>
                                        @else
                                            <p class="text-center" style="margin-top:2rem;">No hay comentarios por corregir.</p>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- Fondo oscuro para cuando el modal está abierto -->
            <div id="modalBackdrop" class="modal-backdrop"></div>
            <!-- Script para manejar los modales con animación y fondo oscuro -->
            <script>
                document.addEventListener("input", function(event) {
                    if (event.target.classList.contains("auto-expand")) {
                        const textarea = event.target;
                        textarea.style.height = "auto";
                        textarea.style.height = (textarea.scrollHeight) + "px";
                    }
                });

                function showModal(modalId) {
                    const modal = document.getElementById(modalId);
                    const backdrop = document.getElementById("modalBackdrop");

                    // Mostrar el fondo oscuro con transición
                    backdrop.classList.add("show");

                    // Mostrar el modal con transición
                    modal.style.display = "block";
                    setTimeout(() => {
                        modal.classList.add("show");
                    }, 10);
                    document.body.classList.add("modal-open");

                    // Cerrar al hacer clic fuera del modal
                    modal.addEventListener("click", function(event) {
                        if (event.target === modal) {
                            hideModal(modalId);
                        }
                    });
                }

                function hideModal(modalId) {
                    const modal = document.getElementById(modalId);
                    const backdrop = document.getElementById("modalBackdrop");

                    // Ocultar el modal
                    modal.classList.remove("show");
                    setTimeout(() => {
                        modal.style.display = "none";
                        document.body.classList.remove("modal-open");
                    }, 400);

                    // Ocultar el fondo oscuro con transición
                    backdrop.classList.remove("show");
                }

                // Verificar si el botón existe antes de añadir los event listeners
                const btnFaltaInfoSeccion4 = document.getElementById("btnFaltaInfoSeccion4");
                const closeModalFaltaInfoSeccion4 = document.getElementById("closeModalFaltaInfoSeccion4");
                const cancelModalFaltaInfoSeccion4 = document.getElementById("cancelModalFaltaInfoSeccion4");

                if (btnFaltaInfoSeccion4) {
                    btnFaltaInfoSeccion4.addEventListener("click", function() {
                        showModal("modalFaltaInfoSeccion4");
                    });
                }

                if (closeModalFaltaInfoSeccion4) {
                    closeModalFaltaInfoSeccion4.addEventListener("click", function() {
                        hideModal("modalFaltaInfoSeccion4");
                    });
                }

                if (cancelModalFaltaInfoSeccion4) {
                    cancelModalFaltaInfoSeccion4.addEventListener("click", function() {
                        hideModal("modalFaltaInfoSeccion4");
                    });
                }
            </script>
            <!-- Estilos personalizados para los modales y el fondo oscuro -->
            <style>
                .custom-modal {
                    max-width: 400px;
                    height: 500px;
                }
                .modal-backdrop {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0); /* Fondo transparente inicialmente */
                    opacity: 0;
                    transition: opacity 0.4s ease-in-out;
                    z-index: 1040; /* Aseguramos que el fondo esté por debajo del modal */
                    display: none; /* Inicialmente oculto */
                }
                .modal-backdrop.show {
                    display: block; /* Mostrar cuando se activa */
                    opacity: 0.5; /* Nivel de opacidad deseado */
                }
                .fade {
                    opacity: 0;
                    transition: opacity 0.4s ease-in-out;
                }
                .fade.show {
                    opacity: 1;
                }

                /* Personaliza el fondo opaco del modal */
                .modal-backdrop {
                    background-color: rgba(0, 0, 0, 0.7); /* Cambia la opacidad */
                    transition: opacity 0.3s ease-in-out; /* Añade una transición suave */
                }

                /* Asegúrate de que el modal tenga la transición suave también */
                .modal.fade .modal-dialog {
                    transition: transform 0.3s ease-out;
                }
            </style>

    <div class="modal fade" id="modalCrearSocio" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Nuevo socio/a comunitario/a</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route($role . '.crear.socios') }} " method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nombre del socio/a comunitario/a</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-pen-nib"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="" required
                                    autocomplete="off">
                            </div>
                            @if ($errors->has('nombre'))
                                <div class="alert alert-warning alert-dismissible show fade mt-2 text-center"
                                    style="width:100%">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        <strong>{{ $errors->first('nombre') }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Grupo de interés</label>
                            <div class="input-group">
                                <select class="form-control select2" required style="width: 100%" id="grupointres" name="grupo" onchange="cargarSubgrupos()">
                                    <option value="" disabled selected>Seleccione...</option>
                                    @foreach ($grupos as $grupo)
                                        <option value="{{ $grupo->grin_codigo }}">
                                            {{ $grupo->grin_nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('grupo'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2 text-center"
                                        style="width:100%">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('grupo') }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Subgrupo de interés</label>
                            <div class="input-group">
                                <select class="form-control select2" required style="width: 100%" id="subgrupo2" name="subgrupo2">
                                    <option value="" disabled selected>Seleccione...</option>

                                </select>
                                @if ($errors->has('subgrupo'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2 text-center"
                                        style="width:100%">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('subgrupo') }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group" style="">
                            <label>Domicilio del socio/a comunitario/a</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" required id="domicilio" name="domicilio"
                                    value="" autocomplete="off">
                            </div>
                            @if ($errors->has('domicilio'))
                                <div class="alert alert-warning alert-dismissible show fade mt-2 text-center"
                                    style="width:100%">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        <strong>{{ $errors->first('domicilio') }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Nombre de la contraparte</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-pen-nib"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" required id="nombre_contraparte"
                                    name="nombre_contraparte" value="" autocomplete="off">
                            </div>
                            @if ($errors->has('nombre_contraparte'))
                                <div class="alert alert-warning alert-dismissible show fade mt-2 text-center"
                                    style="width:100%">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        <strong>{{ $errors->first('nombre_contraparte') }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Teléfono de la contraparte</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" required id="telefono" name="telefono"
                                    value="" autocomplete="off">
                            </div>
                            @if ($errors->has('telefono'))
                                <div class="alert alert-warning alert-dismissible show fade mt-2 text-center"
                                    style="width:100%">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        <strong>{{ $errors->first('telefono') }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Correo de la contraparte</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="far fa-envelope"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="email" required name="email" value=""
                                    autocomplete="off">
                            </div>
                            @if ($errors->has('email'))
                                <div class="alert alert-warning alert-dismissible show fade mt-2 text-center"
                                    style="width:100%">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>
                        {{-- <label>Sedes Asociadas</label>
                        <div class="input-group">
                            <select class="form-control select2" style="width: 100%" id="sedesT" name="sedesT[]"
                                multiple>
                                <option value="" disabled>Seleccione...</option>
                                @foreach ($sedesT as $sede)
                                    <option value="{{ $sede->sede_codigo }}">{{ $sede->sede_nombre }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('sedesT'))
                                <div class="alert alert-warning alert-dismissible show fade mt-2 text-center"
                                    style="width:100%">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        <strong>{{ $errors->first('sedesT') }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="form-group" style="width: 100%">
                            <div class="pretty p-switch p-fill">
                                <input type="checkbox" id="nacional" name="nacional" />
                                <div class="state p-success">
                                    <label><strong>Asociar a todas las sedes (Socio nacional)</strong></label>
                                </div>
                            </div>
                        </div> --}}
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary waves-effect"
                                style="margin-top: 20px">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
                                        <h6>INVI Total</h6>
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('/js/admin/iniciativas/INVI.js') }}"></script>
    <script>
        function cargarSubgrupos() {
            var grupo = $('#grupointres').val()
            $.ajax({
                url: window.location.origin + '/' + @json($role)+'/socios/listar-subgrupos',
                type: 'POST',
                dataType: 'json',

                data: {
                    _token: '{{ csrf_token() }}',
                    grin_codigo: grupo
                },
                success: function(data) {
                    console.log('subgrupos');
                    console.log(data)
                    $('#subgrupo2').empty();
                    $.each(data, function(key, value) {
                        $('#subgrupo2').append(
                            `<option value="${value.sugr_codigo}">${value.sugr_nombre}</option>`
                        );
                    });
                }
            });


        }
        function cargarSubgrupos2() {
            var grupo = $('#grupo').val()
            $.ajax({
                url: window.location.origin + '/' + @json($role)+'/socios/listar-subgrupos',
                type: 'POST',
                dataType: 'json',

                data: {
                    _token: '{{ csrf_token() }}',
                    grin_codigo: grupo
                },
                success: function(data) {
                    console.log(data)
                    $('#subgrupo2').empty();
                    $.each(data, function(key, value) {
                        $('#subgrupo2').append(
                            `<option value="${value.sugr_codigo}">${value.sugr_nombre}</option>`
                        );
                    });
                }
            });


        }
        $(document).ready(function() {
            $('#idIniciativa').hide();
            escuelasBySedesPaso2();
            listarInterno();
            modificar();
            sociosBySubgrupos();
            listarExterno();
            listarResultados();
        });

        function getURLParams(url) {
            let params = {};
            new URLSearchParams(url.replace(/^.*?\?/, '?')).forEach(function(value, key) {
                params[key] = value
            });
            return params;
        }

        function listarResultados() {
            var inic_codigo = $('#iniciativa').val();
            var datosResultados, fila, alertError;
            $('#div-alert-resultado').html('');

            // TODO: petición para listar resultados asociados a la iniciativa
            $.ajax({
                type: 'GET',
                url: window.location.origin + '/' + @json($role)+'/iniciativa/listar-resultados',

                data: {
                    _token: '{{ csrf_token() }}',
                    iniciativa: inic_codigo
                },
                success: function(resListar) {
                    respuesta = JSON.parse(resListar);
                    console.log(respuesta);

                    $('#body-tabla-resultados').empty();

                    if (!respuesta.estado) {
                        if (respuesta.resultado != '') {
                            alertError =
                                `<div class="alert alert-danger alert-dismissible show fade mb-3"><div class="alert-body"><button class="close" data-dismiss="alert"><span>&times;</span></button><strong>${respuesta.resultado}</strong></div></div>`;
                            $('#div-alert-resultado').html(alertError);
                        }
                        $('#card-tabla-resultados').hide();
                        return;
                    }

                    datosResultados = respuesta.resultado;
                    datosResultados.forEach(registro => {
                        fila = '<tr>' +
                            '<td>' + registro.resu_cuantificacion_inicial + '</td>' +
                            '<td>' + registro.resu_nombre + '</td>' +
                            '<td>' +
                            '<button type="button" class="btn btn-icon btn-warning" onclick="mostrarModalEditar(' +
                            registro.resu_codigo + ', `' + registro.resu_nombre + '`, ' + registro.resu_cuantificacion_inicial +
                            ')"><i class="fas fa-edit"></i></button>' +
                            '<button type="button" class="btn btn-icon btn-danger" onclick="eliminarResultado(' +
                            registro.resu_codigo + ', ' + registro.inic_codigo +
                            ')"><i class="fas fa-trash"></i></button>' +
                            '</td>' +
                            '</tr>';
                        $('#body-tabla-resultados').append(fila);
                    });
                    $('#card-tabla-resultados').show();

                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        window.mostrarModalEditar = function(resuCodigo, resuNombre, resuCuantificacionInicial) {
            resuCodigoParaEditar = resuCodigo;
            $('#resu_codigo').val(resuCodigo);
            $('#resu_inic_codigo').val($('#iniciativa').val());
            $('#resu_nombre').val(resuNombre);
            $('#resu_cuantificacion_inicial').val(resuCuantificacionInicial);
            $('#modalEditarResultado').modal('show');
        }

        function agregarResultado() {
            var inic_codigo = $('#iniciativa').val();
            console.log(inic_codigo);
            var resu_cantidad = $('#cuantificacion').val();
            var resu_nombre = $('#resultado').val();
            var alertError, alertExito;
            $('#div-alert-resultado').html('');

            // petición para guardar un resultado asociado a la iniciativa
            $.ajax({
                type: 'POST',
                url: window.location.origin + '/' + @json($role)+'/iniciativa/guardar-resultado',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    inic_codigo: inic_codigo,
                    cantidad: resu_cantidad,
                    nombre: resu_nombre
                },
                success: function(resGuardar) {
                    respuesta = JSON.parse(resGuardar);
                    if (!respuesta.estado) {
                        alertError =
                            `<div class="alert alert-warning alert-dismissible show fade"><div class="alert-body"><button class="close" data-dismiss="alert"><span>&times;</span></button><strong>${respuesta.resultado}</strong></div></div>`;
                        $('#div-alert-resultado').html(alertError);
                        return;
                    }
                    alertExito =
                        `<div class="alert alert-success alert-dismissible show fade"><div class="alert-body"><button class="close" data-dismiss="alert"><span>&times;</span></button><strong>${respuesta.resultado}</strong></div></div>`;
                    $('#cuantificacion').val('');
                    $('#resultado').val('');
                    listarResultados();
                    $('#div-alert-resultado').html(alertExito);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function eliminarResultado(resu_codigo, inic_codigo) {
            var alertError, alertExito;
            $('#div-alert-resultado').html('');

            // petición para eliminar un resultado asociada a la iniciativa
            $.ajax({
                type: 'POST',
                url: window.location.origin + '/' + @json($role)+'/iniciativa/eliminar-resultado',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    resu_codigo: resu_codigo,
                    inic_codigo: inic_codigo
                },
                success: function(resEliminar) {
                    respuesta = JSON.parse(resEliminar);
                    if (!respuesta.estado) {
                        alertError =
                            `<div class="alert alert-danger alert-dismissible show fade mb-3"><div class="alert-body"><button class="close" data-dismiss="alert"><span>&times;</span></button><strong>${respuesta.resultado}</strong></div></div>`;
                        $('#div-alert-resultado').html(alertError);
                        return;
                    }
                    alertExito =
                        `<div class="alert alert-success alert-dismissible show fade mb-3"><div class="alert-body"><button class="close" data-dismiss="alert"><span>&times;</span></button><strong>${respuesta.resultado}</strong></div></div>`;
                    listarResultados();
                    $('#div-alert-resultado').html(alertExito);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }


        function escuelasBySedesPaso2() {
            $('#sedes').on('change', function() {
                var sedesId = $(this).val();
                if (sedesId) {
                    $.ajax({
                        url: window.location.origin + '/' + @json($role)+'/iniciativas/obtener-escuelas/paso2',
                        type: 'POST',
                        dataType: 'json',

                        data: {
                            _token: '{{ csrf_token() }}',
                            sedes: sedesId,
                            inic_codigo: $('#idIniciativa').text()
                        },
                        success: function(data) {
                            $('#escuelas').empty();
                            $.each(data, function(key, value) {
                                $('#escuelas').append(
                                    `<option value="${value.escu_codigo}">${value.escu_nombre}</option>`
                                );
                            });
                        }
                    });
                } else {
                    $('#escuelas').empty();
                }
            })
        }

        function sociosBySubgrupos() {
            $('#subgrupo').on('change', function() {
                var subgrupoId = $(this).val();
                if (subgrupoId) {
                    $.ajax({
                        url: window.location.origin + '/' + @json($role)+'/iniciativas/obtener-socio/paso2',
                        type: 'POST',
                        dataType: 'json',

                        data: {
                            _token: '{{ csrf_token() }}',
                            sugr_codigo: subgrupoId,
                            // inic_codigo: $('#idIniciativa').text()
                        },
                        success: function(data) {
                            $('#socio').empty();
                            $.each(data, function(key, value) {
                                $('#socio').append(
                                    `<option value="${value.soco_codigo}">${value.soco_nombre_socio}</option>`
                                );
                            });
                        }
                    });
                } else {
                    $('#socio').empty();
                }
            })
        }

        function modificar() {

            $.ajax({
                type: 'POST',
                url: window.location.origin + '/' + @json($role)+'/actualizar/participantes-internos',
                data: {
                    _token: '{{ csrf_token() }}',
                    inic_codigo: $("#idIniciativa").text(),
                    sede_codigo: $("#sedes").val(),
                    escu_codigo: $("#escuelas").val(),
                    care_codigo: $("#carreras").val(),
                    pain_docentes: $("#ndocentes").val(),
                    pain_estudiantes: $("#nestudiantes").val(),
                    pain_funcionarios: $("#nfuncionarios").val(),

                    // pain_total: $("#ntotal").val()
                },
                success: function(resConsultar) {
                    respuesta = JSON.parse(resConsultar);
                    // console.log(respuesta)
                    $('#body-tabla-internos').empty();

                    datosInternos = respuesta.resultado;
                    datosInternos.forEach(registro => {
                        if (registro.pain_docentes == null) {
                            registro.pain_docentes = 0
                        }

                        if (registro.pain_estudiantes == null) {
                            registro.pain_estudiantes = 0
                        }

                        // if (registro.pain_total == null) {
                        //     registro.pain_total = 0
                        // }

                        // <td>${registro.pain_total}</td>
                        fila = `<tr>
                                <td>${registro.sede_nombre}</td>
                                <td>${registro.escu_nombre}</td>
                                <td>${registro.care_nombre}</td>
                                <td>${registro.pain_estudiantes}</td>
                                <td>${registro.pain_docentes}</td>
                                <td>${registro.pain_funcionarios}</td>
                                </tr>`
                        $('#body-tabla-internos').append(fila)
                        listarInterno()
                    })
                }


            })
        }

        function AgregarParticipantesExternos() {
            $.ajax({
                type: 'POST',
                url: window.location.origin + '/' + @json($role)+'/iniciativas/agregar/participantes-externos',
                data: {
                    _token: '{{ csrf_token() }}',
                    inic_codigo: $("#idIniciativa").text(),
                    soco_codigo: $("#socio").val(),
                    inpr_total: $("#npersonas").val(),

                },
                success: function(resConsultar) {
                    respuesta = JSON.parse(resConsultar);
                    $('#body-tabla-externos').empty();

                    datosInternos = respuesta.resultado;
                    listarExterno();
                }

            })
        }

        function listarExterno() {

            $.ajax({
                type: 'GET',
                url: window.location.origin + '/' + @json($role)+'/crear/iniciativa/listar-externos',
                data: {
                    _token: '{{ csrf_token() }}',
                    inic_codigo: $('#idIniciativa').text()
                },

                success: function(resConsultar) {
                    respuesta = JSON.parse(resConsultar);
                    $('#body-tabla-externos').empty();

                    datosInternos = respuesta.resultado;

                    console.log('externos');
                    datosInternos.forEach(registro => {

                        fila = `<tr>
                                <td>${registro.sugr_nombre}</td>
                                <td>${registro.soco_nombre_socio}</td>
                                <td>${registro.inpr_total}</td>
                                <td>
                                    <a href="javascript:void(0)" class="btn btn-icon btn-warning"
                                    onclick="editarSede(${registro.sugr_codigo}, ${registro.soco_codigo}, ${registro.inpr_total})" data-toggle="tooltip"
                                    data-placement="top" title="Editar"><i class="fas fa-edit"></i></a>
                                    <button type='button' onclick=eliminarExterno(${registro.inic_codigo},${registro.sugr_codigo},${registro.soco_codigo}) class= 'btn btn-icon btn-danger' ><i class="fas fa-trash"></i></button>
                                </td>
                                </tr>`
                        $('#body-tabla-externos').append(fila)
                    })
                }
            })
        }

        function eliminarExterno(inic_codigo, sugr_codigo, soco_codigo) {
            $.ajax({
                type: 'POST',
                url: window.location.origin + '/' + @json($role)+'/inicitiativa/eliminar-externo',
                data: {
                    _token: '{{ csrf_token() }}',
                    inic_codigo: inic_codigo,
                    sugr_codigo: sugr_codigo,
                    soco_codigo: soco_codigo
                },
                success: function(resEliminar) {
                    respuesta = JSON.parse(resEliminar);
                    listarExterno();
                },
                error: function(error) {
                    console.log(error);
                }
            })
        }

        function listarInterno() {
            console.log($('#idIniciativa').text())

            $.ajax({
                type: 'GET',
                url: window.location.origin + '/' + @json($role)+'/crear/iniciativa/listar-internos',
                data: {
                    _token: '{{ csrf_token() }}',
                    inic_codigo: $('#idIniciativa').text()
                },

                success: function(resConsultar) {
                    respuesta = JSON.parse(resConsultar);
                    console.log(respuesta);
                    $('#body-tabla-internos').empty();

                    datosInternos = respuesta.resultado;
                    datosInternos.forEach(registro => {
                        if (registro.pain_docentes == null) {
                            registro.pain_docentes = 0
                        }

                        if (registro.pain_estudiantes == null) {
                            registro.pain_estudiantes = 0
                        }
                        // if (registro.pain_total == null) {
                        //     registro.pain_total = 0
                        // }
                        // <td>${registro.pain_total}</td>
                        fila = `<tr>
                                    <td>${registro.sede_nombre}</td>
                                    <td>${registro.escu_nombre}</td>
                                    <td>${registro.care_nombre}</td>
                                    <td>${registro.pain_estudiantes}</td>
                                    <td>${registro.pain_docentes}</td>
                                    <td>${registro.pain_funcionarios}</td>
                                </tr>`
                        $('#body-tabla-internos').append(fila)
                    })
                }
            })
        }
        function editarSede(sugr_codigo, soco_nombre_socio, sugr_nombre, inpr_total) {
            // Llenar los campos del modal con los datos recibidos
            $('#soco_nombre_socio').val(soco_nombre_socio);
            $('#sugr_nombre').val(sugr_nombre);
            $('#inpr_total').val(inpr_total);



            $('#socio_inic_codigo').val($('#iniciativa').val());
            $('#soco_codigo_antiguo').val(soco_nombre_socio);
            // seleciconar en el select socioSeleccionado el valor del socio
            $('#socioSeleccionado').val(soco_nombre_socio).trigger('change');

            $('#personasBeneficiadas').val(sugr_nombre);

            // Mostrar el modal
            $('#modalEditarSede').modal('show');
        }


    </script>
    <script src="{{ asset('/js/admin/iniciativas/INVI.js') }}"></script>


    <!-- Modal de Edición -->
    <div class="modal fade" id="modalEditarSede" tabindex="-1" role="dialog" aria-labelledby="modalEditarSedeLabel" aria-hidden="true" style="z-index: 1050 !important;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarSedeLabel">Editar Socio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route($role . '.socio.paso2.actualizar') }} " method="POST" id="formEditarSede" action="#">
                         @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label>Nombre del socio</label>
                            <input hidden type="text" id="soco_codigo_antiguo" name="soco_codigo_antiguo">
                            <input hidden type="text" id="socio_inic_codigo" name="socio_inic_codigo">
                            <select class="form-control select2" id="socioSeleccionado" name="socioSeleccionado"
                            style="width: 100%">
                                @forelse ($socios as $socio)
                                    <option value="{{ $socio->soco_codigo }}">{{ $socio->soco_nombre_socio }}
                                    </option>
                                @empty
                                    <option value="-1">No existen registros</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Personas Beneficiadas</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-calculator"></i>
                                    </div>
                                </div>
                                <input type="number" class="form-control" id="personasBeneficiadas" name="personasBeneficiadas" autocomplete="off">
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary waves-effect">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Edición -->
<div class="modal fade" id="modalEditarResultado" tabindex="-1" role="dialog" aria-labelledby="modalEditarResultadoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarResultadoLabel">Editar Resultado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route($role . '.resultado.actualizar') }} " method="POST" id="formEditarResultado">
                    @method('PUT')
                    @csrf
                    <input id="resu_codigo" hidden name="resu_codigo">
                    <input id="resu_inic_codigo" hidden name="resu_inic_codigo">
                    <div class="form-group">
                        <label for="resu_nombre">Nombre del Resultado</label>
                        <input type="text" class="form-control" id="resu_nombre" name="resu_nombre">
                    </div>
                    <div class="form-group">
                        <label for="resu_cuantificacion_inicial">Cuantificación Inicial</label>
                        <input type="number" class="form-control" id="resu_cuantificacion_inicial" name="resu_cuantificacion_inicial">
                    </div>
                    <button type="submit" class="btn btn-primary" id="btnGuardarCambios">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
