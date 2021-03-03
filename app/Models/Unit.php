<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    public function unittenants() {
        return $this->hasMany(UnitTenant::class,'unit_id'); 
    }

    public function vehicles() {
        return $this->hasMany(UnitVehicle::class); 
    }

    public function pets() {
        return $this->hasMany(UnitPet::class); 
    }


}
