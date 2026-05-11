<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'user_name', 'email', 'email_verified_at', 'password' , 'phone_no','approval_code_time','approval_code_create_time','approval_code','approval_code_end_time'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Scope a query to only include set default order.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('id', 'desc')->get();
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
     * Get the user chats.
     */
    public function chat_messages()
    {
        return $this->hasMany('App\Models\ChatMessage', 'user_id');
    }

    /**
     *
     * Serarch by user name & email
     */
    public function scopeFilter($query, $searchValue)
    {

        /*if(isset($params['user_name'])) {
            $query->where('user_name','LIKE',"%{$params['user_name']}%");
        }*/
        if(!empty($searchValue)) {
            $query->where(function ($query) use ($searchValue) {
                 $query->where('id','LIKE',"%{$searchValue}%")
                 ->orWhere('user_name','LIKE',"%{$searchValue}%")
                 ->orWhere('email','LIKE',"%{$searchValue}%");
            });
        }
    }
    
    public function get_images()
    {
        return $this->hasMany('App\Models\Cart','user_id');
    }
}
