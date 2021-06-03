<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Land_Has_Organization extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'land_has_organizations';

    protected $fillable = [
        'organization_id',
        'land_parcel_id',
        'status'
    ];

    protected $attributes = [
        
    ];

    public function organization()
    {
        return $this->belongsTo('App\Models\Organization');
    }
}
