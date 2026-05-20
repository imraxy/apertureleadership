<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class CollaborationSessionMember extends Model
{
    protected $fillable = ['session_id', 'user_id', 'role', 'joined_at'];

    public function session()
    {
        return $this->belongsTo(CollaborationSession::class, 'session_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
