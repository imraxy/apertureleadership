<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a new admin
        Admin::create([
            'name'          =>  'Aperture',
            'user_name'          =>  'aperture',
            'email' =>  'admin@admin.com',
            '//email_verified_at' =>  Carbon::now()->toDateTimeString(),
            'password'   =>  Hash::make('123456789'),
            'is_role'   =>  1,
            'last_login_at'   =>  Carbon::now()->toDateTimeString(),
            'last_login_ip'   =>  '2405:201:5c07:a04c:98dc:5f34:5864:6c63',
        ]);
    }
}
