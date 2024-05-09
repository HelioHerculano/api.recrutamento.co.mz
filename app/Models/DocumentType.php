<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;

    const INACTIVE = '0';
    const ACTIVE = '1';
    public function attachments(){
        return $this->hasMany(Attachment::class);
    }

}
