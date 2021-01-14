<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\User as Model;

class Client extends Model
{
    //Fields
    protected $fillable = [
        'razon_social','tipo_persona','rfc', 'ciudad', 'estado', 'email','contraseña','telefono','celular','pagina_web','facebook','twitter','instagram','es_lucrativa','r_legal','banco','cta_bancaria', 'clave_interbancaria','imss','ace_stps','registrado_en'
    ];
    public $timestamps = false;

    public function getAuthPassword(){
        return $this->contraseña;
    }
}
