<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    public function candidates(){
        return $this->hasMany(Candidate::class);
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }
}
