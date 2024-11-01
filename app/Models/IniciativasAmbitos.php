<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mecanismos;

class IniciativasAmbitos extends Model
{
    use HasFactory;

    protected $table = 'iniciativas_ambitos';
    public $timestamps = false;

    protected $fillable = [
        'inic_codigo',
        'amb_codigo'
    ];
}
