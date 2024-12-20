@extends('admin.panel')

@section('contenido')
    <section class="section" style="font-size: 115%;">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            @if (
                                $errors->has('nombre') ||
                                $errors->has('director')
                                )
                            <div class="alert alert-warning alert-dismissible show fade mb-4 text-center">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    @if ($errors->has('nombre'))
                                        <strong>{{ $errors->first('nombre') }}</strong><br>
                                    @endif
                                    @if ($errors->has('director'))
                                        <strong>{{ $errors->first('director') }}</strong><br>
                                    @endif

                                </div>
                            </div>
                            @endif
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
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Listado de unidades ejecutoras</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalCrearEscuela"><i class="fas fa-plus"></i> Nueva Unidad ejecutora</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table  class="table table-striped" id="table-1" style="font-size: 110%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre</th>
                                            {{-- <th>Carreras</th> --}}
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $contador = 0;
                                        ?>
                                        @foreach ($escuelas as $escu)
                                            <?php
                                            $contador = $contador + 1;
                                            ?>
                                            <tr>
                                                <td>{{ $contador }}</td>
                                                <td>{{ $escu->escu_nombre }}</td>
                                                {{-- <td>{{ $escu->escu_carreras }}</td> --}}
                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-icon btn-warning"
                                                        onclick="editarEscu({{ $escu->escu_codigo }})" data-toggle="tooltip"
                                                        data-placement="top" title="Editar"><i class="fas fa-edit"></i></a>
                                                    <a href="javascript:void(0)" class="btn btn-icon btn-danger"
                                                        onclick="eliminarEscu({{ $escu->escu_codigo }})"
                                                        data-toggle="tooltip" data-placement="top" title="Eliminar"><i
                                                            class="fas fa-trash"></i></a>
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

    <div class="modal fade" id="modalCrearEscuela" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Nueva unidad ejecutora</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.crear.escuelas') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nombre de la unidad ejecutora</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-pen-nib"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder=""
                                    autocomplete="off">
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
                        </div>

                        <div class="form-group">
                            <label style="font-size: 110%">Sedes</label> {{-- <label for=""
                                style="color: red;">*</label> --}}
                            {{-- <input type="checkbox" id="selectAllEscuelas" style="margin-left: 60%"> <label
                                for="selectAllEscuelas">Todas</label> --}}
                            <select class="form-control select2" name="sedes[]" multiple=""
                                style="width: 100%" id="sedes">
                                @if (isset($sedes))
                                    @forelse ($sedes as $sede)
                                        <option value="{{ $sede->sede_codigo }}">
                                            {{ $sede->sede_nombre }}</option>
                                    @empty
                                        <option value="-1">No existen registros</option>
                                    @endforelse
                                @endif
                            </select>
                        </div>
                        {{-- <div class="form-group">
                            <label>Descripción de la escuela</label>
                            <div class="input-group">
                                <textarea rows="6" class="formbold-form-input" id="descripcion" name="descripcion" autocomplete="off"
                                    style="width:100%">{{ old('descripcion') }}</textarea>
                                @if ($errors->has('descripcion'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2 text-center"
                                        style="width:100%">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('descripcion') }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div> --}}
                        <div class="form-group" hidden>
                            <label>Director/a</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="director" name="director" placeholder="" value=" - "
                                    autocomplete="off">
                                @if ($errors->has('director'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2 text-center"
                                        style="width:100%">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('director') }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta Servicio Disciplinar</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="escu_meta_serv"
                                            name="escu_meta_serv" value="" placeholder="0"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta Extensión Académica</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="escu_meta_ext"
                                            name="escu_meta_ext" value="" placeholder="0"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta Consejo Consultivo</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="escu_meta_con"
                                            name="escu_meta_con" value="" placeholder="0"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta Red laboral</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="escu_meta_red"
                                            name="escu_meta_red" value="" placeholder="0"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="form-group">
                            <label>Institución</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-building"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="institucion" name="institucion"
                                    placeholder="" autocomplete="off">
                                @if ($errors->has('institucion'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2 text-center"
                                        style="width:100%">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('institucion') }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div> --}}
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary waves-effect">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach ($escuelas as $escu)
        <div class="modal fade" id="modalEditarEscuela-{{ $escu->escu_codigo }}" tabindex="-1" role="dialog"
            aria-labelledby="modalEditarEscuela" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarEscuela">Editar Unidad ejecutora</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.actualizar.escuelas', $escu->escu_codigo) }}" method="POST">
                            @method('PUT')
                            @csrf

                            <div class="form-group">
                                <label>Nombre de la unidad ejecutora</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-pen-nib"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="escu_nombre" name="escu_nombre"
                                        value="{{ $escu->escu_nombre }}" autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group" @if ($escu->escu_codigo == 12) hidden @endif>
                                <label style="font-size: 110%">Sedes</label> {{-- <label for=""
                                    style="color: red;">*</label> --}}
                                {{-- <input type="checkbox" id="selectAllEscuelas" style="margin-left: 60%"> <label
                                    for="selectAllEscuelas">Todas</label> --}}
                                <select class="form-control select2" name="sedes[]" multiple=""
                                    style="width: 100%" id="sedes">
                                    @if (isset($sedes))
                                        @forelse ($sedes as $sede)
                                            @php
                                                // Comprueba si $escu->escu_codigo y $sede->sede_codigo están en la misma fila de sedesxescuelas
                                                $relationExists = $sedesxescuelas->where('escu_codigo', $escu->escu_codigo)
                                                    ->where('sede_codigo', $sede->sede_codigo)
                                                    ->isNotEmpty();
                                            @endphp
                                            <option value="{{ $sede->sede_codigo }}" {{ $relationExists ? 'selected' : '' }}>
                                                {{ $sede->sede_nombre }}
                                            </option>
                                        @empty
                                            <option value="-1">No existen registros</option>
                                        @endforelse
                                    @endif
                                </select>
                            </div>

                            {{-- <div class="form-group">
                                <label>Descripción de la escuela</label>
                                <div class="input-group">
                                    <textarea rows="6" class="formbold-form-input" id="descripcion" name="descripcion" autocomplete="off"
                                    style="width:100%">{{ $escu->escu_descripcion }}</textarea>
                                </div>
                            </div> --}}
                            <div class="form-group" hidden>
                                <label>Director/a</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="escu_director" name="escu_director"
                                        value="{{ $escu->escu_director }}" autocomplete="off">
                                </div>
                            </div>

                            <div class="row">
                                @if ($escu->escu_codigo != 12)
                                <div class="col-6 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label>Meta servicio disciplinar</label>

                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-calendar-check"></i>
                                                </div>
                                            </div>
                                            <input type="number" class="form-control" id="escu_meta_serv"
                                                name="escu_meta_serv" value="{{$escu->escu_meta_serv}}" placeholder="0"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if ($escu->escu_codigo != 9)
                                <div class="col-6 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label>Meta extensión académica</label>

                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-calendar-check"></i>
                                                </div>
                                            </div>
                                            <input type="number" class="form-control" id="escu_meta_ext"
                                                name="escu_meta_ext" value="{{$escu->escu_meta_ext}}" placeholder="0"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="row">
                                @if ($escu->escu_codigo != 9)
                                <div class="col-6 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label>Meta Consejo consultivo</label>

                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-calendar-check"></i>
                                                </div>
                                            </div>
                                            <input type="number" class="form-control" id="escu_meta_con"
                                                name="escu_meta_con" value="{{$escu->escu_meta_con}}" placeholder="0"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if ($escu->escu_codigo == 12)
                                <div class="col-6 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label>Meta Red laboral</label>

                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-calendar-check"></i>
                                                </div>
                                            </div>
                                            <input type="number" class="form-control" id="escu_meta_red"
                                                name="escu_meta_red" value="{{$escu->escu_meta_red}}" placeholder="0"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                @endif
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

    <div class="modal fade" id="modalEliminarEscuela" tabindex="-1" role="dialog" aria-labelledby="modalEliminar"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.eliminar.escuelas') }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEliminar">Eliminar Unidad ejecutora</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-ban text-danger" style="font-size: 50px; color"></i>
                        <h6 class="mt-2">La Unidad ejecutora dejará de existir dentro del sistema. <br> ¿Desea continuar de todos
                            modos?</h6>
                        <input type="hidden" id="escu_codigo" name="escu_codigo" value="">
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
        function eliminarEscu(escu_codigo) {
            $('#escu_codigo').val(escu_codigo);
            $('#modalEliminarEscuela').modal('show');
        }

        function editarEscu(escu_codigo) {
            $('#modalEditarEscuela-' + escu_codigo).modal('show');
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
