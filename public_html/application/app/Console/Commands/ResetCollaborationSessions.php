<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetCollaborationSessions extends Command
{
    protected $signature = 'collaboration:reset {--clear-approval-codes : Also clear legacy approval_code fields on all users}';

    protected $description = 'Remove all collaboration sessions, members, invites (and optionally legacy approval codes)';

    public function handle()
    {
        if (!Schema::hasTable('collaboration_sessions')) {
            $this->warn('Collaboration tables do not exist.');
            return 0;
        }

        DB::table('collaboration_invites')->delete();
        DB::table('collaboration_session_members')->delete();
        DB::table('collaboration_sessions')->delete();

        $this->info('Cleared collaboration_invites, collaboration_session_members, collaboration_sessions.');

        if ($this->option('clear-approval-codes')) {
            DB::table('users')->update([
                'approval_code' => null,
                'approval_code_create_time' => null,
                'approval_code_end_time' => null,
                'approval_code_time' => null,
            ]);
            $this->info('Cleared legacy approval_code fields on all users.');
        }

        return 0;
    }
}
