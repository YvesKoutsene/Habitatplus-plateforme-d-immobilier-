<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    use HasFactory;

    protected $fillable = ['keytransaction', 'montant', 'reference', 'statut', 'user_id', 'createdby', 'updatedby'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
