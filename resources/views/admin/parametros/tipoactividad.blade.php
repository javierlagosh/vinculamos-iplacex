@extends('admin.panel')

@section('contenido')
    <section class="section" style="font-size: 115%;">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            @if ($errors->has('tiac_nombre'))
                                <div class="alert alert-warning alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        @if ($errors->has('tiac_nombre'))
                                            <strong>{{ $errors->first('tiac_nombre') }}</strong><br>
                                        @endif

                                    </div>
                                </div>
                            @endif
                            @if (Session::has('errorTipoact'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorTipoact') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                            @if (Session::has('exitoTipoact'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoTipoact') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Listado de Tipos de actividad</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalCrearTipoact"><i class="fas fa-plus"></i> Nuevo Tipo de
                                    actividad</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1" style="font-size: 110%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre</th>

                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $contador = 0;
                                        ?>

                                        @foreach ($tipoact as $tiac)
                                            <?php
                                            $contador = $contador + 1;
                                            ?>
                                            <tr>
                                                <td>{{ $contador }}</td>
                                                <td>{{ $tiac->tiac_nombre }}</td>

                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-icon btn-danger"
                                                        onclick="eliminarTipoact({{ $tiac->tiac_codigo }})"
                                                        data-toggle="tooltip" data-placement="top"
                                                        title="Eliminar grupos de interés"><i class="fas fa-trash"></i></a>


                                                    <a href="javascript:void(0)" class="btn btn-icon btn-warning"
                                                        onclick="editarTipoact({{ $tiac->tiac_codigo }})"
                                                        data-toggle="tooltip" data-placement="top" title="Editar"><i
                                                            class="fas fa-edit"></i></a>

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

    <div class="modal fade" id="modalCrearTipoact" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Nuevo Grupo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.crear.tipoact') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nombre del tipo de actividad</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-pen-nib"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control @error('tiac_nombre') is-invalid @enderror"
                                    id="tiac_nombre" name="tiac_nombre" placeholder="" autocomplete="off"
                                    value="{{ old('tiac_nombre') }}">
                                @error('tiac_nombre')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Componente asociado</label>
                            <div class="input-group">
                                <select class="form-control @error('componente') is-invalid @enderror" id="componente"
                                    name="componente" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    @forelse ($componentes as $compo)
                                        <option style="font-size: 120%;" value="{{ $compo->comp_codigo }}">
                                            {{ $compo->comp_nombre }}</option>
                                    @empty
                                        <option value="-1">No existen registros</option>
                                    @endforelse
                                </select>
                                @error('componente')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mecanismos">Mecanismos asociados</label>
                            <div class="input-group">
                                <select name="mecanismos[]" id="mecasnimos" class="form-control select2" style="width: 100%"
                                    multiple>
                                    @forelse ($mecanismos as $mecanismo)
                                        <option value="{{$mecanismo->meca_codigo}}" {{ collect(old('mecanismos'), [])->contains($mecanismo->meca_codigo) ? 'selected' : '' }}>
                                        {{ $mecanismo->meca_nombre }}</option>
                                    @empty
                                        <option value="">No hay registros...</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <label style="display: block; text-align: center; width: 100%;">METAS POR SEDE</label>
                        <div class="row">
                            @foreach ($sedes as $sede)
                                <div class="col-6 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label>{{ $sede->sede_nombre }}</label>

                                        <div class="input-group" style="margin-top: 10px">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-calendar-check"></i>
                                                </div>
                                            </div>
                                            <input type="hidden" name="sede_codigo[]" value="{{ $sede->sede_codigo }}">
                                            <input type="number" class="form-control" id="{{ $sede->sede_codigo }}"
                                                name="metas[]" value="{!! old($sede->sede_codigo) !!}" autocomplete="off"
                                                placeholder="META">
                                        </div>
                                        @error('{{ $sede->sede_codigo }}')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary waves-effect">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    @foreach ($tipoact as $tiac)
        <div class="modal fade" id="modalEditartiac-{{ $tiac->tiac_codigo }}" tabindex="-1" role="dialog"
            aria-labelledby="modalEditartiac" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditartiac">Editar tipo de actividad</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.actualizar.tipoact', $tiac->tiac_codigo) }}" method="POST">
                            @method('PUT')
                            @csrf

                            <div class="form-group">
                                <label>Nombre del Tipo de actividad</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-pen-nib"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control @error('tiac_nombre') is-invalid @enderror"
                                        id="tiac_nombre" name="tiac_nombre" value="{{ $tiac->tiac_nombre }}"
                                        autocomplete="off">
                                    @error('tiac_nombre')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Componente asociado</label>
                                <div class="input-group">
                                    <select class="form-control @error('componente') is-invalid @enderror" id="componente"
                                        name="componente" required>
                                        <option value="" selected disabled>Seleccione...</option>
                                        @forelse ($componentes as $compo)
                                            <option style="font-size: 120%;" value="{{ $compo->comp_codigo }}"
                                                {{ $compo->comp_codigo == $tiac->comp_codigo ? 'selected' : '' }}>
                                                {{ $compo->comp_nombre }}
                                            </option>
                                        @empty
                                            <option value="-1">No existen registros</option>
                                        @endforelse
                                    </select>
                                    @error('componente')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="mecanismos">Mecanismos asociados</label>
                                <div class="input-group">
                                    <select name="mecanismos[]" id="mecasnimos" class="form-control select2"
                                        style="width: 100%" multiple>
                                        @forelse ($mecanismos as $mecanismo)
                                            <option value='{{ $mecanismo->meca_codigo }}'
                                                {{ in_array($mecanismo->meca_codigo, $mecanismos_actividades[$tiac->tiac_codigo] ?? []) ? 'selected' : '' }}>
                                                {{ $mecanismo->meca_nombre }}</option>

                                        @empty
                                            <option value=""> No hay datos </option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <label style="display: block; text-align: center; width: 100%;">METAS POR SEDE</label>
                            <div class="row">
                                @foreach ($sedes as $sede)
                                    <div class="col-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label>{{ $sede->sede_nombre }}</label>
                                            <div class="input-group" style="margin-top: 10px">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-calendar-check"></i>
                                                    </div>
                                                </div>

                                                <input type="hidden" name="sede_codigo[]"
                                                    value="{{ $sede->sede_codigo }}">

                                                @php
                                                    $metaExist = $tiac_metas
                                                        ->where('tiac_codigo', $tiac->tiac_codigo)
                                                        ->where('sede_codigo', $sede->sede_codigo)
                                                        ->pluck('tiacme_meta')
                                                        ->first();
                                                @endphp

                                                <input type="number" class="form-control" id="{{ $sede->sede_codigo }}"
                                                    name="metas[]" value="{{ $metaExist ?? '' }}" autocomplete="off"
                                                    placeholder="SIN META">

                                                @error($sede->sede_codigo)
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary waves-effect">Actualizar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach




    <div class="modal fade" id="modalEliminaTipoact" tabindex="-1" role="dialog" aria-labelledby="modalEliminar"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.eliminar.tipoact') }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEliminar">Eliminar Tipo de actividad</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-ban text-danger" style="font-size: 50px; color"></i>
                        <h6 class="mt-2">El Tipo de actividad dejará de existir dentro del sistema. <br> ¿Desea continuar
                            de todos
                            modos?</h6>
                        <input type="hidden" id="tiac_codigo" name="tiac_codigo" value="">
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="submit" class="btn btn-primary">Continuar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function eliminarTipoact(tiac_codigo) {
            $('#modalEliminaTipoact').find('#tiac_codigo').val(tiac_codigo);
            $('#modalEliminaTipoact').modal('show');
        }

        function editarTipoact(tiac_codigo) {
            $('#modalEditartiac-' + tiac_codigo).modal('show');
        }
    </script>

    <script>
        function eliminartiac(tiac_codigo) {
            $('#tiac_codigo').val(tiac_codigo);
            $('#modalEliminatiacram').modal('show');
        }

        function editartiac(tiac_codigo) {
            $('#modalEditartiacramas-' + tiac_codigo).modal('show');
        }

        function limpiarInputSocio() {
            const inputMetaSocios = document.querySelector('#div_socios input');
            inputMetaSocios.value = '';
        }

        function limpiarInputIni() {
            const inputMetaIniciativas = document.querySelector('#div_{{ $sede->sede_codigo }} input');
            inputMetaIniciativas.value = '';
        }

        function limpiarInputCarre() {
            const inputMetaCarreras = document.querySelector('#div_carreras input');
            inputMetaCarreras.value = '';
        }

        function limpiarInputAsig() {
            const inputMetaAsignatura = document.querySelector('#div_asignaturas input');
            inputMetaAsignatura.value = '';
        }

        function limpiarInputEstu() {
            const inputMetaEstudiantes = document.querySelector('#div_estudiantes input');
            inputMetaEstudiantes.value = '';
        }

        function limpiarInputDoce() {
            const inputMetaDocentes = document.querySelector('#div_docentes input');
            inputMetaDocentes.value = '';
        }

        function limpiarInputBene() {
            const inputMetaBeneficiarios = document.querySelector('#div_beneficiarios input');
            inputMetaBeneficiarios.value = '';
        }

        function limpiarInputEgre() {
            const inputMetaEgresados = document.querySelector('#div_egresados input');
            inputMetaEgresados.value = '';
        }
    </script>




    {{-- <link rel="stylesheet" href="{{ asset('/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('/js/page/datatables.js') }}"></script> --}}
@endsection
