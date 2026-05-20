<?php

namespace App\Console\Commands;

use App\User;
use App\Models\CollaborationSession;
use App\Models\CollaborationSessionMember;
use App\Services\CollaborationService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SeedTestCollaboration extends Command
{
    protected $signature = 'collaboration:seed-test {--emails=imraxy@gmail.com,kuntalemail@gmail.com}';

    protected $description = 'Create one test collaboration session with the given user emails';

    public function handle(CollaborationService $collaboration)
    {
        $emails = array_filter(array_map('trim', explode(',', $this->option('emails'))));
        $users = User::whereIn('email', $emails)->get();

        if ($users->count() < 2) {
            $this->error('Need at least 2 users. Found: ' . $users->pluck('email')->implode(', '));
            return 1;
        }

        $session = CollaborationSession::create([
            'name' => 'Test session',
            'chat_key' => strtoupper(Str::random(8)),
            'created_by' => $users->first()->id,
        ]);

        $first = true;
        foreach ($users as $user) {
            CollaborationSessionMember::create([
                'session_id' => $session->id,
                'user_id' => $user->id,
                'role' => $first ? 'owner' : 'member',
                'joined_at' => now(),
            ]);
            $first = false;
            $this->info('Added: ' . $user->email);
        }

        $this->info('Session id=' . $session->id . ' chat_key=' . $session->chat_key);

        return 0;
    }
}
