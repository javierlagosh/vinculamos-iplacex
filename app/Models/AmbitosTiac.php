<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmbitosTiac extends Model
{
    use HasFactory;

    protected $table = 'ambito_tiac';

    public $timestamps = false;

    protected $fillable = [
        'amb_codigo',
        'tiac_codigo',
    ];
}
