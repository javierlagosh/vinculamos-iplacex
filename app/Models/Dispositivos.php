<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispositivos extends Model
{
    use HasFactory;

    protected $table = 'dispositivo';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
        'meta_adm',
        'meta_edu',
        'meta_salud',
        'meta_tec',
        'meta_gastr',
        'meta_inf',
        'meta_const',
        'meta_desa',
    ];
}
