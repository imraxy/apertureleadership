<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionImage extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'session_images'; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'album_category_id', 'session_image', 'thumbnail_image', 'title', 'description', 'slug','shape'
    ];


    public function imageFindById($id)
    {
        return  self::find($id);
    }

    /**
     * Get the AlbumCategory.
     */
    public function albumCategory()
    {
        return $this->belongsTo(AlbumCategory::class, 'album_category_id');
    }

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
           return $query->where('title','LIKE',"%{$searchValue}%");
        }
        
    }
}
