<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  <title>Unirse a una Encuesta </title>
</head>
<body style="background: #6777ef;">
  <div class="mt-5 w-100">
    <div class="center-block">
            <img  style="display: block; margin-left: auto; margin-right: auto;" width="300px" alt="Logo" src="{{ asset('/img/logos/logo1.webp') }}" class="header-logo">

            <hr style="margin-left: 20%; margin-right: 20%;
            border: 0;
            background-color: white;
            width: 60%;
            border-top: 1px solid rgba(0, 0, 0, 0.1);" />

    </div>
    <div class="container bg-white w-100 p-5 rounded-bottom">
      <h1 class="text-center ">Formulario de adhesión a una encuesta</h1>
      <p class="text-center ">Por favor, ingresa tu información para que seas invitad@ a la encuesta
        <strong>"{{$iniciativa->inic_nombre}}"</strong>
      </p>
        @if (Session::has('exito'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exito') }}</strong>
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
          <form action="{{ route('evaluacion.auto.invitarse') }}" method="POST">
          @csrf
          <div class="form-group">
            <input type="text" name="tipo" value="{{$tipo}}" hidden>
            <input type="text" name="evatotal_codigo" value="{{$evatotal_codigo}}" hidden>
            <input type="text" name="inic_codigo" value="{{$inic_codigo}}" hidden>

            <label for="nombre">
              Nombre
            </label>
            <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombre completo" required />
          </div>
          <div class="form-group">

            <label for="email">
              Correo electrónico
            </label>
            <input type="email" name="email" class="form-control" id="email" placeholder="correo@iplacex.cl" required />
          </div>
          <button type="submit" class="btn btn-primary">
            Solicitar invitación
          </button>
        </form>
    </div>
  </div>
</body>
</html>
