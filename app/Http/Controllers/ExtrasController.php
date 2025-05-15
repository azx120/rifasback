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

class ExtrasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function banner() 
    { 

        return view('extras.banner');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Talonarios  $Talonarios
     * @return \Illuminate\Http\Response
     */
    public function store_banner(Request $request)
    {
        if ($request->hasFile('imageInput')) {
           
            
            $image = public_path('/storage/banner/banner.jpg');
            //var_dump(file_exists($image));
            //exit;
            if(file_exists($image)) {
                
                unlink($image);
            }
           
            $uploadPath = public_path('/storage/banner/');
            $file = $request->file('imageInput');
            $fileName = "banner.jpg";
            $file->move($uploadPath, $fileName);
            $url = asset('/storage/banner/'.$fileName);
        }
        return view('extras.banner')->with('success', 'subido Exitosamente!');
    }
}