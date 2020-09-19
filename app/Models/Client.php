<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Model;

class Client extends Model
{
    //Fields
    protected $fillable = [
        'razon_social','tipo_persona','rfc','email','contraseña','telefono','celular','pagina_web','facebook','twitter','instagram'
    ];
    public $timestamps = false;

    public function getAuthPassword(){
        return $this->contraseña;
    }
}
