<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DispositivosTiac extends Model
{
    use HasFactory;

    protected $table = 'dispositivos_tiac';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'dispositivo_id',
        'tiac_codigo'
    ];
}
