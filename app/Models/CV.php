<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CV extends Model
{
    protected $table = "cvs";
    protected $fillable = [
        'user_id',
        'title',
        'file_path',
        'file_name',
        'file_size',
        'summary',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function applications() {
        return $this->hasMany(JobApplication::class);
    }
}
