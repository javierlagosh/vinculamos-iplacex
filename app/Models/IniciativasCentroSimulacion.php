<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mecanismos;

class IniciativasCentroSimulacion extends Model
{
    use HasFactory;

    protected $table = 'iniciativas_centro_simulacion';
    public $timestamps = false;

    protected $fillable = [
        'inic_codigo',
        'cs_codigo'
    ];
}
