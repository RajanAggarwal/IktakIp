<?php

namespace App;
 
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{ 

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','latitude','longitude','location_name', 'location_address', 'employer_id',
    ];
 
    protected $table = 'employer_locations';
}
