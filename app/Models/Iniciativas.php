<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mecanismos;

class Iniciativas extends Model
{
    use HasFactory;

    protected $table = 'iniciativas';
    protected $primaryKey = 'inic_codigo';
    public $timestamps = false;

    protected $fillable = [
        'inic_codigo',
        'conv_codigo',
        'prog_codigo',
        'meca_codigo',
        'dispositivo_id',
        'inic_nombre',
        'inic_territorio',
        'inic_brecha',
        'inic_bimestre',
        'inic_macrozona',
        'inic_escuela_ejecutora',
        'inic_diagnostico',
        'inic_descripcion',
        'inic_estado',
        'inic_anho',
        'inic_desde',
        'inic_hasta',
        'inic_responsable',
        'inic_creado',
        'inic_actualizado',
        'inic_nickname_mod',
        'inic_rol_mod'
    ];
}
