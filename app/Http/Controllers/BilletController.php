<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Billet; 
use App\Models\Tenant; 
use App\Models\UnitTenant; 

class BilletController extends Controller
{
    public function getAll(Request $request)
    {
        $resp = ['error' => '',
                 'billets' => []];
        $unit = $request->input('unit');
        if ($unit) {
            $user = Auth()->user(); 
            $tenant = Tenant::where('company_id',$user->company_id)
                          ->where('user_id',$user->id)->first(); 
            if (isset($tenant->id) && $tenant->user_id == $user->id){
                $unitTenant = UnitTenant::where('company_id',$user->company_id)
                                        ->where('unit_id',$unit)
                                        ->where('tenant_id',$tenant->id)
                                        ->where('is_owner',true)
                                        ->first(); 
                if (isset($unitTenant->unit_id)){
                    $billets = Billet::where('company_id',$user->company_id)
                                  ->where('unit_id',$unitTenant->unit_id)
                                  ->get();                
                    foreach($billets as $keyBillets => $valueBillet) {
                        $billets[$keyBillets]['url'] = asset('storage/'.$valueBillet['url']); 
                    }
                    $resp['billets'] = $billets; 
                }  

            } else {
                $resp['error'] = 'Não existe inquilino cadastrado para este usuario '; 
                return $resp; 
            }
            
        } else  {
            $resp['error'] = 'É necessário informar uma unidade'; 
            return $resp; 
        }
         return $resp; 
    } 
}
