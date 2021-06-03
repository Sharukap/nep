<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization_Activity extends Model
{
    use HasFactory;

    

    protected $table = 'organization_activity';

    public function form_type()
    {
        return $this->belongsTo('App\Models\Form_Type');
    }

    public function organization()
    {
        return $this->belongsTo('App\Models\Organization');
    }

    public function district()
    {
        return $this->belongsTo('App\Models\District');
    }

    public function province()
    {
        return $this->belongsTo('App\Models\Province');
    }
}
