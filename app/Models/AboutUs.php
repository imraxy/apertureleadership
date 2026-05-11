<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    /**
     * @var string
     */
    protected $table = 'about_us';

    /**
     * @var array
     */
    protected $fillable = ['name', 'content', 'image', 'no_of_clients', 'no_of_meetings', 'no_of_sessions', 'established_year'];


    public function aboutmeDetail()
    {
    	return self::first(['id', 'name', 'content', 'image', 'no_of_clients', 'no_of_meetings', 'no_of_sessions', 'established_year']);
    }
}
