<!DOCTYPE html>
<html>
<head>
    <title>gptbot</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="wrapper">
    <div id="chat-messages" class="overflow-y-scroll pr-5 h-[600px] bg-gray-100 scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch">

    </div>
    <div class="card-footer bg-gray-100" data-kt-element="footer">
        <div id="escrituraUcetin">&nbsp;</div>
        <div class="p-3">

            <div class="flex">
                <input id="user-input" type="text" placeholder="Escribe un mensaje" class="flex-1 border rounded-l-md py-2 px-3">

                <button id="send-button" class="bg-blue-500 text-white py-2 px-4 rounded-r-md">Enviar</button>
            </div>
        </div>
    </div>
    <div id="fotosods"></div>
</div>

<script>

function extraerMetas(respuesta) {

  const regexMetasNumericas = /Meta\s*(\d+(\.\d+)?)(?![a-zA-Z])/g;

  const regexMetasAlfanumericas = /Meta\s*(\d+\.[a-zA-Z])/g;

  const metasNumericas = [];
  const metasAlfanumericas = [];

  let matchNumerico;
  while ((matchNumerico = regexMetasNumericas.exec(respuesta)) !== null) {
    const valorNumerico = matchNumerico[2] ? matchNumerico[1] : null;
    if (valorNumerico !== null) {
      metasNumericas.push(valorNumerico);
    }
  }

  regexMetasAlfanumericas.lastIndex = 0;

  let matchAlfanumerico;
  while ((matchAlfanumerico = regexMetasAlfanumericas.exec(respuesta)) !== null) {
    metasAlfanumericas.push(matchAlfanumerico[1]);
  }

  const todasLasMetas = [...metasNumericas, ...metasAlfanumericas];

  return todasLasMetas;
}

function extraerFundamentos(respuesta) {
  // Expresión regular para extraer el fundamento
  const regexFundamento = /Fundamento:\s*([^]*?)(?=\s*(Meta|$))/g;

  // Array para almacenar todos los fundamentos encontrados
  const fundamentos = [];

  // Buscar todas las coincidencias con la expresión regular
  let matchFundamento;
  while ((matchFundamento = regexFundamento.exec(respuesta)) !== null) {
    const fundamento = matchFundamento[1].trim();
    fundamentos.push(fundamento);
  }

  return fundamentos;
}



$(document).ready(function() {
    $('#send-button').click(function() {
        enviarMensaje();
    });

    $('#user-input').keydown(function(event) {
        if (event.keyCode === 13) { // 13 es el código de la tecla "Enter"
            event.preventDefault();
            enviarMensaje();
        }
    });

    function enviarMensaje() {
        var userInput = $('#user-input').val();
        console.log(userInput);
        // Mostrar el mensaje del usuario en la derecha
        $('#chat-messages').append('<div>' + userInput + '</div>');

        // Enviar el mensaje al servidor
        $.ajax({
            url: '{{ route("admin") }}',
            type: 'POST',
            data: {
                '_token': '{{ csrf_token() }}',
                'message': userInput
            },
            success: function(response) {
                try {
                    escrituraUcetin.innerHTML = '&nbsp;';
                    var respuestaBot = response.message;
                    // var metas = metasNumericas.concat(metasAlfanumericas);
                    var fundamentos = [];
                    var fundamentoGeneral = [];
                    const metas = extraerMetas(respuestaBot);
                    fundamentos = extraerFundamentos(respuestaBot);

                    console.log("Metas Numéricas:", metas);
                    console.log("fundamento:", fundamentos);
                    // console.log('Fundamentos: ', metas);
                    // console.log('Fundamento General: ', fundamentoGeneral);
                    var ods = response.ods;
                    // ods a array
                    var odsArray = ods.split(',');
                    console.log(odsArray);
                    // Obtener el div donde se agregarán las fotos
                    var fotosDiv = document.getElementById("fotosods");

                    // Utilizamos una expresión regular para encontrar las cadenas después de los números y puntos
                    var matches = respuestaBot.match(/\d+\.\s([^0-9]+)(?=\s*\d+\.\s|$)/g);

                    // Limpiamos los resultados eliminando los números y puntos
                    var arrayRespuestas = matches.map(function(element) {
                        return element.replace(/\d+\.\s/, '');
                    });

                    console.log(arrayRespuestas);


                } catch (error) {
                    var respuestaBot = 'Lo siento, ha surgido un error.';
                }

                // Mostrar la respuesta del bot en la izquierda

                $('#chat-messages').append('<ul class="list-group"><li class="list-group-item">'+ arrayRespuestas[0] +'</li>'
                     + '<li class="list-group-item">'+ arrayRespuestas[1] +'</li>'
                     + '<li class="list-group-item">'+ arrayRespuestas[2] +'</li>'
                     + '</ul>' );
            }
        });

        $('#user-input').val(''); // Limpiar el campo de entrada
    }
});

</script>
</body>
</html>
