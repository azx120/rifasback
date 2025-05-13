<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Paises;
use App\Models\Provincias;
use App\Models\Ciudades;
//use App\Models\Version;
use Illuminate\Support\Facades\DB;
//use App\Http\Controllers\API\NotificationsController;

class DatesBdController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCountry(){


        $citys = Ciudades::all();
        $provincias = Provincias::all();


        $array_provincias = [];
        $array_ciudades = [];
        



        foreach($provincias as $provincia){

                foreach($citys as $city){
                    if($city->id_provincia == $provincia->id){
                        array_push($array_ciudades, $city);
                    }
                }

                $provincia['ciudades'] = $array_ciudades;
                array_push($array_provincias, $provincia);
                $array_ciudades = [];

        }

        return response()->json($array_provincias, 200);   
    }


 
 /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /*
    public function postTry(Request $request){

        $data = [
            'business_id' => $request->bussines_id,
            'user_id' => $request->user_id,
            'type' => 'REJECTED_BUSINESS',
            'body' => [
                'menssage' => 'no sirve',
                'mail' => 'xample@gmail.com'
            ]
        ];
        $data = json_encode($data);
        NotificationsController::createNotification($data);
        return response()->json(':)', 200);   
    }*/

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function xample(){
        $query = DB::table('provincias')
        ->join('paises', 'provincias.paises_id' , '=', 'paises.id')
        ->select('provincias.id',  'provincias.name', 'paises.name as pais', 'paises.flag')
        ->get();
    
        return response()->json($query, 200);   
    }

}