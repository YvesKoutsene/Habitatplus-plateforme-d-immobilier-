<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favori extends Model
{
    //
    use HasFactory;

    protected $fillable = ['keyfavoris', 'id_user', 'id_bien', 'createdby', 'updatedby'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function bien()
    {
        return $this->belongsTo(Bien::class, 'id_bien');
    }

}
