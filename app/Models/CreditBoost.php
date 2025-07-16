<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditBoost extends Model
{
    use HasFactory;

    protected $table = 'credit_boosts';
    protected $fillable = ['keycredit', 'point', 'statut', 'id_user', 'createdby', 'updatedby'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }


}
