<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ods extends Model
{
    use HasFactory;

    protected $table = 'ods';
    protected $primaryKey = 'id_ods';
    public $timestamps = false;

    protected $fillable = [
        'nombre_ods'
    ];

}
