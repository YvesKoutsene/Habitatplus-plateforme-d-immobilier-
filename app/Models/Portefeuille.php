<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portefeuille extends Model
{
    use HasFactory;

    protected $table = 'portefeuilles';
    protected $fillable = ['keyportefeuille', 'solde', 'statut', 'id_user', 'createdby', 'updatedby'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

}
