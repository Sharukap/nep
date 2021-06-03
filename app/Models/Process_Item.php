<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Process_Item extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table = 'process_items';

    protected $fillable = [
        'form_type_id',
        'form_id',
        'request_organization',
        'activity_organization',
        'activity_user_id',
        'remark',
        'prerequisite',
        'prerequisite_id',
        'created_by_user_id',
        'status_id',
        'other_land_owner_type',
        'other_land_owner_name',
        'other_removal_requestor_type',
        'other_removal_requestor_name',
        'requestor_email'
    ];

    protected $attributes = [
        'prerequisite' => 0,
        'remark' => 0,
        'status_id' => 1,
        'other_land_owner_type' => 0,
        'other_land_owner_name' => '',
        'other_removal_requestor_type' => 0,
        'other_removal_requestor_name' => '',
        'requestor_email' => "no email",
    ];

    public function form_type()
    {
        return $this->belongsTo('App\Models\Form_Type');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Status');
    }

    public function prerequisite_process()
    {
        return $this->belongsTo('App\Models\Process_Item','prerequisite_id');
    }

    public function Activity_organization()
    {
        return $this->belongsTo('App\Models\Organization','activity_organization');
    }

    public function activity_user()
    {
        return $this->belongsTo('App\Models\User','activity_user_id');
    }

    public function requesting_organization()
    {
        return $this->belongsTo('App\Models\Organization','request_organization');
    }

    public function created_by_user()
    {
        return $this->belongsTo('App\Models\User','created_by_user_id');
    }
}