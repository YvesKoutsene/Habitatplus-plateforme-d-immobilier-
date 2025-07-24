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

    // Pour récuperer le modele d'abonnement Freemium
    public static function getModeleFreemium(): ?self
    {
        return self::whereRaw('LOWER(nom) = ?', ['freemium'])
                   ->orWhere('prix', 0)
                   ->orderBy('id')
                   ->first();
    }

    // Pour renvoyer la valeur d'un parametre d'un modele d'abonnement
    public function getValeurParametre(string $nomParametre): ?string
    {
        return $this->parametres()
            ->where('parametre_modeles.nom_parametre', $nomParametre)
            ->first()?->pivot->valeur;
    }

}
