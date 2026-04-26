# CartController Fix - approval_code NULL Bug

## Problem
In `get_album_images()` function (line 165-167):
```php
$access_code = Auth::user()->approval_code;
$all_users_data = DB::table('users')->where(['approval_code'=>$access_code])->get();
```

When `$access_code` is NULL, this returns ALL users with NULL approval_code (67 garbage/bot users).

## Solution

Replace lines 165-167 with:

```php
$access_code = Auth::user()->approval_code;
$user_id = Auth::id();

// FIX: Handle NULL approval_code - solo users should only see themselves
if (empty($access_code)) {
    // User has no approval_code - show only their own folders
    $all_users_data = DB::table('users')
        ->where('id', $user_id)
        ->whereNotNull('approval_code')
        ->orWhere(function($query) use ($user_id) {
            $query->where('id', $user_id)
                  ->whereNull('approval_code');
        })
        ->get();
} else {
    // User has valid approval_code - show users with same code
    $all_users_data = DB::table('users')
        ->where('approval_code', $access_code)
        ->whereNotNull('approval_code')
        ->get();
}
```

## Alternative Simple Fix

Just add `whereNotNull` to exclude NULL approval_codes:

```php
$access_code = Auth::user()->approval_code;
$user_id = Auth::id();

$all_users_data = DB::table('users')
    ->where('approval_code', $access_code)
    ->whereNotNull('approval_code')  // FIX: exclude garbage users
    ->get();
```

## Files to Modify
- `domains/apertureleadership.com/public_html/stg/application/app/Http/Controllers/CartController.php`
- Line 167

## Testing
1. Log in as Rakesh (approval_code: hzYhR8)
2. Navigate to /account/folders
3. Should show only Rakesh's folders, not 67 garbage users