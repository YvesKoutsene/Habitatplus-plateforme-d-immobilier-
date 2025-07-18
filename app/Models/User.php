<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'keyuser',
        'name',
        'email',
        'password',
        'numero',
        'typeUser',
        'pays',
        'photo_profil',
        'motif_blocage',
        'statut',
        'createdby',
        'updatedby',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function biens()
    {
        return $this->hasMany(Bien::class, 'id_user');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_user');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'id_user');
    }

    // Pour renvoyer le code de parrainage d'un user
    public function parrainage()
    {
        return $this->hasOne(Parrainage::class, 'id_user')
                ->where('statut', 'actif');
    }

    // Pour renvoyer l'abonnement actif
    public function abonnementActif()
    {
        return $this->hasOne(Abonnement::class, 'id_user')
               ->where('statut', 'actif');
    }

    // Pour renvoyer l'energie du user
    public function creditboost()
    {
        return $this->hasOne(CreditBoost::class, 'id_user')
               ->where('statut', 'actif');
    }

    // Pour créer le portefeuille
    public function portefeuille()
    {
        return $this->hasOne(Portefeuille::class, 'id_user');
    }

    // Pour renovyer son portefeuille actif
    public function portefeuilleActif()
    {
        return $this->hasOne(Portefeuille::class, 'id_user')
               ->where('statut', 'actif');
    }

    // Pour créer le code de parrainage
    public function parrain()
    {
        return $this->hasOne(Parrainage::class, 'id_user');
    }

    // Pour lui créer et ou ajouter un credit boost
    public function credit()
    {
        return $this->hasOne(CreditBoost::class, 'id_user');
    }

}
