<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Paises;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Hash;
use Illuminate\Validation\Rule;
 
class UsersController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->rol == "ADMIN"){
            $data = User::all();
        }else{
            $data = User::where('rol', "USER")->get();
        }
        return view('users.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $paises = Paises::all();
        return view('users.create', compact('paises'));
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
            'email' => ['required', 'max:200', 'unique:users'], 
            'password' => ['required', 'max:200'],
            'rol' => ['required', 'max:200'],
            'telefono' => ['required', 'max:200'],
        ],[
            'nombre.required' => 'El valor del campo Nombre de Usuario es obligatorio.',
            'apellido.required' => 'El valor del campo apellido de Usuario es obligatorio.',
            'email.required' => 'El valor del campo Correo es obligatorio.',
            'dni.required' => 'El valor del campo dni es obligatorio.',
            'telefono.required' => 'El valor del campo telefono es obligatorio.',
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
        $dni = $request->input('dni');
        $email = $request->input('email');
        $password = bcrypt($request->input('password'));
        $rol = $request->input('rol');
        $phone = $request->input('telefono');

        # create
        $dato = New User;
        $dato->name = $nombre;
        $dato->lastname = $apellido;
        $dato->dni = $dni;
        $dato->phone = $phone;
        $dato->email = $email;
        $dato->password = $password;
        $dato->rol = $rol;
        $dato->save();

        return redirect('/users')->with('success', 'Nuevo Usuario Guardado Exitosamente!');
      

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $count = User::where('id', $id)->count();
        if($count>0){
            $datos = User::where('id', $id)->first(); 
            $persons = DB::table('persons')
                                ->leftjoin('users', 'persons.user_id', '=', 'users.id')
                                ->where('users.id', $id)
                                ->first();
            $direccion = DB::table('addresses')
                                ->leftjoin('persons', 'addresses.person_id', '=', 'persons.id')
                                ->leftjoin('users', 'persons.user_id', '=', 'users.id')
                                ->select('addresses.country as country', 'addresses.province as province', 'addresses.city as city', 'addresses.direccion as direccion')
                                ->where('users.id', $id)
                                ->groupBy('country', 'province', 'city', 'direccion')
                                ->first();
            $familiares = DB::table('familys')
                                ->leftjoin('persons', 'familys.person_id', '=', 'persons.id')
                                ->leftjoin('users', 'persons.user_id', '=', 'users.id')
                                ->select('familys.dni as dni', 'familys.name as name', 'familys.last_name', 'familys.type_family as type', 'familys.phone as phone')
                                ->where('users.id', $id)
                                ->groupBy('dni', 'name', 'last_name', 'type', 'phone')
                                ->get();
            return view('users.show', compact('datos', 'persons', 'direccion','familiares'));
        }else{
            return redirect('/users')->with('Error', 'Problemas para visualizar el registro');
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
        $count = User::where('id', $id)->count();
        if($count>0){
            $data = User::where('id', $id)->first();
            $paises = Paises::all();
            //$pass = Hash::make($data->password);       
            return view('users.edit', compact('data','paises'));
        }else{
            return redirect('/users')->with('Error', 'Problemas para visualizar el registro');
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
        $count = User::where('id', $id)->count();
      
        if($count>0){
 
            $request->validate([
                'nombre' => ['required', 'max:200'],
                'apellido' => ['required', 'max:200'],
                'dni' => ['required', 'max:200'],
                'email' => ['required', 'max:200', Rule::unique('users')->ignore($id)], 
                'rol' => ['required', 'max:200'],
                'telefono' => ['required', 'max:200'],
                
            ],[
                'nombre.required' => 'El valor del campo Nombre de Usuario es obligatorio.',
                
                'apellido.required' => 'El valor del campo apellido de Usuario es obligatorio.',
                'email.required' => 'El valor del campo Correo es obligatorio.',
                'sexo.required' => 'El valor del campo sexo es obligatorio.',
                'dni.required' => 'El valor del campo dni es obligatorio.',
                'paises_id.required' => 'El valor de pais es obligatorio.',
                'provincias_id.required' => 'El valor de provincia es obligatorio.',
                'ciudades_id.required' => 'El valor de ciudad es obligatorio.',
                'sectores_id.required' => 'El valor de sector es obligatorio.',
                'edad.required' => 'El valor del campo edad es obligatorio.',
                'telefono.required' => 'El valor del campo telefono es obligatorio.',
                'rol_id.required' => 'El valor del campo Rol es obligatorio.',
            ]);
    
            $nombre = $request->input('nombre');
            $apellido = $request->input('apellido');
            $dni = $request->input('dni');
            $password = bcrypt($request->input('password'));
            $rol = $request->input('rol');
            $phone = $request->input('telefono');
     
            $dato = User::where('id', $id)->first();
            $dato->name = $nombre;
            $dato->lastname = $apellido;
            $dato->dni = $dni;
            $dato->phone = $phone;
            if($request->input('password') != NULL){
                $dato->password = $password;
            }
            $dato->rol = $rol;
            $dato->save();

            return redirect('/users')->with('success', 'Usuario Actualizado Exitosamente!');
            /*}else{
                throw ValidationException::withMessages(['phone' => 'el antiguo valor ingresado ya existe en otro registro']);
            }*/ 
        }else{
            return redirect('/users')->with('Error', 'Problemas para visualizar el registro');
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
        $verificar = User::where('id', $id)->count();
        if($verificar > 0)
        {
            User::where('id', $id)->delete();
            return redirect('/users')->with('success', 'Registro Eliminado Existosamente.');
        }else{
            return redirect('/users')->with('warning', 'Error al Eliminar! No se puede eliminar el registro.');
        }
    }
}
