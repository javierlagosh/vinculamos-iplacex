<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IniciativasEscuelas extends Model
{
    use HasFactory;

    protected $table = 'iniciativas_escuelas';

    public $timestamps = false;

    protected $fillable = [
        'inic_codigo',
        'sede_codigo',
        'escu_codigo',
        'care_codigo',
        'tipo'
    ];
}
