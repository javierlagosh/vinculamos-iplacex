<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroCostos extends Model
{
    use HasFactory;

    protected $table = 'centro_costos';
    protected $primaryKey = 'ceco_codigo';
    public $timestamps = false;

    protected $fillable = [
        'ceco_nombre',
        'ceco_visible',
        'ceco_creado',
        'ceco_actualizado',
        'ceco_nickname_mod',
        'ceco_rol_mod'
    ];
}
