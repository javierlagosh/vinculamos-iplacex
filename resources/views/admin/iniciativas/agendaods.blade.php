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

<style>
    .card {
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Agrega una sombra sutil */
    }

    .table-responsive {
        overflow-x: auto;
    }

    /* table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    } */

    table, th, td {
     padding: 5px;
     text-align: center;
    }

    th {
        background-color: #f2f2f2;
    }

    img {
        display: block;
        margin: 0 auto;
        max-width: 80px;
        max-height: 80px;
        border-radius: 10%; /* Mejora el estilo de bordes redondeados */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Agrega una sombra sutil */
    }

    /* Estilos para las filas impares */
    tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    .card-header-action {
        margin-top: -5px; /* Ajusta el espacio entre el título y los botones */
    }

    .btn-primary {
        background-color: #3498db;
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #2980b9;
    }


</style>
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-xl-12">
                <div class="row"></div>
                <div class="card">
                    <div class="card-header">
                        <h4>Agenda 2030</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.iniciativas.pdf', $iniciativa)}}" class="btn btn-icon btn-success icon-left"
                                data-toggle="tooltip" data-placement="top" title="Agenda 2030"><i
                                    class="fas  fa-file-pdf"></i>Generar pdf con ODS</a>
                            <a href="{{ route('admin.iniciativas.detalles', $iniciativa) }}"
                               class="btn btn-primary mr-1 waves-effect icon-left" type="button">
                                <i class="fas fa-angle-left"></i> Volver a la Iniciativa
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-striped table-md">
                                    <tbody>
                                        <!-- Encabezados de columna -->
                                        <tr>
                                            <td></td> <!-- Celda vacía para la primera columna -->
                                            <td><strong>Meta</strong></td>
                                            {{-- <td><strong>Descripción Meta</strong></td> --}}
                                            <td><strong>Fundamento</strong></td>
                                        </tr>

                                        <!-- Contenido de la tabla -->
                                        @foreach ($odsValues as $ods)
                                            <tr>
                                                <td style="text-align: center; vertical-align: middle;">
                                                    <img src="https://cftpucv.vinculamosvm02.cl/vinculamos_v5_cftpucv/app/img/ods-{{ $ods->id_ods }}.png" alt="Ods {{ $ods->id_ods }}" style="width: 150px; height: 150px; margin: 0 auto; display: block; margin-bottom: 10px; transition: transform 0.3s;">

                                                    <!-- Agregamos el estilo para el efecto de zoom en hover -->
                                                    <style>
                                                        img:hover {
                                                            transform: scale(1.3); /* Puedes ajustar el valor para cambiar el nivel de zoom */
                                                        }
                                                    </style>
                                                </td>

                                                <td style="vertical-align: middle;">
                                                    @foreach ($metas as $meta)
                                                        @php
                                                            // Obtener el valor antes del punto en la cadena meta_ods
                                                            $metaValue = explode('.', $meta->meta_ods)[0];
                                                        @endphp

                                                        @if ($metaValue == $ods->id_ods)
                                                            <div style="margin: 0 20px; text-align: justify;">
                                                                <p style="margin: 5px 0px; font-size: 14px; color: #333;">
                                                                    {{$meta->desc_meta}}
                                                                </p>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </td>

                                                <td >
                                                    @foreach ($metas as $meta)
                                                        @php
                                                            // Obtener el valor antes del punto en la cadena meta_ods
                                                            $metaValue = explode('.', $meta->meta_ods)[0];
                                                        @endphp

                                                        @if ($metaValue == $ods->id_ods)
                                                            <div style="margin: 0 20px;">
                                                                <p style="margin: 5px 0; font-size: 14px; color: #333; text-align: justify;">
                                                                @if ($meta->fundamento[0] == ",")
                                                                    {{ str_replace(",","",$meta->fundamento) }}
                                                                @else
                                                                    {{$meta->fundamento}}

                                                                @endif
                                                                </p>
                                                            </div>
                                                        @endif
                                                    @endforeach
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
    </div>
</section>

@endsection
