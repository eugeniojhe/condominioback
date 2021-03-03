<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 
use App\Models\Unit; 
use App\Models\Tenant;
use App\Models\UnitTenant;  
use App\Models\UnitVehicle; 
use App\Models\UnitPet; 

class UnitController extends Controller
{
   public function getInfo($id){
       $resp = ['error' => '', 'list' => []];     
       if (!isset($id)){
           return $resp['error'] = "Código da Unidade não informado"; 
       }
       $user = Auth()->user(); 
       $units   = Unit::where('company_id',$user->company_id)->get();
       if ($units[1]->id){
           foreach($units as $unitKey => $unitValue) {
               $units[$unitKey]['tenants'] = $unitValue->unittenants()
                                                       ->where('company_id',$user->company_id)
                                                       ->get(); 
               $units[$unitKey]['vehicles'] = $unitValue->vehicles()
                                                        ->where('company_id',$user->company_id)
                                                        ->get(); 
               $units[$unitKey]['pets'] = $unitValue->pets()
                                                    ->where('company_id',$user->company_id)
                                                    ->get(); 
               $resp['list'] = $units; 
           }

       }else {
           return $resp['error'] = "Unidade não cadastrada"; 
       }
       return $resp; 
    }

    public function addPerson($unit,Request $request)
    {
        $resp = ['error' => ''];
        if (!isset($unit)) {
            $resp['error'] = "Informe a unidade"; 
            return $resp; 
        }
        $user = Auth()->user(); 
        $unit = Unit::where('id',$unit)
                     ->where('company_id',$user->company_id); 
        if (!$unit){
            $resp['error'] = "Unidade não cadastrada"; 
        }

        $validator = Validator::make($request->all(), [
            'unit_id' => 'required|exists:units,id', 
            'tenant_id' => 'required|exists:tenants,id',
            'is_owner' => 'sometimes:required|boolean',
            'tenanttype_id' => 'required|exists:tenanttypes,id',
            'entry_date'  => 'sometimes:required|date_format:d/m/Y',
            'departure_date' => 'sometimes:required:date_format:d/m/Y',
            'purchased_date' => 'sometimes:requied:date_format:d/m/Y',
        ]);

        if($validator->fails()){
            $resp['error'] = $validator->errors(); 
            return $resp; 
        }
        $unitId = $request->input('unit_id'); 
        $tenantId = $request->input('tenant_id');
        $isOwner = $request->input('is_owner');
        $tenanttypeId = $request->input('tenanttype_id'); 
        $entryDate = $request->input('entry_date');
        $departureDate = $request->input('departure_date');
        $purchaseDate = $request->input('purchased_date'); 
        
        
        $unitTenant = new UnitTenant(); 
        $unitTenant->company_id = $user->company_id; 
        $unitTenant->unit_id = $unitId;
        $unitTenant->tenant_id = $tenantId;
        if ($isOwner !=null){
            $unitTenant->is_owner = $isOwner; 
        }; 
        if (isset($tenanttypeId)){
            $unitTenant->tenanttype_id = $tenanttypeId;; 
        };      
       
        $unitTenant->created_by = $user->id;  
        $unitTenant->entry_date = $entryDate; 
        $unitTenant->departure_date = $departureDate; 
        $unitTenant->purchased_date = $purchaseDate;
        try {
            $unitTenant->save(); 
        }catch(\Exception $e){
            $resp['error'] = $e->getMessage(); 
        }              
        return $resp;
    }

    public function addVehicle($unitId, Request $request)
    {
        $resp = ['error' => ''];
        if(!$unitId) {
            $resp['error'] = 'O Código da Unidade é obrigatório'; 
            return $resp; 
        }
     
       $unit = Unit::find($unitId);
       if (!$unit) {
           $resp['error'] = 'Unidade não cadastrada'; 
           return $resp; 
       }

        $user = Auth()->user(); 
        $validator = Validator::make($request->all(), [
            'name' => 'required:min:4',
            'color' =>  ['sometimes:required','regex:/^[#][0-9A-F]{3,6}$/i'],
            'plate' =>  'sometimes:required|min:4',
            'register' => 'sometimes:required|numeric|min:8', 
            'renavan' => 'sometimes:requerid:min:10',
            'year' => 'sometimes:required:date_format:"Y"',
            'photo' => 'sometimes|file|mimes:jpg,png',            
        ]);

        if ($validator->fails()){
            $resp['error'] = $validator->errors(); 
            return $resp; 
        }
        $file = $request->file('photo')->store('public'); 
        if ($file) {
            $file = explode('public/',$file);
            $file = $file[1]; 
        }
        $name = $request->input('name');
        $color = $request->input('color'); 
        $plate = $request->input('plate');
        $register = $request->input('register'); 
        $renavan = $request->input('renavan'); 
        $year = $request->input('year'); 
        $photo = $file; 

        $unitVehicle = new UnitVehicle(); 
        $unitVehicle->company_id = $user->company_id; 
        $unitVehicle->unit_id = $unitId; 
        $unitVehicle->name = $name;
        $unitVehicle->color = $color; 
        $unitVehicle->plate = $plate;
        $unitVehicle->register = $register; 
        $unitVehicle->renavan = $renavan; 
        $unitVehicle->year = $year; 
        if ($photo){
            $unitVehicle->photo = $photo;
        };         
        $unitVehicle->created_by = $user->id; 
        try{
            $unitVehicle->save(); 
        }catch(\Exception $e) {
            $resp['error'] = $e->getMessage(); 
        }     
        return $resp; 
    }


    public function addPet($unitId, Request $request)
    {
        $resp = ['error' => ''];
        if(!$unitId) {
            $resp['error'] = 'O Código da Unidade é obrigatório'; 
            return $resp; 
        }
     
       $unit = Unit::find($unitId);
       if (!$unit) {
           $resp['error'] = 'Unidade não cadastrada'; 
           return $resp; 
       }

        $user = Auth()->user(); 
        $validator = Validator::make($request->all(), [
            'name' => 'required:min:2',
            'race' => 'sometimes:require|min:2',
            'color' =>  ['sometimes:required','regex:/^[#][0-9A-F]{3,6}$/i'],
            'photo' => 'sometimes|file|mimes:jpg,png',            
        ]);

        if ($validator->fails()){
            $resp['error'] = $validator->errors(); 
            return $resp; 
        }
        $file = $request->file('photo')->store('public'); 
        if ($file) {
            $file = explode('public/',$file);
            $file = $file[1]; 
        }
        $name = $request->input('name');
        $race = $request->input('race'); 
        $color = $request->input('color'); 
        $photo = $file; 

        $unitPet = new UnitPet(); 
        $unitPet->company_id = $user->company_id; 
        $unitPet->unit_id = $unitId; 
        $unitPet->name = $name;
        $unitPet->race = $race; 
        $unitPet->color = $color;  
        if ($photo){
            $unitPet->photo = $photo;
        };        
        $unitPet->created_by = $user->id; 
        try{
            $unitPet->save(); 
        }catch(\Exception $e) {
            $resp['error'] = $e->getMessage(); 
        }     
        return $resp; 
    }


}
