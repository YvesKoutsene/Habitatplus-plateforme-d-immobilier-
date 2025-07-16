<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PhotoBien extends Model
{
    //
    use HasFactory;

    protected $fillable = ['keyphoto', 'url_photo', 'id_bien', 'createdby', 'updatedby'];

    public function bien()
    {
        return $this->belongsTo(Bien::class, 'id_bien');
    }
}
