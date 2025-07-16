<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoBien extends Model
{
    use HasFactory;

    protected $fillable = ['keyvideo', 'url_video', 'id_bien', 'createdby', 'updatedby'];

    public function bien()
    {
        return $this->belongsTo(Bien::class, 'id_bien');
    }
}
