<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'organization_contacts';
    public $timestamps = true;
    use SoftDeletes;
 
   
    protected $fillable = [
        'org_id'
  ];

  /**
   * The roles that belong to the User.
   */
  public function organizations_contact()
  {
    return $this->belongsTo('App\Models\Organization');
  }
}
