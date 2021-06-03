<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tree_Removal_Request extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'tree_removal_requests';

    protected $fillable = [
        'created_by_user_id',
        'description',
        'land_size',
        'land_size_unit',
        'no_of_trees',
        'no_of_tree_species',
        'no_of_mammal_species',
        'no_of_amphibian_species',
        'no_of_reptile_species',
        'no_of_avian_species',
        'no_of_flora_species',
        'species_special_notes',
        'status_id',
        'land_parcel_id',
        'governing_organizations',
        'images',
        'special_approval',
        'tree_locations',
    ];

    protected $casts = [
        'tree_locations' => 'array',
        'special_approval' => 'array',
        'governing_organizations' => 'array',
    ];

    protected $attributes = [
        'images' => '{}',
        'special_approval' => 0,
        'tree_locations' => 0,
        'status_id' => 1,
        'land_size_unit' => "acres",
        'land_size' => 0,
        'no_of_tree_species' => 0,
        'no_of_mammal_species' => 0,
        'no_of_amphibian_species' => 0,
        'no_of_reptile_species' => 0,
        'no_of_avian_species' => 0,
        'no_of_flora_species' => 0,
        'species_special_notes' => 0,
    ];


    public function status()
    {
        return $this->belongsTo('App\Models\Status');
    }

    public function land_parcel()
    {
        return $this->belongsTo('App\Models\Land_Parcel');
    }
}
