<?php

namespace App\Http\Controllers;

use App\Models\Planes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PlanesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Planes::all();
        return view('planes.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('planes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'saldo' => ['required'],
        ],
        [
            'name.required' => 'El campo Nombre es obligatorio',
            'saldo.required' => 'El campo saldo es obligatorio',
            
        ]);
        
        $registro = new Planes();
        $registro->name = $request->name;
        $registro->precio = $request->name;
        $registro->saldo = $request->saldo;
        $registro->descripcion = $request->descripcion;
        $registro->save();



        return redirect('planes')->with('success', 'Registro Guardado exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ciudades  $ciudades
     * @return \Illuminate\Http\Response
     */
    public function show(Ciudades $ciudades)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ciudades  $ciudades
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $count = Planes::where('id', $id)->count();
        if ($count>0) {
            $data = Planes::where('id', $id)->first();
            $data->descripcion = str_replace("<p>", "", $data->descripcion);
            $data->descripcion = str_replace("</p>", "", $data->descripcion);
            return view('planes.edit', compact('data'));
        } else {
            return redirect('/paises')->with('danger', 'Problemas para Mostrar el Registro.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required'],
            'saldo' => ['required'],
        ],
        [
            'name.required' => 'El campo Nombre es obligatorio',
            'saldo.required' => 'El campo saldo es obligatorio',
            
        ]);

        $count = Planes::where('id', $id)->count();
        
        if ($count>0) {
            $registro = Planes::where('id', $id)->first();
            $registro->name = $request->name;
            $registro->precio = intval($request->precio);
            $registro->saldo = $request->saldo;
            $registro->descripcion = $request->descripcion;
            $registro->save();

            return redirect('/planes')->with('success', 'Registro Actualizado Exitosamente!');
        } else {
            return redirect('/planes')->with('danger', 'Problemas para Actualizar el Registro.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ciudades  $ciudades
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $count = Planes::where('id', $id)->count();
        if ($count>0) {
            Planes::where('id', $id)->delete();
            return redirect('/planes')->with('success', 'Registro Eliminado Exitosamente!');
        } else {
            return redirect('/planes')->with('danger', 'Problemas para Eliminar el Registro.');
        }
    }
}
