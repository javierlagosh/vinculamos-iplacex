<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaEspecialidad extends Model
{
    use HasFactory;

    protected $table = 'area_especialidad';

    public $timestamps = false;

    protected $primaryKey = 'aes_codigo';

    protected $fillable = [
        'aes_nombre',
    ];

}
