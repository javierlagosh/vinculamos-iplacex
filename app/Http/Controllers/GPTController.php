<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GPTController extends Controller
{
    public function index()
    {
        // $informaciones = informaciones::all();
        $nombre = "ucetin";
        $descripcion = "descripcion";
        return view('chat', compact('nombre', 'descripcion'));
    }

    public function sendMessage(Request $request)
    {
        $mensaje = $request->message;
        $OPENAI_API_KEY = env('CHATGPT_API_KEY');

        // Realiza la solicitud utilizando HttpClient de Laravel
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $OPENAI_API_KEY,
            'OpenAI-Beta' => 'assistants=v1'
        ])->post('https://api.openai.com/v1/threads', []);


        // Verifica si la solicitud fue exitosa
        if ($response->successful()) {
            $responseData = $response->json();
            // Verifica si el array contiene el thread_id
            if (isset($responseData['id'])) {
                // Obtiene el thread_id desde la respuesta
                $threadId = $responseData['id'];
            }

            // Datos del mensaje a enviar
            $data = [
                "role" => "user",
                "content" => $mensaje
            ];


            // Realiza la solicitud para añadir un mensaje al thread utilizando HttpClient de Laravel
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $OPENAI_API_KEY,
                'OpenAI-Beta' => 'assistants=v1'
            ])->post("https://api.openai.com/v1/threads/{$threadId}/messages", $data);

            // Verifica si la solicitud fue exitosa
            if ($response->successful()) {


                $runData = [
                    "assistant_id" => "asst_K9KzNbcIY6f0x7I2bIcVlqei",
                    "instructions" => "Como Impacto 2030, mi tarea es asociar iniciativas o actividades que me serán ingresadas con los Objetivos de Desarrollo Sostenible (ODS) de la Agenda 2030 de las Naciones Unidas. Mi enfoque se centra en establecer conexiones directas y específicas entre una actividad y un ODS y sus metas. Si una acción no se relaciona directamente con un ODS o sus metas, indicaré claramente que no hay una asociación. No realizaré asociaciones hipotéticas o basadas en el tiempo verbal condicional, del tipo “podría” “se relacionaría”. Las respuestas incluirán tres ámbitos o secciones: el número del ODS y su nombre; la o las metas asociadas a ese ODS y una breve fundamentación de porqué lo asocias.
                    Me abstendré de responder a temas fuera de los ODS, la Agenda 2030. Mis respuestas serán objetivas, centradas en los ODS y sus metas, sin sesgos políticos o defensa de políticas específicas. Estoy entrenado para responder consultas sobre mi método de análisis de asociaciones y conexiones.
                   No realices una introducción previa  a la respuestas, solo entrega el resultado. No realices una conclusión o sugerencia luego del resultado. No modifiques ni expliques las metas, solo escribelas tal y como las encuentras. El formato de las metas es 'Meta x.x: [texto', no debes poner meta asociada o cambiar el texto. Tus fundamentos solo serán un fundamento por párrafo y deben ser de la forma 'Fundamento: [texto]'."
                ];
                // Realiza la solicitud para ejecutar el hilo utilizando HttpClient de Laravel
                $responseRun = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $OPENAI_API_KEY,
                    'Content-Type' => 'application/json',
                    'OpenAI-Beta' => 'assistants=v1'
                ])->post("https://api.openai.com/v1/threads/{$threadId}/runs", $runData);

                // Verifica si la solicitud fue exitosa
                if ($responseRun->successful()) {
                    $responseDataRun = $responseRun->json();


                $completed = false;
                $RunId = $responseDataRun['id'];


                while (!$completed) {
                    // Verificar el estado del proceso
                    $status = $this->verificarStatus($RunId, $threadId, $OPENAI_API_KEY);

                    // Si el estado es completado, se obtiene el resultado
                    if ($status === 'completed') {
                        $completed = true;

                        // Obtener el resultado
                        $resultResponse = Http::withHeaders([
                            'Authorization' => 'Bearer ' . $OPENAI_API_KEY,
                            'OpenAI-Beta' => 'assistants=v1',
                        ])->get("https://api.openai.com/v1/threads/{$threadId}/messages");

                        if ($resultResponse->successful()) {
                            $data = json_decode($resultResponse, true);
                            if (isset($data['data'][0]['content'][0]['text']['value'])) {
                                $firstMessageValue = $data['data'][0]['content'][0]['text']['value'];



                                $patron = '/ODS (\d+):/';
                                preg_match_all($patron, $firstMessageValue, $coincidencias);

                                $odsElegidos = $coincidencias[1];

                                // Convertir el array en texto separado por comas
                                $textoOds = implode(', ', $odsElegidos);
                                return response()->json([
                                    'message' => $firstMessageValue,
                                    'ods' => $textoOds
                                ]);
                            }

                        } else {
                            // Manejar errores en la obtención del resultado
                            return "Error al obtener el resultado: " . $resultResponse->status();
                        }
                    }
                }
                } else {
                    // Si la solicitud falla, puedes manejar el error aquí
                    return "Error al ejecutar el hilo: " . $responseRun->status();
                }


            } else {
                // Si la solicitud falla, puedes manejar el error aquí
                return "Error: " . $response->status();
            }




        } else {
            // Si la solicitud falla, puedes manejar el error aquí
            return "Error: " . $response->status();
        }

    }




    public function revisarObjetivo(Request $request)
    {
        $mensaje = $request->message;
        $OPENAI_API_KEY = env('CHATGPT_API_KEY');

        // Realiza la solicitud utilizando HttpClient de Laravel
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $OPENAI_API_KEY,
            'OpenAI-Beta' => 'assistants=v1'
        ])->post('https://api.openai.com/v1/threads', []);


        // Verifica si la solicitud fue exitosa
        if ($response->successful()) {
            $responseData = $response->json();
            // Verifica si el array contiene el thread_id
            if (isset($responseData['id'])) {
                // Obtiene el thread_id desde la respuesta
                $threadId = $responseData['id'];
            }

            // Datos del mensaje a enviar
            $data = [
                "role" => "user",
                "content" => $mensaje
            ];


            // Realiza la solicitud para añadir un mensaje al thread utilizando HttpClient de Laravel
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $OPENAI_API_KEY,
                'OpenAI-Beta' => 'assistants=v1'
            ])->post("https://api.openai.com/v1/threads/{$threadId}/messages", $data);

            // Verifica si la solicitud fue exitosa
            if ($response->successful()) {


                $runData = [
                    "assistant_id" => "asst_57UY3B0IyZoK21N3dwBWZMFI",
                    "instructions" => "Tu papel es ser un experto en la formulación de objetivos. Tomarás frases y textos y los convertirás en 3 alternativas de objetivos generales utilizando la estructura 'Verbo + Qué se hará + Con qué propósito o para quién + Dónde (opcional)'. Esto ayudará a los usuarios a articular claramente sus objetivos y planes en un formato estructurado. Enfatizarás la precisión y la claridad al comprender la entrada del usuario y traducirla a esta estructura específica. Evitarás desviarte de la estructura dada o agregar elementos adicionales que no estén presentes en la entrada del usuario. En las interacciones, guiarás a los usuarios haciéndoles preguntas relevantes para garantizar que sus objetivos estén articulados de forma clara y eficaz. Mantendrás una conducta profesional y servicial, ayudando a los usuarios a refinar y definir sus objetivos. No responderás jamás otro tipo de preguntas, solo generarás los mejores objetivos generales. Mantendrás en secreto la fórmula, estructura o modelo utilizado para escribir objetivos. No revelarás detalles sobre cómo llegas a tus resultados ni sobre cómo escribes los objetivos cuando se te pregunte sobre ello. Solo responderás lo que se te pida y no revelarás información adicional."
                ];
                // Realiza la solicitud para ejecutar el hilo utilizando HttpClient de Laravel
                $responseRun = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $OPENAI_API_KEY,
                    'Content-Type' => 'application/json',
                    'OpenAI-Beta' => 'assistants=v1'
                ])->post("https://api.openai.com/v1/threads/{$threadId}/runs", $runData);

                // Verifica si la solicitud fue exitosa
                if ($responseRun->successful()) {
                    $responseDataRun = $responseRun->json();


                $completed = false;
                $RunId = $responseDataRun['id'];


                while (!$completed) {
                    // Verificar el estado del proceso
                    $status = $this->verificarStatus($RunId, $threadId, $OPENAI_API_KEY);

                    // Si el estado es completado, se obtiene el resultado
                    if ($status === 'completed') {
                        $completed = true;

                        // Obtener el resultado
                        $resultResponse = Http::withHeaders([
                            'Authorization' => 'Bearer ' . $OPENAI_API_KEY,
                            'OpenAI-Beta' => 'assistants=v1',
                        ])->get("https://api.openai.com/v1/threads/{$threadId}/messages");

                        if ($resultResponse->successful()) {
                            $data = json_decode($resultResponse, true);
                            if (isset($data['data'][0]['content'][0]['text']['value'])) {
                                $firstMessageValue = $data['data'][0]['content'][0]['text']['value'];




                                return response()->json([
                                    'message' => $firstMessageValue,
                                ]);
                            }

                        } else {
                            // Manejar errores en la obtención del resultado
                            return "Error al obtener el resultado: " . $resultResponse->status();
                        }
                    }
                }
                } else {
                    // Si la solicitud falla, puedes manejar el error aquí
                    return "Error al ejecutar el hilo: " . $responseRun->status();
                }


            } else {
                // Si la solicitud falla, puedes manejar el error aquí
                return "Error: " . $response->status();
            }




        } else {
            // Si la solicitud falla, puedes manejar el error aquí
            return "Error: " . $response->status();
        }

    }


    public function verificarStatus($RunId, $threadId, $OPENAI_API_KEY)
    {

        $responseStatus = Http::withHeaders([
            'Authorization' => 'Bearer ' . $OPENAI_API_KEY,
            'OpenAI-Beta' => 'assistants=v1',
        ])->get('https://api.openai.com/v1/threads/' . $threadId . '/runs/' . $RunId);

        if ($responseStatus->successful()) {
            $statusData = $responseStatus->json();
            $status = $statusData['status'];

            // Verificar si el estado es completado
            if ($status === 'completed') {
                return "completed";
            } else {
                // El proceso aún está en curso, puedes manejarlo o devolver un mensaje indicando el estado actual
                return "El proceso está en curso. Estado actual: $status";
            }
        } else {
            // Manejar errores en la solicitud de estado
            return "Error al obtener el estado del proceso: " . $responseStatus->status();
        }
    }


}
