<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * @var string
     */
    protected $table = 'payments';

    /**
     * @var array
     */
    protected $fillable = ['reference_number', 'transaction_id', 'amount', 'payment_status', 'user_id', 'session_image_id', 'cart_id'];
}
