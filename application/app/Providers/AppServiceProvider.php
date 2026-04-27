<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\AboutUs;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        View()->composer('*', function ($view) {

            $about_tbl = new AboutUs;

            $about_me = $about_tbl->aboutmeDetail();

            $view->with(['about_me' => $about_me]);
            if(Auth::user()){
					if(strtotime(Auth::user()->approval_code_end_time) < strtotime(date('d-m-Y H:i:s')) && 
						strtotime(Auth::user()->approval_code_create_time) >= strtotime(date('d-m-Y H:i:s'))
					){
						Auth::logout();
						session(['login_error' => 'Your approval code is expired!!']);
						return redirect('/login');
					}
			} 

        });
    }
}
