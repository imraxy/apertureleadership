<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\ContactEnquiry;
use App\Mail\ContactEnquiryEmail;
use Exception;

class ContactenquiryController extends Controller
{
	
	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		try {
			
			$data['name'] = $request->name ? : NULL;
			$data['email'] = $request->email ? : NULL;
			$data['phone'] = $request->phone ? : NULL;
			$data['message'] = $request->message ? : NULL;
			$data['service'] = $request->service ? : NULL;

			$create = ContactEnquiry::create($data);
			
			if($create) {

				try {
						
					Mail::to('andy_craggs@hotmail.com')->send(new ContactEnquiryEmail($create));

				} catch (Exception $e) {
				    
				}
				

				return redirect()->back()->with('success', 'Thank you for your enquiry. we will be in touch shortly.');

			}else {
				return redirect()->back()->with('error', 'Contact form submit failed please try later.');
			}
		} catch (Exception $e) {
          
            return redirect()->back()->with('error', $e->getMessage()); 
            
        }
		
	}

}
