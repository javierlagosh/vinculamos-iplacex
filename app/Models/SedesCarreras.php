<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SedesCarreras extends Model
{
    use HasFactory;

    protected $table = 'sedes_carreras';

    public $timestamps = false;

    protected $fillable = [
        'sede_codigo',
        'care_codigo'
    ];
}
