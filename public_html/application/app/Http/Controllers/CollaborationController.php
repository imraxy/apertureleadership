<?php

namespace App\Http\Controllers;

use App\Services\CollaborationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollaborationController extends Controller
{
    protected $collaboration;

    public function __construct(CollaborationService $collaboration)
    {
        $this->middleware('auth');
        $this->collaboration = $collaboration;
    }

    public function createSession(Request $request)
    {
        $session = $this->collaboration->createSession(
            Auth::user(),
            $request->input('name')
        );

        return response()->json([
            'success' => true,
            'session' => [
                'id' => $session->id,
                'name' => $session->name,
            ],
        ]);
    }

    public function searchUsers(Request $request)
    {
        $users = $this->collaboration->searchUsers(
            Auth::user(),
            $request->get('q', ''),
            $request->get('session_id')
        );

        return response()->json([
            'users' => $users->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                ];
            }),
        ]);
    }

    public function sendInvite(Request $request)
    {
        $request->validate([
            'invitee_id' => 'required|integer',
            'session_id' => 'nullable|integer',
            'create_new' => 'nullable|boolean',
        ]);

        try {
            $invite = $this->collaboration->sendInvite(
                Auth::user(),
                $request->invitee_id,
                $request->input('session_id'),
                $request->boolean('create_new')
            );

            return response()->json([
                'success' => true,
                'message' => 'Invite sent.',
                'invite_id' => $invite->id,
                'session_id' => $invite->session_id,
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function acceptInvite(Request $request, $inviteId)
    {
        try {
            $this->collaboration->acceptInvite(Auth::user(), $inviteId);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Invite accepted.']);
            }

            return redirect()->route('account.folders')->with('success', 'Invite accepted.');
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    public function declineInvite($inviteId)
    {
        try {
            $this->collaboration->declineInvite(Auth::user(), $inviteId);

            return response()->json(['success' => true, 'message' => 'Invite declined.']);
        } catch (\Exception $e) {
            return $this->errorResponse('Invite not found.', 404);
        }
    }

    public function cancelInvite($inviteId)
    {
        try {
            $this->collaboration->cancelInvite(Auth::user(), $inviteId);

            return response()->json(['success' => true, 'message' => 'Invite cancelled.']);
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    public function removeMember(Request $request, $userId)
    {
        $request->validate(['session_id' => 'required|integer']);

        try {
            $this->collaboration->removeMember(Auth::user(), $userId, $request->session_id);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Member removed.']);
            }

            return redirect()->route('account.folders')->with('success', 'Member removed from session.');
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    public function leaveSession(Request $request)
    {
        $request->validate(['session_id' => 'required|integer']);

        try {
            $this->collaboration->leaveSession(Auth::user(), $request->session_id);

            return redirect()->route('account.folders')->with('success', 'You left the session.');
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    protected function errorResponse($message, $code = 422)
    {
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => false, 'message' => $message], $code);
        }

        return redirect()->route('account.folders')->with('error', $message);
    }
}
