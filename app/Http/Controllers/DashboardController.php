<?php

namespace App\Http\Controllers;

use App\Models\Talonarios;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Auth;
use App\Models\Participants;
use App\Models\Bitacoras;
use Carbon\Carbon;

class DashboardController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        $talonarios = DB::table('talonarios')->get();
        $tickets_sell = 0;
        $tickets_temp = 0;
        $participants_temp = [];
        $participants = Participants::all();
        foreach($participants as $participante){
            $participante->tickets_all = 0; 
        }
        foreach($talonarios as $talonario){
            $talonario->array_numbers = json_decode($talonario->array_numbers);
            foreach($talonario->array_numbers as $tickets){
                if ($tickets->participant != ''){
                    array_push($participants_temp, $tickets->participant);
                    $tickets_temp = $tickets_temp + 1;
                }
                
            }
            foreach($participants as $participante){
                foreach($participants_temp as $participant){
                    if($participant == $participante->ci){
                        $participante->tickets_all = $participante->tickets_all + 1;
                    }
                }
            }
            $talonario->participants = array_unique($participants_temp);
            $talonario->tickets_sell = $tickets_temp;
            $tickets_sell = $tickets_sell + $tickets_temp; 
            $tickets_temp = 0;
            $participants_temp = [];
        } 

        $participants = $participants->sortByDesc('tickets_all')->take(5);

       $talonarios_actives = $talonarios->where('status', '1');
       $now = Carbon::now()->startOfDay();
       $limitDate = $now->copy()->addDays(3);

       $talonarios_expire =  Talonarios::where('endDate', '>=', $now)
        ->where('endDate', '<=', $limitDate)
        ->get();
       
       $bitacoras = Bitacoras::all();

        return view('dashboard', compact('talonarios','participants', 'tickets_sell', 'talonarios_actives', 'talonarios_expire', 'bitacoras'));
    }

    public function getIp(){

        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) { // Soporte de Cloudflare
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif (isset($_SERVER['DIRECCIÓN REMOTA']) === true) {
            $ip = $_SERVER['DIRECCIÓN REMOTA'];
            if (preg_match('/^(?:127|10)\.0\.0\.[12]?\d{1,2}$/', $ip)) {
                if (isset($_SERVER['HTTP_X_REAL_IP'])) {
                    $ip = $_SERVER['HTTP_X_REAL_IP'];
                } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                }
            }
        } else {
            $ip = '127.0.0.1';
        }
        if (in_array($ip, ['::1', '0.0.0.0', 'localhost'], true)) {
            $ip = '127.0.0.1';
        }
        $filter = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        if ($filter === false) {
            $ip = '127.0.0.1';
        }

        return $ip;
    }

}