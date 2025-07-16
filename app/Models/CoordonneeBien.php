<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoordonneeBien extends Model
{
    use HasFactory;

    protected $fillable = ['keycoordonnee', 'latitude', 'longitude', 'id_bien', 'createdby', 'updatedby'];

    public function bien()
    {
        return $this->belongsTo(Bien::class, 'id_bien');
    }
}
