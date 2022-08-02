<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function purchase_detail()
    {
        return $this->hasMany(PurchaseDetail::class, 'id_purchase');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
