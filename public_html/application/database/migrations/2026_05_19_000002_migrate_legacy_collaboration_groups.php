<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\User;
use App\Models\CollaborationSession;
use App\Models\CollaborationSessionMember;

class MigrateLegacyCollaborationGroups extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('collaboration_sessions')) {
            return;
        }

        $codes = User::whereNotNull('approval_code')
            ->where('approval_code', '!=', '')
            ->distinct()
            ->pluck('approval_code');

        foreach ($codes as $code) {
            $code = trim((string) $code);
            if ($code === '') {
                continue;
            }

            if (CollaborationSession::where('legacy_access_code', $code)->exists()) {
                continue;
            }

            $userIds = User::where('approval_code', $code)->pluck('id');
            if ($userIds->isEmpty()) {
                continue;
            }

            $session = CollaborationSession::create([
                'name' => 'Group ' . $code,
                'chat_key' => $code,
                'created_by' => $userIds->first(),
                'legacy_access_code' => $code,
            ]);

            $first = true;
            foreach ($userIds as $uid) {
                CollaborationSessionMember::firstOrCreate(
                    ['session_id' => $session->id, 'user_id' => $uid],
                    ['role' => $first ? 'owner' : 'member', 'joined_at' => now()]
                );
                $first = false;
            }
        }
    }

    public function down()
    {
        // Non-destructive: legacy rows remain; sessions can be cleared manually if needed.
    }
}
