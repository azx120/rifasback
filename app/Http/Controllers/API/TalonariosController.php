<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Talonarios;
use App\Models\Planes;
use App\Models\Pagos;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\Business;
use Illuminate\Support\Facades\Validator;
use App\Models\Participants;
use App\Http\Controllers\PHPMailerController;

class TalonariosController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allTalonarios()
    { 
        
        $talonario = DB::table('talonarios')->orderBy('created_at', 'desc')->first();
        $tickets_sell = 0;
        $tickets_temp = 0;
        $participants_temp = [];
        $talonario->array_numbers = json_decode($talonario->array_numbers);
        $talonario->winners = json_decode($talonario->winners);
        $talonario->gallery = json_decode($talonario->gallery);

        foreach($talonario->array_numbers as $tickets){
            if ($tickets->participant != ''){
                $tickets_temp = $tickets_temp + 1;
                array_push($participants_temp, $tickets->participant);
            }
            
        }


        foreach($talonario->gallery as $gallery){
            $gallery->url = url('/').$gallery->url;
        }
        $talonario->array_numbers = [];
        $talonario->participants = count(array_unique($participants_temp));
        $talonario->imageUrl = url('/').$talonario->imageUrl;
        $talonario->tickets_sell = $tickets_temp;
        $tickets_sell = $tickets_sell + $tickets_temp; 
                $talonario->description = str_replace("<p>", "",$talonario->description);
        $talonario->description = str_replace("</p>", "",$talonario->description);

        return  response()->json([
            'data' => $talonario,
        ], 200);  
    }

       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function talonario_by_id($id)
    { 
        
        $data = Talonarios::where('id', $id)->first();
        $data->array_numbers = json_decode($data->array_numbers);

        return  response()->json($data, 200);
    }


           /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function numbers_by_client(Request $request)
    { 
        $participant = Participants::where('email', $request->email)->first();
        if(!empty($participant)){
            $get_numbers = '';
            $data = Talonarios::where('id', $request->id)->first();
            $data->array_numbers = json_decode($data->array_numbers);

           
            $pago = Pagos::where('participant_id', $participant->id)->where('talonario_id', $data->id)->first();

            foreach($data->array_numbers as $number){
                if($number->participant == $participant->ci){
                    if($get_numbers == ''){
                        $get_numbers = $number->id;
                    }else{
                        $get_numbers = $get_numbers.', '.$number->id;
                    }
                }
            }
    
            $data = [
                'type' => "recibo",
                'msj' => $get_numbers,
                'rifa' => $data->title,
                'email' => $participant->email,
                'names' => $participant->name.' '.$participant->lastname,
                'pedido' => $pago->code,
                'fecha' => $pago->created_at,
                'monto'=> $pago->numbers,
                'qty'=> $pago->numbers,
                'selecionados' => $get_numbers,
                'metodo' => 'Transferencia bancaria o depósito',
            ];
            
            if(PHPMailerController::composeEmail($data)){
                return  response()->json('correo enviado', 200);

            }else{
                return  response()->json('correo no enviado', 403);

            }
        }else{
            return  response()->json('correo no encontrado', 403);
        }
        

        
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allTalonariosWinner()
    { 
        
        $talonarios = DB::table('talonarios')
        ->join('participants', 'talonarios.winner' , '=', 'participants.ci')
        ->select('talonarios.*', 'participants.ci', 'participants.name', 'participants.lastname', 'participants.phone')
        ->where('talonarios.status', 0)
        ->get();

        
       $data = [];

       foreach($talonarios as $talonario){

            array_push($data, [
                "id" => $talonario->id ,
                "name" => $talonario->name.' '. $talonario->lastname,
                "date" => $talonario->endDate ,
                "raffle" => $talonario->title ,
                "image" => url('/').$talonario->imageUrl ,
                "confesion" => $talonario->confession ,
            ]);
       }
       

        return  response()->json([
            'data' => $data,
        ], 200);  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verifyNumbers(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'max:200'],
            'numbers' => ['max:200'],
        ],[
            'id.required' => 'Id rifa obligatorio.',
            'numbers.required' => 'Numero es obligatorio.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                'message' => $validator->errors()->first(),
            ], 403);
        }
        $take_numbers = json_decode($request->numbers);

        if(!empty($take_numbers)){
            $array_response = [];
            $dato = Talonarios::where('id', $request->id)->first();
            $new_array = json_decode($dato->array_numbers);

            foreach($take_numbers as $take_number){
                foreach($new_array as $numero){
                    if($numero->id == $take_number){
                        if($numero->status == "free"){
                            $tmp = [
                                "id" => $take_number,
                                "status" => true
                            ];
                            array_push($array_response, $tmp);
                        }else{
                            $tmp = [
                                "id" => $take_number,
                                "status" => false
                            ];
                            array_push($array_response, $tmp);
                        }

                    }
                }
            }

            return response()->json([
                'status' => 200,
                'Numeros' => $array_response,
            ], 200);
        }else{
            return response()->json([
                'status' => 403,
                'message' => 'Se necesita un numero minimo',
            ], 403);
        }  
    }


/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function takeNumber(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'max:200'],
            'ci' => ['max:200'],
            'numbers' => ['max:200'],
        ],[
            'id.required' => 'Id rifa obligatorio.',
            'ci.required' => 'Telefono es obligatorio.',
            'numbers.required' => 'Numero es obligatorio.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                'message' => $validator->errors()->first(),
            ], 403);
        }
        $take_numbers = json_decode($request->numbers);

        if(!empty($take_numbers)){
            $participants = Participants::where('ci', $request->ci)->first();
            $dato = Talonarios::where('id', $request->id)->first();
            $fecha = date('dmY'); // 8 dígitos
            $aleatorio = mt_rand(10, 99); // 2 dígitos aleatorios
            $codigo = $fecha . $aleatorio; // 10 dígitos total

            if(empty($participants)){
                $participants = New Participants;
                $participants->ci = $request->ci;
            }
            $participants->name = $request->name;	
            $participants->lastname = $request->lastname;
            $participants->phone = $request->phone;	
            $participants->city_id = $request->ciudad;	
            $participants->email = $request->email;	
            $participants->save();

            $pago = new Pagos;
            $pago->participant_id = $participants->id;	
            $pago->code = $codigo;
            $pago->talonario_id	= $request->id;
            $pago->numbers = $request->numbers;
            $pago->status = 'process';
            
            if ($request->hasFile('recive')) {
                $uploadPath = public_path('/storage/pagos/'.$participants->ci);
                $file = $request->file('recive');
                $fileName = $file->getClientOriginalName();
                $file->move($uploadPath, $fileName);
                $url = asset('/storage/pagos/'.$participants->ci.'/'.$fileName);
                $pago->comprobante = $url;
            }
            if($pago->save()){
                $data = [
                    'type' => "compra",
                    'rifa' => $dato->title,
                    'pedido' => $pago->code,
                    'fecha' => $pago->created_at,
                    'monto'=> $pago->numbers,
                    'qty'=> $pago->numbers,
                    'metodo' => 'Transferencia bancaria o depósito',
                    'email' => $participants->email,
                    'names' => $participants->name.' '.$participants->lastname,
                ];
                
                PHPMailerController::composeEmail($data);
            }

            

            $array_response = [];
            /*$dato = Talonarios::where('id', $request->id)->first();
            $new_array = json_decode($dato->array_numbers);

            foreach($take_numbers as $take_number){
                foreach($new_array as $numero){
                    if($numero->id == $take_number){
                        if($numero->status == "free"){
                            $numero->participant = $request->ci;
                            $numero->status = 'reserved';
                            $tmp = [
                                "id" => $take_number,
                                "status" => true
                            ];
                            array_push($array_response, $tmp);
                        }else{
                            $tmp = [
                                "id" => $take_number,
                                "status" => false
                            ];
                            array_push($array_response, $tmp);
                        }

                    }
                }
            }

            $dato->array_numbers = json_encode($new_array);
            $dato->save();
            $dato->array_numbers = json_decode($dato->array_numbers);*/
            return response()->json([
                'status' => 200,
                'message' => 'Numeros Reservados',
                //'pago' => $array_response,
            ], 200);
        }else{
            return response()->json([
                'status' => 403,
                'message' => 'Se necesita un numero minimo',
            ], 403);
        }  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postChat(Request $request)
    {
        $data = '';
        $status = 200;
        $chat = Chats::where('id', $request->room)->first();
        //return response()->json(count(json_decode($request->msg)), $status);
       // exit;

       $type = '';
       if(is_array (json_decode($request->msg)) == 0){
           $type = "MENSAJE";
       }else{
           $type = "PRODUCTOS";
       }

        $msg = [
            "id" => Uuid::uuid4(),
            "sender" => $request->idSender,
            "type" =>  $type,
            "msg" => $request->msg,
            "time" => date("H:i:s"),
            "date" =>date("d-m-Y")
        ];
        
        if(!empty($chat)){
            if($chat->id_user == $request->idSender){
                /*$userAuth = User::where('id', $request->idSender)->where('remember_token', $request->token)->first();
                if(empty($userAuth)){
                    $data = "error de de token usuario";
                    $status = 403;
                }else{*/

                    $newChat = json_decode($chat->chat);
                    array_push($newChat, $msg);
                    $chat->chat = json_encode($newChat);
                    $chat->save();
                    if($type == "PRODUCTOS"){
                        $chat->chat = [json_decode($msg['msg'])];
                    }else{
                        $chat->chat = [$msg];
                    }
                    $data = $chat;
                    $status = 200;
                //}
                
            }else if($chat->id_business == $request->idSender){
                /*$userAuth = User::where('id', $request->idProprietor)->where('remember_token', $request->token)->first();
                if(empty($userAuth)){
                    $data = "error de token de negocio";
                    $status = 403;
                }else{*/

                    $newChat = json_decode($chat->chat);
                    array_push($newChat, $msg);
                    $chat->chat = json_encode($newChat);
                    $chat->save();
                    if($type == "PRODUCTOS"){
                        $chat->chat = [json_decode($msg['msg'])];
                    }else{
                        $chat->chat = [$msg];
                    }
                    $data = $chat;
                    $status = 200;
                //}
            }else{
                $data = "error de negocio";
                $status = 403;
            }
        }else{
            $data = "error de peticion";
            $status = 403;
        }
        return response()->json($data, $status);
    }

}

/*setlocale(LC_TIME, "spanish");
echo strftime("%A %d de %B del %Y");*/