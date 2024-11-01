@extends('admin.panel')

@section('contenido')
    <section class="section" style="font-size: 115%;">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            @if (Session::has('exitoAsignatura'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoAsignatura') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                            @if (Session::has('errorAsignatura'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorAsignatura') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Listado de Dispositivos</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalCrearCarrera"><i class="fas fa-plus"></i> Nuevo Dispositivo</button>
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
                                        @foreach ($dispositivos as $dispositivo)
                                            <?php
                                            $contador = $contador + 1;
                                            ?>
                                            <tr>
                                                <td>{{ $contador }}</td>
                                                <td>{{ $dispositivo->nombre }}</td>
                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-icon btn-warning"
                                                        onclick="editarCare({{ $dispositivo->id }})" data-toggle="tooltip"
                                                        data-placement="top" title="Editar dispositivo"><i class="fas fa-edit"></i></a>
                                                    <a href="javascript:void(0)" class="btn btn-icon btn-danger"
                                                        onclick="eliminarCare({{ $dispositivo->id }})"
                                                        data-toggle="tooltip" data-placement="top" title="Eliminar dispositivo"><i
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

    @foreach ($dispositivos as $dispositivo)
        <div class="modal fade" id="modalEditarCarrera-{{ $dispositivo->id }}" tabindex="-1" role="dialog"
            aria-labelledby="modalEditarCarrera" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarCarrera">Editar dispositivo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.actualizar.dispositivo', $dispositivo->id) }}" method="POST">
                            @method('PUT')
                            @csrf

                            <div class="form-group">
                                <label>Nombre del dispositivo</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-pen-nib"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="asignatura_nombre" name="asignatura_nombre"
                                        value="{{ $dispositivo->nombre }}" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Instrumento asociado</label>
                                <select name="tiac_codigo" class="form-control" id="">
                                    <option value="">Seleccione un instrumento</option>
                                    @foreach ($instrumentos as $instrumento)
                                    @if ($instrumento->tiac_codigo == $dispositivo->tiac_codigo)
                                        <option value="{{ $instrumento->tiac_codigo }}" selected>{{ $instrumento->tiac_nombre }}</option>
                                    @else
                                        <option value="{{ $instrumento->tiac_codigo }}">{{ $instrumento->tiac_nombre }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div hidden>
                                {{-- Metas --}}
                        <strong>Metas por escuela</strong>
                        <div class="row">
                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta Administración y Negocios</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_iniciativas"
                                            name="meta_adm" value="{{$dispositivo->meta_adm}}" placeholder="NO APLICA"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta Educación</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_estudiantes"
                                            name="meta_edu" value="{{$dispositivo->meta_edu}}" placeholder="NO APLICA"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta Salud</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_iniciativas"
                                            name="meta_salud" value="{{$dispositivo->meta_salud}}" placeholder="NO APLICA"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta Tecnología</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_estudiantes"
                                            name="meta_tec" value="{{$dispositivo->meta_tec}}" placeholder="NO APLICA"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta Gastronomía y Turismo</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_iniciativas"
                                            name="meta_gastr" value="{{$dispositivo->meta_gastr}}" placeholder="NO APLICA"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta Informática y Telecomunicaciones</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_estudiantes"
                                            name="meta_inf" value="{{$dispositivo->meta_inf}}" placeholder="NO APLICA"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta Construcción</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_iniciativas"
                                            name="meta_const" value="{{$dispositivo->meta_const}}" placeholder="NO APLICA"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta Desarrollo Social y Servicios Públicos</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_estudiantes"
                                            name="meta_desa" value="{{$dispositivo->meta_desa}}" placeholder="NO APLICA"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
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
    @endforeach


    <div class="modal fade" id="modalCrearCarrera" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Nuevo dispositivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.crear.dispositivo') }}" method="POST">

                        @csrf
                        <div class="form-group">
                            <label>Nombre del dispositivo</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-pen-nib"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="dispositivo_nombre" name="dispositivo_nombre"
                                    placeholder="" autocomplete="off" value="{{ old('nombre') }}">
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
                                <label style="font-size: 110%">Instrumentos</label> <label for=""
                                    style="color: red;">*</label>

                                <select name="tiac_codigo" class="form-control" id="">
                                    <option value="">Seleccione un instrumento</option>
                                    @foreach ($instrumentos as $instrumento)
                                        <option value="{{ $instrumento->tiac_codigo }}">{{ $instrumento->tiac_nombre }}
                                        </option>
                                    @endforeach
                                </select>



                            </div>
                            <div hidden>
                                {{-- Metas --}}
                        <strong>Metas por escuela</strong>
                        <div class="row">
                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta Administración y Negocios</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_iniciativas"
                                            name="meta_adm" value="" placeholder="NO APLICA"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta Educación</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_estudiantes"
                                            name="meta_edu" value="" placeholder="NO APLICA"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta Salud</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_iniciativas"
                                            name="meta_salud" value="" placeholder="NO APLICA"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta Tecnología</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_estudiantes"
                                            name="meta_tec" value="" placeholder="NO APLICA"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta Gastronomía y Turismo</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_iniciativas"
                                            name="meta_gastr" value="" placeholder="NO APLICA"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta Informática y Telecomunicaciones</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_estudiantes"
                                            name="meta_inf" value="" placeholder="NO APLICA"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta Construcción</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_iniciativas"
                                            name="meta_const" value="" placeholder="NO APLICA"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta Desarrollo Social y Servicios Públicos</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_estudiantes"
                                            name="meta_desa" value="" placeholder="NO APLICA"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>

                            </div>

                        </div>



                        <div class="text-center">
                            <button type="submit" class="btn btn-primary waves-effect">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEliminaCarrera" tabindex="-1" role="dialog" aria-labelledby="modalEliminar"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.eliminar.dispositivo') }}" method="POST">
                    {{-- <form action=""></form> --}}
                    @method('DELETE')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEliminar">Eliminar Dispositivo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-ban text-danger" style="font-size: 50px; color"></i>
                        <h6 class="mt-2">El dispositivo dejará de existir dentro del sistema. <br> ¿Desea continuar de todos
                            modos?</h6>
                        <input type="hidden" id="dispositivo_id" name="dispositivo_id" value="">
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
        function eliminarCare(dispositivo_id) {
            $('#dispositivo_id').val(dispositivo_id);
            $('#modalEliminaCarrera').modal('show');
        }

        function editarCare(dispositivo_id) {
            $('#modalEditarCarrera-' + dispositivo_id).modal('show');
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
