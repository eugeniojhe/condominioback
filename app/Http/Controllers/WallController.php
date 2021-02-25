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

    public function like($id) 
    {
        $resp = ['error' => '']; 
        if (!isset($id)){
             $resp['error'] = 'Código do wall nao informado'; 
             return $resp; 
        } else {
            $user = Auth()->user();
            $countLikes = WallLike::where('company_id',$user->company_id)
                                ->where('wall_id',$id)
                                ->where('user_id',$user->id)
                                ->count();           ; 
            if ($countLikes > 0) {
                $resp['liked'] = false; 
                WallLike::where('company_id',$user->company_id)
                                ->where('wall_id',$id)
                                ->where('user_id',$user->id)
                                ->delete(); 
            } else {
                $resp['liked'] = true;
                $newWallLike = new WallLike();
                $newWallLike->company_id = $user->company_id;
                $newWallLike->user_id = $user->id; 
                $newWallLike->wall_id = $id; 
                $newWallLike->save(); 
            }            
        }  

        $countLikes = WallLike::where('company_id',$user->company_id)
                                ->where('wall_id',$id)
                                ->where('user_id',$user->id)
                                ->count();
        $resp['likes'] = $countLikes;
        return $resp; 
    }
}
