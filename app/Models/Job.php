<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    const INACTIVE = '0';
    const ACTIVE = '1';

    protected $fillable = [
        'designation',
        'area_id',
    ];
}
