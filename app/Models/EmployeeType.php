<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeType extends Model
{
    use HasFactory;

    const INACTIVE = '0';
    const ACTIVE = '1';

    public function candidates(){
        return $this->hasMany(Candidate::class);
    }
}
