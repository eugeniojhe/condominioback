<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 
use App\Models\Reservation; 
use App\Models\Unit;
use App\Models\Area;
use App\Models\AreaDisableDay;  
use DateTime;

class ReservationController extends Controller
{
    public function getReservations()
    {
            $resp = ['error' => '',
                     'list' => []];            
            $daysWeek = ['Domingo','Segunda', 'Terca','Quarta','Quinta','Sexta','Sabado'];           
            $areas = Area::where('status', 1)->get();
             foreach($areas as $area) {
                 $daysGroup = [];
                if (!is_null($area['days'])){
                    $daysList = explode(',',$area['days']);                
                                        
                    if (count($daysList) > 0  ) {
                        $idx = 0;
                        $dayHours = [];   
                        $dayHours[$idx] = $daysWeek[intval(current($daysList))];
                        $lastDay = intval(current($daysList));
                        array_shift($daysList); 
                        foreach($daysList as $currentDay){
                            if ($lastDay+1 != intval($currentDay)){
                                $idx++;
                                $dayHours[$idx] = $daysWeek[intval($currentDay)];    
                            } else {
                                $dayHours[$idx] .= ','.$daysWeek[intval($currentDay)];
                            }
                            $lastDay = intval($currentDay);                                                        
                        }
                                                   
                        $dayOpens = []; 
                        foreach($dayHours as $currentDay){
                            $dayString = $currentDay; 
                            $array = explode(',',$dayString); 
                            $separator = (count($array) > 2)?' a ': '-';        
                            $newArray = array_shift($array);
                            $newArray .= (end($array)? $separator.end($array): ' ').' ';
                            $startTime = date('H:i',strtotime($area->start_time)).' as ';
                            $endTime = date('H:i',strtotime($area->end_time));

                            $newArray .= $startTime.$endTime;  
                            $dayOpens[] = $newArray;                   
                        }
                 
                    }                 
                    $resp['list'][] = [
                        'id' => $area->id,
                        'title' => $area->title, 
                        'cover' => asset('storage/').$area->cover,
                        'shedules' => $dayOpens,
                    ];
                }                    
             }
                     
            return $resp; 
    }

      public function setReservation($areaId, Request $request) {
        $resp = ['error' => ''];
        if (!$areaId){
            $resp['error'] = 'Informa a area a ser servada'; 
            return $resp; 
        }
        $user = Auth()->user();    
        
        $area = Area::where('id',$areaId)
                     ->where('company_id',$user->company_id)
                     ->first(); 
        if (!$area){
            $resp['error'] = 'Area para alocação não existe'; 
            return $resp; 
        }
      
        $today = strtotime('now');
        $yesterday = strtotime('-1 day',$today);
        $yesterday = date('Y-m-d',$yesterday);  
        $end_time = date('H:i:s',strtotime($area->end_time)); 
        $newRequest = $request->request->add(['area_id' => $areaId]);
        $areaPreviousHours = $area->previous_hours; 
        $validator = Validator::make($request->all(), [
            'area_id' => 'required|exists:areas,id', 
            'unit_id' => 'required|exists:units,id',
            'title' => 'required|min:2', 
            'reservation_date' => 'required|date_format:Y-m-d',
            //'reservation_date' => 'required|date_format:Y-m-d|after:yesterday', 
            'start_time' => 'required|before:end_time|date_format:"H:i:s"',           
        ]);
        
        if ($validator->fails()){
            $resp['error'] = $validator->errors(); 
            return $resp;             
        }
        
        
        $reservationDate = $request->input('reservation_date'); 
        $weekDay = date('w',strtotime($reservationDate));
        $weekDays = explode(',',$area->days);
        $reservationStart = strtotime($request->input('start_time'));
        $areaStartTime = strtotime($area->start_time); 
        $areaEndTime = strtotime($area->end_time);  

        $disableDays = AreaDisableDay::where('company_id',$user->id)
                                      ->where('area_id',$area->id)
                                      ->where('day',$reservationDate)                                     
                                      ->count(); 
        if($disableDays > 0){
            $resp['error'] = 'Data não disponivel para alocação desta area';
            return $resp; 
        }

         $message = ''; 
         $message = $this->validateReservationTime($weekDay,
                                             $weekDays,
                                             $reservationStart,
                                             $areaStartTime,
                                             $areaEndTime,
                                             $areaPreviousHours,
                                             );
         
         
         if ($message != ''){
             $resp['error'] = $message;
             return $message; 
         }
              
      
        $countReservation = Reservation::where('company_id',$user->company_id)
                                   ->where('area_id',$area->id) 
                                   ->where('reservation_date', $reservationDate)
                                   ->whereNull('end_time')
                                   ->count();
        if($countReservation > 0){
            $resp['error'] = 'Periódo indispónivel ou já reservado'; 
            return $resp; 
        } 
            
        $reservation = new Reservation(); 
        $reservation->area_id  = $request->input('area_id'); 
        $reservation->company_id = $user->company_id; 
        $reservation->unit_Id = $request->input('unit_id');
        $reservation->title = $request->input('title');
        $reservation->reservation_date = $reservationDate; 
        $reservation->start_time = $request->input('start_time');    
        $reservation->user_id = $user->id; 
        try {
            $reservation->save(); 
        }catch(\Exception $e){
            $resp['error'] = $e->getMessage(); 
        }
        return $resp; 

    }


    private function validateReservationTime($weekDay,$weekDays,
                                              $reservationStart,
                                              $areaStartTime,
                                              $areaEndTime,
                                              $areaPreviousHours 
                                             )
        {

        if (!in_array($weekDay,$weekDays)){
            $message = 'Area não disponivel para alocação neste dia'; 
            return;
        }

        $areaPreviousHours = (is_null($areaPreviousHours)? 0 : $areaPreviousHours); 
        $areaEndTime =   strtotime('-1 hour',strtotime($areaEndTime));
        if ($reservationStart < $areaStartTime || $reservationStart > $areaEndTime){
            $message= 'Horário reserva, esta fora do limite de reserva para este dia'; 
            return; 
        }                                          

    }

}
