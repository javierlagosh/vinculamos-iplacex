<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mecanismos;

class CentroSimulacion extends Model
{
    use HasFactory;

    protected $table = 'centro_simulacion';
    protected $primaryKey = 'cs_codigo';
    public $timestamps = false;

    protected $fillable = [
        'cs_nombre'
    ];
}
