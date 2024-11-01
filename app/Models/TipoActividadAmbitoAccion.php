<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoActividadAmbitoAccion extends Model
{
    use HasFactory;

    protected $table = 'tipoactividad_ambitosaccion';

    public $timestamps = false;


    protected $fillable = [
        'tiac_codigo',
        'amac_codigo'
    ];
}
