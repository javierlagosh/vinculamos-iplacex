<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignaturas extends Model
{
    use HasFactory;

    protected $table = 'asignaturas';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
        'care_codigo',
        'descripcion'
    ];
}
