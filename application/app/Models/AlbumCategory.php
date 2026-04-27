<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlbumCategory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'album_categories'; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'is_order'
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
     * Serarch by name
     */
    public function scopeFilter($query, $searchValue)
    {

        if(!empty($searchValue)) {
           return $query->where('name','LIKE',"%{$searchValue}%");
        }
        
    }
	
	/**
     * Get the category album sessions.
     */
    public function albumsessions()
    {
        return $this->hasMany(AlbumSession::class);
    }
}
