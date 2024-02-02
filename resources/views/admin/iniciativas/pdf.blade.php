<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de ODS</title>
</head>
{{-- <style>
    .contenedor {
        display: flex;
        flex-direction: column;
    }

    .valor {
        margin-bottom: 0px; /* Puedes ajustar este valor según tus preferencias */
    }
</style> --}}

<body>
    <div style="text-align: center;">
        <img src="https://cftpucv.vinculamos.org/img/logo-CFT-PUCV-con-catolica.png" alt="logo cftpucv" style="width:293px;heigh:81px;">
      </div>

    {{-- {{dd($odsValues)}} --}}

    <!--HEADER-->
    <table class="div-1Header">
            <thead>
                <th class="headerDatosh titulos">Iniciativa: <span class="titulos">{{$iniciativa->inic_nombre}}</span></th>
            </thead>
    </table>
    <div class="contenedor">
        <div class="valor">
            <p style="font-size: 10"><strong>ID: </strong><span>{{$iniciativa->inic_codigo}}</span></p>
            <p style="font-size: 10"><strong>Año: </strong><span>{{$iniciativa->inic_anho}}</span></p>
            <p style="font-size: 10"><strong>Programas: </strong><span>{{$iniciativa->meca_nombre}}</span></p>
            <p style="font-size: 10"><strong>Tipo de actividades: </strong><span>{{$iniciativa->tiac_nombre}}</span></p>
        </div>
        <div class="valor"></div>
        <div class="valor datos-grales-td"></div>
    </div>
    <p class="title">Descripción:</p>
    <p style="text-align: justify; font-size: 13px;" >{{$iniciativa->inic_descripcion}}</p>

    <!--ODS-->
    <table class="table table-striped table-md">
        <tbody>
            <!-- Encabezados de columna -->
            <tr>
                <td></td> <!-- Celda vacía para la primera columna -->
                <td><strong>Meta</strong></td>
                <td><strong>Fundamento</strong></td>
            </tr>

            <!-- Contenido de la tabla -->
            @foreach ($odsValues as $ods)
                <tr>
                    <td style="text-align: center; vertical-align: middle;">
                        <img src="https://cftpucv.vinculamosvm02.cl/vinculamos_v5_cftpucv/app/img/ods-{{ $ods->id_ods }}.png" alt="Ods {{ $ods->id_ods }}" style="width: 85px; height: 85px; margin: 0 auto; display: block; margin-bottom: 10px; transition: transform 0.3s;">

                        <!-- Agregamos el estilo para el efecto de zoom en hover -->
                        <style>
                            img:hover {
                                transform: scale(1.3); /* Puedes ajustar el valor para cambiar el nivel de zoom */
                            }
                        </style>
                    </td>

                    <td>
                        @foreach ($metas as $meta)
                            @php
                                // Obtener el valor antes del punto en la cadena meta_ods
                                $metaValue = explode('.', $meta->meta_ods)[0];
                            @endphp

                            @if ($metaValue == $ods->id_ods)
                                <div style="margin: 0 20px;">
                                    <p style="margin: 5px 0; font-size: 14px; color: #333; text-align: justify;">Meta {{ $meta->desc_meta }}</p>
                                </div>
                            @endif
                        @endforeach
                    </td>
                    <td>
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

    <footer>
        <p>PDF generado en: https://cftpucv.vinculamos.org/ | CFTPUCV  </p>
    </footer>
</body>

</html>
<style>
    /* Estilos Generales */
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        margin: 0;
        padding: 0;
    }

    .titulos {
        font-size: 15px;
        text-transform: uppercase;
    }

    .title {
        font-size: 20px;
    }

    /* Header */
    .div-1Header, .div-1Datos {
        width: 100%;
    }

    .table_h_factura {
        /* width: 50%; */
        background-color: #FFF;
        margin: 0;
        padding: 0;
    }

    .table_h_factura tr td p {
        margin: 0;
        padding: 2px;
        text-align: left; /* Cambiado de 'right' a 'left' */
        padding-right: 5px;
    }

    .headerDatosh {
        text-align: left;
        color: #FFF;
        padding: 5px;
        background-color: rgb(24, 140, 207);
    }

    .table_h_factura tr td p {
        margin: 0;
        padding: 2px;
        text-align: right;
        padding-right: 5px;
    }

    .datos-grales-td {
        width: 50%;
    }

    /* ODS */
    .table {
        width: 100%;
        margin-top: 20px;
    }

    .table-md td {
        text-align: center;
        padding: 10px;
        border-bottom: 1px solid rgba(20, 20, 20, 0.096);
    }

    .table-md thead tr {
        background-color: rgb(24, 140, 207);
        color: #FFF;
    }

    .ods-image {
        width: 150px;
        height: 150px;
        margin: 0 auto;
        display: block;
        margin-bottom: 10px;
        transition: transform 0.3s;
    }

    .ods-image:hover {
        transform: scale(1.3);
    }

    .meta-title,
    .fundamento-text {
        margin: 5px 0;
        font-size: 14px;
        color: #333;
        text-align: center;
    }

    .meta-container,
    .fundamento-container {
        margin: 0 20px;
    }

    /* Footer */
    footer {
        width: 100%;
        text-align: center;
        position: absolute;
        bottom: 0;
        padding: 10px;
        background-color: rgb(24, 140, 207);
        color: #FFF;
    }

    footer a {
        color: #FFF;
        text-decoration: none;
    }
</style>
