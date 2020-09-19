<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Model;

class Admin extends Model
{
    use HasFactory, Notifiable;

    //Fields
    protected $fillable = [
        'email','contraseña'
    ];
    public $timestamps = false;

    public function getAuthPassword(){
        return $this->contraseña;
    }
}
