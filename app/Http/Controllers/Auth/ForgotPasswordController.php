<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Models\Usuarios;
use Hash;
use Illuminate\Support\Str;

use Mailjet\Client;
use Mailjet\Resources;

class ForgotPasswordController extends Controller
{

    public function showForgetPasswordForm()
    {
        return view('auth.passwords.email');
    }

    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:usuarios,usua_email',
        ]);

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('email.forgetPassword', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return back()->with('message', '¡Hemos enviado el enlace para restablecer tu contraseña por correo electrónico!');
    }

    public function showResetPasswordForm($token)
    {
        $usuario = DB::table('password_resets')
            ->where('token', $token)
            ->first();
        return view('auth.passwords.forgetPasswordLink', ['token' => $token, 'usuario' => $usuario]);
    }

    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:usuarios,usua_email',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Token  inválido!');
        }

        $user = Usuarios::where('usua_email', $request->email)
            ->update(['usua_clave' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();

        return redirect('/')->with('message', 'La contraseña ha sido cambiada correctamente!');
    }



    public function sendRecoveryEmail(Request $request)
    {

        // Validar el email
        $request->validate([
            'email' => 'required|email'
        ]);

        // Crear instancia de Mailjet Client
        $mj = new Client(env('MJ_APIKEY_PUBLIC'), env('MJ_APIKEY_PRIVATE'), true, ['version' => 'v3.1']);

        // Obtener el usuario
        $email = $request->email;
        $usuario = Usuarios::where('usua_email', $email)->first();

        // Verificar si el usuario existe
        if (!$usuario) {
            return redirect()->back()->with('error', 'No se encontró un usuario con ese correo.');
        }

        // Generar el token para la recuperación
        $token = Str::random(64);

        // Insertar el token en la tabla password_resets
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        // Crear el mensaje
        $mensaje = "Hola, " . $usuario->usua_nombre . ".<br>Para restablecer tu contraseña, haz clic en el siguiente enlace: <a href='" . env('URL_EVALUACIONES'). 'reset-password/' . $token . "'>Restablecer contraseña</a><br>Si no solicitaste restablecer tu contraseña, ignora este mensaje.<br>Saludos,<br>Equipo de VINCULAMOS IPLACEX";

        // Configurar el cuerpo del mensaje
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => env('SENDER_EMAIL'),
                        'Name'  => "VINCULAMOS IPLACEX"
                    ],
                    'To' => [
                        [
                            'Email' => $email,
                            'Name' => $usuario->usua_nombre
                        ]
                    ],
                    'Subject' => 'Recuperación de contraseña - VINCULAMOS IPLACEX',
                    'HTMLPart' => $mensaje,
                ]
            ]
        ];

        try {
            // Enviar el correo
            $response = $mj->post(Resources::$Email, ['body' => $body]);

            // Verificar si se envió correctamente
            if ($response->success()) {
                return redirect()->back()->with('exito', '¡Correo enviado correctamente!');
            } else {
                return redirect()->back()->with('error', 'Error al enviar el correo.');
            }
        } catch (\Exception $e) {
            // Capturar cualquier excepción y retornar error
            return redirect()->back()->with('error', 'Ocurrió un error al intentar enviar el correo: ' . $e->getMessage());
        }
    }

}
