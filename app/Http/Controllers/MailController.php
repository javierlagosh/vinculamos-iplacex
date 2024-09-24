<?php

namespace App\Http\Controllers;
use App\Models\EvaluacionInvitado;
use Illuminate\Http\Request;
use Mailjet\Client;
use Mailjet\Resources;
//response



class MailController extends Controller
{
    public function sendEmail(Request $request)
    {
        // Crear instancia de Mailjet Client
        $mj = new Client(env('MJ_APIKEY_PUBLIC'), env('MJ_APIKEY_PRIVATE'), true, ['version' => 'v3.1']);

        // Convertir el string de destinatarios en array usando explode
        $emails = explode(',', $request->destinatarios);

        // Crear un array de destinatarios en el formato necesario
        $recipients = [];
        if($request->destinatarios == null){
            return redirect()->back()->with('error', 'No se ha ingresado ningún destinatario');
        }
        foreach ($emails as $email) {
            $recipients[] = [
                'Email' => trim($email),  // Usar trim para eliminar espacios en blanco
                'Name'  => 'Destinatario'
            ];
        }

        // Configurar el cuerpo del mensaje
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => env('SENDER_EMAIL'),
                        'Name'  => env('SENDER_NAME')
                    ],
                    'To' => $recipients, // Usar array de destinatarios
                    'Subject' => $request->asunto,
                    'HTMLPart' => $request->mensaje,
                ]
            ]
        ];

        // Enviar el correo
        $response = $mj->post(Resources::$Email, ['body' => $body]);

        // Verificar si se envió correctamente
        if ($response->success()) {
            //cambiar el estado de enviado a los emails
            foreach ($emails as $email) {
                $email = trim($email);
                $evaluacionInvitado = EvaluacionInvitado::where('evainv_correo', $email)
                ->where('inic_codigo', $request->iniciativa_codigo)
                ->where('evatotal_tipo', $request->invitado)
                ->where('evainv_estado', 0)
                ->first();
                if($evaluacionInvitado){
                    $evaluacionInvitado->evainv_estado = 1;
                    $evaluacionInvitado->save();
                }
            }

            //return back
            return redirect()->back()->with('exito', '¡Correo(s) enviado(s) correctamente!');
        } else {
            //return back
            return redirect()->back()->with('error', 'Error al enviar el correo');
        }
    }
}
