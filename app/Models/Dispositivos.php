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
        'nombre'
    ];
}
