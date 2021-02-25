<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 

use App\Models\Doc; 

class DocController extends Controller
{
    public function getAll()
    {
        $resp = ['error' => ''];
        $user = Auth()->user(); 
        $docs = Doc::where('company_id',$user->company_id)
                     ->get();
        foreach($docs as $key => $docValue)  {
            $docs[$key]['url'] = asset('storage/'.$docValue['url']); 
        }
        
        $resp['docs'] = $docs; 
        return $resp; 
    }
}
