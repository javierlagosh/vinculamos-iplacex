<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen de Iniciativas</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f7f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 2.5em;
        }
        .initiative-section {
            page-break-after: always;
        }
        .initiative-title {
            font-size: 1.5em;
            font-weight: bold;
            margin-top: 30px;
            margin-bottom: 15px;
            color: #1A202C; /* Gris Oscuro */
            border-bottom: 3px solid #0071BC; /* Azul Corporativo */
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 30px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
        }
        th {
            background-color: #0071BC; /* Azul Corporativo */
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.9em;
            letter-spacing: 1px;
        }
        tr:nth-child(even) {
            background-color: #F4F4F6; /* Gris Claro */
        }
        tr:hover {
            background-color: #E3F2FD; /* Azul Suave */
        }
        td:first-child {
            font-weight: bold;
            color: #0071BC; /* Azul Corporativo */
        }
        .cover {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            page-break-after: always;
            text-align: center;
        }
        .cover h1 {
            color: #1A202C; /* Gris Oscuro */
        }
        .cover img {
            max-width: 550px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="cover">
        <h1>Resumen de Iniciativas</h1>
        <br><br><br><br>
        <img src="https://iplacex.qavcmlegado.xyz/img/Logo-iplacex-2022.webp" alt="AIEP Logo">
        <br>
        <br>
        <br>
        <img src="https://vinculamos.cl/assets/imgs/logo.svg" alt="vinculamos logo" class="logo">
    </div>

    <div class="container">
        @foreach($iniciativas as $iniciativa)
            <div class="initiative-section">
                <div class="initiative-title">Iniciativa ID {{ $iniciativa->inic_codigo }}: {{ $iniciativa->inic_nombre }}</div>
                <table>
                    <tbody>
                        <tr>
                            <th>Descripción</th>
                            <td>{{ $iniciativa->inic_descripcion ?? "No especificado aún" }}</td>
                        </tr>
                        <tr>
                            <th>Objetivo</th>
                            <td>{{ $iniciativa->inic_objetivo ?? "No especificado aún" }}</td>
                        </tr>
                        <tr>
                            <th>Brecha</th>
                            <td>{{ $iniciativa->inic_brecha ?? "No especificado aún" }}</td>
                        </tr>
                        <tr>
                            <th>Diagnóstico</th>
                            <td>{{ $iniciativa->inic_diagnostico ?? "No especificado aún" }}</td>
                        </tr>
                        <tr>
                            <th>Convenio</th>
                            <td>{{ $iniciativa->conv_nombre ?? "No especificado aún" }}</td>
                        </tr>
                        <tr>
                            <th>Mecanismo</th>
                            <td>{{ $iniciativa->meca_nombre ?? "No especificado aún" }}</td>
                        </tr>
                        <tr>
                            <th>Tipo de actividad</th>
                            <td>{{ $iniciativa->tiac_nombre ?? "No especificado aún" }}</td>
                        </tr>

                        <tr>
                            <th>Componentes</th>
                            <td>{{ $iniciativa->comp_nombre ?? "No especificado aún" }}</td>
                        </tr>

                        <tr>
                            <th>Ámbito</th>
                            <td>{{ $iniciativa->amac_nombre ?? "No especificado aún" }}</td>
                        </tr>

                        <tr>
                            <th>Sedes</th>
                            <td>{{ $iniciativa->sede_nombre ?? "No especificado aún" }}</td>
                        </tr>

                        <tr>
                            <th>Escuelas</th>
                            <td>{{ $iniciativa->escu_nombre ?? "No especificado aún" }}</td>
                        </tr>


                        <tr>
                            <th>Fecha</th>
                            <td>
                                @if ($iniciativa->inic_desde)
                                    <strong>Desde:</strong> {{ $iniciativa->inic_desde_formateada }}
                                @else
                                    <strong>Desde:</strong> No especificada aún
                                @endif
                                @if ($iniciativa->inic_hasta)
                                    <br><strong>Ejecución:</strong> {{ $iniciativa->inic_hasta_formateada }}
                                @else
                                    <br><strong>Ejecución:</strong> No especificada aún
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Asignatura</th>
                            <td>{{ $iniciativa->inic_asignaturas ?? "Sin asignatura asociada" }}</td>
                        </tr>
                        <tr>
                            <th>Formato</th>
                            <td>{{ $iniciativa->inic_formato ?? "Sin formato asociado" }}</td>
                        </tr>
                        <tr>
                            <th>Estado</th>
                            <td>
                                @if ($iniciativa->inic_estado == 0)
                                    En revisión
                                @elseif ($iniciativa->inic_estado == 1)
                                    En revisión
                                @elseif ($iniciativa->inic_estado == 2)
                                    En ejecución
                                @elseif ($iniciativa->inic_estado == 3)
                                    Aprobada
                                @elseif ($iniciativa->inic_estado == 4)
                                    Falta información
                                @elseif ($iniciativa->inic_estado == 5)
                                    Cerrada
                                @elseif ($iniciativa->inic_estado == 6)
                                    Finalizada
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</body>
</html>
