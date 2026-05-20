<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class CollaborationSession extends Model
{
    protected $fillable = ['name', 'chat_key', 'created_by', 'legacy_access_code'];

    public function members()
    {
        return $this->hasMany(CollaborationSessionMember::class, 'session_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'collaboration_session_members', 'session_id', 'user_id')
            ->withPivot('role', 'joined_at');
    }

    public function invites()
    {
        return $this->hasMany(CollaborationInvite::class, 'session_id');
    }
}
