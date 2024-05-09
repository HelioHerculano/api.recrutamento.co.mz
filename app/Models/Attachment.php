<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable =[
        'path',
        'document_type_id',
        'candidate_id'
    ];
    public function documentType(){
        return $this->belongsTo(DocumentType::class);
    }

    public function candidate(){
        return $this->belongsTo(Candidate::class);
    }
}
