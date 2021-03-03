<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Storage; 
use App\Models\Warning; 

class WarningController extends Controller
{
    public function insert(Request $request) 
    {
        $resp = ['error' => ''];
        $user = Auth()->user();
        $validator = Validator::make($request->all(),[
            'title' => 'required|min:5', 
            'description' => 'required',
            'unit' => 'required|exists:units,id', 
        ]);
        if (!$validator->fails()){
            $company_id = $user->company_id; 
            $unit = $request->input('unit');
            $title = $request->input('title');
            $description = $request->input('description');
            $expirationDate = $request->input('expiration'); 
            $list = $request->input('list'); 

            $newWarning = new Warning(); 
            $newWarning->company_id = $company_id; 
            $newWarning->unit_id = $unit; 
            $newWarning->title = $title; 
            $newWarning->description = $description;
            $newWarning->expiration_date = $expirationDate;  
            $newWarning->created_by = $user->id; 
            if ($list && is_array($list)) {
                $photos = []; 
                foreach($list as $listItem){
                    $url = explode('/',$listItem);
                    $photos[] = end($url); 
                }
                $newWarning->photos = implode(',',$photos);                
            } else {
                $newWarning->photos = ''; 
            }
            try {
                $newWarning->save(); 
            }catch(Exception $e){
                $resp['error'] = $e->getMessage(); 
            }
            
        } else {
            $resp['error'] = $validator->errors(); 
            return $resp; 
        }
        return $resp; 
    }

   public function getMyWarnings(Request $request) 
   {
       $resp = ['error' => '',
                'myWarnings' => '']; 
       $user = Auth()->user(); 
       $warnings = Warning::where('company_id',$user->company_id)
                            ->where('created_by',$user->id)->get(); 
       $resp['myWarning'] = $warnings; 


       return $resp; 
   }

    public function addWarningFile(Request $request) 
    {
        $resp = ['error' => ''];
        $validator = Validator::make($request->all(), [
            'photo' => 'required|file|mimes:png,jpg',             
        ]); 

        if (!$validator->fails()) {
            $file = $request->file('photo')->store('public'); 
            $resp['photo'] = asset(Storage::url($file)); 
        } else {
            $resp['error'] = $validator->errors(); 
            return $resp; 
        }

        return $resp; 
    }
}
