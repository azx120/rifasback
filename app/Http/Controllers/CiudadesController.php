<?php

namespace App\Http\Controllers;

use App\Models\Ciudades; 
use App\Models\Paises;
use App\Models\Provincias;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Bitacoras;


class CiudadesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('ciudades')
        ->join('provincias', 'ciudades.id_provincia' , '=', 'provincias.id')
        ->select('ciudades.id',  'ciudades.name', 'provincias.name as provincia')
        ->get();
        return view('ciudades.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = Provincias::all();
        return view('ciudades.create', compact('data'));
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
            'id_provincia' => ['required']
        ],
        [
            'name.required' => 'El campo Nombre es obligatorio',
            'id_provincia.required' => 'El campo pais es obligatorio',
        ]);

        $registro = new Ciudades();
        $registro->name = $request->name;
        $registro->id_provincia = $request->id_provincia;
        $registro->save();

        $datoBitacoras = New Bitacoras();
        $datoBitacoras->id_user = Auth::id();
        $datoBitacoras->type = 'CREATE_CANTON';
        $datoBitacoras->id_ref = $registro->id;
        $datoBitacoras->ip = $request->getClientIp();
        $datoBitacoras->color = 'muted';
        $datoBitacoras->comment = 'usuario'. Auth::user()->email. ' creo el canton '. $registro->name;
        $datoBitacoras->save();
        
        return redirect('ciudades')->with('success', 'Registro Guardado exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ciudades  $ciudades
     * @return \Illuminate\Http\Response
     */
    public function show(ciudades $ciudades)
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
        $count = Ciudades::where('id', $id)->count();
 
        if ($count>0) {
            $data = Ciudades::where('id', $id)->first();
            $provincias = Provincias::all();
            return view('ciudades.edit', compact('data', 'provincias'));
        } else {
            return redirect('/ciudades')->with('danger', 'Problemas para Mostrar el Registro.');
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
            'id_provincia' => ['required']
        ],
        [
            'name.required' => 'El valor del campo Nombre es obligatorio',
            'id_provincia.required' => 'provincia desconocida',
        ]);

        $count = Ciudades::where('id', $id)->count();
        if ($count>0) {
            $registro = Ciudades::where('id', $id)->first();
            $registro->name = $request->name;
            $registro->id_provincia = $request->id_provincia;
            $registro->save();

            $datoBitacoras = New Bitacoras();
            $datoBitacoras->id_user = Auth::id();
            $datoBitacoras->type = 'EDIT_CANTON';
            $datoBitacoras->id_ref = $registro->id;
            $datoBitacoras->ip = $request->getClientIp();
            $datoBitacoras->color = 'muted';
            $datoBitacoras->comment = 'usuario'. Auth::user()->email. ' edito el canton '. $registro->name;
            $datoBitacoras->save();

            return redirect('/ciudades')->with('success', 'Registro Actualizado Exitosamente!');
        } else {
            return redirect('/ciudades')->with('danger', 'Problemas para Actualizar el Registro.');
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
        $count = Ciudades::where('id', $id)->count();
        if ($count>0) {
            Ciudades::where('id', $id)->delete();
            return redirect('/ciudades')->with('success', 'Registro Eliminado Exitosamente!');
        } else {
            return redirect('/ciudades')->with('danger', 'Problemas para Eliminar el Registro.');
        }
    }

    public function ciudades(Request $request, $id){

        $data['ciudades'] = Ciudades::where('id_provincia', $id)->orderBy('name')->get()->toJson();
        return response()->json($data);
    }
}
