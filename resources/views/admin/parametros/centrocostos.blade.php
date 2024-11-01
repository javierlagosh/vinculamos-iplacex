@extends('admin.panel')
@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            @if (Session::has('exitoCentroCosto'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoCentroCosto') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                            @if (Session::has('errorCentroCosto'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorCentroCosto') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Listado de centros de costos</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalCrearCeco"><i class="fas fa-plus"></i> Nuevo centro de
                                    costos</button>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if (count($centroCostos) != 0)
                                            <?php
                                            $contador = 0;
                                            ?>
                                            @foreach ($centroCostos as $ceco)
                                                <tr>
                                                    <?php
                                                    $contador = $contador + 1;
                                                    ?>
                                                    <td>{{ $contador }}</td>
                                                    <td>{{ $ceco->ceco_nombre }}</td>
                                                    <td>
                                                        <a href="javascript:void(0)" class="btn btn-icon btn-warning"
                                                            onclick="editarCeco({{ $ceco->ceco_codigo }})"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="Editar centro de costos"><i class="fas fa-edit"></i></a>
                                                        <a href="javascript:void(0)" class="btn btn-icon btn-danger"
                                                            onclick="eliminarCeco({{ $ceco->ceco_codigo }})"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="Eliminar centro de costos"><i
                                                                class="fas fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3" class="text-center">No hay registros disponibles</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    @foreach ($centroCostos as $ceco)
        <div class="modal fade" id="modalEditarCentroCosto-{{ $ceco->ceco_codigo }}" tabindex="-1" role="dialog"
            aria-labelledby="modalEditarCentroCosto" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarCentroCosto">Editar Centro de simulación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.actualizar.ccostos', $ceco->ceco_codigo) }}" method="POST">
                            @method('PUT')
                            @csrf

                            <div class="form-group">
                                <label>Nombre del centro de costos</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-pen-nib"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="ceco_nombre" name="ceco_nombre"
                                        value="{{ $ceco->ceco_nombre }}" autocomplete="off">
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

    <div class="modal fade" id="modalCrearCeco" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Nuevo centro de costos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.crear.ccostos') }}" method="POST">

                        @csrf
                        <div class="form-group">
                            <label>Nombre del centro de costos</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-pen-nib"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="ceco_nombre" name="ceco_nombre"
                                    placeholder="" autocomplete="off" value="{{ old('ceco_nombre') }}">
                                @if ($errors->has('ceco_nombre'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2 text-center"
                                        style="width:100%">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('ceco_nombre') }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary waves-effect"><i class="fa fa-save"></i>
                                Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEliminaCentroCosto" tabindex="-1" role="dialog" aria-labelledby="modalEliminar"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.eliminar.ccostos') }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEliminar">Eliminar centro de costos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-ban text-danger" style="font-size: 50px; color"></i>
                        <h6 class="mt-2">El centro de costos dejará de existir dentro del sistema. <br> ¿Desea continuar
                            de todos
                            modos?</h6>
                        <input type="hidden" id="ceco_codigo" name="ceco_codigo" value="">
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
        function eliminarCeco(ceco_codigo) {
            $('#ceco_codigo').val(ceco_codigo);
            $('#modalEliminaCentroCosto').modal('show');
        }

        function editarCeco(asignatura_id) {
            $('#modalEditarCentroCosto-' + asignatura_id).modal('show');
        }
    </script>
@endsection
