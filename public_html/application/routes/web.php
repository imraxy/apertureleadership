<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Cart routes
Route::get('cart/add_item', 'CartController@store')->name('cart.addItem');
Route::post('/get_album_images', 'CartController@get_album_images')->name('user.get_album_images')->middleware(['auth', 'group.session']);

//front-end routes
Route::group(['namespace' => 'Front'], function () {

    Route::get('/', 'HomeController@index')->name('front.index');
	Route::view('contact', 'front.contact')->name('front.contact');
	Route::view('about-us', 'front.about-us')->name('front.about_us');
});

Route::get('/session-images/{slug?}', 'Albums\AlbumsController@sessionImgaes')->name('front.sessionImgaes');
Route::get('/albums/{slug?}', 'Albums\AlbumsController@index')->name('front.albums');
Route::get('/albums/{slug}', 'Albums\AlbumsController@show')->name('front.albums.session');

Route::post('/getAlbumImage', 'Albums\AlbumsController@getAlbumImage');

Route::any('/setImageShape', 'Albums\AlbumsController@setImageShape')->name('setImageShape');

Route::any('/getImageShape', 'Albums\AlbumsController@getImageShape')->name('getImageShape');

Route::get('/posts', 'PostsController@index')->name('front.posts');
Route::get('/posts/{slug}', 'PostsController@show')->name('front.posts.detail');
Route::post('/ajax_contact_enquiry', 'ContactenquiryController@store')->name('front.ajax_contact_enquiry');

// Admin Authentication Routes...

Route::prefix('admin')->group(function () {

	Route::get('/', 'Admin\Auth\AdminloginController@showLoginForm');
	Route::get('login', 'Admin\Auth\AdminloginController@showLoginForm')->name('admin_login');
	Route::post('login', 'Admin\Auth\AdminloginController@adminUserLogin');

});

//Admin routes list

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'admin'], function() {

	//Clear application cache
    Route::get('/cache-clear', function () {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
    	return redirect(route('admin_dashboard'))->with('success', 'Cache & temp files Cleared Successfully');
    })->name('cacheClear');
	
	//
	
	Route::get('imageconvert', 'SessionimagesController@imageconvert')->name('imageconvert');
	
	
    Route::get('profile', 'AdminprofileController@show')->name('admin_profile');
    Route::put('profile', 'AdminprofileController@update');
    Route::post('logout', 'AdminprofileController@logout')->name('admin.logout');
    
    Route::get('dashboard', 'DashboardController@index')->name('admin_dashboard');

	//Settings & SEO
	Route::get('settings', 'SettingsController@index')->name('admin.settings.update');
    Route::put('settings', 'SettingsController@update');

    //About-us
    Route::group(['prefix' => 'about-us'], function() {
		Route::get('/', 'AboutusController@index')->name('admin.about_us');
		Route::post('/', 'AboutusController@update');
	});
	
	//Admin users
    Route::group(['namespace' => 'Users', 'prefix' => 'users'], function() {
		Route::get('/', 'AdminusersController@index')->name('admin.users');
		Route::post('ajax_users_datatables', 'AdminusersController@users')->name('admin.users.datatables-users');
		Route::get('create', 'AdminusersController@create')->name('admin.create_user');
		Route::post('create', 'AdminusersController@store');
		Route::get('edit/{id}', 'AdminusersController@edit')->name('admin.edit_user');
		Route::put('edit/{id}', 'AdminusersController@update');
		Route::get('delete/{id}', 'AdminusersController@destroy')->name('admin.delete_user');
	});

	//Slider routes
    Route::group(['namespace' => 'Sliders', 'prefix' => 'sliders'], function() {
		Route::get('/', 'HomeslidersController@index')->name('admin.sliders');
		Route::post('ajax_sliders_datatables', 'HomeslidersController@sliders')->name('admin.sliders.datatables-sliders');
		Route::get('create', 'HomeslidersController@create')->name('admin.create_slider');
		Route::post('create', 'HomeslidersController@store');
		Route::get('edit/{id}', 'HomeslidersController@edit')->name('admin.edit_slider');
		Route::put('edit/{id}', 'HomeslidersController@update');
		Route::get('delete/{id}', 'HomeslidersController@destroy')->name('admin.delete_slider');
	});

	//Albums routes & albums category
    Route::group(['namespace' => 'Albums', 'prefix' => 'albums'], function() {

		Route::get('/', 'AlbumsController@index')->name('admin.albums');
		Route::post('ajax_albums_datatables', 'AlbumsController@albums')->name('admin.albums.datatables-albums');
		Route::get('create', 'AlbumsController@create')->name('admin.create_albums');
		Route::post('create', 'AlbumsController@store');
		Route::get('edit/{id}', 'AlbumsController@edit')->name('admin.edit_albums');
		Route::put('edit/{id}', 'AlbumsController@update');
		Route::get('delete/{id}', 'AlbumsController@destroy')->name('admin.delete_albums');
		Route::post('ajax_delete_gallery_album', 'AlbumsController@destroygalleryImage')->name('admin.delete_gallery_album');

		Route::get('categories', 'CategoriesController@index')->name('admin.albums_categories');
		Route::post('ajax_album_categories_datatable', 'CategoriesController@categories')->name('admin.albums_categories_datatables');
		Route::get('categories/create', 'CategoriesController@create')->name('admin.create_albums_category');
		Route::post('categories/create', 'CategoriesController@store');
		Route::get('categories/edit/{id}', 'CategoriesController@edit')->name('admin.edit_albums_category');
		Route::put('categories/edit/{id}', 'CategoriesController@update');
		Route::get('categories/delete/{id}', 'CategoriesController@destroy')->name('admin.delete_albums_category');
		//Session gallery images
		//Route::get('/{id}/session-images', 'SessionimagesController@index')->name('admin.albums.session_images');
		//Route::post('/{id}/session-images', 'SessionimagesController@index');
		//Route::get('session-images/create', 'SessionimagesController@create')->name('admin.add_albums_session_image');
		Route::post('session-images/create', 'SessionimagesController@store')->name('admin.add_albums_session_image');
		//Route::get('/{id}/session-images/edit/{session_image_id}', 'SessionimagesController@edit')->name('admin.edit_albums_session_image');
		Route::put('session-images/edit/{session_image_id}', 'SessionimagesController@update')->name('admin.edit_albums_session_image');
		Route::get('session-images/delete/{session_image_id}', 'SessionimagesController@destroy')->name('admin.delete_albums_session_image');
		
		

	});

	//testimonials
    Route::group(['namespace' => 'Testimonials', 'prefix' => 'testimonials'], function() {

		Route::get('/', 'TestimonialsController@index')->name('admin.testimonials');
		Route::post('ajax_testimonials_datatables', 'TestimonialsController@testimonials')->name('admin.testimonials_datatables');
		Route::get('create', 'TestimonialsController@create')->name('admin.create_testimonial');
		Route::post('create', 'TestimonialsController@store');
		Route::get('edit/{id}', 'TestimonialsController@edit')->name('admin.edit_testimonial');
		Route::put('edit/{id}', 'TestimonialsController@update');
		Route::get('delete/{id}', 'TestimonialsController@destroy')->name('admin.delete_testimonial');
	
	});

	//Journal & journal category
    Route::group(['namespace' => 'Posts', 'prefix' => 'posts'], function() {

		Route::get('/', 'PostsController@index')->name('admin.posts');
		Route::post('ajax_posts_datatables', 'PostsController@posts')->name('admin.posts.datatables-posts');
		Route::get('create', 'PostsController@create')->name('admin.create_posts');
		Route::post('create', 'PostsController@store');
		Route::get('edit/{id}', 'PostsController@edit')->name('admin.edit_posts');
		Route::put('edit/{id}', 'PostsController@update');
		Route::get('delete/{id}', 'PostsController@destroy')->name('admin.delete_posts');
		
		Route::get('categories', 'CategoriesController@index')->name('admin.posts_categories');
		Route::post('ajax_posts_categories_datatable', 'CategoriesController@categories')->name('admin.posts_categories_datatables');
		Route::get('categories/create', 'CategoriesController@create')->name('admin.create_posts_category');
		Route::post('categories/create', 'CategoriesController@store');
		Route::get('categories/edit/{id}', 'CategoriesController@edit')->name('admin.edit_posts_category');
		Route::put('categories/edit/{id}', 'CategoriesController@update');
		Route::get('categories/delete/{id}', 'CategoriesController@destroy')->name('admin.delete_posts_category');

	});

	//session_packages
    Route::group(['prefix' => 'session-packages'], function() {

		Route::get('/', 'SessionspackagesController@index')->name('admin.packages');
		Route::post('ajax_posts_datatables', 'SessionspackagesController@packages')->name('admin.packages.datatables-packages');
		Route::get('create', 'SessionspackagesController@create')->name('admin.create_packages');
		Route::post('create', 'SessionspackagesController@store');
		Route::get('edit/{id}', 'SessionspackagesController@edit')->name('admin.edit_packages');
		Route::put('edit/{id}', 'SessionspackagesController@update');
		Route::get('delete/{id}', 'SessionspackagesController@destroy')->name('admin.delete_packages');

	});


	//Chat routes.
	Route::group(['namespace' => 'Chats', 'prefix' => 'chat-conversation'], function () {
		//Route::get('/{id?}/{user?}', 'ChatsController@index')->name('admin.chat-conversation');	
		Route::get('/', 'ChatsController@index')->name('admin.chat-conversation');	
		Route::post('/chat_action', 'ChatsController@store')->name('admin.chat_action');
		//Route::post('/chat_conversation_list', 'ChatsController@chatmessageList')->name('admin.chatmessageList');	

		//Route::get('{user_id}/{user_name}/folders', 'ChatsController@userFolders')->name('admin.userFolders');
		Route::get('/chat_conversation_list', 'ChatsController@chatmessageList')->name('admin.chatmessageList');
		
		
		Route::get('/available_group', 'ChatsController@available_group')->name('admin.available_group');
		
		
		
	});

	//User folders routes.
	Route::group(['namespace' => 'Chats', 'prefix' => 'folders'], function () {
		Route::get('{user_id}/{user_name}', 'ChatsController@userFolders')->name('admin.userFolders');
		Route::get('{user_id}/{user_name}/{folder_id}/chat', 'ChatsController@show')->name('admin.userFolders.chat');
	});
	
	
	//Approval code
    Route::group(['namespace' => 'Approval', 'prefix' => 'approval'], function() {
		Route::get('/', 'ApprovalController@index')->name('admin.approval');
		Route::post('datatables-approval', 'ApprovalController@datatablesapproval')->name('admin.approval.datatables-approval');
		Route::get('select_user', 'ApprovalController@select_user')->name('admin.approval.select_user');
		Route::any('assign_code', 'ApprovalController@assign_code')->name('admin.approval.assign_code');
		Route::any('delete_approval_code/{id}', 'ApprovalController@delete_approval_code')->name('admin.approval.delete_approval_code');
		Route::any('assign_code_multipel/', 'ApprovalController@assign_code_multipel')->name('admin.approval.assign_code_multipel');
	 
	 
	});

});

Auth::routes();

Route::get('/home', 'CartController@index')->name('home')->middleware(['auth', 'group.session']);
Route::get('/account/folders/{folder_id?}', 'CartController@index')->name('account.folders')->middleware(['auth', 'group.session']);
Route::get('/account/folders/{user_id}/remove/{cart_id}', 'CartController@destroy')->name('account.folders.remove')->middleware(['auth', 'group.session']);

Route::get('/account/folders/{folder_id}/chat', 'Chats\ChatsController@show')->name('account.folders.chat')->middleware(['auth', 'group.session']);

Route::group(['namespace' => 'Chats', 'prefix' => 'chat-conversation', 'middleware' => ['auth', 'group.session']], function() {
	Route::post('/chat_action', 'ChatsController@store')->name('user.chat_action');
	Route::post('/chat_conversation_list', 'ChatsController@chatmessageList')->name('user.chatmessageList');
	Route::get('/chat_conversation_list', 'ChatsController@chatmessageList')->name('user.chatmessageList');
});

//Route::get('paywithpaypal/{folder_id}', array('as' => 'addmoney.paywithpaypal','uses' => 'PaypalController@payWithPaypal',));
//Route::post('paypal/{folder_id}', array('as' => 'addmoney.paypal','uses' => 'PaypalController@postPaymentWithpaypal',));
//Route::get('paypal', array('as' => 'payment.status','uses' => 'PaypalController@getPaymentStatus',));

Route::post('paypal/{folder_id}', array('as' => 'addmoney.paypal','uses' => 'PaypalController@postPaymentWithpaypal',));
Route::get('paypal', array('as' => 'payment.status','uses' => 'PaypalController@getPaymentStatus',));
