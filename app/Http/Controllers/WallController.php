<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Wall; 
use App\Models\Walllike; 

class WallController extends Controller
{
    public function getAll() 
    {
        $resp = ['error' => '',  'list' => []];
        $user = Auth()->user(); 
        $walls = Wall::where('company_id',$user->company_id)
                      ->get(); 
        foreach ($walls as $key => $wallValue) {
            $walls[$key]['likes'] = 0;
            $walls[$key]['liked'] = false; 
            $walls[$key]['likes'] = WallLike::where('company_id',$wallValue->company_id)
                                            ->where('wall_id',$wallValue->id)
                                            ->count();
             $countLikes = WallLike::where('company_id',$wallValue->company_id)
                                            ->where('wall_id',$wallValue->id)
                                            ->where('user_id',$user->id)
                                            ->count();
             if ($countLikes > 0) {
                 $walls[$key]['liked'] = true; 
             }

        }
        $resp['list'] = $walls; 
        
        
        return $resp; 
    }
}
