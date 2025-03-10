<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'category',
        'contact_type',
        'recruiter_id'
    ];

    public function recruiter()  {
        return $this->belongsTo(User::class, 'recruiter_id');
    }
}
