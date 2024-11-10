<!DOCTYPE html>
<html>
<head>
    <title>Respuesta de la API</title>
</head>
<body>

    <h1>Imagen devuelta por la API</h1>

    @if(!empty($imageData))
        <img src="data:image/png;base64,{{ $imageData }}" alt="Imagen">
    @else
        <p>No se recibió una imagen de la API.</p>
    @endif

</body>
</html>
