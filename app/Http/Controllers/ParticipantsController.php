<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Participants;
use App\Models\Ciudades;
use App\Models\Provincias;
use App\Models\Paises;
use App\Models\Talonarios;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Hash;

class ParticipantsController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
            $data = Participants::all();

        return view('participants.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = Ciudades::all();
        $paises = Paises::all();
        return view('participants.create', compact('data', 'paises'));
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
            'nombre' => ['required', 'max:200'],
            'apellido' => ['required', 'max:200'],
            'dni' => ['required', 'max:200'],
            'sexo' => ['required', 'max:200'],
            'edad' => ['required', 'max:200'],
            'paises_id' => ['required', 'max:200'],
            'provincias_id' => ['required', 'max:200'],
            'ciudades_id' => ['required', 'max:200'],
            'sectores_id' => ['required', 'max:200'],
            'email' => ['required', 'max:200', 'unique:participants'], 
            'password' => ['required', 'max:200'],
            'rol' => ['required', 'max:200'],
            'phone' => ['required', 'max:200'],
        ],[
            'nombre.required' => 'El valor del campo Nombre de Usuario es obligatorio.',
            'nombre.max' => [
                'numeric' => 'El campo Nombre de Usuario no debe ser mayor a :max.',
                'file'    => 'El archivo Nombre de Usuario no debe pesar más de :max kilobytes.',
                'string'  => 'El campo Nombre de Usuario no debe contener más de :max caracteres.',
                'array'   => 'El campo Nombre de Usuario no debe contener más de :max elementos.',
            ],
            'apellido.required' => 'El valor del campo apellido de Usuario es obligatorio.',
            'apellido.max' => [
                'numeric' => 'El campo Nombre de Usuario no debe ser mayor a :max.',
                'file'    => 'El archivo Nombre de Usuario no debe pesar más de :max kilobytes.',
                'string'  => 'El campo Nombre de Usuario no debe contener más de :max caracteres.',
                'array'   => 'El campo Nombre de Usuario no debe contener más de :max elementos.',
            ],
            'email.required' => 'El valor del campo Correo es obligatorio.',
            'sexo.required' => 'El valor del campo sexo es obligatorio.',
            'dni.required' => 'El valor del campo dni es obligatorio.',
            'paises_id.required' => 'El valor de pais es obligatorio.',
            'provincias_id.required' => 'El valor de provincia es obligatorio.',
            'ciudades_id.required' => 'El valor de ciudad es obligatorio.',
            'sectores_id.required' => 'El valor de sector es obligatorio.',
            'edad.required' => 'El valor del campo edad es obligatorio.',
            'phone.required' => 'El valor del campo telefono es obligatorio.',
            

            'password.required' => 'El valor del campo Contraseña es obligatorio.',
            'password.max' => [
                'numeric' => 'El campo Contraseña no debe ser mayor a :max.',
                'file'    => 'El archivo Contraseña no debe pesar más de :max kilobytes.',
                'string'  => 'El campo Contraseña no debe contener más de :max caracteres.',
                'array'   => 'El campo Contraseña no debe contener más de :max elementos.',
            ],
            'rol.required' => 'El valor del campo Rol es obligatorio.',
        ]);

        $nombre = $request->input('nombre');
        $apellido = $request->input('apellido');
        $sexo = $request->input('sexo');
        $dni = $request->input('dni');
        $edad = $request->input('edad');
        $paises_id = $request->input('paises_id');
        $provincias_id = $request->input('provincias_id');
        $ciudades_id = $request->input('ciudades_id');
        $sectores_id = $request->input('sectores_id');
        $email = $request->input('email');
        $password = bcrypt($request->input('password'));
        $rol = $request->input('rol');
        $phone = $request->input('phone');

             

            # create
            $dato = New Participants;
            $dato->name = $nombre;
            $dato->apellido = $apellido;
            $dato->dni = $dni;
            $dato->edad = $edad;
            $dato->paises_id = $paises_id;
            $dato->provincia_id = $provincias_id;
            $dato->ciudad_id = $ciudades_id;
            $dato->sector_id = $sectores_id;
            $dato->sexo = $sexo;
            $dato->phone = $phone;
            $dato->confirm_phone = '0';
            $dato->email = $email;
            $dato->password = $password;
            $dato->rol = $rol;
            $dato->save();

            return redirect('/participants/crear-usuario')->with('success', 'Nuevo Usuario Guardado Exitosamente!');
      

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $count = Participants::where('id', $id)->count();
        if($count>0){
            $data = Participants::where('id', $id)->first(); 
            $ciudades = Ciudades::where('id', $data->city_id)->first(); 
            $provincias = Provincias::where('id', $ciudades->id_provincia)->first(); 
            $talonarios = Talonarios::where('array_numbers', 'LIKE', '%'.$data->ci.'%')->get();
            return view('participants.show', compact('data', 'provincias', 'ciudades', 'talonarios'));
        }else{
            return redirect('/participants')->with('Error', 'Problemas para visualizar el registro');
        }
       
    }

        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_by_ci($ci)
    {
        $count = Participants::where('ci', $ci)->count();
        if($count>0){
            $data = Participants::where('ci', $ci)->first(); 
            $ciudades = Ciudades::where('id', $data->city_id)->first(); 
            $provincias = Provincias::where('id', $ciudades->id_provincia)->first(); 
            $talonarios = Talonarios::where('array_numbers', 'LIKE', '%'.$data->ci.'%')->get();
            return view('participants.show', compact('data', 'provincias', 'ciudades', 'talonarios'));
        }else{
            return redirect('/participants')->with('Error', 'Problemas para visualizar el registro');
        }
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $count = Participants::where('id', $id)->count();
        if($count>0){
            $data = Participants::where('id', $id)->first();
            $citys = Ciudades::all();
            $paises = Paises::all();
            //$pass = Hash::make($data->password);       
            return view('participants.edit', compact('data', 'citys', 'paises'));
        }else{
            return redirect('/participants')->with('Error', 'Problemas para visualizar el registro');
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $count = Participants::where('id', $id)->count();
      

        if($count>0){
 
            $request->validate([
                'nombre' => ['required', 'max:200'],
                'apellido' => ['required', 'max:200'],
                'dni' => ['required', 'max:200'],
                'sexo' => ['required', 'max:200'],
                'edad' => ['required', 'max:200'],
                'paises_id' => ['required', 'max:200'],
                'ciudades_id' => ['required', 'max:200'],
                'sectores_id' => ['required', 'max:200'],
                //'email' => ['required', 'max:200', 'unique:participants'], 
                'password' => ['required', 'max:200'],
                'rol' => ['required', 'max:200'],
                'phone' => ['required', 'max:200'],
                
            ],[
                'nombre.required' => 'El valor del campo Nombre de Usuario es obligatorio.',
                'nombre.max' => [
                    'numeric' => 'El campo Nombre de Usuario no debe ser mayor a :max.',
                    'file'    => 'El archivo Nombre de Usuario no debe pesar más de :max kilobytes.',
                    'string'  => 'El campo Nombre de Usuario no debe contener más de :max caracteres.',
                    'array'   => 'El campo Nombre de Usuario no debe contener más de :max elementos.',
                ],
                'apellido.required' => 'El valor del campo apellido de Usuario es obligatorio.',
                'apellido.max' => [
                    'numeric' => 'El campo Nombre de Usuario no debe ser mayor a :max.',
                    'file'    => 'El archivo Nombre de Usuario no debe pesar más de :max kilobytes.',
                    'string'  => 'El campo Nombre de Usuario no debe contener más de :max caracteres.',
                    'array'   => 'El campo Nombre de Usuario no debe contener más de :max elementos.',
                ],
               // 'email.required' => 'El valor del campo Correo es obligatorio.',
                'sexo.required' => 'El valor del campo sexo es obligatorio.',
                'dni.required' => 'El valor del campo dni es obligatorio.',
                'paises_id.required' => 'El valor de pais es obligatorio.',
                'provincias_id.required' => 'El valor de provincia es obligatorio.',
                'ciudades_id.required' => 'El valor de ciudad es obligatorio.',
                'sectores_id.required' => 'El valor de sector es obligatorio.',
                'edad.required' => 'El valor del campo edad es obligatorio.',
                'phone.required' => 'El valor del campo telefono es obligatorio.',
    
                'password.required' => 'El valor del campo Contraseña es obligatorio.',
                'password.max' => [
                    'numeric' => 'El campo Contraseña no debe ser mayor a :max.',
                    'file'    => 'El archivo Contraseña no debe pesar más de :max kilobytes.',
                    'string'  => 'El campo Contraseña no debe contener más de :max caracteres.',
                    'array'   => 'El campo Contraseña no debe contener más de :max elementos.',
                ],
                'rol_id.required' => 'El valor del campo Rol es obligatorio.',
            ]);
    
            $nombre = $request->input('nombre');
            $apellido = $request->input('apellido');
            $sexo = $request->input('sexo');
            $dni = $request->input('dni');
            $edad = $request->input('edad');
            $paises_id = $request->input('paises_id');
            $provincias_id = $request->input('provincias_id');
            $ciudades_id = $request->input('ciudades_id');
            $sectores_id = $request->input('sectores_id');
            $password = bcrypt($request->input('password'));
            $rol = $request->input('rol');
            $phone = $request->input('phone');
     

                $dato = Participants::where('id', $id)->first();
            
                $dato->name = $nombre;
                $dato->apellido = $apellido;
                $dato->dni = $dni;
                $dato->edad = $edad;
                $dato->paises_id = $paises_id;
                $dato->provincia_id = $provincias_id;
                $dato->ciudad_id = $ciudades_id;
                $dato->sector_id = $sectores_id;
                $dato->sexo = $sexo;
                $dato->phone = $phone;
                $dato->password = $password;
                $dato->rol = $rol;
                $dato->save();

                if(Auth::Participants()->rol == "ADMIN" || Auth::Participants()->rol == "SELLER"){     
                    return redirect('/participants')->with('success', 'Usuario Actualizado Exitosamente!');
                }else{
                    return redirect('/')->with('success', 'Usuario Actualizado Exitosamente!');
                }

            /*}else{
                throw ValidationException::withMessages(['phone' => 'el antiguo valor ingresado ya existe en otro registro']);
            }*/
        }else if(Auth::Participants()->rol == "ADMIN" || Auth::Participants()->rol == "SELLER"){
            return redirect('/participants')->with('Error', 'Problemas para visualizar el registro');
        }else{
            return redirect('/')->with('Error', 'Problemas para visualizar el registro');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $verificar = Participants::where('id', $id)->count();
        if($verificar > 0)
        {
            Participants::where('id', $id)->delete();
            return redirect('/participants')->with('success', 'Registro Eliminado Existosamente.');
        }else{
            return redirect('/participants')->with('warning', 'Error al Eliminar! No se puede eliminar el registro.');
        }
    }

    public function participante($ci){

        $paciente = Participants::where('ci', $ci)->first();
        $city = Ciudades::where('id', $paciente->city_id)->first();
        $paciente->province_id = $city->id_provincia;
        $paciente->city_name = $city->name;
        $data['participante'] = $paciente->toJson();
        return response()->json($data);
    }

}
