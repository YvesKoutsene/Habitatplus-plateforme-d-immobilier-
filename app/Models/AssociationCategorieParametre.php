<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssociationCategorieParametre extends Model
{
    //
    use HasFactory;

    protected $fillable = ['keyassociationcategorie', 'id_categorie', 'id_parametre', 'createdby', 'updatedby'];

    public function categorie()
    {
        return $this->belongsTo(CategorieBien::class, 'id_categorie');
    }

    public function parametre()
    {
        return $this->belongsTo(ParametreCategorie::class, 'id_parametre');
    }

}
