<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
   
   /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sliders'; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'image'
    ];

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
     * Serarch by title
     */
    public function scopeFilter($query, $searchValue)
    {

        if(!empty($searchValue)) {
            return $query->where('title','LIKE',"%{$searchValue}%");
        }
        
    }
}
