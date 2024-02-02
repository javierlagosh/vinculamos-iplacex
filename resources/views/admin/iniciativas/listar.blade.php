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
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-3"></div>
                        <div class="col-xl-6">
                            @if (Session::has('exitoIniciativa'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoIniciativa') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
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
                            <h4>Listado de Iniciativas</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.iniciativa.listar') }}" method="GET">
                                <div class="row">
                                    <div class="col-xl-4 col-md-4 col-lg-4">
                                        <div class="form-group"><label for="sede">Sedes</label>
                                            <select name="sede" id="sede" class="form-control select2"
                                                style="width: 100%">
                                                <option value=""  selected>Seleccione...</option>
                                                <option value="all">Todas</option>
                                                @forelse ($sedes as $sede)
                                                    <option value="{{ $sede->sede_codigo }}"
                                                        {{ Request::get('sede') == $sede->sede_codigo ? 'selected' : '' }}>
                                                        {{ $sede->sede_nombre }}</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-md-4 col-lg-4">
                                        <div class="form-group"><label for="componente">Componentes</label>
                                            <select name="componente" id="componente" class="form-control select2"
                                                style="width: 100%">
                                                <option value=""  selected>Seleccione...</option>
                                                <option value="all">Todos</option>
                                                @forelse ($componentes as $componente)
                                                    <option value="{{ $componente->comp_codigo }}"
                                                        {{ Request::get('componente') == $componente->comp_codigo ? 'selected' : '' }}>
                                                        {{ $componente->comp_nombre }}</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-md-4 col-lg-4">
                                        <div class="form-group"><label for="anho">Año</label>
                                            <select name="anho" id="anho" class="form-control select2"
                                                style="width: 100%">
                                                <option value=""  selected>Seleccione...</option>
                                                <option value="all">Todos</option>
                                                @forelse ($anhos as $anho)
                                                    <option value="{{ $anho->inic_anho }}"
                                                        {{ Request::get('anho') == $anho->inic_anho ? 'selected' : '' }}>
                                                        {{ $anho->inic_anho }}</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <div class="mb-4">
                                                <button type="submit" class="btn btn-warning mr-1 waves-effect"
                                                    {{--  onclick="Filtro()" --}} style="width: 100%;color:white"><i
                                                        class="fas fa-search"></i> Filtrar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <div class="mb-4">
                                                <a type="button" class="btn btn-primary mr-1 waves-effect"
                                                    href="{{ route($role . '.iniciativa.listar') }}"
                                                    style="width: 100%;color:white"><i class="fas fa-broom"></i> Limpiar</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Componente</th>
                                            <th>Año</th>
                                            <th>Sedes</th>
                                            {{-- <th>Carreras</th> --}}
                                            <th>Estado</th>
                                            <th>Fecha de creación</th>
                                            <th style="width: 30%">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabla-iniciativas">
                                        @foreach ($iniciativas as $iniciativa)
                                            <tr>
                                                <td>{{ $iniciativa->inic_codigo }}</td>
                                                <td>{{ $iniciativa->inic_nombre }}</td>
                                                <td>{{ $iniciativa->comp_nombre }}</td>
                                                <td>{{ $iniciativa->inic_anho }}</td>
                                                <td>
                                                    @php
                                                        $sedesArray = explode('/', $iniciativa->sedes);
                                                    @endphp
                                                    @if (count($sedesArray) > 6)
                                                        Todas
                                                    @else
                                                        {{ $iniciativa->sedes }}
                                                    @endif
                                                </td>
                                                {{-- <td>{{ $iniciativa->carreras }}</td> --}}
                                                {{-- <td>
                                                    @php
                                                        $carrerasArray = explode(',', $iniciativa->carreras);
                                                    @endphp
                                                    @if (count($carrerasArray) > 24)
                                                        Todas
                                                    @else
                                                        {{ $iniciativa->carreras }}
                                                    @endif
                                                </td> --}}
                                                <td>
                                                    @php
                                                        $estadoBadges = [
                                                            1 => ['class' => 'light', 'icon' => 'history', 'text' => 'En revisión'],
                                                            2 => ['class' => 'info', 'icon' => 'play-circle', 'text' => 'En ejecución'],
                                                            3 => ['class' => 'success', 'icon' => 'lock', 'text' => 'Aceptada'],
                                                            4 => ['class' => 'info', 'icon' => 'info-circle', 'text' => 'Falta info'],
                                                            5 => ['class' => 'primary', 'icon' => 'pause-circle', 'text' => 'Cerrada'],
                                                            6 => ['class' => 'success', 'icon' => 'check-double', 'text' => 'Finalizada'],
                                                        ];
                                                    @endphp

                                                    <div
                                                        class="badge badge-{{ $estadoBadges[$iniciativa->inic_estado]['class'] }} badge-shadow">
                                                        <i
                                                            class="fas fa-{{ $estadoBadges[$iniciativa->inic_estado]['icon'] }}"></i>
                                                        {{ $estadoBadges[$iniciativa->inic_estado]['text'] }}
                                                    </div>
                                                </td>
                                                <td>{{ $iniciativa->inic_creado }}</td>
                                                <td>
                                                    <div class="dropdown d-inline">
                                                        <button class="btn btn-primary dropdown-toggle"
                                                            id="dropdownMenuButton2" data-toggle="dropdown"title="Opciones">
                                                            <i class="fas fa-cog"></i></button>
                                                        <div class="dropdown-menu dropright">

                                                            <a href="{{ route('admin.editar.paso1', $iniciativa->inic_codigo) }}"
                                                                class="dropdown-item has-icon"><i
                                                                    class="fas fa-edit"></i>Editar Iniciativa</a>
                                                            <a href="javascript:void(0)" class="dropdown-item has-icon"
                                                                onclick="eliminarIniciativa({{ $iniciativa->inic_codigo }})"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Eliminar">Eliminar Iniciativa<i
                                                                    class="fas fa-trash"></i></a>

                                                            <a href="{{ route('admin.iniciativas.detalles', $iniciativa->inic_codigo) }}"
                                                                class="dropdown-item has-icon" data-toggle="tooltip"
                                                                data-placement="top" title="Ver detalles"><i
                                                                    class="fas fa-eye"></i> Ver detalles</a>

                                                            <a href="javascript:void(0)" class="dropdown-item has-icon"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Calcular INVI"
                                                                onclick="calcularIndice({{ $iniciativa->inic_codigo }})"><i
                                                                    class="fas fa-tachometer-alt"></i> Calcular INVI</a>

                                                            <a href="{{ route('admin.evidencias.listar', $iniciativa->inic_codigo) }}"
                                                                class="dropdown-item has-icon" data-toggle="tooltip"
                                                                data-placement="top" title="Adjuntar evidencia"><i
                                                                    class="fas fa-paperclip"></i> Adjuntar evidencia</a>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown d-inline">
                                                        <button class="btn btn-primary dropdown-toggle"
                                                            id="dropdownMenuButton2"
                                                            data-toggle="dropdown"title="ingresar">
                                                            <i class="fas fa-plus-circle"></i> Ingresar</button>
                                                        <div class="dropdown-menu dropright">
                                                            <a href="{{ route('admin.cobertura.index', $iniciativa->inic_codigo) }}"
                                                                class="dropdown-item has-icon" data-toggle="tooltip"
                                                                data-placement="top" title="Ingresar cobertura"><i
                                                                    class="fas fa-users"></i> Ingresar cobertura</a>

                                                            <a href="{{ route('admin.resultados.listado', $iniciativa->inic_codigo) }}"
                                                                class="dropdown-item has-icon" data-toggle="tooltip"
                                                                data-placement="top" title="Ingresar resultado"><i
                                                                    class="fas fa-flag"></i> Ingresar resultados</a>

                                                            <a href="{{ route($role . '.evaluar.iniciativa', $iniciativa->inic_codigo) }}"
                                                                class="dropdown-item has-icon" data-toggle="tooltip"
                                                                data-placement="top" title="Evaluar iniciativa"><i
                                                                    class="fas fa-file-signature"></i> Evaluar
                                                                iniciativa</a>
                                                        </div>
                                                    </div>




                                                    {{-- <a href="{{ route('admin.cobertura.index', $iniciativa->inic_codigo) }}"
                                                        class="btn btn-icon btn-success" data-toggle="tooltip"
                                                        data-placement="top" title="Ingresar cobertura"><i
                                                            class="fas fa-users"></i></a>
                                                    <a href="{{ route('admin.resultados.listado', $iniciativa->inic_codigo) }}"
                                                        class="btn btn-icon btn-success" data-toggle="tooltip"
                                                        data-placement="top" title="Ingresar resultado"><i
                                                            class="fas fa-flag"></i></a> --}}

                                                    {{-- <a href="" class="btn btn-icon btn-success" data-toggle="tooltip"
                                                        data-placement="top" title="Ingresar resultado"><i
                                                            class="fas fa-flag"></i></a>

                                                    <a href="" class="btn btn-icon btn-success" data-toggle="tooltip"
                                                        data-placement="top" title="Evaluar iniciativa"><i
                                                            class="fas fa-file-signature"></i></a> --}}

                                                    {{-- <a href="{{ route($role . '.evaluar.iniciativa', $iniciativa->inic_codigo) }}"
                                                        class="btn btn-icon btn-success" data-toggle="tooltip"
                                                        data-placement="top" title="Evaluar iniciativa"><i
                                                            class="fas fa-file-signature"></i></a> --}}


                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
    <script src="{{ asset('/js/admin/iniciativas/INVI.js') }}"></script>
    <script>
        function eliminarIniciativa(inic_codigo) {
            $('#inic_codigo').val(inic_codigo);
            $('#modalEliminaIniciativa').modal('show');
        }


        function filtrarTablaxMecanismo() {
            const selectElement = document.querySelector('select[name="table-1_length"]');
            selectElement.selectedIndex = 3;
            const changeEvent = new Event('change', {
                bubbles: true
            });
            selectElement.dispatchEvent(changeEvent);

            const mecaSeleccionado = document.getElementById('mecanismo').value;
            const anoSeleccionado = document.getElementById('ano').value;
            const filtro2Seleccionado = document.getElementById('filtro2').value;
            const filtro3Seleccionado = document.getElementById('filtro3').value;

            console.log(filtro2Seleccionado);

            const filasTabla = document.querySelectorAll('#tabla-iniciativas tr');


            filasTabla.forEach(function(fila) {
                const mecaFila = fila.getAttribute('data-meca');
                const anoFila = fila.getAttribute('data-ano');
                const data_filtro2 = JSON.parse(fila.getAttribute('data-filtro2')); // Parsea JSON a objeto o array
                const data_filtro3 = JSON.parse(fila.getAttribute('data-filtro3')); // Parsea JSON a objeto o array

                const filtroMeca = mecaSeleccionado === '' || mecaSeleccionado === mecaFila;
                const filtroEstado = anoSeleccionado === '' || anoSeleccionado === anoFila;
                const resultado2 = filtro2Seleccionado === '' || data_filtro2.includes(filtro2Seleccionado);
                const resultado3 = filtro3Seleccionado === '' || data_filtro3.includes(filtro3Seleccionado);

                if (filtroMeca && filtroEstado && resultado2 && resultado3) {
                    fila.style.display = ''; // Mostrar la fila
                } else {
                    fila.style.display = 'none'; // Ocultar la fila
                }
                if (mecaSeleccionado === '' && anoSeleccionado === '' && filtro2Seleccionado === '' &&
                    filtro3Seleccionado === '') {
                    selectElement.selectedIndex = 0;
                    selectElement.dispatchEvent(changeEvent);
                    fila.style.display = 'table-row';
                }
            });
        }
    </script>
@endsection
