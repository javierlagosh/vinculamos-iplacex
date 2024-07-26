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
                            <h4>Listado de Asignaturas</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalCrearCarrera"><i class="fas fa-plus"></i> Nueva Asignaturas</button>
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
                                        @foreach ($asignaturas as $asignatura)
                                            <?php
                                            $contador = $contador + 1;
                                            ?>
                                            <tr>
                                                <td>{{ $contador }}</td>
                                                <td>{{ $asignatura->nombre }}</td>
                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-icon btn-warning"
                                                        onclick="editarCare({{ $asignatura->id }})" data-toggle="tooltip"
                                                        data-placement="top" title="Editar carrera"><i class="fas fa-edit"></i></a>
                                                    <a href="javascript:void(0)" class="btn btn-icon btn-danger"
                                                        onclick="eliminarCare({{ $asignatura->id }})"
                                                        data-toggle="tooltip" data-placement="top" title="Eliminar asignatura"><i
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

    @foreach ($asignaturas as $asignatura)
        <div class="modal fade" id="modalEditarCarrera-{{ $asignatura->id }}" tabindex="-1" role="dialog"
            aria-labelledby="modalEditarCarrera" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarCarrera">Editar asignatura</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.actualizar.asignatura', $asignatura->id) }}" method="POST">
                            @method('PUT')
                            @csrf

                            <div class="form-group">
                                <label>Nombre de la carrera</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-pen-nib"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="asignatura_nombre" name="asignatura_nombre"
                                        value="{{ $asignatura->nombre }}" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Descripción de la asignatura</label>
                                <div class="input-group">
                                        <textarea class="form-control" name="descripcion" id="" cols="30" rows="10">{{ $asignatura->descripcion }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label style="font-size: 110%">Carreras</label> {{-- <label for=""
                                    style="color: red;">*</label> --}}
                                {{-- <input type="checkbox" id="selectAllEscuelas" style="margin-left: 60%"> <label
                                    for="selectAllEscuelas">Todas</label> --}}
                                <select class="form-control select2" name="carreras[]" multiple=""
                                    style="width: 100%" id="carreras">
                                    @if (isset($carreras))
                                        @forelse ($carreras as $carrera)
                                            @php
                                                // Comprueba si $escu->escu_codigo y $sede->sede_codigo están en la misma fila de sedesxescuelas
                                                $relationExists = $carrerasAsignaturas->where('asignatura_id', $asignatura->id)
                                                    ->where('care_codigo', $carrera->care_codigo)
                                                    ->isNotEmpty();
                                            @endphp
                                            <option value="{{ $carrera->care_codigo }}" {{ $relationExists ? 'selected' : '' }}>
                                                {{ $carrera->care_nombre }}
                                            </option>
                                        @empty
                                            <option value="-1">No existen registros</option>
                                        @endforelse
                                    @endif
                                </select>
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
                    <h5 class="modal-title" id="formModal">Nueva asignatura</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.crear.asignatura') }}" method="POST">

                        @csrf
                        <div class="form-group">
                            <label>Nombre de la asignatura</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-pen-nib"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="asignatura_nombre" name="asignatura_nombre"
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
                            <label>Descripción de la asignatura</label>
                            <div class="input-group">
                                    <textarea class="form-control" name="descripcion" id="" cols="30" rows="10">{{ old('descripcion') ?? ""  }}</textarea>

                            </div>
                        </div>
                            <div class="form-group">
                                <label style="font-size: 110%">Carreras que la imparten</label> <label for=""
                                    style="color: red;">*</label>

                                <select class="form-control select2" multiple="" id="carreras"
                                    name="carreras[]" style="width: 100%">
                                        {{-- <select class="form-control select2" name="sedes[]" multiple id="sedes"> --}}
                                        @forelse ($carreras as $carrera)
                                            <option value="{{ $carrera->care_codigo }}"
                                                {{ collect(old('carreras'))->contains($carrera->care_codigo) ? 'selected' : '' }}>
                                                {{ $carrera->care_nombre }}</option>
                                        @empty
                                            <option value="-1">No existen registros</option>
                                        @endforelse
                                </select>

                                @if ($errors->has('carreras'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2">
                                        <div class="alert-body">
                                            <button class="close"
                                                data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('carreras') }}</strong>
                                        </div>
                                    </div>
                                @endif

                            </div>


                        {{-- <div class="form-group">
                            <label>Descripción de la carrera</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                </div>
                                <textarea rows="6" class="formbold-form-input" id="care_descripcion" name="care_descripcion" autocomplete="off"
                                    style="width:100%">{{ old('care_descripcion') }}</textarea>
                                @if ($errors->has('care_desripcion'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2 text-center"
                                        style="width:100%">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('care_desripcion') }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div> --}}
                        {{-- <div class="form-group">
                            <label>Jefe/Jefa de la carrera</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="care_director" name="care_director"
                                    placeholder="" autocomplete="off" value="{{ old('care_director') }}">
                                @if ($errors->has('care_director'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2 text-center"
                                        style="width:100%">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('care_director') }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div> --}}
                        {{-- <div class="form-group">
                            <label>Institución</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-building"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="care_institucion" name="care_institucion"
                                    placeholder="" autocomplete="off">
                                @if ($errors->has('care_institucion'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2 text-center"
                                        style="width:100%">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('care_institucion') }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div> --}}
                        {{-- <div class="form-group">
                            <label>Escuela</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-building"></i>
                                    </div>
                                </div>
                                <select class="form-control" id="escu_codigo" name="escu_codigo">
                                    @foreach ($escuelas as $escuela)
                                        <option value="{{ $escuela->escu_codigo }}">{{ $escuela->escu_nombre }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('escu_codigo'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2 text-center"
                                        style="width:100%">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('escu_codigo') }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div> --}}
                        {{-- <div class="form-group">
                            <label>Área de especialidad</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-building"></i>
                                    </div>
                                </div>
                                <select class="form-control" id="aes_codigo" name="aes_codigo">
                                    @foreach ($aes as $area)
                                        <option value="{{ $area->aes_codigo }}">{{ $area->aes_nombre }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('escu_codigo'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2 text-center"
                                        style="width:100%">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('escu_codigo') }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div> --}}
                        {{-- <div class="row">
                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta Estudiantes</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_estudiantes"
                                            name="meta_estudiantes" value="{{ old('meta_estudiantes') }}"
                                            autocomplete="off">
                                    </div>
                                    @error('meta_estudiantes')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta Docentes</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_docentes"
                                            name="meta_docentes" value="{{ old('meta_docentes') }}" autocomplete="off">
                                    </div>
                                    @error('meta_docentes')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}
                        {{-- <div class="row">
                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta socios/as Comunitarios/as</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_comunitarios"
                                            name="meta_comunitarios" value="{{ old('meta_comunitarios') }}"
                                            autocomplete="off">
                                    </div>
                                    @error('meta_comunitarios')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta Beneficiarios</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_benicifiarios"
                                            name="meta_benicifiarios" value="{{ old('meta_benicifiarios') }}" autocomplete="off">
                                    </div>
                                    @error('meta_benicifiarios')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}
                        {{-- <div class="row">
                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>Meta Iniciativas</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_iniciativas"
                                            name="meta_iniciativas" value="{{ old('meta_iniciativas') }}"
                                            autocomplete="off">
                                    </div>
                                    @error('meta_iniciativas')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
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

    <div class="modal fade" id="modalEliminaCarrera" tabindex="-1" role="dialog" aria-labelledby="modalEliminar"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.eliminar.asignatura') }}" method="POST">
                    {{-- <form action=""></form> --}}
                    @method('DELETE')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEliminar">Eliminar Asignatura</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-ban text-danger" style="font-size: 50px; color"></i>
                        <h6 class="mt-2">La asignatura dejará de existir dentro del sistema. <br> ¿Desea continuar de todos
                            modos?</h6>
                        <input type="hidden" id="asignatura_id" name="asignatura_id" value="">
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
        function eliminarCare(asignatura_id) {
            $('#asignatura_id').val(asignatura_id);
            $('#modalEliminaCarrera').modal('show');
        }

        function editarCare(asignatura_id) {
            $('#modalEditarCarrera-' + asignatura_id).modal('show');
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
