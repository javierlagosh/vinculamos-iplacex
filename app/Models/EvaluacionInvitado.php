<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionInvitado extends Model
{
    use HasFactory;

    protected $table = 'evaluacion_invitado';

    protected $primaryKey = 'evainv_codigo';

    public $timestamps = false;

    protected $fillable = [
        'evatotal_codigo',
        'inic_codigo',
        'evainv_nombre',
        'evainv_correo',
        'evainv_estado',
        'evatotal_tipo'
    ];
}
