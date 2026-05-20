<?php

namespace App\Services;

use App\User;
use App\Models\CollaborationSession;
use App\Models\CollaborationSessionMember;
use App\Models\CollaborationInvite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CollaborationService
{
    public function syncLegacyForUser(User $user)
    {
        $code = trim((string) $user->approval_code);
        if ($code === '') {
            return;
        }

        $session = CollaborationSession::where('legacy_access_code', $code)->first();

        if (!$session) {
            $session = CollaborationSession::create([
                'name' => 'Group ' . $code,
                'chat_key' => $code,
                'created_by' => $user->id,
                'legacy_access_code' => $code,
            ]);

            $legacyUsers = User::where('approval_code', $code)->pluck('id');
            foreach ($legacyUsers as $uid) {
                $this->addMember($session, (int) $uid, (int) $uid === (int) $session->created_by ? 'owner' : 'member');
            }
        } else {
            $this->addMember($session, $user->id, 'member');
        }
    }

    public function getSessionsForUser(User $user)
    {
        $this->syncLegacyForUser($user);

        $sessionIds = CollaborationSessionMember::where('user_id', $user->id)
            ->pluck('session_id');

        return CollaborationSession::whereIn('id', $sessionIds)
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    public function getSessionForUser(User $user, $sessionId = null)
    {
        $sessions = $this->getSessionsForUser($user);

        if ($sessionId) {
            return $sessions->firstWhere('id', (int) $sessionId);
        }

        return $sessions->first();
    }

    public function getChatKeysForUser(User $user)
    {
        return $this->getActiveChatKeysForUser($user);
    }

    /**
     * Chat keys for sessions with at least two members (chat is meaningful).
     */
    public function getActiveChatKeysForUser(User $user)
    {
        $sessionIds = CollaborationSessionMember::where('user_id', $user->id)->pluck('session_id');

        $activeIds = [];
        foreach ($sessionIds as $sessionId) {
            if (CollaborationSessionMember::where('session_id', $sessionId)->count() > 1) {
                $activeIds[] = $sessionId;
            }
        }

        if (!empty($activeIds)) {
            return CollaborationSession::whereIn('id', $activeIds)
                ->pluck('chat_key')
                ->filter()
                ->unique()
                ->values()
                ->all();
        }

        $code = trim((string) $user->approval_code);

        return $code !== '' ? [$code] : [];
    }

    public function memberUserIds(User $user)
    {
        $sessionIds = CollaborationSessionMember::where('user_id', $user->id)->pluck('session_id');

        if ($sessionIds->isEmpty()) {
            return [$user->id];
        }

        $ids = CollaborationSessionMember::whereIn('session_id', $sessionIds)
            ->pluck('user_id')
            ->push($user->id)
            ->unique()
            ->values()
            ->all();

        return $ids;
    }

    public function chatEnabledForUser(User $user)
    {
        $sessionIds = CollaborationSessionMember::where('user_id', $user->id)->pluck('session_id');

        foreach ($sessionIds as $sessionId) {
            if (CollaborationSessionMember::where('session_id', $sessionId)->count() > 1) {
                return true;
            }
        }

        return false;
    }

    public function getPanelData(User $user)
    {
        $sessions = $this->getSessionsForUser($user);

        $sessionBlocks = [];
        foreach ($sessions as $session) {
            $sessionBlocks[] = [
                'session' => $session,
                'members' => CollaborationSessionMember::with('user')
                    ->where('session_id', $session->id)
                    ->get(),
                'sentInvites' => CollaborationInvite::with(['invitee'])
                    ->where('session_id', $session->id)
                    ->where('status', 'pending')
                    ->where('inviter_id', $user->id)
                    ->get(),
            ];
        }

        $receivedInvites = CollaborationInvite::with(['inviter', 'session'])
            ->where('invitee_id', $user->id)
            ->where('status', 'pending')
            ->get();

        return compact('sessionBlocks', 'receivedInvites', 'sessions');
    }

    public function createSession(User $user, $name = null)
    {
        $session = CollaborationSession::create([
            'name' => $name ?: ($user->name . "'s session"),
            'chat_key' => $this->generateChatKey(),
            'created_by' => $user->id,
        ]);

        $this->addMember($session, $user->id, 'owner');

        return $session;
    }

    public function searchUsers(User $user, $query, $sessionId = null)
    {
        $query = trim((string) $query);
        if (strlen($query) < 2) {
            return collect();
        }

        $excludeIds = [$user->id];

        if ($sessionId) {
            $session = $this->getSessionForUser($user, $sessionId);
            if ($session) {
                $excludeIds = array_merge(
                    $excludeIds,
                    CollaborationSessionMember::where('session_id', $session->id)->pluck('user_id')->all()
                );
                $excludeIds = array_merge(
                    $excludeIds,
                    CollaborationInvite::where('session_id', $session->id)
                        ->where('status', 'pending')
                        ->pluck('invitee_id')
                        ->all()
                );
            }
        }

        return User::whereNotIn('id', array_unique($excludeIds))
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', '%' . $query . '%')
                    ->orWhere('email', 'LIKE', '%' . $query . '%')
                    ->orWhere('user_name', 'LIKE', '%' . $query . '%');
            })
            ->orderBy('name')
            ->limit(15)
            ->get(['id', 'name', 'email', 'user_name']);
    }

    public function sendInvite(User $inviter, $inviteeId, $sessionId = null, $createNew = false)
    {
        $inviteeId = (int) $inviteeId;

        if ($inviteeId === (int) $inviter->id) {
            throw new \InvalidArgumentException('You cannot invite yourself.');
        }

        if (!User::where('id', $inviteeId)->exists()) {
            throw new \InvalidArgumentException('User not found.');
        }

        if ($createNew) {
            $session = $this->createSession($inviter);
        } elseif ($sessionId) {
            $session = $this->getSessionForUser($inviter, $sessionId);
            if (!$session) {
                throw new \InvalidArgumentException('Session not found or you are not a member.');
            }
        } else {
            $session = $this->getSessionForUser($inviter) ?: $this->createSession($inviter);
        }

        if (CollaborationSessionMember::where('session_id', $session->id)->where('user_id', $inviteeId)->exists()) {
            throw new \InvalidArgumentException('That user is already in this session.');
        }

        $pending = CollaborationInvite::where('session_id', $session->id)
            ->where('invitee_id', $inviteeId)
            ->where('status', 'pending')
            ->first();

        if ($pending) {
            throw new \InvalidArgumentException('An invite is already pending for that user in this session.');
        }

        return CollaborationInvite::create([
            'session_id' => $session->id,
            'inviter_id' => $inviter->id,
            'invitee_id' => $inviteeId,
            'status' => 'pending',
            'token' => Str::random(40),
        ]);
    }

    public function acceptInvite(User $user, $inviteId)
    {
        $invite = CollaborationInvite::where('id', $inviteId)
            ->where('invitee_id', $user->id)
            ->where('status', 'pending')
            ->firstOrFail();

        if (CollaborationSessionMember::where('session_id', $invite->session_id)->where('user_id', $user->id)->exists()) {
            $invite->update(['status' => 'accepted']);
            return $invite->session()->first();
        }

        DB::transaction(function () use ($invite, $user) {
            $this->addMember($invite->session, $user->id, 'member');
            $invite->update(['status' => 'accepted']);
        });

        return $invite->session()->first();
    }

    public function declineInvite(User $user, $inviteId)
    {
        $invite = CollaborationInvite::where('id', $inviteId)
            ->where('invitee_id', $user->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $invite->update(['status' => 'declined']);

        return $invite;
    }

    public function cancelInvite(User $user, $inviteId)
    {
        $invite = CollaborationInvite::where('id', $inviteId)
            ->where('inviter_id', $user->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $invite->update(['status' => 'cancelled']);

        return $invite;
    }

    public function removeMember(User $actor, $memberUserId, $sessionId)
    {
        $memberUserId = (int) $memberUserId;
        $session = $this->getSessionForUser($actor, $sessionId);

        if (!$session) {
            throw new \InvalidArgumentException('Session not found or you are not a member.');
        }

        $target = CollaborationSessionMember::where('session_id', $session->id)
            ->where('user_id', $memberUserId)
            ->first();

        if (!$target) {
            throw new \InvalidArgumentException('That user is not in this session.');
        }

        $actorMember = CollaborationSessionMember::where('session_id', $session->id)
            ->where('user_id', $actor->id)
            ->first();

        if (!$actorMember) {
            throw new \InvalidArgumentException('You are not in this session.');
        }

        if ($memberUserId !== (int) $actor->id && $target->role === 'owner') {
            throw new \InvalidArgumentException('The session owner cannot be removed by others.');
        }

        DB::transaction(function () use ($target, $session, $memberUserId) {
            $target->delete();

            CollaborationInvite::where('session_id', $session->id)
                ->where('invitee_id', $memberUserId)
                ->where('status', 'pending')
                ->update(['status' => 'cancelled']);

            $remaining = CollaborationSessionMember::where('session_id', $session->id)->count();

            if ($remaining === 0) {
                CollaborationInvite::where('session_id', $session->id)->delete();
                $session->delete();
            } else {
                $hasOwner = CollaborationSessionMember::where('session_id', $session->id)
                    ->where('role', 'owner')
                    ->exists();

                if (!$hasOwner) {
                    $next = CollaborationSessionMember::where('session_id', $session->id)
                        ->orderBy('joined_at')
                        ->first();

                    if ($next) {
                        $next->update(['role' => 'owner']);
                    }
                }
            }
        });
    }

    public function leaveSession(User $user, $sessionId)
    {
        $this->removeMember($user, $user->id, $sessionId);
    }

    protected function addMember(CollaborationSession $session, $userId, $role = 'member')
    {
        return CollaborationSessionMember::firstOrCreate(
            ['session_id' => $session->id, 'user_id' => $userId],
            ['role' => $role, 'joined_at' => now()]
        );
    }

    protected function generateChatKey()
    {
        do {
            $key = strtoupper(Str::random(8));
        } while (
            CollaborationSession::where('chat_key', $key)->exists()
            || User::where('approval_code', $key)->exists()
        );

        return $key;
    }
}
