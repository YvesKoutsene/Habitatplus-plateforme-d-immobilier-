<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{
    use HasFactory;

    protected $fillable = ['keyabonnement', 'montant', 'duree', 'id_user', 'modele_id', 'date_début', 'date_fin', 'statut', 'createdby', 'updatedby'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function modele()
    {
        return $this->belongsTo(ModeleAbonnement::class, 'modele_id');
    }

    // Pour vérification de l'expiration de l'abonnement au cas ou on l'a pas mis à jour
        # Convertion necessaire
    protected $casts = [
        'date_début' => 'datetime',
        'date_fin' => 'datetime',
    ];
        # Ensuite la vérification
    public function isExpired(): bool
    {
        return $this->date_fin ? $this->date_fin->lt(now()) : true;
    }
}
