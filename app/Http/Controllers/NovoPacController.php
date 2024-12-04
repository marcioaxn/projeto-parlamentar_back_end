<?php

namespace App\Http\Controllers;

use App\Models\TabNovoPac;

use Illuminate\Http\Request;

class NovoPacController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $cod_pac
     * @return \Illuminate\Http\Response
     */
    public function show($cod_pac)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $cod_pac
     * @return \Illuminate\Http\Response
     */
    public function edit($cod_pac)
    {
        $novoPac = TabNovoPac::find($cod_pac);

        dd($novoPac);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $cod_pac
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $cod_pac)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $cod_pac
     * @return \Illuminate\Http\Response
     */
    public function destroy($cod_pac)
    {
        //
    }
}
