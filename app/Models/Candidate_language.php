<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate_language extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'language_id',
        'level_id'
    ];

    public function language(){
        return $this->belongsTo(Language::class);
    }

    public function level(){
        return $this->belongsTo(Level::class);
    }
}
