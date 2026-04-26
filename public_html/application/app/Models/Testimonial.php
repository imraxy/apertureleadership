<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'testimonials'; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'message', 'image'];

    /**
     * Scope a query to only include set by give value & order.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderebycoloumn($query, $cl_name, $ortype)
    {
        return $query->orderBy($cl_name, $ortype);
    }

    /**
     *
     * Serarch by name
     */
    public function scopeFilter($query, $searchValue)
    {

        if(!empty($searchValue)) {
            return $query->where('name','LIKE',"%{$searchValue}%");
        }
        
    }
}
