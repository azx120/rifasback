<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Chats;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\Business;
use OpenAI\Laravel\Facades\OpenAI;
use Smalot\PdfParser\Parser;
class ChatController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allChats($id)
    { 
        $user = User::where('id' ,'=', $id)->first();
        $chats = [];
        if(empty($user)){
            return  response()->json([
                'data' => [],
            ], 200);
        }else{
            if($user->rol == "APPUSER"){
                $chats = DB::table('chats')
                ->where('chats.phone' ,'=',  $user->telefono)
                ->select('chats.*')
                ->get();
                foreach($chats as $data) {
                    $data->chat = json_decode($data->chat); 
                }
                return  response()->json([
                    'data' => $chats,
                ], 200);

            }/*else if($user->rol == "USER"){

                $business = Business::where('user_id', '=', $user->id)->get();
                $chats = [];

                foreach ($business as $negocio){
  
                    $chatsQuery = DB::table('chats')
                        ->join('users', 'chats.id_user' , '=', 'users.id')
                        ->where('chats.id_business' ,'=',  $negocio->id)
                        ->select('users.id', 'users.name', 'chats.*')
                        ->get();
    
                    foreach($chatsQuery as $data) {
                      
                        $data->chat = json_decode($data->chat);
                        foreach($data->chat as $d){
                            if($d->type == "PRODUCTOS"){
                                $d->msg = json_decode($d->msg);
                            }
                        }
                        array_push($chats, $data);
                    }  
                }

                $chatsUser = DB::table('chats')
                ->join('business', 'chats.id_business' , '=', 'business.id')
                ->where('chats.id_user' ,'=',  $user->id)
                ->select('business.id', 'business.name', 'business.url_logo', 'chats.*')
                ->get();
                
                foreach($chatsUser as $data) {
                    $data->chat = json_decode($data->chat); 
                    foreach($data->chat as $d){
                        if($d->type == "PRODUCTOS"){
                            $d->msg = json_decode($d->msg);
                        }
                    }
                    array_push($chats, $data);
                }

                return  response()->json([
                    'data' => $chats,
                ], 200);
            }*/else{
                return  response()->json([
                    'data' => [],
                ], 200);
            }
        }
        
    }


        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function consultChat($phone)
    { 
        $chat = Chats::where('phone', $request->phone)->first();
        if(empty($chat)){
            return  response()->json([
                'data' => $chat->ai,
            ], 200);
        }else{
            return  response()->json([
                'data' => 1,
            ], 200);
        }
            
    }


    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createChat(Request $request)
    {

        $chat = Chats::where('phone', $request->phone)->first();
        $user = User::where('telefono' ,'=', $request->phone)->first();
        $type = '';

        if(empty($chat)){

            $newMsg = [
                [
                    "id" => Uuid::uuid4(),
                    "sender" => 'USER',
                    "type" =>  'ASK',
                    "msg" => $request->pregunta,
                    "time" => date("H:i:s"),
                    "date" =>date("d-m-Y")
                ],
    
                [
                    "id" => Uuid::uuid4(),
                    "sender" => 'GTP',
                    "type" =>  'RESPONSE',
                    "msg" => $responseGTP,
                    "time" => date("H:i:s"),
                    "date" =>date("d-m-Y")
                ],
            ];

            $newChat = new Chats();
            $newChat->user_id = $user->id;
            $newChat->phone = $request->phone;
            $newChat->chat = json_encode($newMsg);
            $newChat->save();

            return response()->json(json_decode($newChat['chat']), 200);

        }else{


            $msg = [
                "id" => Uuid::uuid4(),
                "sender" => 'USER',
                "type" =>  'ASK',
                "msg" => $request->pregunta,
                "time" => date("H:i:s"),
                "date" =>date("d-m-Y")
            ];
    
            $msgGtp = [
                "id" => Uuid::uuid4(),
                    "sender" => 'GTP',
                    "type" =>  'RESPONSE',
                    "msg" => $responseGTP,
                    "time" => date("H:i:s"),
                    "date" =>date("d-m-Y")
            ];
            
            $newChatArray = json_decode($chat->chat);
            array_push($newChatArray, $msg);
            array_push($newChatArray, $msgGtp);
            $chat->chat = json_encode($newChatArray);
            $chat->save();
            
            return response()->json(json_decode($chat['chat']), 200);
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
        $chat = Chats::where('phone', $request->phone)->first();
        $user = User::where('telefono' ,'=', $request->phone)->first();

        $type = '';      

        $msg = [
            "id" => Uuid::uuid4(),
            "sender" => 'GTP',
            "type" =>  'RESPONSE',
            "msg" => $request->msg,
            "time" => date("H:i:s"),
            "date" =>date("d-m-Y")
        ];
        if(!empty($chat)){
            $newChatArray = json_decode($chat->chat);
            array_push($newChatArray, $msg);
            $chat->chat = json_encode($newChatArray);
            $chat->ia = 0;
            $send = sendPost($request->msg, $request->phone);

            if(isset($send['contacts'][0]['input'])){
                $chat->save(); 
                return response()->json(['check'=>$chat->ia, 'status'=>200]);  
            }else{
                return response()->json("mensaje no enviado", 403);  
            }

        }else{
            return response()->json("usuario no encontrado", 403);   

        }    
    }

}

function sendPost($msj, $numberPhone)
{
   

        $mensaje = array( 
            "messaging_product" => "whatsapp",
            "preview_url" => false,
            "recipient_type" => "individual",
            "to" => $numberPhone,
            "type" => "text",
            "text" => array(
                "body" => $msj,
            )
        );
            
        $mensaje = json_encode($mensaje); 

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/v18.0/184815844707599/messages/"); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
        'Authorization: Bearer EAAVfF7edYckBO5IFb1mhyZArZBZBrLBdgj0Hr9duXGQ6q7TruSZCU6vCvlKa90gm5FxuWsATZCc1zKX3MOpo2pU5hnZAxduA9OANNYhHOKbs94WYyv3YYXBvILhcYXbKVAIZAUbtNJbwrsQPyxx3q0PIN5BUeMX4Ir53ZAh1WXEm42sibCqbvvjFbj7ssKnMBJ5E' // one siynal keys_and_ids
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

function sendGTP($text, $question_request){

    $preguntaGtp = "responde a la pregunta siguiente contestanto la informacion que te estoy otorgando para generar un respuesta basada a ella.\n" . "pregunta:".$question_request."\n"."informacion:".$text. "\n"."si la pregunta no tiene mucha coherencia con la informacion responda algo relacionado que no estas capaitado para responder ese tipo de preguntas";

    $result = OpenAI::chat()->create([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'user', 'content' => $preguntaGtp],
        ],
    ]);

        return $result->choices[0]->message->content;
}

function extractPDF($question_request)
{

    $question = explode(" ", $question_request);
    $parser = new Parser();
    $pdf = $parser->parseFile(public_path().'/pdf'.'/book.pdf');
    $final_text = [];
    //$text = $pdf->getText();
    $pages = $pdf->getPages();
    foreach ($question as $word) {
        if(strlen($word) > 4){
            foreach ($pages as $page) {
                $tex_page = $page->getText();

                if(strpos($tex_page, $word)){
                    array_push($final_text, $tex_page);
                }
            }
        }
    }
    $final_text = implode(",", $final_text);
    $final_text = str_replace(array("\r\n", "\n", "\r"), '', $final_text);
    return $final_text;
}
