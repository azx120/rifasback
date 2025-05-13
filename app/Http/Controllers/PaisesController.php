<?php

namespace App\Http\Controllers;

use App\Models\Paises;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaisesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Paises::all();
        return view('paises.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('paises.create');
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
            'name' => ['required', 'unique:categorys'],
        ],
        [
            'name.required' => 'El campo Nombre es obligatorio',
            'name.unique' => 'El valor del campo Nombre ya existe',
        ]);
        $flag = "";
        $image = public_path('/assets/banderas/'.$request->flag.'.svg');
        if(!file_exists($image)){
            throw ValidationException::withMessages(['flag' => 'esta bandera no se pudo encontrar']); 
        }else{
            $flag = asset('/assets/banderas/'.$request->flag.'.svg');
        }
        
        $registro = new Paises();
        $registro->name = $request->name;
        $registro->flag = $flag;
        $registro->code_country = $request->code_country;
        $registro->save();



        return redirect('paises')->with('success', 'Registro Guardado exitosamente');
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
        $count = Paises::where('id', $id)->count();
        if ($count>0) {
            $data = Paises::where('id', $id)->first();
            return view('paises.edit', compact('data'));
        } else {
            return redirect('/paises')->with('danger', 'Problemas para Mostrar el Registro.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ciudades  $ciudades
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required'],
        ],
        [
            'name.unique' => 'El valor del campo Nombre ya existe',
        ]);

        $count = Paises::where('id', $id)->count();
        $flag = "";
        $image = public_path('/assets/banderas/'.$request->flag.'.svg');
        if(!file_exists($image)){
            throw ValidationException::withMessages(['flag' => 'esta bandera no se pudo encontrar']); 
        }else{
            $flag = asset('/assets/banderas/'.$request->flag.'.svg');
        }
        if ($count>0) {
            $registro = Paises::where('id', $id)->first();
            $registro->name = $request->name;
            $registro->flag = $flag;
            $registro->code_country = $request->code_country;
            $registro->save();

            return redirect('/paises')->with('success', 'Registro Actualizado Exitosamente!');
        } else {
            return redirect('/paises')->with('danger', 'Problemas para Actualizar el Registro.');
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
        $count = Paises::where('id', $id)->count();
        if ($count>0) {
            Paises::where('id', $id)->delete();
            return redirect('/paises')->with('success', 'Registro Eliminado Exitosamente!');
        } else {
            return redirect('/paises')->with('danger', 'Problemas para Eliminar el Registro.');
        }
    }
}
