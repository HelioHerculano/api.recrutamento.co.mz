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
        // save image
        $filename = \Storage::disk($path)->put('', $attachment);
        //return the path
        // Url is the base url exp: localhost:8000
        return URL::to('/').'/storage/'.$path.'/'.$filename;
    }
}
