<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieTicket extends Model
{
    //
    use HasFactory;

    protected $fillable = ['keycategorieticket', 'nom_categorie', 'description', 'statut', 'createdby', 'updatedby'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'id_categorie');
    }
}
