<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundamentoInic extends Model
{
    use HasFactory;

    protected $table = 'fundamento_inic';
    protected $primaryKey = 'id'; // Nombre de la clave primaria autoincremental
    public $timestamps = false;
    protected $fillable = [
        'inic_codigo',
        'fund_ods',
    ];

    public function iniciativa()
    {
        return $this->belongsTo(Iniciativas::class, 'inic_codigo', 'inic_codigo');
    }

}
