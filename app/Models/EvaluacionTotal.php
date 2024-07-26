<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionTotal extends Model
{
    use HasFactory;

    protected $table = 'evaluacion_total';

    protected $primaryKey = 'evatotal_codigo';

    public $timestamps = false;

    protected $fillable = [
        'inic_codigo',
        'evatotal_tipo',
        'evatotal_encriptado'
    ];
}
