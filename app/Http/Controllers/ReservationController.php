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
        
            $user = Auth()->user();    
            
            $area = Area::where('id',$areaId)
                        ->where('company_id',$user->company_id)
                        ->first(); 
            if (!$area){
                $message = 'Area para alocação não existe';
                $resp['error'] = $message;  
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
                'start_time' => 'required|date_format:H:i:s',              
                'reservation_date' => 'required|date_format:Y-m-d|after:yesterday',           
            ]);
            
            if ($validator->fails()){
                $resp['error'] = $validator->errors(); 
                return $resp;             
            } else {
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
                        $message = 'Data não disponivel para alocação desta area';
                        $resp['error'] = $message; 
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
                        $message = 'Periódo indispónivel ou já reservado';
                        $resp['error'] = $message; 
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
            }  
        return $resp; 
    }


    public function getDisableDates($idArea)
    {
        $resp = ['error' => '', 'disabledays' => []];
        $user = Auth()->user(); 
        $area = Area::where('id',$idArea)
                    ->where('company_id',$user->company_id)
                    ->first();
                
        if ($area) {
            $disableDays = AreaDisableDay::where('company_id',$user->company_id)
                                         ->where('area_id',$area->id)
                                         ->orderBy('day')
                                         ->get();
             $daysDisable = []; 
             foreach($disableDays as $disableDay){
                $daysDisable[] = $disableDay['day']; 
             }
             
             $areaDays = explode(',',$area->days);
             $areaDaysOff = [];
             for($day = 0;$day<7;$day++){
                 if (!in_array($day,$areaDays)){
                    $areaDaysOff[] = $day; 
                 }
             }
    
             $start = time();
             $areaNextMonths = (is_null($area->advance_months))?0:$area->advance_months;             
             $end = strtotime('+'.$areaNextMonths. 'months'); 
              for($currentDay = $start;$currentDay < $end;){
                $d = date('w',$currentDay);
                if(in_array($d,$areaDaysOff)){
                  if(!in_array(date('Y-m-d',$currentDay),$daysDisable)){
                    $daysDisable[] = date('Y-m-d',$currentDay); 
                  }
                  
                }
                $currentDay = strtotime('+1 day',$currentDay); 
             }
             sort($daysDisable,SORT_REGULAR); 
             $resp['disabledays'] = [
                'area' => $area->id,
                'days' => $daysDisable,
                ];              
        }else {
            $resp['error'] = 'Area lazer não existe'; 
            return $resp; 
        } 
         return $resp; 
    }



    public function getTimes($areaId, Request $request)
    {
        $resp= ['error' => '', 'list' => []];
        $daysWeek = ['Domingo','Segunda', 'Terca','Quarta','Quinta','Sexta','Sabado'];           
        $user = Auth()->user(); 
        $yesterday = strtotime('-1 day'); 
        $yesterday = date('Y-m-d',$yesterday); 

        $area = Area::where('id',$areaId)
                    ->where('company_id',$user->id)
                    ->first();
        if(!isset($area->id)){
            $resp['error'] = 'Area não existe'; 
            return $resp; 
        }


        $request->request->add(['area_id' => $areaId]);
        $validator = Validator::make($request->all(),[
            'area_id' => 'required|exists:areas,id',
            'reservation_date' => 'required:format_date:Y-m-d|after:yesterday', 
        ]);
        if ($validator->fails()){
            $resp['error'] = $validator->errors(); 
        }else {
            $reservationDate = $request->input('reservation_date'); 
            $weekDay = date('w',strtotime($reservationDate));
            $weekDays = explode(',',$area->days);
             if(in_array($weekDay,$weekDays)){
                $hasReservation = Reservation::where('area_id',$areaId)
                                              ->where('company_id',$user->id)
                                              ->where('reservation_date',$reservationDate)
                                              ->whereNull('end_time')
                                              ->count(); 
                $hasDisableDays = AreaDisableDay::where('area_id',$areaId)
                                              ->where('company_id',$user->company_id)
                                              ->where('day',$reservationDate)
                                              ->count(); 
                if ($hasReservation || $hasDisableDays) {
                    $resp['error'] = 'Não existe reserva disponivel para esta area'; 
                    return $resp; 
                }else {
                    $daysDisable = explode(',',$area->days);
                    $startTime = strtotime($area->start_time);
                    $endTime = strtotime($area->end_time);
                    $times = []; 
                    
                    $daysWeekGroup = []; 
                   
                    foreach($daysDisable as $dayDisable){
                        $daysWeekGroup[] = $daysWeek[$dayDisable];  
                    }


                    for($time = $startTime;$time < $endTime;
                        $time = strtotime('+1 hours',$time))
                    {
                        $times[] = $time; 
                    }

                    $timeList = []; 
                    foreach($times as $time){
                        $interval =  date('H:i',$time).' - ';
                        $interval .=  (strtotime('+1 hour',$time) > $endTime) ?
                                    date('H:i',$endTime):
                                    date('H:i',strtotime('+1 hour',$time)); 
                        $timeList[] = [
                                        'id' => date('H:i:s',$time),                             
                                        'interval' => $interval, 
                        ];
                    }
                    $resp['list']['daysweek'] =   $daysWeekGroup; 
                    $resp['list']['daysweek'][] = $timeList; 
                    return $resp;
                } 
             }else {
                 $resp['error'] = 'Data não disponivel para alocação'; 
                 return $resp; 
             }            
        }
        return $resp; 
    }

    public function getMyReservations() 
    {
        $resp = ['error' => ''];
        $user = Auth()->user(); 
       
      
          $reservations = Reservation::with('area:id,title,cover')                                      
                                      ->where('company_id',$user->company_id)
                                      ->where('user_id',$user->id)
                                      ->orderBy('reservation_date')                                      
                                      ->get(['area_id','title','start_time','end_time']);
        
         $resp['reservations'] = $reservations; 

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
            return $message;
        }

        $areaPreviousHours = (is_null($areaPreviousHours)? 0 : $areaPreviousHours); 
        $message = $reservationStart.'/'.$areaEndTime; 
        $areaEndTime =   strtotime('-'.intval($areaPreviousHours).' hours',$areaEndTime);
        if ($reservationStart < $areaStartTime || $reservationStart >= $areaEndTime){
            $message= 'Horário reserva, esta fora do limite de reserva para este dia'; 
            return $message; 
        }                                          

    }

}
