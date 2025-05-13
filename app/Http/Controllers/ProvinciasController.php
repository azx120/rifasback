<?php

namespace App\Http\Controllers;

use App\Models\Provincias; 
use App\Models\Paises;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Bitacoras;

class ProvinciasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = DB::table('provincias')
        ->join('paises', 'provincias.id_pais' , '=', 'paises.id')
        ->select('provincias.id',  'provincias.name', 'paises.name as pais', 'paises.flag')
        ->get();
        return view('provincias.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = Paises::all();
        return view('provincias.create', compact('data'));
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
            'paises_id' => ['required']
        ],
        [
            'name.required' => 'El campo Nombre es obligatorio',
            'paises_id.required' => 'El campo pais es obligatorio',
        ]);

        $registro = new Provincias();
        $registro->name = $request->name;
        $registro->id_pais = $request->paises_id;
        $registro->save();

        $datoBitacoras = New Bitacoras();
        $datoBitacoras->id_user = Auth::id();
        $datoBitacoras->type = 'CREATE_PROVI';
        $datoBitacoras->id_ref = $registro->id;
        $datoBitacoras->ip = $request->getClientIp();
        $datoBitacoras->color = 'muted';
        $datoBitacoras->comment = 'usuario'. Auth::user()->email. ' creo la provincia '. $registro->name;
        $datoBitacoras->save();

        return redirect('provincias')->with('success', 'Registro Guardado exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Provincias  $provincias
     * @return \Illuminate\Http\Response
     */
    public function show(Provincias $provincias)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Provincias  $provincias
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $count = Provincias::where('id', $id)->count();
        if ($count>0) {
            $data = Provincias::where('id', $id)->first();
            $paises = Paises::all();
            return view('provincias.edit', compact('data', 'paises'));
        } else {
            return redirect('/provincias')->with('danger', 'Problemas para Mostrar el Registro.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Provincias  $provincias
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required'],
            'paises_id' => ['required']
        ],
        [
           'name.required' => 'El campo Nombre es obligatorio',
           'paises_id.required' => 'pais deconocido',

        ]);

        $count = Provincias::where('id', $id)->count();
        if ($count>0) {
            $registro = Provincias::where('id', $id)->first();
            $registro->name = $request->name;
            $registro->id_pais = $request->paises_id;
            $registro->save();

            $datoBitacoras = New Bitacoras();
            $datoBitacoras->id_user = Auth::id();
            $datoBitacoras->type = 'EDIT_PROVI';
            $datoBitacoras->id_ref = $registro->id;
            $datoBitacoras->ip = $request->getClientIp();
            $datoBitacoras->color = 'muted';
            $datoBitacoras->comment = 'usuario'. Auth::user()->email. ' edit la provincia '. $registro->name;
            $datoBitacoras->save();

            return redirect('/provincias')->with('success', 'Registro Actualizado Exitosamente!');
        } else {
            return redirect('/provincias')->with('danger', 'Problemas para Actualizar el Registro.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Provincias  $provincias
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $count = Provincias::where('id', $id)->count();
        if ($count>0) {
            Provincias::where('id', $id)->delete();
            return redirect('/provincias')->with('success', 'Registro Eliminado Exitosamente!');
        } else {
            return redirect('/provincias')->with('danger', 'Problemas para Eliminar el Registro.');
        }
    }

    public function provincias(Request $request, $id){

        $data['provincias'] = Provincias::where('id_pais', $id)->orderBy('name')->get()->toJson();
        return response()->json($data);
    }
}
