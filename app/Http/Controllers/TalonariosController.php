<?php

namespace App\Http\Controllers;

use App\Models\Talonarios;
use App\Models\User;
use App\Models\Pagos;
use App\Models\Provincias;
use App\Models\Participants;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Bitacoras;

class TalonariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        $data = DB::table('talonarios')->get();
        $tickets_sell = 0;
        $tickets_temp = 0;
        foreach($data as $talonario){
            $talonario->array_numbers = json_decode($talonario->array_numbers);
            foreach($talonario->array_numbers as $tickets){
                if ($tickets->participant != ''){
                    $tickets_temp = $tickets_temp + 1;
                }
                
            }

            $talonario->tickets_sell = $tickets_temp;
            $tickets_sell = $tickets_sell + $tickets_temp; 
            $tickets_temp = 0;

        } 
        return view('talonarios.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('talonarios.create');
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
            'title' => ['required'],
            'price' => ['required'],
            'numbers' => ['required'],
            'endDate' => ['required'],
        ],
        [
            'title.required' => 'El campo titulo  es obligatorio',
            'price.required' => 'El campo pricio es obligatorio',
            'endDate.required' => 'El campo Finalización es obligatorio',
            'numbers.required' => 'El campo cantidad de voletos es obligatorio',
        ]);


        $array_numbers = [];

        for ($i = 1; $i <= $request->numbers; $i++) {
            $array_numbers[] = [
                'id' => str_pad($i, 5, '0', STR_PAD_LEFT),
                'participant' => '', // Número de teléfono único
                'winner' => false,
                'status' => "free"
            ];
        }

        $talonario_last = DB::table('talonarios')->where('status', 1)->orderBy('created_at', 'desc')->first();

        if(!empty($talonario_last)){
            $talonario_last = Talonarios::where('id', $talonario_last->id)->first();
            //$talonario_last->status = 0;
            $talonario_last->save();
        }


        $registro = new Talonarios();
        $registro->title = 	$request->title;
        $registro->price =	$request->price;
        $registro->winners =	"[]";
        $registro->numbers	=	$request->numbers;
        $registro->endDate	=	$request->endDate;
        $registro->array_numbers = json_encode($array_numbers);
        $registro->user_id = Auth::user()->id;
        $registro->description = 	$request->description;

        if ($request->hasFile('imageUrl')) {
            $uploadPath = public_path('/storage/images_rifas/');
            $file = $request->file('imageUrl');
            $fileName = $file->getClientOriginalName();
            $file->move($uploadPath, $fileName);
            $url = asset('/storage/images_rifas/'.$fileName);
            $registro->imageUrl = $url;
        }else{
            $registro->imageUrl = '';
        }

        if ($request->counterGallery > 0) {
            $arrayGallery = [];

            for ($i = 1; $i <= $request->counterGallery; $i++) {

                $uploadPath = public_path('/storage/gallery_rifas/'.$registro->id.'/');
                $file = $request->file('imgGallery_'.$i);
                $extension = $file->getClientOriginalExtension();
                $uuid = Str::uuid(4);
                $fileName = $uuid . '.' . $extension;
                $file->move($uploadPath, $fileName);
                $url = asset('/storage/gallery_rifas/'.$registro->id.'/'.$fileName);
           
                array_push($arrayGallery, [
                    "id" => $uuid,
                    "name" => $fileName,
                    "url" => $url,
                ]);
            }
        
            $registro->gallery =json_encode($arrayGallery);
        }else{
            $registro->gallery = json_encode([]);

        }

        $registro->save();

        $datoBitacoras = New Bitacoras();
        $datoBitacoras->id_user = Auth::id();
        $datoBitacoras->type = 'CREATE_RIFA';
        $datoBitacoras->id_ref = $registro->id;
        $datoBitacoras->ip = $request->getClientIp();
        $datoBitacoras->color = 'success';
        $datoBitacoras->comment = 'usuario '. Auth::user()->email. ' creo la rifa '. $registro->title ;
        $datoBitacoras->save();

        return redirect('talonarios')->with('success', 'Registro Guardado exitosamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\  $Talonarios
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
        $count = Talonarios::where('id', $id)->count();
        if ($count>0) {
            $data = Talonarios::where('id', $id)->first();
            $data->array_numbers = json_decode($data->array_numbers);
            $data->winners = json_decode($data->winners);
            $items_per_row = 5;
            $row_count = ceil(count($data->array_numbers) / $items_per_row);
            $provincias = Provincias::all();
            $pagos = DB::table('pagos')
            ->join('participants', 'pagos.participant_id' , '=', 'participants.id')
            ->select('pagos.*', 'participants.ci', 'participants.name', 'participants.lastname', 'participants.phone')
            ->where('pagos.talonario_id', $data->id)
            ->get();

            //var_dump($data->winners);
            //exit;

            $data->array_numbers = json_encode($data->array_numbers);
            return view('talonarios.show', compact('data', 'items_per_row', 'row_count', 'provincias','pagos'));
        } else {
            return redirect('/talonarios')->with('danger', 'Problemas para Mostrar el Registro.');
        }
    }

     /**
     * Display the specified resource.
     *
     * @param  \App\Models\  $Talonarios
     * @return \Illuminate\Http\Response
     */
    public function selecionar_ganadores($id)
    {
       
        $count = Talonarios::where('id', $id)->count();
        if ($count>0) {
            $data = Talonarios::where('id', $id)->first();
            $data->array_numbers = json_decode($data->array_numbers);
            $items_per_row = 5;
            $row_count = ceil(count($data->array_numbers) / $items_per_row);
           
            $data->array_numbers = json_encode($data->array_numbers);
            
            return view('talonarios.winner', compact('data', 'items_per_row', 'row_count'));
        } else {
            return redirect('/talonarios')->with('danger', 'Problemas para Mostrar el Registro.');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Talonarios  $Talonarios
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $count = Talonarios::where('id', $id)->count();
        if ($count>0) {
            $data = Talonarios::where('id', $id)->first();
            $provincias = Provincias::all();
            return view('talonarios.edit', compact('data', 'provincias'));
        } else {
            return redirect('/talonarios')->with('danger', 'Problemas para Mostrar el Registro.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Talonarios  $Talonarios
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => ['required'],
            'price' => ['required'],
            'endDate' => ['required'],
        ],
        [
            'title.required' => 'El campo titulo  es obligatorio',
            'price.required' => 'El campo pricio es obligatorio',
            'endDate.required' => 'El campo Finalización es obligatorio',
        ]);

        $count = Talonarios::where('id', $id)->count();
        if ($count>0) {
            $registro = Talonarios::where('id', $id)->first();

            $registro->title = 	$request->title;
            $registro->price =	$request->price;
            $registro->endDate	=	$request->endDate;
            $registro->description = 	$request->description;

            if ($request->hasFile('imageUrl')) {
                $uploadPath = public_path('/storage/images_rifas/');
                $file = $request->file('imageUrl');
                $fileName = $file->getClientOriginalName();
                $file->move($uploadPath, $fileName);
                $url = asset('/storage/images_rifas/'.$fileName);
                $registro->imageUrl = $url;
            }

            if ($request->counterGallery > 0) {
                $arrayGallery = [];

                for ($i = 1; $i <= $request->counterGallery; $i++) {

                    $uploadPath = public_path('/storage/gallery_rifas/'.$registro->id.'/');
                    $file = $request->file('imgGallery_'.$i);
                    $extension = $file->getClientOriginalExtension();
                    $uuid = Str::uuid(4);
                    $fileName = $uuid . '.' . $extension;
                    $file->move($uploadPath, $fileName);
                    $url = asset('/storage/gallery_rifas/'.$registro->id.'/'.$fileName);
            
                    array_push($arrayGallery, [
                        "id" => $uuid,
                        "name" => $fileName,
                        "url" => $url,
                    ]);
                }
            
                $registro->gallery =json_encode($arrayGallery);
            }

            $registro->save();

            $datoBitacoras = New Bitacoras();
            $datoBitacoras->id_user = Auth::id();
            $datoBitacoras->type = 'CREATE_RIFA';
            $datoBitacoras->id_ref = $registro->id;
            $datoBitacoras->ip = $request->getClientIp();
            $datoBitacoras->color = 'success';
            $datoBitacoras->comment = 'usuario '. Auth::user()->email. ' edito la rifa '. $registro->title ;
            $datoBitacoras->save();
            
                return redirect('/talonarios')->with('success', 'Registro Actualizado Exitosamente!');

        } else {
            return redirect('/talonarios')->with('danger', 'Problemas para Actualizar el Registro.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Talonarios  $Talonarios
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $count = talonarios::where('id', $id)->count();
        if ($count>0) {
            Talonarios::where('id', $id)->delete();
           
            return redirect('/talonarios')->with('success', 'Registro Eliminado Exitosamente!');
        } else {
            return redirect('/talonarios')->with('danger', 'Problemas para Eliminar el Registro.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function tomar_numero(Request $request)
    {

        $request->validate([
            'id' => ['required', 'max:200'],
            'phone' => ['required'],
            'ci' => ['required'],
            'numbers' => ['required'],
        ],[
            'id.required' => 'Id rifa obligatorio.',
            'phone.required' => 'Telefono es obligatorio.',
            'ci.required' => 'Cedula es obligatoria.',
            'numbers.required' => 'Numero es obligatorio.',
        ]);

        $numbers_save = $request->numbers;
        $take_numbers = json_decode($request->numbers);

        if(!empty($take_numbers)){

            $participants = Participants::where('ci', $request->ci)->first();
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

            if($request->id_tra !== null){
                $pago = Pagos::where('id', $request->id_tra)->first();
                if (!empty($pago)){
                    //json_encode($request->numbers);
                    $pago->selected_numbers = $numbers_save;
                    $pago->status = "complete";
                    $pago->save();
                }
            }


            $array_response = [];
            $dato = Talonarios::where('id', $request->id)->first();
            $new_array = json_decode($dato->array_numbers);
            $get_numbers = '';

            foreach($take_numbers as $take_number){
                foreach($new_array as $numero){
                    if($numero->id == $take_number){
                        if($numero->status == "winner"){
                            $numero->participant = $request->ci;
                        }else if($numero->status == "free"){
                            $numero->participant = $request->ci;
                            $numero->status = 'sold';

                            if($get_numbers == ''){
                                $get_numbers = $numero->id;
                            }else{
                                $get_numbers = $get_numbers.', '.$numero->id;
                            }
                            
                        }
                    }
                }
            }

            $dato->array_numbers = json_encode($new_array);
            $dato->save();
            $data = [
                'type' => "recibo",
                'msj' => $get_numbers,
                'rifa' => $dato->title,
                'email' => $participants->email,
                'names' => $participants->name.' '.$participants->lastname,
                'pedido' => $pago->code,
                'fecha' => $pago->created_at,
                'monto'=> $pago->numbers,
                'qty'=> $pago->numbers,
                'selecionados' => $get_numbers,
                'metodo' => 'Transferencia bancaria o depósito',
            ];
            
            
            PHPMailerController::composeEmail($data);
            $dato->array_numbers = json_decode($dato->array_numbers);
            $datoBitacoras = New Bitacoras();
            $datoBitacoras->id_user = Auth::id();
            $datoBitacoras->type = 'CREATE_RIFA';
            $datoBitacoras->id_ref = $dato->id;
            $datoBitacoras->ip = $request->getClientIp();
            $datoBitacoras->color = 'success';
            $datoBitacoras->comment = 'usuario '. Auth::user()->email. ' vendio numeros de la rifa '. $dato->title ;
            $datoBitacoras->save();
            return redirect('/talonarios/'.$request->id.'/ver-talonario')->with('success', 'Registro Exitoso!');
        }else{
            return redirect('/talonarios')->with('danger', 'Problemas para Eliminar el Registro.');

        }  
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function accion_numbers(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'max:200'],
            'type' => ['required'],
            'numbers' => ['required'],
        ],[
            'id.required' => 'Id rifa obligatorio.',
            'type.required' => 'tipo accion es obligatoria.',
            'numbers.required' => 'Numero es obligatorio.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                'message' => $validator->errors()->first(),
            ], 403);
        }

        $take_numbers = json_decode($request->numbers);
        $pago = Pagos::where('id', $request->id)->first();
        $talonario = Talonarios::where('id', $pago->talonario_id)->first();

        $new_array = json_decode($talonario->array_numbers);
        if($request->type == "validar"){

            foreach($take_numbers as $take_number){
                foreach($new_array as $numero){
                    if($numero->id == $take_number){
                        if($numero->status == "reserved"){
                            $numero->status = 'sold';
                            
                        }

                    }
                }
            }
            $talonario->array_numbers = json_encode($new_array);
            $talonario->save();

            $pago->status = "complete";
            $pago->save();
            return response()->json("completado", 200);
        }else{

            foreach($take_numbers as $take_number){
                foreach($new_array as $numero){
                    if($numero->id == $take_number){
                        if($numero->status == "reserved"){
                            $numero->status = 'free';
                            
                        }

                    }
                }
            }
            $talonario->array_numbers = json_encode($new_array);
            $talonario->save();
            
            $pago->status = "refused";
            $pago->save();

            $datoBitacoras = New Bitacoras();
            $datoBitacoras->id_user = Auth::id();
            $datoBitacoras->type = 'CREATE_RIFA';
            $datoBitacoras->id_ref = $pago->id;
            $datoBitacoras->ip = $request->getClientIp();
            $datoBitacoras->color = 'success';
            $datoBitacoras->comment = 'usuario '. Auth::user()->email. ' vendio numeros de la rifa '. $talonario->title ;
            $datoBitacoras->save();

            return response()->json("completado", 200);
        }  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get_great_winner(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'max:200'],
    
        ],[
            'id.required' => 'Id rifa obligatorio.',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                'message' => $validator->errors()->first(),
            ], 403);
        }

        $talonario = Talonarios::where('id', $request->id)->where('status', 1)->first();

       


        if(!empty($talonario)){
            $new_array = json_decode($talonario->array_numbers);
            $number_winner = rand(1, $talonario->numbers);
            $participant = '';
            foreach($new_array as $numero){
                if($numero->id == $number_winner){

                    $numero->winner = true;

                    $numero->status = "winner plus";


                    $talonario->winner = $numero->participant;
                }
            }
            
            $talonario->array_numbers = json_encode($new_array);
            //$talonario->status = 0;
            $talonario->save();

            $datoBitacoras = New Bitacoras();
            $datoBitacoras->id_user = Auth::id();
            $datoBitacoras->type = 'CREATE_RIFA';
            $datoBitacoras->id_ref = $talonario->id;
            $datoBitacoras->ip = $request->getClientIp();
            $datoBitacoras->color = 'success';
            $datoBitacoras->comment = 'usuario '. Auth::user()->email. ' Seleciono ganadores automaticamente de la rifa '. $talonario->title ;
            $datoBitacoras->save();

            return response()->json($number_winner, 200);
        }else{

          
            return response()->json("Talonario finalizado", 403);
        }  
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get_winner(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'max:200'],
    
        ],[
            'id.required' => 'Id rifa obligatorio.',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                'message' => $validator->errors()->first(),
            ], 403);
        }

        $talonario = Talonarios::where('id', $request->id)->where('status', 1)->first();

       


        if(!empty($talonario)){
            $new_array = json_decode($talonario->array_numbers, true);
            //$number_winner = rand(1, $talonario->numbers);
            $participant = '';
         

            // 1. Filtrar elementos con status "free" y winner false
            $freeElements = array_filter($new_array, function($item) {
                return  $item['winner'] === false;
            });

            // 2. Mezclar los elementos libres y seleccionar 10
            shuffle($freeElements);
            $selected = array_slice($freeElements, 0, 10);

            // 3. Actualizar los seleccionados en el array original
            foreach ($selected as $item) {
                $key = array_search($item['id'], array_column($new_array, 'id'));
                if ($key !== false) {
                    $new_array[$key]['winner'] = true;
                    // Opcional: Cambiar status a "reserved" si es necesario
                    // $data[$key]['status'] = 'reserved';
                }
            }

         // Mostrar los ganadores
            $winners = array_filter($new_array, function($item) {
                return $item['winner'] === true;
            });

            $talonario->winners = json_encode(array_values($winners));
            
            $talonario->array_numbers = json_encode($new_array);
            //$talonario->status = 0;
            $talonario->save();

            $datoBitacoras = New Bitacoras();
            $datoBitacoras->id_user = Auth::id();
            $datoBitacoras->type = 'CREATE_RIFA';
            $datoBitacoras->id_ref = $talonario->id;
            $datoBitacoras->ip = $request->getClientIp();
            $datoBitacoras->color = 'success';
            $datoBitacoras->comment = 'usuario '. Auth::user()->email. ' Seleciono ganadores automaticamente de la rifa '. $talonario->title ;
            $datoBitacoras->save();

            return response()->json("Los numeros han sido selecionados automaticamente", 200);
        }else{

          
            return response()->json("Talonario finalizado", 403);
        }  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get_winner_manual(Request $request)
    {

        $request->validate([
            'id' => ['required', 'max:200'],
            'numbers' => ['required'],
            'type' => ['required']
    
        ],[
            'id.required' => 'Id rifa obligatorio.',
            'numbers.required' => 'numeros obligatorios.',
            'type.required' => 'tipo es obligatorio.',

        ]);

   

        $talonario = Talonarios::where('id', $request->id)->first();
        //$talonario = Talonarios::where('id', $request->id)->where('status', 1)->first();
        $winner = "";
        $ci_participan = "";

        if(!empty($talonario)){
            $new_array = json_decode($talonario->array_numbers);
            $take_numbers = json_decode($request->numbers);
            $great = '';
        
            foreach($take_numbers as $key => $take_number){
                foreach($new_array as $numero){
                    if($numero->id == $take_number){

                            $numero->winner = true;
                            if($request->type == 'winner'){
                                $numero->status = "winner plus";
                                $winner = $numero->id;
                                $ci_participan = $numero->participant;
                            }else{
                                $numero->status = "winner";
                            }

                          $great = $numero;

                    }
                }
            }
            $talonario->array_numbers = json_encode($new_array);
            $winners = json_decode($talonario->winners);
            array_push($winners, $great);
            $talonario->winners = json_encode($winners);
            //$talonario->status = 0;
            $talonario->save();

            $participants = Participants::where('ci', $ci_participan)->first();
            if(!empty($participants)){
                $data = [
                    'type' => "ganador",
                    'rifa' => $talonario->title,
                    'fecha' => $talonario->updated_at,
                    'numero'=>  $winner,
                    'email' => $participants->email,
                    'names' => $participants->name.' '.$participants->lastname,
                ];
                
                PHPMailerController::composeEmail($data);
            }



            $datoBitacoras = New Bitacoras();
            $datoBitacoras->id_user = Auth::id();
            $datoBitacoras->type = 'CREATE_RIFA';
            $datoBitacoras->id_ref = $talonario->id;
            $datoBitacoras->ip = $request->getClientIp();
            $datoBitacoras->color = 'success';
            $datoBitacoras->comment = 'usuario '. Auth::user()->email. ' Seleciono ganadores manualmente de la rifa '. $talonario->title ;
            $datoBitacoras->save();

            return redirect('/talonarios/'.$request->id.'/selecionar-ganadores')->with('success', 'exitoso');
        }else{

          
            return redirect('/talonarios/'.$request->id.'/selecionar-ganadores')->with('danger', 'finalizado');
        }  
    }

}


