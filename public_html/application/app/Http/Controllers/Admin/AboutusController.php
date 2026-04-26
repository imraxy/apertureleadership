<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Admin\AboutmeRequest;
use App\Models\AboutUs;
use App\Traits\UploadTrait;
use Exception;

class AboutusController extends Controller
{

    private $image_dir = "uploads/about";

    use UploadTrait;

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
        $about_tbl = new AboutUs;

        $about_me = $about_tbl->aboutmeDetail();

        return view('admin.about-us.about-us', compact('about_me'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(AboutmeRequest $request)
    {
        try {
            
            $about_tbl = new AboutUs;

            $about_me = $about_tbl->aboutmeDetail();

            $data['name'] = $request->name ? : NULL;
            $data['content'] = $request->about_me_content ? : NULL;
            $data['no_of_clients'] = $request->satisfied_client ? : NULL;
            $data['no_of_meetings'] = $request->meetings ? : NULL;
            $data['no_of_sessions'] = $request->sessions_have_done ? : NULL; 
            $data['established_year'] = $request->established_from ? : NULL;
            
            if($about_me) {

                if($request->hasFile('about_me_image')) {

                    if ($request->file('about_me_image')->isValid()) {

                        $uplode_dir = public_path($this->image_dir);

                        $about_me_image = $request->file('about_me_image');

                        //Unlik old file
                        if(!empty($about_me->image) && File::exists($uplode_dir.'/'.$about_me->image)){

                            unlink($uplode_dir.'/'.$about_me->image);
                        }

                        $data['image'] =  $this->uploadTwo($about_me_image, $uplode_dir);

                    }
                }

                AboutUs::where('id', $about_me->id)->update($data);

                return redirect()->back()->with('success', 'About content updated successfully.');

                exit();
            }


            $create = AboutUs::create($data);
            
            if($create) {

                if($request->hasFile('about_me_image')) {

                    if ($request->file('about_me_image')->isValid()) {

                        $uplode_dir = public_path($this->image_dir);

                        $about_me_image = $request->file('about_me_image');

                        $image_about['image'] =  $this->uploadTwo($about_me_image, $uplode_dir);

                        AboutUs::whereId($create->id)->update($image_about);

                    }
                }
            }

            return redirect()->back()->with('success', 'About content updated successfully.');

            
        } catch (Exception $e) {

            $error = $e->getMessage();

            return redirect()->back()->with('warning', $error);
            
        }
    } 

}
