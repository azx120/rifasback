<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\AppDatas;
use App\Http\Resources\UsersResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{

    /**
     * Inicio de Sesión
     */
    public  function  login(Request  $request) {
         # Validación de los campos de inicio de sesion

         $validator = Validator::make($request->all(), [
            'phone' => ['required'],
            'code' => ['required'],
        ],[
            'phone.required' => 'tlefono es obligatorio.',
            'code.required' => 'codigo es obligatorio',
        ]);
        
        # Muestra el error en caso de que los datos no cumplan con la validación

        if ($validator->fails()) {
            return response()->json([ 
                'status' => 403,
                'message' => $validator->errors()->first(),
            ], 403);
        }

        $token = null;
     
        $user = User::where('telefono', '=', $request->phone)->where('code', '=', $request->code)->first();
              

        if(!empty($user)){

            $token = Auth::loginUsingId($user->id);
           
            $usert = $request->user();
    
            //$tokenResult = $user->delete();
            $tokenResult = $usert->createToken('Personal Access Token');
           
            $token = $tokenResult->token;   

            if ($request->remember_me) {
                $token->expires_at = Carbon::now()->addYears(1);
            }
        
            $token->save();
            $saveToken = User::where('id', $user->id)->first();
            $saveToken->remember_token = $tokenResult->accessToken;
            $saveToken->save();

           
            return response()->json([
                'status' => 200,
                'user' => $user,
                'access_token' => $tokenResult->accessToken,
                'token_type'   => 'Bearer',
                'expires_at'   => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
            ], 200);
        }else{
            return response()->json([
                'status' => 403,
                'user' => 'credenciales invalidas',
            ], 403);
        }

        
    }

    /**
     * Inicio de Sesión
     */
    public  function  sendCode(Request  $request) {
        # Validación de los campos de inicio de sesion

        $validator = Validator::make($request->all(), [
           'phone' => ['required'],
       ],[
           'phone.required' => 'tlefono es obligatorio.',
       ]);

       $user = User::where('telefono', '=',$request->phone)->first();
       if(!empty($user)){
            $code = rand(0, 9999);
            $user->code = $code;
            $user->save();
            $msj = sendCodeWhatsapp($code, $request->phone);

           
       }else{
            $code = rand(0, 9999);
            $dato = New User;
            $dato->name = '';
            $dato->dni = '';
            $dato->edad = '';
            $dato->paises_id = '';
            $dato->provincia_id = '';
            $dato->ciudad_id = '';
            $dato->sector_id = '';
            $dato->sexo = '';
            $dato->code = $code;
            $dato->telefono = $request->phone;
            $dato->email = '';
            $dato->password = '';
            $dato->registered = 0;
            $dato->rol = 'APPUSER';
            $dato->save();
            $msj = sendCodeWhatsapp($code, $request->phone);
  

       }

       if(isset($msj['error'])){
        return response()->json([
            'status' => 403,
            'message' => 'no se pude enviar el mensaje'
            ], 200);
    
       }else{
        return response()->json([
            'status' => 200,
            'message' => $msj
            ], 200);
    
       }
       
    }


    /**
     * Inicio de Sesión
     */
    public  function  loginRed(Request  $request) {
        # Validación de los campos de inicio de sesion

        $validator = Validator::make($request->all(), [
           'email' => ['required'],
           'names' => ['required'],
           
       ],[
           'email.required' => 'correo es obligatorio.',
           'names.required' => 'nombre es obligatorio.',

       ]);
       
       # Muestra el error en caso de que los datos no cumplan con la validación

       if ($validator->fails()) {
           return response()->json([ 
               'status' => 403,
               'message' => $validator->errors()->first(),
           ], 403);
       }

       $token = null;
    
       $user = User::where('email', '=',$request->email)->with('AppDatas')->with('Pagos')->with('Chats')->first();
       

       if($user && $user->redes == 1){

           $token = Auth::loginUsingId($user->id);
          
           $usert= $request->user();
   
           //$tokenResult = $user->delete();
           $tokenResult = $usert->createToken('Personal Access Token');
          
           $token = $tokenResult->token;   

           if ($request->remember_me) {
               $token->expires_at = Carbon::now()->addYears(1);
           }
       
           $token->save();
           $saveToken = User::where('id', $user->id)->first();
           $saveToken->remember_token = $tokenResult->accessToken;
           $saveToken->save();

           return response()->json([
               'status' => 200,
               'user' => $user,
               'access_token' => $tokenResult->accessToken,
               'token_type'   => 'Bearer',
               'expires_at'   => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
           ], 200);

       }else if($user && $user->redes == 0 && $user->password !== ""){

        $user->redes = 1;
        $user->save();

        $token = Auth::loginUsingId($user->id);
          
        $usert= $request->user();

        //$tokenResult = $user->delete();
        $tokenResult = $usert->createToken('Personal Access Token');
       
        $token = $tokenResult->token;   

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addYears(1);
        }
    
        $token->save();
        $saveToken = User::where('id', $user->id)->first();
        $saveToken->remember_token = $tokenResult->accessToken;
        $saveToken->save();

        return response()->json([
            'status' => 200,
            'user' => $user,
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
        ], 200);
       } else if(empty($user)){
            # create
            $dato = New User;
            $dato->name = $request->names;
            $dato->dni = '';
            $dato->edad = '';
            $dato->paises_id = '';
            $dato->provincia_id = '';
            $dato->ciudad_id = '';
            $dato->sector_id = '';
            $dato->sexo = '';
            $dato->redes = 1;
            $dato->telefono = $request->telefono;
            $dato->email = $request->email;
            $dato->password = '';
            $dato->registered = 0;
            $dato->rol = 'APPUSER';
            $dato->save();
  
            $token = Auth::loginUsingId($dato->id);
          
           $usert= $request->user();
   
           //$tokenResult = $user->delete();
           $tokenResult = $usert->createToken('Personal Access Token');
          
           $token = $tokenResult->token;   

           if ($request->remember_me) {
               $token->expires_at = Carbon::now()->addYears(1);
           }
       
           $token->save();
           $saveToken = User::where('id', $dato->id)->first();
           $saveToken->remember_token = $tokenResult->accessToken;
           $saveToken->save();

           return response()->json([
               'status' => 200,
               'user' => $dato,
               'access_token' => $tokenResult->accessToken,
               'token_type'   => 'Bearer',
               'expires_at'   => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
           ], 200);
       }

       
   }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registerUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nombre' => ['required', 'max:200'],
            //'apellido' => ['required', 'max:200'],
            'dni' => ['max:200'],
            'provincias_id' => ['max:200'],
            'ciudades_id' => ['max:200'],
            'email' => ['required', 'max:200'], 
            'phone' => ['required', 'max:200'],
            'cumpleanos' => ['required', 'max:200'],
            'sexo' => ['required', 'max:200'],
        ],[
            'nombre.required' => 'El valor del campo Nombre es obligatorio.',
            //'apellido.required' => 'El valor del campo Apellido es obligatorio.',
            'email.required' => 'El valor del campo Correo es obligatorio.',
            'dni.required' => 'El valor del campo dni es obligatorio.',
            'provincias_id.required' => 'El valor del campo provincia es obligatorio.',
            'ciudades_id.required' => 'El valor del campo ciudad es obligatorio.',
            'phone.required' => 'telefono desconocido',
            'cumpleanos.required' => 'El valor del campo cumpleaños es obligatori',
            'sexo.required' => 'El valor del campo sexo es obligatori',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                'message' => $validator->errors()->first(),
            ], 403);
        }

            $dato = User::where('telefono', $request->phone)->first();
            

            if(!empty($dato)){
                $dato->name = $request->nombre;
                $dato->apellido = '';
                $dato->dni = $request->nombre;
                $dato->edad = $request->cumpleanos;
                $dato->paises_id = '';
                $dato->provincia_id = $request->provincia_id;
                $dato->ciudad_id = $request->ciudad_id;
                $dato->sector_id = '';
                $dato->sexo = $request->sexo;
                $dato->registered = 1;
                $dato->email = $request->email;
                $dato->rol = 'APPUSER';
              
                if($dato->save()){

                    return  response()->json([
                        'user' => $dato,
                        'message' => 'Usuario Registrado',
                    ], 200);  
            }else{
                return  response()->json([
                    'message' => 'Numero invalido',
                ], 403);
            }
       

            }else{
                return  response()->json([

                    'message' => 'Registro invalido',

                ], 403);
            }
      

        }


        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registerUserRedes(Request $request)
    {

        $validator = Validator::make($request->all(), [
            
            'dni' => ['max:200'],
            //'sexo' => ['required', 'max:200'],
            'paises_id' => ['max:200'],
            'provincias_id' => ['max:200'],
            'ciudades_id' => ['max:200'],
            'sectores_id' => ['max:200'],
            'email' => ['required', 'max:200', 'unique:users'], 
        ],[
        
            'email.unique' => 'El correo ya esta en uso por otro usuario',
            'email.required' => 'El valor del campo Correo es obligatorio.',
            'dni.required' => 'El valor del campo dni es obligatorio.',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                'message' => $validator->errors()->first(),
            ], 403);
        }

            
            # create
            $dato = New User;
            $dato->name = $request->names;
            $dato->dni = '';
            $dato->edad = '';
            $dato->paises_id = $request->paises_id;
            $dato->provincia_id = $request->provincia_id;
            $dato->ciudad_id = $request->ciudad_id;
            $dato->sector_id = '';
            $dato->sexo = '';
            $dato->redes = 0;
            $dato->registered = 1;
            $dato->telefono = '';
            $dato->email = $request->email;
            $dato->password = bcrypt($request->password);
            $dato->rol = 'APPUSER';
            
            if($dato->save()){
                $dato->app_datas = [];
                $dato->chats = [];
                $dato->pagos = [];
                return  response()->json([
                    'user' => $dato,
                    'access_token' => '',
                    'status' => 'Registro de usuario exitoso',
                ], 200);

            }else{
                return  response()->json([

                    'status' => 'Registro invalido',

                ], 403);
            }
      

        }


     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
      
    # Validación de los campos de inicio de sesion

        $validator = Validator::make($request->all(), [
            'id' => ['required'],
            'newPassword' => ['required'],
            'oldPassword' => ['required'],
            'access_token'=> ['required'],
        ],[
            'id.required' => 'usuario desconocido',
            'newPassword.required' => 'nueva contraseña es obligatoria',
            'oldPassword.required' => 'antigua contraseña es obligatoria',
            'access_token.required' => 'token desconocido',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                'message' => $validator->errors()->first(),
            ], 403);
        }
        # Muestra el error en caso de que los datos no cumplan con la validación
        $dato = User::where('id', $request->id)->first();
        if($request->access_token == $dato->remember_token){
            if(Hash::check($request->oldPassword,  $dato->password)){
    
                $dato->password = bcrypt($request->newPassword);

                $dato->save();
                
                return response()->json([
                    'status' => 200,
                    'message' => 'cambio exitoso',
                ], 200);
            }else{
                return response()->json([
                    'status' => 403,
                    'message' => 'contraseña incorrecta',
                ], 403);
            }
        }else{
            return response()->json([
                'status' => 403,
                'message' => 'token vencido o invalido',
            ], 403);
        }
    }

    
     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
      
    # Validación de los campos de inicio de sesion

        $validator = Validator::make($request->all(), [
            'id' => ['required'],
            'name' => ['required'],
            'apellido' => ['required'],
            'provincia_id' => ['required'],
            'ciudad_id' => ['required'],
            'sector_id' => ['required'],
        ],[
            'id.required' => 'usuario desconocido',
            'name.required' => 'nombre es obligatorio',
            'apellido.required' => 'apellido es obligatorio.',
            'provincia_id.required' => 'provincia es obligatorio',
            'ciudad_id.required' => 'ciudad es obligatorio.',
            'sector_id.required' => 'sector es obligatorio',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                
                'message' => $validator->errors()->first(),
            ], 403);
        }
        # Muestra el error en caso de que los datos no cumplan con la validación
        $dato = User::where('id', $request->id)->first();

        if($request->access_token == $dato->remember_token){  

            $dato->name = $request->name;
            $dato->apellido = $request->apellido;
            $dato->direccion = $request->address;
            $dato->provincia_id = $request->provincia_id;
            $dato->ciudad_id = $request->ciudad_id;
            $dato->sector_id = $request->sector_id;
            $dato->save();
            
            return response()->json([
                'status' => 200,
                'user' => $dato,
                'message' => 'exitoso',
            ], 200);
        }else{
            return response()->json([
                'status' => 403,
                'message' => 'token vencido o invalido',
            ], 403);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function editActiveGruop(Request $request)
    {
      
    # Validación de los campos de inicio de sesion

        $validator = Validator::make($request->all(), [
            'id_user' => ['required'],
            'id_group' => ['required'],
           
        ],[
            'id.required' => 'usuario desconocido',
            'id_group.required' => 'grupo es obligatorio',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                
                'message' => $validator->errors()->first(),
            ], 403);
        }
        
        # Muestra el error en caso de que los datos no cumplan con la validación
        $dato = User::where('id', $request->id_user)->first();

        if($request->access_token == $dato->remember_token){   

            $dato->group_active = $request->id_group;
            $dato->save();
            
            return response()->json([
                'status' => 200,
                'user' => $dato->group_active,
                'message' => 'exitoso',
            ], 200);
        }else{
            return response()->json([
                'status' => 403,
                'message' => 'token vencido o invalido',
            ], 403);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function hiddenPhone(Request $request)
    {
      
    # Validación de los campos de inicio de sesion

        $validator = Validator::make($request->all(), [
            'id_user' => ['required'],
            'hiddenPhone' => ['required'],
           
        ],[
            'id.required' => 'usuario desconocido',
            'hiddenPhone.required' => 'eleccion es obligatorio',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                
                'message' => $validator->errors()->first(),
            ], 403);
        }
        
        # Muestra el error en caso de que los datos no cumplan con la validación
        $dato = User::where('id', $request->id_user)->first();

        if($request->access_token == $dato->remember_token){   

            $dato->hidden_phone = $request->hiddenPhone;
            $dato->save();
            
            return response()->json([
                'status' => 200,
                'data' =>  $dato->hidden_phone,
                'message' => 'exitoso',
            ], 200);
        }else{
            return response()->json([
                'status' => 403,
                'message' => 'token vencido o invalido',
            ], 403);
        }
    }


    /**
     * Ingresar telefono
     */
    public  function  validatePhone(Request  $request) {
        # Validación de los campos de inicio de sesion

        $validator = Validator::make($request->all(), [
           'idUser' => ['required'],
           'telefono' => ['required'],
       ],[
           'idUser.required' => 'usuario desconocido',
           'telefono.required' => 'El numero telefónico es obligatorio',
        
       ]);
            
       
       # Muestra el error en caso de que los datos no cumplan con la validación

        if ($validator->fails()) {
           return response()->json([ 
               'status' => 403,
               'message' => $validator->errors()->first(),
           ], 403);
        }

        $dato = User::where('id', $request->idUser)->first();
        if(!empty($dato)){

            $datasUser = AppDatas::where('user_id', $dato->id)->first();

            if($dato->telefono == $request->telefono && $datasUser->phone_verified == 1){

                return response()->json([
                    'status' => 403,
                    "mensaje" => 'telefono validado en uso' 
                ], 403);

            }else if($dato->telefono == $request->telefono && $datasUser->phone_verified == 0){

                $datasUser->code_phone =  rand(1, 99999);                     
                $datasUser->save();

            

                return response()->json([
                    'status' => 200,
                    "mensaje" => 'reenvio de codigo exitoso' 
                ], 200);

            }else{

                $dato->telefono =  $request->telefono;                     
                $dato->save();

                $datasUser->code_phone =  rand(1, 99999);                     
                $datasUser->save();

                $sms = sendPost($datasUser->code_phone, $request->telefono);

                return response()->json([
                    'status' => 200,
                    "mensaje" => 'registro telefono exitoso' 
                ], 200);
            }
            
        }else{
            return response()->json([
                'status' => 403,
                "mensaje" => 'usuario no encontrado' 
                //'user' => 'credenciales invalidas',
            ], 403);
        }
      
    }

     /**
     * Inicio de Sesión
     */
    public  function  confirmPhone(Request  $request) {
        # Validación de los campos de inicio de sesion

        $validator = Validator::make($request->all(), [
           'idUser' => ['required'],
           'code' => ['required'],
       ],[
           'idUser.required' => 'usuario desconocido',
           'code.required' => 'El codigo es obligatorio',
       ]);
       
       # Muestra el error en caso de que los datos no cumplan con la validación

        if ($validator->fails()) {
           return response()->json([ 
               'status' => 403,
               'message' => $validator->errors()->first(),
           ], 403);
        }

        $dato = User::where('id', $request->idUser)->first();
        $datasUser = AppDatas::where('user_id', $dato->id)->first();

        if(!empty($dato)){
                   
            if($datasUser->code_phone !== $request->code){
         
                return response()->json([
                    'status' => 403,
                    "mensaje" => 'codigo incorrecto' 
                ], 403);
            }else{
                
                $datasUser->phone_verified = 1;                
                 $datasUser->save();

                return response()->json([
                    'status' => 200,
                    "mensaje" => 'registro exitoso' 
                ], 200);
            }
           
    
        }else{
            return response()->json([
                'status' => 403,
                "mensaje" => 'usuario no encontrado' 
                //'user' => 'credenciales invalidas',
            ], 403);
        }
      
    }


    /**
     * Cierre de sesión
     */
    public  function  logout(Request  $request) {

        auth('sanctum')->user()->tokens()->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Cerraste sesión',
        ], 200);
        
    }

    public function refresh(Request $request) {
        
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;        
        $token->expires_at = Carbon::now()->addYears(1);
        $token->save();   

        return response()->json([
            'status' => 200,
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
        ], 200);
        
    }
}

function sendCodeWhatsapp($code, $numberPhone)
{
   
    $mensaje = array( 
        "messaging_product" => "whatsapp",
        "recipient_type" => "individual",
        "to" => $numberPhone,
        "type" => "text",
        "text" => array(
            "preview_url" => false,
            "body" => 'nuevo codigo de inicio de sesion: '.$code,
        )
    );

    $mensaje = json_encode($mensaje); 

    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/v22.0/184815844707599/messages"); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
    'Authorization: Bearer EAAVfF7edYckBO4wfU2KyKtfsUZBxF2w7dZCXbPPZCB7KnA5YucqHth4bSJHdw5lPMAriqN0ebidvg7nFImYZA6EGZCKZBbviU3jU5mdJZB1gHaeSzQcZCr7TfesZBwGdvvBfehkKYNMBcxnWLix1dxz3ZBvKmWg4BXF4igyX9lbaPw83rAMqofUe930BRKYtATgs6o2AZDZD' // one siynal keys_and_ids
    )); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    curl_setopt($ch, CURLOPT_HEADER, FALSE); 
    curl_setopt($ch, CURLOPT_POST, TRUE); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $mensaje); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    
    $response = curl_exec($ch); 
    curl_close($ch); 
    return $r = json_decode($response,true);
    /*if($r !== null){
        if($r['success'] == true){
            $content      = array(
                "en" => 'tu codigo es: '.$code,
            );
                    
            $fields = array(
                'app_id' => "f4e0f05a-e5de-4fac-b340-eba2b6c77b35", // one siynal keys_and_ids
        
                "sms_from" => "+19592511582",
                "name"=> "Identifier for SMS Message",
                "include_phone_numbers" => ["+".$numberPhone],
                'data' => array(
                    "foo" => "bar"
                ),
                'contents' => $content,
            );
            
            $fields = json_encode($fields);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Authorization: Basic NThmYmIxNWItY2FkOC00NDBjLTg3ZTQtMmZjMjMxMmFkYTgw' // one siynal keys_and_ids
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            return true;         
        

        }else{
            return false;
        }
    }else{
        return false;
    }*/    


/*$response = sendMessage();
$return["allresponses"] = $response;
$return = json_encode($return);

$data = json_decode($response, true);
print_r($data);
$id = $data['id'];
print_r($id);
*/
}
