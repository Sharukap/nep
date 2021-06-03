<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    protected $table = 'provinces';

    protected $fillable = [
        'province',
    ];

    public function land_parcels(){
        return $this->hasMany('App\Models\Land_Parcel');
    }
}
