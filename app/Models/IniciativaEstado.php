<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IniciativaEstado extends Model
{
    use HasFactory;

    protected $table = 'iniciativas_estado';
    // protected $primaryKey = 'inic_codigo';
    // public $incrementing = false;
    // protected $keyType = 'int';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'inic_codigo',
        'seccion',
        'motivo',
        'estado',
        'usua_nickname',
        'fecha_registro',
        'usua_nickname_corrector',
        'fecha_correccion',
        'fecha_validacion',
        'usua_nickname_validador',
    ];

    // Deshabilitar los timestamps (created_at, updated_at)
    public $timestamps = false;

    // Relación con el modelo Iniciativas
    public function iniciativa()
    {
        return $this->belongsTo(Iniciativas::class, 'inic_codigo');
    }
}
