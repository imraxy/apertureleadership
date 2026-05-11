<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Models\Setting;
use App\Http\Requests\Admin\SettingupdateRequest;
use App\Http\Controllers\BaseController;
use App\Traits\UploadAble;

class SettingsController extends BaseController
{

    use UploadAble;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->setPageTitle('Settings', 'Manage Settings');
		
        return view('admin.settings.settings');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(SettingupdateRequest $request)
    {

        if ($request->has('site_logo') && ($request->file('site_logo') instanceof UploadedFile)) {

            if (config('settings.site_logo') != null) {

                $this->deleteOne(config('settings.site_logo'));

            }

            $logo = $this->uploadOne($request->file('site_logo'), 'img');

            Setting::set('site_logo', $logo);

        } 

        if ($request->has('site_favicon') && ($request->file('site_favicon') instanceof UploadedFile)) {

            if (config('settings.site_favicon') != null) {
                
                $this->deleteOne(config('settings.site_favicon'));
            }

            $favicon = $this->uploadOne($request->file('site_favicon'), 'img');
            
            Setting::set('site_favicon', $favicon);

        }

        $keys = $request->except('_method', '_token', 'site_favicon', 'site_logo', 'current_request');

        foreach ($keys as $key => $value)
        {
            Setting::set($key, $value);
        }
        
        $tab = 'm_tabs_7_1';

        /*if($request->current_request==1) {
            $tab = 'm_tabs_7_1';
        }elseif ($request->current_request==2) {
            $tab = 'm_tabs_7_2';
        }elseif ($request->current_request==3) {
            $tab = 'm_tabs_7_3';
        }elseif ($request->current_request==4) {
            $tab = 'm_tabs_7_4';
        }*/
        return redirect(route('admin.settings.update'))->with('success', 'Settings updated successfully.');
        //return redirect()->back()->with('success', 'Settings updated successfully.');
        //return $this->responseRedirectBack('success', 'Settings updated successfully.');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateseo(SettingupdateRequest $request)
    {
        if ($request->has('site_logo') && ($request->file('site_logo') instanceof UploadedFile)) {

            if (config('settings.site_logo') != null) {

                $this->deleteOne(config('settings.site_logo'));

            }

            $logo = $this->uploadOne($request->file('site_logo'), 'img');

            Setting::set('site_logo', $logo);

        } elseif ($request->has('site_favicon') && ($request->file('site_favicon') instanceof UploadedFile)) {

            if (config('settings.site_favicon') != null) {
                
                $this->deleteOne(config('settings.site_favicon'));
            }

            $favicon = $this->uploadOne($request->file('site_favicon'), 'img');
            
            Setting::set('site_favicon', $favicon);

        } else {

            $keys = $request->except('_method', '_token');

            foreach ($keys as $key => $value)
            {
                Setting::set($key, $value);
            }
        }

        return $this->responseRedirectBack('Settings updated successfully.', 'success');
    }

}
