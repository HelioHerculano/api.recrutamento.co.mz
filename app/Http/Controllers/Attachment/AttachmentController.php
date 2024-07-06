<?php

namespace App\Http\Controllers\Attachment;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttachmentController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $roles = [
            'document_type_id' => 'required',
            'candidate_id' => 'required',
            'attachment' => 'required|file'
        ];

        $attributes = [
            'document_type_id' => '"tipo de documento"',
            'candidate_id' => '"candidato"',
            'attachment' => '"anexo do documento"'
        ];

        $costumMessages = [
            'required' => 'O campo :attribute é obrigatorio',
            'file' => 'O campo :attribute deve ser um ficheiro valido'
        ];

        $validator = Validator::make($request->all(),$roles,$costumMessages,$attributes);

        if($validator->fails()){
            return $this->errorResponse($validator->errors(),422);
        }

        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $path = $this->saveAttachment($attachment,'attachments');
        }

        $attachment = Attachment::create([
            'path' => $path,
            'document_type_id' => $request->document_type_id,
            'candidate_id' => $request->candidate_id
        ]);

        return $this->showOne($attachment,"Documento adicionado com sucesso");
    }

    /**
     * Display the specified resource.
     */
    public function show(Attachment $attachment)
    {
        return $this->showOne($attachment);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $attachment = Attachment::findOrFail($id);

        $roles = [
            'document_type_id' => 'required',
            'attachment' => 'nullable'
        ];

        $attributes = [
            'document_type_id' => '"tipo de documento"',
            'attachment' => '"anexo do documento"'
        ];

        $costumMessages = [
            'required' => 'O campo :attribute é obrigatorio',
            'file' => 'O campo :attribute deve ser um ficheiro valido'
        ];

        $validator = Validator::make($request->all(),$roles,$costumMessages,$attributes);

        if($validator->fails()){
            return $this->errorResponse($validator->errors(),422);
        }


        if ($request->hasFile('attachment')) {
            $attach = $request->file('attachment');
            $path = $this->saveAttachment($attach,'attachments');
            $data = $request->except("attachment");
            $data['path'] = $path;
        }else{
            $data = $request->except("attachment");
        }

        $attachment->update($data);

        return $this->showOne($attachment,"Documento actualizado com sucesso");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attachment $attachment)
    {
        $attachment->delete();
        return $this->showMessage("Documento removido com sucesso");
    }
    public function upload(Request $request)
    {

        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $path = $this->saveAttachment($attachment,'attachments');
        }

        return response()->json(["path"=>$path],200);
    }
}
