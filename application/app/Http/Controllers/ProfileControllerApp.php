<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileControllerApp extends Controller
{
    public function search(Request $request)
    {
        $search = $request->input('search');
        $results = Profile::where('nameProfile', 'like', "%$search%")->get();

        return view('search', ['results' => $results]);
        
    }
    public function perfil(){
        return view('perfil');
    }

       
    
}
