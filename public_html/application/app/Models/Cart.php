<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{

	/**
     * @var string
     */
    protected $table = 'carts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'session_image_id'];


    /**
     * Get the album session detail.
     */
    public function sessionimage()
    {
        return $this->belongsTo(SessionImage::class, 'session_image_id');
    }


    /**
     * Get the album user detail.
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
