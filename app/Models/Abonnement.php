<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{
    //
    use HasFactory;

    protected $fillable = ['keyabonnement', 'duree', 'id_user', 'modele_id', 'date_début', 'date_fin', 'statut', 'createdby', 'updatedby'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function modele()
    {
        return $this->belongsTo(ModeleAbonnement::class);
    }

}
