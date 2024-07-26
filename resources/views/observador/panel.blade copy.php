@extends('layout.index')

@section('acceso')
    <ul class="sidebar-menu" style="font-size: 110%;">
    <li class="menu-header">Administrador/a</li>

        <li class="dropdown">
            <a href="{{route('dashboard.ver')}}" class="nav-link">
                <i data-feather="home" id="saludo"></i><span>Inicio</span></a>
        </li>
        <li class="{{ Route::is('admin.iniciativa.listar') ||
                    Route::is('admin.inicitiativas.crear.primero')
                    ? 'dropdown active' : 'dropdown' }}">
            <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="book-open"></i><span>Iniciativas</span></a>
            <ul class="dropdown-menu">
                <li><a style="font-size: 90%;" class="nav-link" href="{{route('admin.iniciativa.listar')}}">Listado de iniciativas</a></li>
                <li><a style="font-size: 90%;" class="nav-link" href="{{route('admin.inicitiativas.crear.primero')}}">Crear iniciativa</a></li>
            </ul>
        </li>
        <li class="{{ Route::is('admin.listar.actividades')? 'dropdown active' : 'dropdown' }}">
            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="clipboard"></i><span>Bitácora</span></a>
            <ul class="dropdown-menu">
                <li><a style="font-size: 90%;" class="nav-link" href="{{route('admin.listar.actividades')}}">Actividades</a></li>
                {{-- <li><a style="font-size: 90%;" class="nav-link" href="{{route('admin.listar.donaciones')}}">Listar donación</a></li>
                <li><a style="font-size: 90%;" class="nav-link" href="{{route('admin.ingresar.donaciones')}}">Ingresar donación</a></li> --}}
            </ul>
        </li>
        <li class="{{ Route::is('admin.listar.sedes') ||
            Route::is('admin.listar.escuelas')||
            Route::is('admin.listar.carreras')||
            Route::is('admin.listar.ambitos')||
            Route::is('admin.listar.ambitosaccion')||
            Route::is('admin.listar.componente')||
            Route::is('admin.listar.centro-simulacion')||
            /* Route::is('admin.listar.programas')|| */
            Route::is('admin.listar.convenios')||
            Route::is('admin.listar.socios')||
            Route::is('admin.listar.mecanismos')||
            Route::is('admin.listar.grupos_int')||
            Route::is('admin.listar.subgrupos')||
            Route::is('admin.listar.tipoact')||
            Route::is('admin.listar.rrhh')||
            Route::is('admin.listar.tipoinfra')||
            Route::is('admin.listar.unidades')||
            Route::is('admin.listar.subunidades')
            ? 'dropdown active'
            : 'dropdown' }}">
            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="command"></i><span>Parámetros</span></a>
            <ul class="dropdown-menu">
                <li><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.sedes")}}">Sedes</a></li>
                <li><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.escuelas")}}">Escuelas</a></li>
                <li><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.aespecialidad")}}">Áreas de especialidad</a></li>
                <li><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.carreras")}}">Carreras</a></li>
                <li><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.centro-simulacion")}}">Centros de Simulación</a></li>
                <li><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.dispositivos")}}">Dispositivos</a></li>
                <li><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.asignaturas")}}">Asignaturas</a></li>
                <li><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.ambitos")}}">Impactos</a></li>
                <li><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.ambitosaccion")}}">Ambitos Acción</a></li>
                {{-- <li><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.componente")}}">Componentes</a></li> --}}
                <li><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.tipoact")}}">Instrumentos</a></li>
                {{-- <li><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.programas")}}">Programas</a></li> --}}
                <li style="padding-bottom: 1px;line-height: 15px;margin-top:5px;"><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.convenios")}}">Documento de colaboración</a></li>
                <li><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.socios")}}">Socios Comunitarios</a></li>
                <li><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.mecanismos")}}">Mecanismos</a></li>
                <li><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.grupos_int")}}">Grupos de interés</a></li>
                <li><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.subgrupos")}}">Sub-Grupos de interés</a></li>
                <li><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.rrhh")}}">Tipos de RRHH</a></li>
                <li><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.tipoinfra")}}">Tipos de Infraestructuras</a></li>
                {{-- <li><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.tipoiniciativa")}}">Tipos de iniciativa</a></li> --}}
                <li><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.unidades")}}">Unidades</a></li>
                <li><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.subunidades")}}">SubUnidades</a></li>
                {{-- <li><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.tematica")}}">Tematicas</a></li> --}}
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="star"></i><span>Impactos Externos</span></a>
            <ul class="dropdown-menu">
                <li><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.ods")}}">Agenda 2030</a></li>
            </ul>
        </li>
        {{-- <li class="dropdown">
            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="bar-chart-2"></i><span>Análisis de datos</span></a>
        </li> --}}
        {{-- <li class="dropdown">
            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="arrow-left-circle"></i><span>Extracción de datos</span></a>
        </li> --}}
        <li class="dropdown">
            <a href="javascript:void(0)" class="menu-toggle nav-link has-dropdown"><i data-feather="users"></i><span>Usuarios</span></a>
            <ul class="dropdown-menu">
                <li><a style="font-size: 90%;" class="nav-link" href="{{route("admin.listar.usuarios")}}">Listado de usuarios</a></li>
            </ul>
        </li>

@endsection

@section('contenido')
