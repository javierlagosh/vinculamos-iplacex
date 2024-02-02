<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoActividades extends Model
{
    use HasFactory;

    protected $table = 'tipo_actividades';

    public $timestamps = false;

    protected $primaryKey = 'tiac_codigo';


    protected $fillable = [
        'tiac_codigo',
        'comp_codigo',
        'tiac_nombre',
        'tiac_meta_iniciativas',
        'tiac_meta_estudiantes',
        'tiac_meta_docentes',
        'tiac_meta_socios',
        'tiac_meta_beneficiarios',
        'tiac_meta_egresados',
        'tiac_visible',
        'tiac_creado',
        'tiac_actualizado',
        'tiac_nickname_mod',
        'tick_rol_mod'
    ];

    protected $attributes = [
        'tiac_visible' => 1, // Establece el valor predeterminado para 'tiac_visible' como 1
    ];
}
