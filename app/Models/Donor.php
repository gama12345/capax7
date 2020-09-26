<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory;

    //Fields
    protected $fillable = [
        'razon_social','tipo_persona','rfc','nacionalidad','email','telefono','domicilio','aportacion_mensual','celular'
    ];
    public $timestamps = false;
}
