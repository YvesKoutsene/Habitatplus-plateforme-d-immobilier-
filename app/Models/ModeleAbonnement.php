<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModeleAbonnement extends Model
{
    use HasFactory;

    protected $fillable = ['keymodele', 'nom', 'description', 'prix', 'duree', 'createdby', 'updatedby'];

    public function parametres()
    {
        return $this->belongsToMany(ParametreModele::class, 'association_modele_parametres', 'id_modele', 'id_parametre')
                    ->withPivot('valeur');
    }

    public function abonnements()
    {
        return $this->hasMany(Abonnement::class, 'modele_id');
    }

}
