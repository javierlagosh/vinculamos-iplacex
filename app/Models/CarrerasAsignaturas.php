<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarrerasAsignaturas extends Model
{
    use HasFactory;

    protected $table = 'carreras_asignaturas';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'care_codigo',
        'asignatura_id'
    ];
}
