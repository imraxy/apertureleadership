<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posts'; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'blog_category_id', 'title', 'permalink', 'description', 'meta_keywords', 'meta_description', 'featured_image', 'no_of_views', 'slug'
    ];


    /**
     * Get the post category.
     */
    public function blogCategory()
    {
        return $this->belongsTo(BlogCategory::class);
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
