<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pivoteOds extends Model
{
    use HasFactory;
    protected $table = 'pivote_ods';
    protected $primaryKey = 'id'; // Nombre de la clave primaria autoincremental
    public $timestamps = false;

    protected $fillable = [
        'inic_codigo',
        'id_ods',
    ];
}
