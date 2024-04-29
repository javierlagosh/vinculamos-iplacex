<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IniciativasDispositivos extends Model
{
    use HasFactory;

    protected $table = 'iniciativas_dispositivos';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'inic_codigo',
        'dispositivo_id'
    ];
}
