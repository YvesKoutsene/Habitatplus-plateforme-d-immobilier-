<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametreCategorie extends Model
{
    //
    use HasFactory;

    protected $fillable = [ 'keyparametrecategorie', 'nom_parametre', 'createdby', 'updatedby'];

    public function associations()
    {
        return $this->hasMany(AssociationCategorieParametre::class, 'id_parametre');
    }

}
