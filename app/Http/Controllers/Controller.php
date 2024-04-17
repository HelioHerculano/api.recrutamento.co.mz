<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\URL;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function saveAttachment($attachment,$path = 'attachments'){
        if(!$attachment){
            return null;
        }

        $attachment->store('',$path);
        dd($attachment);
    }
}
