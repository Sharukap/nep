<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Development_Project extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'development_projects';

    protected $fillable = [
        'title',
        'gazette_id',
        'governing_organizations',
        'logs',
        'land_parcel_id',
        'protected_area',
        'created_by_user_id',
        'status_id',
    ];

    protected $attributes = [
        'logs' => 0,
        'protected_area' => 0,
        'status_id' => 1,
    ];

    protected $casts = [
        'governing_organizations' => 'array',
        'logs' => 'array',
    ];

    public function gazette()
    {
        return $this->belongsTo('App\Models\Gazette');
    }

    public function land_parcel()
    {
        return $this->belongsTo('App\Models\Land_Parcel');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Status');
    }

}
