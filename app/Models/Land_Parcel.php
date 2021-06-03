<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Land_Parcel extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'land_parcels';

    protected $fillable = [
        'title',    //plan number
        'surveyor_name',
        'governing_organizations',
        'logs',
        'polygon',
        'protected_area',
        'created_by_user_id',
        'status',
        'size',
        'size_unit',
    ];

    protected $attributes = [
        'logs' => 0,
        'protected_area' => 0,
        'surveyor_name' =>'not available',
        'size_unit' => "acres",
    ];

    protected $casts = [
        'governing_organizations' => 'array',
        'logs' => 'array',
        'polygon' => 'array',
    ];

    public function development_projects()
    {
        return $this->hasMany('App\Models\Development_Project');
    }

    public function tree_removal_requests()
    {
        return $this->hasMany('App\Models\Tree_Removal_Request');
    }

    public function environment_restorations()
    {
        return $this->hasMany('App\Models\EnvironmentRestoration');
    }

    public function organizations()
    {
        return $this->belongsToMany('App\Models\Organization', 'land_has_organizations', 'land_parcel_id', 'organization_id');
    }

    public function gazettes()
    {
        return $this->belongsToMany(Gazette::class, 'Land_Has_Gazette');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Status');
    }

    public function crime_reports()
    {
        return $this->hasMany('App\Models\Crime_report');
    }

    public function gs_division()
    {
        return $this->belongsTo('App\Models\GS_Division');
    }

    public function province()
    {
        return $this->belongsTo('App\Models\Province');
    }

    public function district()
    {
        return $this->belongsTo('App\Models\District');
    }
}
