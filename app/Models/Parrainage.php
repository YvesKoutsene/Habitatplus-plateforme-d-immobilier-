<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parrainage extends Model
{
    use HasFactory;

    protected $table = 'parrainages';
    protected $fillable = ['keyparrainage', 'code', 'statut', 'id_user', 'createdby', 'updatedby'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

}
