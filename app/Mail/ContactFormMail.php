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
    public function __construct($correo, $nombre, $encriptacion, $tipo, $nombre_iniciativa, $html)
    {
        $this->correo = $correo;
        $this->nombre = 'maxicartes';
        $this->encriptacion = 'asdasd';
        $this->tipo = 'tipo';
        $this->nombre_iniciativa = 'iniciativa';
        $this->html = $html;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Has sido invitado para rellenar una encuesta sobre la iniciativa: '.$this->nombre_iniciativa)
                     ->view('email.contact-form-mail')->with(['correo' => $this->correo, 'nombre' => $this->nombre, 'encriptacion' => $this->encriptacion, 'tipo' => $this->tipo, 'nombre_iniciativa' => $this->nombre_iniciativa, 'html' => $this->html]);
    }
}
