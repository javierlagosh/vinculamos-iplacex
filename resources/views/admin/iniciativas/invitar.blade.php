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
<script>
    var fragmento = window.location.hash;
    fragmento = fragmento.replace("#", "");
    // fragmento a numero entero
    fragmento = parseInt(fragmento);
    if (fragmento != 0 && fragmento != 1 && fragmento != 2) {
        fragmento = "";
    }
    console.log(fragmento);

</script>
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-xl-12">


                    <div class="card">
                        <div class="container">
                            <div class="col-xl-6">
                                @if (Session::has('exito'))
                                    <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                        <div class="alert-body">
                                            <strong>{{ Session::get('exito') }}</strong>
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-xl-6">
                                @if (Session::has('error'))
                                    <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                        <div class="alert-body">
                                            <strong>{{ Session::get('error') }}</strong>
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-header">
                            <h4>Listado Invitaciones para: {{$iniciativa[0]->inic_nombre}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="container">
                                <h5>Invitar a responder:</h5>
                                <form method="POST" action="{{route('admin.invitar.evaluacion')}}">
                                    @csrf
                                    <div class="form-row align-items-center">
                                        <div class="col-auto">
                                            <label class="sr-only" for="inlineFormInput">Nombre</label>
                                            <input type="text" class="form-control mb-2" name="nombre" id="inlineFormInput" placeholder="Nombre" required>
                                            <input type="number" hidden name="inic_codigo" value="{{$inic_codigo}}">
                                        </div>
                                        <div class="col-auto">
                                            <label class="sr-only" for="inlineFormInputGroup" required>Correo Electronico</label>
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">@</div>
                                                </div>
                                                <input type="email" name="correo" class="form-control" id="inlineFormInputGroup" placeholder="Correo electrónico">
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <label class="sr-only" for="inlineFormInputGroup">Tipo</label>
                                            <select class="form-control" name="tipo" id="tipo" required>
                                                <option value="">Seleccione el tipo de evaluador...</option>
                                                <option value="0">Estudiante</option>
                                                <option value="1">Docente/Directivo</option>
                                                <option value="2">Externo</option>
                                            </select>
                                        </div>
                                        <script>
                                            var tipo = document.getElementById('tipo');
                                            // que el valor del fragmento sea el selected
                                            tipo.value = fragmento;
                                        </script>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary mb-2">Agregar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <br>

                            
                            
                            
                            <div class="container text-center">
                                <p>
                                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
                                    <button id="buttonEstudiantes" class="btn btn-primary" type="button" onclick="cambioinputTipo2(0)" data-bs-toggle="collapse" data-bs-target="#estudiantesCollapse" aria-expanded="false" aria-controls="estudiantesCollapse">
                                        Estudiantes
                                    </button>
                                    <button id="buttonDocentes" class="btn btn-primary" type="button" onclick="cambioinputTipo2(1)" data-bs-toggle="collapse" data-bs-target="#docentesCollapse" aria-expanded="false" aria-controls="docentesCollapse">
                                        Docentes/Directivos
                                    </button>
                                    <button id="buttonExternos" class="btn btn-primary" type="button" onclick="cambioinputTipo2(2)" data-bs-toggle="collapse" data-bs-target="#externosCollapse" aria-expanded="false" aria-controls="externosCollapse">
                                        Externos
                                    </button>
                                    
                                  </p>
                                  <div class="collapse" id="estudiantesCollapse">
                                    <div class="card card-body">
                                        <div id="estudiantesInvitados" >
                                            <div class="card-header d-flex justify-content-between">
                                                <h4 class="card-title">Listado de invitados estudiantes </h4>
                                            </div>
                                            
                                            
                                            <div class="container d-flex justify-content-center align-items-center mt-5  mb-5">  
                                                <table class="table table-striped" id="table-1">
                                                    <thead>
                                                        <tr>
                                                            <th>Nombre</th>
                                                            <th>Correo</th>
                                                            <th>Estado</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($evaluacion_estudiantes as $eva_estudiantes)
                                                        <tr>
                                                            <td>{{$eva_estudiantes->evainv_nombre}}</td>
                                                            <td>{{$eva_estudiantes->evainv_correo}}</td>
                                                            <td>@if ($eva_estudiantes->evainv_estado == 0)
                                                                <span class="badge badge-warning">Pendiente</span>
                                                                @elseif ($eva_estudiantes->evainv_estado == 1)
                                                                <span class="badge badge-success">Completado</span>
                                                                @elseif ($eva_estudiantes->evainv_estado == 2)
                                                                <span class="badge badge-secondary">Invitado</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                {{-- eliminar --}}
                                                                <form action="{{ route('admin.eliminar.invitacion', ['evainv_codigo' => $eva_estudiantes->evainv_codigo]) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger text-white fas fa-trash" title="Eliminar"></button>
                                                                </form>
                                                                
                                                                
                                                                

                                                                
                                                                
                                                            </td>
                                                            
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                  </div>
                                  <div class="collapse" id="docentesCollapse">
                                    <div class="card card-body">
                                        <div id="docentesInvitados" >
                                            <div class="card-header d-flex justify-content-between">
                                                <h4 class="card-title">Listado de invitados Docentes/Directivo </h4>
                                            </div>
                                            <div class="container d-flex justify-content-center align-items-center mt-5  mb-5">  
                                                <table class="table table-striped" id="table-1">
                                                    <thead>
                                                        <tr>
                                                            <th>Nombre</th>
                                                            <th>Correo</th>
                                                            <th>Estado</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($evaluacion_docentes as $eva_docentes)
                                                        <tr>
                                                            <td>{{$eva_docentes->evainv_nombre}}</td>
                                                            <td>{{$eva_docentes->evainv_correo}}</td>
                                                            <td>@if ($eva_docentes->evainv_estado == 0)
                                                                <span class="badge badge-warning">Pendiente</span>
                                                                @elseif ($eva_docentes->evainv_estado == 1)
                                                                <span class="badge badge-success">Completado</span>
                                                                @elseif ($eva_docentes->evainv_estado == 2)
                                                                <span class="badge badge-secondary">Invitado</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                
                                                                <form action="{{ route('admin.eliminar.invitacion.docente', ['evainv_codigo' => $eva_docentes->evainv_codigo]) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger text-white fas fa-trash" title="Eliminar"></button>
                                                                </form>
                                                            </td>
                                                            
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                  </div>
                                  <div class="collapse" id="externosCollapse">
                                    <div class="card card-body">
                                        <div id="externosInvitados" >
                                            <div class="card-header d-flex justify-content-between">
                                                <h4 class="card-title">Listado de invitados externos </h4>
                                            </div>
                                            <div class="container d-flex justify-content-center align-items-center mt-5  mb-5">  
                                                <table class="table table-striped" id="table-1">
                                                    <thead>
                                                        <tr>
                                                            <th>Nombre</th>
                                                            <th>Correo</th>
                                                            <th>Estado</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($evaluacion_externos as $eva_externos)
                                                        <tr>
                                                            <td>{{$eva_externos->evainv_nombre}}</td>
                                                            <td>{{$eva_externos->evainv_correo}}</td>
                                                            <td>@if ($eva_externos->evainv_estado == 0)
                                                                <span class="badge badge-warning">Pendiente</span>
                                                                @elseif ($eva_externos->evainv_estado == 1)
                                                                <span class="badge badge-success">Completado</span>
                                                                @elseif ($eva_externos->evainv_estado == 2)
                                                                <span class="badge badge-secondary">Invitado</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                
                                                                <form action="{{ route('admin.eliminar.invitacion.externo', ['evainv_codigo' => $eva_externos->evainv_codigo]) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger text-white fas fa-trash" title="Eliminar"></button>
                                                                </form>
                                                            </td>
                                                            
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                  </div>
                            </div>

                            <form method="POST" action="{{ route('send.email') }}">
                                @csrf
                                    <input type="text" hidden name="inic_codigo" value="{{$inic_codigo}}">
                                    <input type="number" hidden name="tipo2" value="0">
                                </div>
                                <button type="submit" class="btn btn-primary" id="botonEnviarPendientes">Enviar</button>
                            </form>
                                
                            <script>

                                var buttonEstudiantes = document.getElementById("buttonEstudiantes");
                                var buttonDocentes = document.getElementById("buttonDocentes");
                                var buttonExternos = document.getElementById("buttonExternos");
                                var collapseEstudiantes = document.getElementById("estudiantesCollapse");
                                var collapseDocentes = document.getElementById("docentesCollapse");
                                var collapseExternos = document.getElementById("externosCollapse");

                                var botonEnviarPendientes = document.getElementById("botonEnviarPendientes");


                                if (fragmento == 0) {
                                    collapseEstudiantes.classList.add("show");
                                    document.getElementsByName('tipo2')[0].value = 0;
                                    botonEnviarPendientes.textContent = "Enviar a Estudiantes Pendientes";
                                } else if (fragmento == 1) {
                                    collapseDocentes.classList.add("show");
                                    document.getElementsByName('tipo2')[0].value = 1;
                                    botonEnviarPendientes.textContent = "Enviar a Docentes/Directivos Pendientes";
                                } else if (fragmento == 2) {
                                    collapseExternos.classList.add("show");
                                    document.getElementsByName('tipo2')[0].value = 2;
                                    botonEnviarPendientes.textContent = "Enviar a Externos Pendientes";
                                }
                                
                                // Escucha los clics en el botón de Estudiantes
                                buttonEstudiantes.addEventListener("click", function() {
                                    collapseDocentes.classList.remove("show");
                                    collapseExternos.classList.remove("show");
                                    document.getElementsByName('tipo')[0].value = 0;
                                    botonEnviarPendientes.textContent = "Enviar a Estudiantes Pendientes";
                                });

                                // Escucha los clics en el botón de Docentes/Directivos
                                buttonDocentes.addEventListener("click", function() {
                                    collapseEstudiantes.classList.remove("show");
                                    collapseExternos.classList.remove("show");
                                    document.getElementsByName('tipo')[0].value = 1;
                                    botonEnviarPendientes.textContent = "Enviar a Docentes/Directivos Pendientes";
                                });

                                // Escucha los clics en el botón de Externos
                                buttonExternos.addEventListener("click", function() {
                                    collapseEstudiantes.classList.remove("show");
                                    collapseDocentes.classList.remove("show");
                                    document.getElementsByName('tipo')[0].value = 2;
                                    botonEnviarPendientes.textContent = "Enviar a Externos Pendientes";
                                });
                                function cambioinputTipo2(tipoSeleccionado) {
                                    document.getElementsByName('tipo2')[0].value = tipoSeleccionado;
                                }
                            </script>
                            
    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
