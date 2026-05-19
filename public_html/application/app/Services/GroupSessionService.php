<?php

namespace App\Services;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class GroupSessionService
{
    /** Default group session length when auto-created (days). */
    const SESSION_DAYS = 30;

    public function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (User::where('approval_code', $code)->exists());

        return $code;
    }

    /**
     * @throws ValidationException
     */
    public function assignToUser(User $user, ?string $joinCode = null): string
    {
        if (!empty($joinCode)) {
            return $this->joinExistingGroup($user, strtoupper(trim($joinCode)));
        }

        return $this->createNewGroup($user);
    }

    protected function createNewGroup(User $user): string
    {
        $code = $this->generateUniqueCode();
        $start = Carbon::now();
        $end = $start->copy()->addDays(self::SESSION_DAYS);
        $minutes = $start->diffInMinutes($end);

        $user->forceFill([
            'approval_code' => $code,
            'approval_code_create_time' => $start->toDateTimeString(),
            'approval_code_end_time' => $end->toDateTimeString(),
            'approval_code_time' => $minutes,
        ])->save();

        return $code;
    }

    /**
     * @throws ValidationException
     */
    protected function joinExistingGroup(User $user, string $code): string
    {
        $member = User::where('approval_code', $code)
            ->whereNotNull('approval_code_end_time')
            ->orderBy('id')
            ->first();

        if (!$member || Carbon::parse($member->approval_code_end_time)->isPast()) {
            throw ValidationException::withMessages([
                'group_code' => ['This group access code is invalid or has expired.'],
            ]);
        }

        $user->forceFill([
            'approval_code' => $member->approval_code,
            'approval_code_create_time' => $member->approval_code_create_time,
            'approval_code_end_time' => $member->approval_code_end_time,
            'approval_code_time' => $member->approval_code_time,
        ])->save();

        return $code;
    }
}
