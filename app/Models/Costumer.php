<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Costumer extends Model
{
    /** @use HasFactory<\Database\Factories\CostumerFactory> */
    use HasFactory;

    public function invoices()  {
        return $this->hasMany(Invoice::class);
    }
}
