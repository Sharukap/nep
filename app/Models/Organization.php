<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $table = 'organizations';
    public $timestamps = true;


    protected $fillable = [
        'title',

    ];


    // A user belongs to one organization and an organization has many users.
    /* public function organizations(){
         return $this->hasMany('App\Models\Organizations');
     }*/


    public function contacts()
    {
        return $this->hasOne('App\Models\Contact');
    }


    // public function type()
    // {
    //     return $this->hasOne('App\Models\Type');
    // }

    public function type()
    {
        return $this->belongsTo('App\Models\Type');
    }

    public function branch_type()
    {
        return $this->belongsTo('App\Models\Branch_Type');
    }
    

    // A user belongs to one organization and an organization has many users.
    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

    public function environment_restorations()
    {
        return $this->hasMany('App\Models\Environment_Restoration');
    }

    public function land_parcels()
    {
        return $this->belongsToMany('App\Models\Land_parcel','land_has_organizations','organization_id','land_parcel_id');
    }
}
