<?php
$file = $argv[1] ?? __DIR__ . '/LoginController.php';
$content = file_get_contents($file);

// Fix 1: Replace redirectTo() function to always go to gallery
$oldRedirectTo = 'protected function redirectTo()
    {
        if(!empty(session()->get(\'url.intended\'))) {
                        
            return redirect(session()->get(\'url.intended\'));
            
        }
    
        // Check if user has approval_code (group user) or not (solo user)
        $user = Auth::user();
        if($user && !empty($user->approval_code)) {
            // Group user - go to folders/chat
            return redirect(route(\'account.folders\'));
        } else {
            // Solo user - go straight to albums/gallery
            return redirect(route(\'front.albums\'));
        }
    }';

$newRedirectTo = 'protected function redirectTo()
    {
        // Always redirect to gallery after login
        // Group users can access folders via navigation
        return redirect(route(\'front.albums\'));
    }';

$content = str_replace($oldRedirectTo, $newRedirectTo, $content);

// Fix 2: Add session clearing on login
$oldLogin = 'public function login(Request $request)
    {';
$newLogin = 'public function login(Request $request)
    {
        // Clear any previous session data (folders, cart, etc.)
        session()->forget(\'cart\');
        session()->forget(\'folder\');
        session()->forget(\'selected_photos\');
        session()->forget(\'chat_messages\');';

$content = str_replace($oldLogin, $newLogin, $content);

file_put_contents($file, $content);
echo "Fixed: redirectTo() now always goes to gallery, session cleared on login\n";
