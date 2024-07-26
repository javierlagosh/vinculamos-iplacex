<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IniciativasAsignaturas extends Model
{
    use HasFactory;

    protected $table = 'iniciativas_asignaturas';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'inic_codigo',
        'asignaturas_id'

    ];
}
