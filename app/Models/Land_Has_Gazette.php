<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Land_Has_Gazette extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'land_has_gazettes';

    protected $fillable = [
        'gazette_id',
        'land_parcel_id',
        'status'
    ];

    public function gazette()
    {
        return $this->belongsTo('App\Models\Gazette');
    }
}
