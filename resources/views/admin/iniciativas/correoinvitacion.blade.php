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
<script src="https://cdn.ckeditor.com/ckeditor5/38.1.1/classic/ckeditor.js"></script>
    <div class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">

                        <div class="col-3"></div>
                    </div>
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6 alert-container" id="exito_ingresar" style="display: none;">
                            <div class="alert alert-success show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>Evaluación ingresada correctamente</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 alert-container" id="exito_crear" style="display: none;">
                            <div class="alert alert-success show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>Evaluación creada correctamente</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            @if (Session::has('exito'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exito') }}  <a style="color:blue;" href="{{route('admin.evaluar.iniciativa', $inic_codigo)}}">Crear nueva encuesta.</a></strong>
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
                        <div class="col-3"></div>
                        <div class="col-6 alert-container" id="error">

                        </div>
                        <div class="col-3"></div>
                    </div>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">


                            <h4>Evaluación de la iniciativa: {{ $iniciativa[0]->inic_nombre }} - Enviar invitación a {{$invitadoNombre}} </h4>
                            <input type="hidden" name="iniciativa_codigo" id="iniciativa_codigo"
                                value="{{ $iniciativa[0]->inic_codigo }}">

                            <div class="card-header-action">

                                    <a href="{{ route('admin.iniciativa.listar') }}"
                                        class="btn btn-primary mr-1 waves-effect icon-left" type="button">
                                        <i class="fas fa-angle-left"></i> Volver a listado
                                    </a>
                            </div>
                        </div>

                        <div class="mx-5 mt-3">
                            <h5>Paso 4: Envío de Correo</h5>
                            <p><span style="color:red;">! </span>Instrucción: en este paso puedes validar o modificar mensaje que se enviará a los evaluadores.  Ten precaución de <strong>NO BORRAR</strong> la palabra <span style="color:blue;">encuesta</span> ya que contiene el link directo a la evaluación.</p>
                            <hr style="
                            border: 0;
                            background-color: white;
                            width: 100%;
                            border-top: 1px solid rgba(0, 0, 0, 0.1);" />
                            <div class="row">
                                <div class="col-md-12">
                                    <form method="post" action="{{ route('enviar.email') }}" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="iniciativa_codigo" value="{{ $iniciativa[0]->inic_codigo }}">
                                        <input type="hidden" name="invitado" value="{{$invitado_rol}}">
                                        <div class="form-group">
                                            <label for="destinatarios">
                                                Destinatario(s):
                                            </label>
                                            <input type="text" class="form-control" id="destinatarios" name="destinatarios" value="{{$destinatarios}}"/>
                                        </div>

                                        <div class="form-group">
                                            <label for="asunto">
                                                Asunto:
                                            </label>
                                            <input type="text" class="form-control" id="asunto" name="asunto" value="Evaluación de actividad: {{$iniciativa[0]->inic_nombre}}" />
                                        </div>

                                        <div class="form-group">
                                            <label for="cuerpo">
                                                Mensaje:
                                            </label>
                                            <textarea name="mensaje" id="editor">
                                                Estimado/a, <br>
                                                Le agradecemos haber participado en la actividad "{{$iniciativa[0]->inic_nombre}}" en el marco de las acciones de Vinculación con el medio que implementa CFT Coquimbo.<br>Con el propósito de continuar mejorando nuestro trabajo, le pedimos que responda la siguiente <a target="_blank" rel="nofollow" style="width: 50px;height:50px;" href="{{ env('URL_EVALUACIONES') }}evaluaciones/{{$evaluaciontotal->evatotal_encriptado}}">encuesta</a>, que nos permitirá evaluar esta actividad. Saluda atentamente a usted.<br><br><img alt="" src="{{ env('SENDER_IMAGE') }}" width="20" height="100">
                                            </textarea>
                                        </div>


                                    <div class="row">
                                        <div class="col-md-6">
                                            El link para acceder a la evaluación es el siguiente:
                                            <button type="button" class="btn btn-success" onclick="copyToClipboard('{{ env('URL_EVALUACIONES') }}evaluaciones/{{$evaluaciontotal->evatotal_encriptado}}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="15" height="15"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M208 0H332.1c12.7 0 24.9 5.1 33.9 14.1l67.9 67.9c9 9 14.1 21.2 14.1 33.9V336c0 26.5-21.5 48-48 48H208c-26.5 0-48-21.5-48-48V48c0-26.5 21.5-48 48-48zM48 128h80v64H64V448H256V416h64v48c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V176c0-26.5 21.5-48 48-48z"/></svg>
                                                Copiar enlace
                                            </button>
                                            <script>
                                                function copyToClipboard(text) {
                                                    var inputc = document.body.appendChild(document.createElement("input"));
                                                    inputc.value = text;
                                                    inputc.focus();
                                                    inputc.select();
                                                    document.execCommand('copy');
                                                    inputc.parentNode.removeChild(inputc);
                                                }
                                            </script>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row justify-content-end mb-3">
                                                <div>

                                                    <button type="button" class="btn mr-1 waves-effect"
                                                    style="background-color:#042344; color:white" onclick="window.location.href = '/admin/iniciativas/{{ $iniciativa[0]->inic_codigo }}/evaluar/invitar/{{$invitado_rol}}'">
                                                    <i
                                                    class="fas fa-chevron-left"></i>
                                                        Paso anterior
                                                    </button>
                                                </div>

                                                <div>

                                                    <button type="submit" class="btn btn-success ml-1 mr-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="13" height="13"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#ffffff" d="M498.1 5.6c10.1 7 15.4 19.1 13.5 31.2l-64 416c-1.5 9.7-7.4 18.2-16 23s-18.9 5.4-28 1.6L284 427.7l-68.5 74.1c-8.9 9.7-22.9 12.9-35.2 8.1S160 493.2 160 480V396.4c0-4 1.5-7.8 4.2-10.7L331.8 202.8c5.8-6.3 5.6-16-.4-22s-15.7-6.4-22-.7L106 360.8 17.7 316.6C7.1 311.3 .3 300.7 0 288.9s5.9-22.8 16.1-28.7l448-256c10.7-6.1 23.9-5.5 34 1.4z"/></svg>
                                                        Enviar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            </div>
                        </div>
                        <script>
                            ClassicEditor
                                .create( document.querySelector( '#editor' ) )
                                .catch( error => {
                                    console.error( error );
                                } );
                        </script>



                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection

