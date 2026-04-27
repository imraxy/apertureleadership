<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AlbumSession extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'album_sessions'; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'album_category_id', 'title', 'description', 'permalink', 'video_link', 'meta_keywords', 'meta_description', 'featured_image', 'gallery_images', 'no_of_views', 'status', 'slug'
    ];

    /**
     * Get the user chats.
     */
    public function sessionimages()
    {
        if (Auth::check()) {
            // The user is logged in...
            return $this->hasMany(SessionImage::class, 'album_session_id');
        }

        return $this->hasMany(SessionImage::class, 'album_session_id')->take(10);
    }

    /**
     * Get the AlbumCategory.
     */
    public function albumCategory()
    {
        return $this->belongsTo(AlbumCategory::class);
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
