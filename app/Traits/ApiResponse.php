<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait ApiResponse{

    private function successResponse($data,$code){
        return response()->json($data,$code);
    }

    protected function errorResponse($message,$code){
        return response()->json(['message' => $message, 'code' => $code],$code);
    }

    protected function showAll(Collection $collection,$code = 200){
        return $this->successResponse(['data' => $collection],$code);
    }

    protected function showOne(Model $model,$message=null,$code = 200){
        return $this->successResponse(['data' => $model,'message' => $message,"success" => true],$code);
    }

    protected function showMessage($message,$success=true,$code = 200){
        return $this->successResponse(['data' => $message,'success'=>$success],$code);
    }

}


