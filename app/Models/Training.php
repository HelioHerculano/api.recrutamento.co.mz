<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'designation',
        'institution',
        'start_date',
        'end_date',
        'training_type_id',
        'candidate_id',
    ];

    public function trainingType(){
        return $this->belongsTo(TrainingType::class);
    }
    public function candidate(){
        return $this->belongsTo(Candidate::class);
    }
}
