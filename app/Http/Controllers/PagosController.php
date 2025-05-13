<?php

namespace App\Http\Controllers;

use App\Models\Pagos;
use App\Models\Planes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; 

class PagosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('pagos')
        ->join('planes', 'pagos.id_plan' , '=', 'planes.id')
        ->join('user', 'pagos.id_user' , '=', 'user.id')
        ->select('pagos.*', 'planes.name', 'planes.saldo', 'planes.precio', 'planes.descripcion', 'user.name as name_user', 'user.email')
        ->get();
        return view('pagos.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = Provincias::all();
        return view('pagos.create', compact('data'));
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
            'provincia_id' => ['required']
        ],
        [
            'name.required' => 'El campo Nombre es obligatorio',
            'name.unique' => 'El valor del campo Nombre ya existe',
            'provincia_id.required' => 'El campo provincia es obligatorio',
        ]);

        $registro = new Planes();
        $registro->name = $request->name;
        $registro->provincia_id = $request->provincia_id;
        $registro->save();

        $ciudad_id = $registro->id;



        return redirect('pagos')->with('success', 'Registro Guardado exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Planes  $planes
     * @return \Illuminate\Http\Response
     */
    public function show(Planes $planes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Planes  $planes
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $count = Planes::where('id', $id)->count();
        if ($count>0) {
            $data = Planes::where('id', $id)->first();
            $provincias = Provincias::all();
            return view('pagos.edit', compact('data', 'galery', 'provincias'));
        } else {
            return redirect('/pagos')->with('danger', 'Problemas para Mostrar el Registro.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Planes  $planes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required'],
            'provincia_id' => ['required']
        ],
        [
            'name.unique' => 'El valor del campo Nombre ya existe',
            'provincia_id.required' => 'El valor del campo provincia es requerido',
        ]);

        $count = Planes::where('id', $id)->count();
        if ($count>0) {
            $registro = Planes::where('id', $id)->first();
            $registro->name = $request->name;
            $registro->provincia_id = $request->provincia_id;
            $registro->save();

           

            return redirect('/pagos')->with('success', 'Registro Actualizado Exitosamente!');
        } else {
            return redirect('/pagos')->with('danger', 'Problemas para Actualizar el Registro.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Planes  $planes
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $count = Pagos::where('id', $id)->count();
        if ($count>0) {
            Planes::where('id', $id)->delete();
           
            return redirect('/pagos')->with('success', 'Registro Eliminado Exitosamente!');
        } else {
            return redirect('/pagos')->with('danger', 'Problemas para Eliminar el Registro.');
        }
    }


    public function pagos($id){
        $pagos = DB::table('pagos')
        ->join('participants', 'pagos.participant_id' , '=', 'participants.id')
        ->select('pagos.*', 'participants.ci', 'participants.name', 'participants.lastname', 'participants.phone')
        ->where('pagos.talonario_id', $id)
        ->get(); 

        $data['pagos'] = $pagos->toJson();
        return response()->json($data);
    }


}
