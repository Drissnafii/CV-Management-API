<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'job_offer_id',
        'cv_id',
        'cover_letter',
        'status'
    ];

    /**
     * Get the user that owns the application.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the job offer that the application is for.
     */
    public function jobOffer()
    {
        return $this->belongsTo(JobOffer::class);
    }

    /**
     * Get the CV associated with the application.
     */
    public function cv()
    {
        return $this->belongsTo(CV::class);
    }
}
