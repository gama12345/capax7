<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    //Fields
    protected $fillable = [
        'cantidad','fecha','donante','cliente'
    ];
    public $timestamps = false;
}
