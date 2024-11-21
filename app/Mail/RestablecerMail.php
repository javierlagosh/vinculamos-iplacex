<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $correo;
    public $nombre;
    public $encriptacion;
    public $tipo;
    public $nombre_iniciativa;
    public $html;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($correo, $nombre, $encriptacion, $cuerpo)
    {
        $this->correo = $correo;
        $this->nombre = $nombre;
        $this->encriptacion = $encriptacion;
        $this->cuerpo = $cuerpo;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Has sido invitado a responder una breve una encuesta sobre una iniciativa ')
                     ->view('email.contact-form-mail')->with(['correo' => $this->correo, 'nombre' => $this->nombre, 'encriptacion' => $this->encriptacion, 'cuerpo' => $this->cuerpo]);
    }
}
