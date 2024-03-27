@extends('admin.panel')

@section('contenido')
    <section class="section" style="font-size: 115%;">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            @if ($errors->has('aes_nombre') || $errors->has('care_director') || $errors->has('escu_codigo'))
                                <div class="alert alert-warning alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        @if ($errors->has('aes_nombre'))
                                            <strong>{{ $errors->first('aes_nombre') }}</strong><br>
                                        @endif
                                        @if ($errors->has('care_director'))
                                            <strong>{{ $errors->first('care_director') }}</strong><br>
                                        @endif
                                        @if ($errors->has('escu_codigo'))
                                            <strong>{{ $errors->first('escu_codigo') }}</strong><br>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @if (Session::has('exitoCarrera'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoCarrera') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                            @if (Session::has('errorCarrera'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorCarrera') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Listado de Áreas de Especialidad</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalCrearAes"><i class="fas fa-plus"></i> Nueva Área de Especialidad</button>
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
                                        @foreach ($aes as $area)
                                            <?php
                                            $contador = $contador + 1;
                                            ?>
                                            <tr>
                                                <td>{{ $contador }}</td>
                                                <td>{{ $area->aes_nombre }}</td>
                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-icon btn-warning"
                                                        onclick="editarCare({{ $area->aes_codigo }})" data-toggle="tooltip"
                                                        data-placement="top" title="Editar"><i class="fas fa-edit"></i></a>
                                                    <a href="javascript:void(0)" class="btn btn-icon btn-danger"
                                                        onclick="eliminarCare({{ $area->aes_codigo }})"
                                                        data-toggle="tooltip" data-placement="top" title="Eliminar área"><i
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

    @foreach ($aes as $area)
        <div class="modal fade" id="modalEditarAes-{{ $area->aes_codigo }}" tabindex="-1" role="dialog"
            aria-labelledby="modalEditarAes" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarCarrera">Editar Área de especialidad</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.actualizar.aes', $area->aes_codigo) }}" method="POST">
                            @method('PUT')
                            @csrf

                            <div class="form-group">
                                <label>Nombre del área de especialidad</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-pen-nib"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="aes_nombre" name="aes_nombre"
                                        value="{{ $area->aes_nombre }}" autocomplete="off">
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


    <div class="modal fade" id="modalCrearAes" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Nueva Área de Especialidad</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.crear.aes') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nombre del área de especialidad</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-pen-nib"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="aes_nombre" name="aes_nombre"
                                    placeholder="" autocomplete="off" value="{{ old('aes_nombre') }}">
                                @if ($errors->has('aes_nombre'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2 text-center"
                                        style="width:100%">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('ncare_ombre') }}</strong>
                                        </div>
                                    </div>
                                @endif
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

    <div class="modal fade" id="modalEliminaAes" tabindex="-1" role="dialog" aria-labelledby="modalEliminar"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.eliminar.aes') }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEliminar">Eliminar Área de especialidad</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-ban text-danger" style="font-size: 50px; color"></i>
                        <h6 class="mt-2">La carrera "{{$area->aes_nombre ?? ''}}" dejará de existir dentro del sistema. <br> ¿Desea continuar de todos
                            modos?</h6>
                        <input type="hidden" id="aes_codigo" name="aes_codigo" value="">
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
        function eliminarCare(aes_codigo) {
            $('#aes_codigo').val(aes_codigo);
            $('#modalEliminaAes').modal('show');
        }

        function editarCare(aes_codigo) {
            $('#modalEditarAes-' + aes_codigo).modal('show');
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
