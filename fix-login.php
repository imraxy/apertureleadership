<?php
$file = __DIR__ . '/app/Http/Controllers/Auth/LoginController.php';
$content = file_get_contents($file);

// Fix redirectTo() to always go to gallery (front.albums)
$oldLogic = '// Check if user has approval_code (group user) or not (solo user)
        $user = Auth::user();
        if($user && !empty($user->approval_code)) {
            // Group user - go to folders/chat
            return redirect(route(\'account.folders\'));
        } else {
            // Solo user - go straight to albums/gallery
            return redirect(route(\'front.albums\'));
        }';

$newLogic = '// Always redirect to gallery after login (solo and group users)
        // Group users can access folders via navigation
        return redirect(route(\'front.albums\'));';

$content = str_replace($oldLogic, $newLogic, $content);

// Also add session clearing on login
$loginMethod = 'public function login(Request $request)
    {';
$newLoginMethod = 'public function login(Request $request)
    {
        // Clear any previous session data (folders, cart, etc.)
        session()->forget(\'cart\');
        session()->forget(\'folder\');
        session()->forget(\'selected_photos\');';
        
$content = str_replace($loginMethod, $newLoginMethod, $content);

file_put_contents($file, $content);
echo Fixed LoginController: always redirect to gallery, clear session on loginn;
