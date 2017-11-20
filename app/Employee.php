<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
	public function employer()
	{
		return $this->belongsTo("App\User", "employer_id");
	}

    public function current_location()
    {
    	return $this->hasOne("App\EmployeeCurrentLocation")->orderBy('id', 'DESC');
    }

    public function current_locations()
    {
    	return $this->hasMany("App\EmployeeCurrentLocation");
    }

    public function reports()
    {
        return $this->hasMany("App\Report");
    }
}
