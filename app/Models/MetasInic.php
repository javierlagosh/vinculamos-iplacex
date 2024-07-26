<?php

namespace App\Models;

use App\Models\Iniciativas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MetasInic extends Model
{
    use HasFactory;
    protected $table = 'metas_inic';
    protected $primaryKey = 'id'; // Nombre de la clave primaria autoincremental
    public $timestamps = false;
    protected $fillable = [
        'inic_codigo',
        'meta_ods',
        'desc_meta',
        'fundamento'
    ];

    public function iniciativa()
    {
        return $this->belongsTo(Iniciativas::class, 'inic_codigo', 'inic_codigo');
    }
}
