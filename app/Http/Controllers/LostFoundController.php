<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 
use App\Models\LostFound; 
use Illuminate\Validation\Rule;

class LostFoundController extends Controller
{
    public function insert(Request $request) 
    {
        $resp = ['error' => ''];
        $user = Auth()->user(); 

        $validator = Validator::make($request->all(),[
            'photo' => 'sometimes|file|mimes:jpg,png',  
            'description' => 'sometimes:required|min:10', 
            'status' => ['sometimes:required',Rule::in(['lost','recovered'])],
            'solution' => 'sometimes|required', 
        ]);
        
        $file = ''; 
        $file = $request->file('photo')->store('public'); 
        if ($file) {
            $file = explode('public/',$file); 
            $file = $file[1]; 
        }
       
        if (!$validator->fails()) {
            $newLostFound = new LostFound(); 
            $newLostFound->company_id = $user->company_id; 
            $newLostFound->photo = $file; 
            $newLostFound->description = $request->input('description'); 
            $newLostFound->created_by = $user->id; 
            try {
                $newLostFound->save(); 
            }catch(Exception $e){
                $resp['error'] = $e->getMessage(); 
            }            
        } else {
            $resp['error'] = $validator->errors();         }
        return $resp;
    }

    public function getAll()
    {
        $resp = ['error' => '','list' => []];
        $user = Auth()->user(); 
        $lostFounds = LostFound::where('company_id',1)->get(); 
        if (isset($lostFounds[0]->id)){
            foreach($lostFounds as $lostFoundKey => $valueLostFound){                ;
                $lostFounds[$lostFoundKey]['photo'] =  asset('storage/'.$valueLostFound->photo);
            }
            $resp['list'] = $lostFounds; 
        }        
        return $resp; 
    }

    public function update($id,Request $request) {
        $resp = ['error' => ''];

        $description = $request->input('description');
        $validator = Validator::make($request->all(),[
            'photo' => 'sometimes|file|mimes:jpg,png',  
            'description' => 'sometimes:required|min:10', 
            'status' => ['sometimes:required',Rule::in(['lost','recovered'])],
            'solution' => 'sometimes|required', 
        ]);

        if ($validator->fails()){
            $resp['error'] = $validator->errors();
            return $resp; 
        }




        if (!$id) {
            $resp['error'] = 'Código do item não informado'; 
            return $resp; 
        } 
        $user = Auth()->user(); 

        $lostFound = LostFound::find($id); 
        if (!$lostFound){
            $resp['error'] = "Registro não encontrado"; 
            return $resp; 
        };

        /*
         Rule::unique('users')->ignore($user)
        Illuminate\Support\Fluent
       
        $v->sometimes(['reason', 'cost'], 'required', function ($input) {
    return $input->games >= 100;
});

        */ 

      
        $file = $request->file('photo');
        $description = $request->input('description');
        $status = $request->input('status');
        $solution = $request->input('solution'); 
        if ($file) {
            $file->store('public');
            $file = explode('public/',$file); 
            $file = $file[1]             ; 
        }        
                
        if ($file){
            $lostFound->photo = $file; 
        }
        if($description) {
            $lostFound->description = $description; 
        }
        if ($status) {
            $lostFound->status = $status; 
        }
        if ($solution) {
            $lostFound->solution = $solution; 
        }
        if ($lostFound->isDirty()) {
            $lostFound->save();
            $resp['save'] = true; 
        } else {
            $resp['save'] = false; 
        }            
        return $resp; 
    }
}
 