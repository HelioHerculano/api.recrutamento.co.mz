<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Neighborhood extends Model
{
    use HasFactory;

    public function candidates(){
        return $this->hasMany(Candidate::class);
    }

    public function province(){
        return $this->belongsTo(Province::class);
    }
}
