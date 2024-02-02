<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <title>Documento</title>
</head>
<body>
    <h1>ODS</h1>
    <input id="user-input" type="text" placeholder="Escribe tu objetivo aquí..." class="w-full rounded-lg border border-gray-300 px-4 py-2" />
    <button id="send-button" class="ml-2 rounded-lg px-4 py-2 text-white" style="background:#0078BB;">Evaluar</button>
    <div id="chat-container" class="chat-messages"></div>
    <div id="ods" class="ods"></div>
</body>
<script>
    $(document).ready(function() {
        $('#send-button').click(function() {
            enviarMensaje();
        });

        function enviarMensaje() {
            var userInput = $('#user-input').val();


            // Mostrar el mensaje del usuario en el chat container
            var userMessageHtml = `
                <div class="flex flex-col items-end">
                    <div class="flex flex-row">
                        <p class="ml-2 font-bold text-xl">Tú &nbsp;</p>
                    </div>
                    <div class="flex items-center self-end rounded-xl rounded-tr bg-blue-500 py-2 px-3 text-white mt-1">
                        <p>${userInput}</p>
                    </div>
                </div>`;

                console.log('{{ route("ENVIARMENSAJE") }}');
                console.log('{{ csrf_token() }}');



            $('#chat-container').html(userMessageHtml);

            $.ajax({
                url: '{{ route("ENVIARMENSAJE") }}',
                type: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'message': userInput
                },
                success: function(response) {
                    var respuestaBot = response.message;
                    var ods = response.ods;
                    console.log(ods);

                    try {
                        // Actualizar la interfaz con la respuesta del servidor
                        var botMessageHtml = `
                            <div class="flex flex-col items-start">
                                <div class="flex flex-row">
                                    <p class="ml-2 font-bold text-xl">Bot</p>
                                </div>
                                <div class="flex items-center self-start rounded-xl rounded-tl bg-gray-300 py-2 px-3 mt-1">
                                    <p>${respuestaBot}</p>
                                </div>
                            </div>`;

                        $('#chat-container').append(botMessageHtml);
                    } catch (error) {
                        console.error('Error al manejar la respuesta del servidor:', error);
                    }

                    // Hacer scroll hacia abajo
                    var chatContainer = document.getElementById('chat-container');
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                },
                error: function(xhr, status, error) {
                    console.error('Error en la petición AJAX:', error);
                }
            });

            $('#user-input').val(''); // Limpiar el campo de entrada
        }
    });
</script>
</html>
