<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Environment_Restoration_Species extends Model
{
    use HasFactory;
    protected $table = "environment_restoration_species";
    protected $fillable = [
        'environment_restoration_activity_id',
        'species_id',
        'height',
        'dimensions',
        'quantity',
        'remarks',
        'created_by_user_id',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $attributes = [
        'created_by_user_id' => 0,
        'status' => 0,
    ];

    public function environment_restorations(){
        return $this->belongsTo('App\Models\Environment_Restoration');
    }

    public function Species(){
        return $this->belongsTo('App\Models\Species');
    }
}
