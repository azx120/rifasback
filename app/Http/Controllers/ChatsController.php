<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Chats;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Hash;

class ChatsController extends Controller
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
        return view('chats.index', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendMsg(Request $request)
    {

        $sms = sendPost($datasUser->code_phone, $request->telefono);
        var_dump($sms);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function useIAChat(Request $request)
    {
        
       $data = Chats::where('id', '=', $request->chatId)->first();

       if(!empty($data)){
            $data->ia = !$data->ia;
            if ($data->save()){
                return $data->ia;
            }else{
                return $request->value;
            }
            
       }else{
            return $request->value;
       }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($phone)
    {
        $user = User::where('telefono', $phone)->count();
        if(!empty($user)){
            $datas = Chats::where('phone', $phone)->first();
            if(!empty($datas)){
                $datas->chat = json_decode($datas->chat); 
         
                return view('chats.show', compact('datas', 'user'));
            }else{
                return redirect('/chats')->with('Error', 'No tiene chat este telefono');

            }
            
        }else{
            return redirect('/chats')->with('Error', 'Problemas para visualizar el registro');
        }
       
    }

}

