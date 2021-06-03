<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GS_Division extends Model
{
    use HasFactory;
    protected $table = 'gs_divisions';

    public function land_parcels(){
        return $this->hasMany('App\Models\Land_Parcel');
    }
}
