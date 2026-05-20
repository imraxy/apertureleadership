<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class CollaborationInvite extends Model
{
    protected $fillable = ['session_id', 'inviter_id', 'invitee_id', 'status', 'token'];

    public function session()
    {
        return $this->belongsTo(CollaborationSession::class, 'session_id');
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function invitee()
    {
        return $this->belongsTo(User::class, 'invitee_id');
    }
}
