<?php

namespace App\Http\Controllers\Area;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $area = Area::all();
        return $this->showAll($area);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
