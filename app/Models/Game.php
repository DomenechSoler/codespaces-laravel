<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = ['user_id', 'clicks', 'points', 'duration'];

    //Relació amb usuari: cada partida pertany a un usuari
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

