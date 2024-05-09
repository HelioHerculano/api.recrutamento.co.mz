<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasFactory,SoftDeletes;

    const ACTIVE = '1';
    const INACTIVE = '2';

    protected $fillable = [
        'designation',
        'area_id',
        'start_date',
        'end_date',
        'description'
    ];

    public function area(){
        return $this->belongsTo(Area::class);
    }
}
