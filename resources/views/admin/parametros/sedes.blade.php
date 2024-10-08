@extends('admin.panel')

@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            @if ($errors->has('sede_nombre') || $errors->has('sede_meta_estudiantes') || $errors->has('sede_meta_docentes'))
                                <div class="alert alert-warning alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        @if ($errors->has('sede_nombre'))
                                            <strong>{{ $errors->first('sede_nombre') }}</strong><br>
                                        @endif
                                        @if ($errors->has('sede_meta_estudiantes'))
                                            <strong>{{ $errors->first('sede_meta_estudiantes') }}</strong><br>
                                        @endif
                                        @if ($errors->has('sede_meta_docentes'))
                                            <strong>{{ $errors->first('sede_meta_docentes') }}</strong><br>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if (Session::has('exitoSede'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoSede') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Listado de Sedes</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalCrearsedes"><i class="fas fa-plus"></i> Añadir nueva sede </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1" style="font-size: 110%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre</th>
                                            {{-- <th>Descipción</th> --}}
                                            {{-- <th>Director</th> --}}
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $contadorSedes = 0;
                                        ?>

                                        @foreach ($sedes as $sede)
                                            <?php
                                            $contadorSedes = $contadorSedes + 1;
                                            ?>
                                            <tr>
                                                <td>{{ $contadorSedes }}</td>
                                                <td>{{ $sede->sede_nombre }}</td>
                                                {{-- <td>{{ $sede->sede_descripcion }}</td> --}}
                                                {{-- <td>{{ $sede->sede_director }}</td> --}}
                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-icon btn-warning"
                                                        onclick="editarSede({{ $sede->sede_codigo }})" data-toggle="tooltip"
                                                        data-placement="top" title="Editar"><i class="fas fa-edit"></i></a>
                                                    <a href="javascript:void(0)" class="btn btn-icon btn-danger"
                                                        onclick="eliminarSede({{ $sede->sede_codigo }})"
                                                        data-toggle="tooltip" data-placement="top" title="Eliminar sede"><i
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

    <div class="modal fade" id="modalCrearsedes" tabindex="-1" role="dialog" aria-labelledby="modalCrearsedesLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCrearsedesLabel">Crear Sede</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.crear.sedes') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>Nombre de la sede</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-pen-nib"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="sede_nombre" name="sede_nombre"
                                    value="{{ old('sede_nombre') }}" autocomplete="off">
                            </div>
                            @error('sede_nombre')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group" hidden>
                            <label>Dirección de la sede</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="direccion" name="direccion"
                                    value=" - " autocomplete="off">
                            </div>
                            @error('direccion')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
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
                                        <input type="number" class="form-control" id="meta_iniciativas"
                                            name="sede_meta_serv" value="" placeholder="0"
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
                                        <input type="number" class="form-control" id="meta_estudiantes"
                                            name="sede_meta_ext" value="" placeholder="0"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{-- <div class="row">
                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <a class="btn btn-primary" data-toggle="collapse" href="#div_iniciativas"
                                        role="button" aria-expanded="false" aria-controls="div_iniciativas"
                                        onclick="limpiarInputIni()">
                                        Aplicar
                                    </a>
                                    <label>Meta Iniciativas</label>
                                    <div class="input-group collapse" id="div_iniciativas" style="margin-top: 10px">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_iniciativas"
                                            name="meta_iniciativas" value="{{ old('meta_iniciativas') }}"
                                            autocomplete="off" placeholder="N° de iniciativas">
                                    </div>
                                    @error('meta_iniciativas')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <a class="btn btn-primary" data-toggle="collapse" href="#div_estudiantes"
                                        role="button" aria-expanded="false" aria-controls="div_estudiantes"
                                        onclick="limpiarInputEstu()">
                                        Aplicar
                                    </a>
                                    <label>Meta Estudiantes</label>
                                    <div class="input-group collapse" id="div_estudiantes" style="margin-top: 10px">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_estudiantes"
                                            name="meta_estudiantes" value="{{ old('meta_estudiantes') }}"
                                            autocomplete="off" placeholder="N° de estudiantes">
                                    </div>
                                    @error('meta_estudiantes')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <a class="btn btn-primary" data-toggle="collapse" href="#div_docentes"
                                        role="button" aria-expanded="false" aria-controls="div_docentes"
                                        onclick="limpiarInputDoce()">
                                        Aplicar
                                    </a>
                                    <label>Meta Docentes</label>
                                    <div class="input-group collapse" id="div_docentes" style="margin-top: 10px">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_docentes"
                                            name="meta_docentes" value="{{ old('meta_docentes') }}" autocomplete="off"
                                            placeholder="N° de docentes">
                                    </div>
                                    @error('meta_docentes')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <a class="btn btn-primary btn-socios" data-toggle="collapse" href="#div_socios"
                                        role="button" aria-expanded="false" aria-controls="div_socios"
                                        onclick="limpiarInputSocio()">
                                        Aplicar
                                    </a>
                                    <label>Meta Socios/as</label>
                                    <div class="input-group collapse" id="div_socios" style="margin-top: 10px">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_socios" name="meta_socios"
                                            value="{{ old('meta_socios') }}" autocomplete="off"
                                            placeholder="N° de socios/as">
                                    </div>
                                    @error('meta_socios')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                        </div>

                        <div class="row">
                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <a class="btn btn-primary" data-toggle="collapse" href="#div_beneficiarios"
                                        role="button" aria-expanded="false" aria-controls="div_beneficiarios"
                                        onclick="limpiarInputBene()">
                                        Aplicar
                                    </a>
                                    <label>Meta Beneficiarios/as</label>
                                    <div class="input-group collapse" id="div_beneficiarios" style="margin-top: 10px">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_beneficiarios"
                                            name="meta_beneficiarios" value="{{ old('meta_beneficiarios') }}"
                                            autocomplete="off" placeholder="N° de beneficiarios/as">
                                    </div>
                                    @error('meta_beneficiarios')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <a class="btn btn-primary" data-toggle="collapse" href="#div_egresados"
                                        role="button" aria-expanded="false" aria-controls="div_egresados"
                                        onclick="limpiarInputEgre()">
                                        Aplicar
                                    </a>
                                    <label>Meta Egresados/as</label>
                                    <div class="input-group collapse" id="div_egresados" style="margin-top: 10px">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                        </div>
                                        <input type="number" class="form-control" id="meta_egresados"
                                            name="meta_egresados" value="{{ old('meta_egresados') }}" autocomplete="off"
                                            placeholder="N° de egresados">
                                    </div>
                                    @error('meta_egresados')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="form-group">
                            <label>Meta Socios</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-pen-nib"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="sede_meta_socios"
                                    name="sede_meta_socios" value="{{ old('sede_meta_socios') }}"
                                    autocomplete="off">
                            </div>
                            @error('sede_meta_socios')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div> --}}
                        {{-- <div class="form-group">
                            <label>Meta Iniciativas</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-pen-nib"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="sede_meta_iniciativas"
                                    name="sede_meta_iniciativas" value="{{ old('sede_meta_iniciativas') }}"
                                    autocomplete="off">
                            </div>
                            @error('sede_meta_iniciativas')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div> --}}

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary waves-effect">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>




    @foreach ($sedes as $sede)
        <div class="modal fade" id="modalEditarsedes-{{ $sede->sede_codigo }}" tabindex="-1" role="dialog"
            aria-labelledby="modalEditarsedes-{{ $sede->sede_codigo }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarsedes-{{ $sede->sede_codigo }}">Editar Sede</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.actualizar.sedes', $sede->sede_codigo) }}" method="POST">
                            @method('PUT')
                            @csrf

                            <div class="form-group">
                                <label>Nombre de la sede</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-pen-nib"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="sede_nombre" name="sede_nombre"
                                        value="{{ $sede->sede_nombre }}" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group" hidden>
                                <label>Dirección de la sede</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="direccion" name="direccion"
                                        value="{{ $sede->sede_direccion }}" autocomplete="off">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label>Meta servicio disciplinar</label>

                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-calendar-check"></i>
                                                </div>
                                            </div>
                                            <input type="number" class="form-control" id="sede_meta_serv"
                                                name="sede_meta_serv" value="{{$sede->sede_meta_serv}}" placeholder="0"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label>Meta extensión académica</label>

                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-calendar-check"></i>
                                                </div>
                                            </div>
                                            <input type="number" class="form-control" id="sede_meta_ext"
                                                name="sede_meta_ext" value="{{$sede->sede_meta_ext}}" placeholder="0"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="form-group">
                                <label>Descripción</label>
                                <div class="input-group">
                                    <textarea rows="6" class="form-control" id="sede_descripcion" name="sede_descripcion">{{ $sede->sede_descripcion }}</textarea>
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
                                                name="meta_iniciativas" value="{{ $sede->sede_meta_iniciativas }}"
                                                placeholder="NO APLICA" autocomplete="off">
                                        </div>
                                    </div>
                                </div>

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
                                                name="meta_estudiantes" value="{{ $sede->sede_meta_estudiantes }}"
                                                placeholder="NO APLICA" autocomplete="off">
                                        </div>
                                        @error('meta_estudiantes')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label>Meta de Docentes</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-calendar-check"></i>
                                                </div>
                                            </div>
                                            <input type="number" class="form-control" id="meta_docentes"
                                                name="meta_docentes" value="{{ $sede->sede_meta_docentes }}"
                                                placeholder="NO APLICA" autocomplete="off">
                                        </div>
                                        @error('meta_docentes')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label>Meta Socios</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-calendar-check"></i>
                                                </div>
                                            </div>
                                            <input type="number" class="form-control" id="meta_socios"
                                                name="meta_socios" value="{{ $sede->sede_meta_socios }}"
                                                placeholder="NO APLICA" autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-6 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label>Meta de Beneficiarios/as</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-calendar-check"></i>
                                                </div>
                                            </div>
                                            <input type="number" class="form-control" id="meta_beneficiarios"
                                                name="meta_beneficiarios" value="{{ $sede->sede_meta_beneficiarios }}"
                                                placeholder="NO APLICA" autocomplete="off">
                                        </div>
                                        @error('meta_beneficiarios')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label>Meta Egresados</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-calendar-check"></i>
                                                </div>
                                            </div>
                                            <input type="number" class="form-control" id="meta_egresados"
                                                name="meta_egresados" value="{{ $sede->sede_meta_egresados }}"
                                                placeholder="NO APLICA" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary waves-effect">Actualizar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="modalEliminaSede" tabindex="-1" role="dialog" aria-labelledby="modalEliminar"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.eliminar.sedes') }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEliminar">Eliminar sede</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-ban text-danger" style="font-size: 50px; color"></i>
                        <h6 class="mt-2">La sede dejará de existir dentro del sistema. <br> ¿Desea continuar de todos
                            modos?</h6>
                        <input type="hidden" id="sedecodigo" name="sedecodigo" value="">
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
        function eliminarSede(sede_codigo) {
            $('#sedecodigo').val(sede_codigo);
            $('#modalEliminaSede').modal('show');
        }

        function editarSede(sede_codigo) {
            $('#modalEditarsedes-' + sede_codigo).modal('show');
        }

        function eliminarsede(sede_codigo) {
            $('#sede_codigo').val(sede_codigo);
            $('#modalEliminasederam').modal('show');
        }
    </script>

    {{-- <script>


        function editarsede(sede_codigo) {
            $('#modalEditarsederamas-' + sede_codigo).modal('show');
        }

        function limpiarInputSocio() {
            const inputMetaSocios = document.querySelector('#div_socios input');
            inputMetaSocios.value = '';
        }

        function limpiarInputIni() {
            const inputMetaIniciativas = document.querySelector('#div_iniciativas input');
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
    </script> --}}


    {{-- <link rel="stylesheet" href="{{ asset('/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('/js/page/datatables.js') }}"></script> --}}
@endsection
