<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoActividadesMetas extends Model
{
    use HasFactory;

    protected $table = 'tipoactividad_metas';

    public $timestamps = false;

    protected $primaryKey = 'tiacme_codigo';

    protected $fillable = [
        'tiacme_codigo',
        'tiac_codigo',
        'sede_codigo',
        'tiacme_meta',
        'tiacme_creado',
        'tiacme_actualizado',
        'tiacme_nickname_mod',
        'tiacme_rol_mod'
    ];
}
