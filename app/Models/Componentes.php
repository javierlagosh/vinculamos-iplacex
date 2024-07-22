<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Componentes extends Model
{
    use HasFactory;

    protected $table = 'componentes';

    public $timestamps = false;

    protected $primaryKey = 'comp_codigo';

    protected $fillable = [
        'comp_codigo',
        'comp_nombre',
        'comp_visible',
        'comp_creado',
        'comp_actualizado',
        'comp_nickname_mod',
        'comp_rol_mod'
    ];
}
