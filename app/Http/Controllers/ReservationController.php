<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 
use App\Models\Reservation; 
use App\Models\Unit;
use App\Models\Area; 

class ReservationController extends Controller
{
    public function setReservation($areaId, Request $request) {
        $resp = ['error' => ''];
        if (!$areaId){
            $resp['error'] = 'Informa a area a ser servada'; 
            return $resp; 
        }
        $user = Auth()->user(); 
        $request->request->add(['area_id',$areaId]);
        $validator = Validator::make($request->all(), [
            'area_id' => 'required|exists:areas,id', 
            'unit_id' => 'required|exists:units,id',
            'title' => 'required|min:2', 
            'reservation_date' => 'required|date_format:Y-m-d', 
            'start_time' => 'required|date_format:"H:i:s"',
            'end_time' => 'required|date_format:"H:i:s"',
        ]);
        
        if ($validator->fails()){
            $resp['error'] = $validator->errors(); 
            return $resp;             
        }
        
        if ($request->input('start_time') > $request->input('end_time')){
            $resp['error'] = 'Horario Invalido'; 
            return $resp; 
        }
        $reservation = new Reservation(); 
        $reservation->area_id  = $request->input('area_id'); 
        $reservation->company_id = $user->company_id; 
        $reservation->unit_Id = $request->input('unit_id');
        $reservation->title = $request->input('title');
        $reservation->reservation_date = $request->input('reservation_date');
        $reservation->start_time = $request->input('start_time');
        $reservation->end_time = $request->input('end_time');
        $reservation->user_id = $user->id; 
        try {
            $reservation->save(); 
        }catch(\Exception $e){
            $resp['error'] = $e->getMessage(); 
        }
        return $resp; 

    }
}
