<?php

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

Route::get('/', function () {
    return view('welcome2');
});


Route::get('/sms', function () {
   
});

Route::get('/home', function () {
    return view('appl.pages.dashboard');
})->name('home')->middleware('auth');

// login routes
Auth::routes();

//under construction page
Route::get('/construction', function () {
        return view('appl.pages.construction');
})->name('construction');

/* Admin Routes */
Route::get('/admin', 'Admin\AdminController@index')->name('admin')->middleware('auth');
Route::get('/admin/analytics', 'Admin\AdminController@analytics')->name('admin.analytics')->middleware('auth');

/* Admin Application Routes */
Route::resource('/admin/test', 'Test\TestController')->middleware('auth');
Route::get('/admin/test/{test}/view', 'Test\AttemptController@view')->middleware('auth')->name('test.view');
Route::get('/admin/test/{test}/cache', 'Test\TestController@cache')->middleware('auth')->name('test.cache');
Route::get('/admin/test/{test}/cache_delete', 'Test\TestController@cache_delete')->middleware('auth')->name('test.cache.delete');

Route::resource('/admin/category', 'Test\CategoryController')->middleware('auth');
Route::resource('/admin/tag', 'Test\TagController')->middleware('auth');
Route::resource('/admin/test/{test}/section', 'Test\SectionController')->middleware('auth');
Route::resource('/admin/test/{test}/extract', 'Test\ExtractController')->middleware('auth');
Route::resource('/admin/test/{test}/mcq', 'Test\McqController')->middleware('auth');
Route::resource('/admin/test/{test}/fillup', 'Test\FillupController')->middleware('auth');
Route::resource('/admin/file', 'Test\FileController')->middleware('auth');
Route::get('/admin/{file}/download','Test\FileController@download')->name('file.download');
Route::get('/admin/{file}/notify','Test\FileController@notify')->name('review.notify');

Route::resource('/admin/group', 'Test\GroupController')->middleware('auth');
Route::resource('/admin/type', 'Test\TypeController')->middleware('auth');


/* User Routes */
Route::resource('/admin/user', 'User\UserController')->middleware('auth');
Route::get('/admin/user/{user}/{test}','User\UserController@test')->middleware('auth')->name('user.test');
Route::post('/admin/user/{user}/{test}','User\UserController@test')->middleware('auth')->name('user.test');

/* Test Attempt Routes */
Route::get('/test/{test}','Test\AttemptController@instructions')->middleware('auth')->name('test');
Route::get('/test/{test}/try','Test\AttemptController@try')->middleware('auth')->name('test.try');
Route::post('/test/{test}/try','Test\AttemptController@store')->name('attempt.store');
Route::post('/test/{test}/upload','Test\AttemptController@upload')->name('attempt.upload');
Route::get('/test/{test}/delete','Test\AttemptController@file_delete')->name('attempt.delete');
Route::get('/test/{test}/review','Test\AttemptController@review')->name('test.review');

Route::get('/test/{test}/evaluation','Test\AttemptController@evaluation')->name('attempt.evaluation');
Route::get('/test/{test}/analysis','Test\AttemptController@analysis')->middleware('auth')->name('test.analysis');


/* Product Routes */
Route::resource('/admin/product', 'Product\ProductController')->middleware('auth');
Route::resource('/admin/coupon', 'Product\CouponController')->middleware('auth');
Route::resource('/admin/order', 'Product\OrderController')->middleware('auth');
Route::get('/orders', 'Product\OrderController@myorders')->middleware('auth')->name('myorders');
Route::get('/orders/{order}', 'Product\OrderController@myordersview')->middleware('auth')->name('myorder.view');

/* Product/Orders Public Routes */
Route::get('/products','Product\ProductController@public')->name('product.public');
Route::get('/products/{product}','Product\ProductController@view')->name('product.view');
Route::get('/checkout/{product}','Product\OrderController@checkout')->name('product.checkout')->middleware('auth');
Route::post('/order','Product\OrderController@order')->name('product.order');
Route::get('/order_payment', 'Product\OrderController@instamojo_return');
Route::post('/order_payment', 'Product\OrderController@instamojo_return');


/* Pages */
Route::get('/terms', function(){ return view('appl.pages.terms');})->name('terms');
Route::get('/privacy', function(){ return view('appl.pages.privacy');})->name('privacy');
Route::get('/refund', function(){ return view('appl.pages.refund');})->name('refund');
Route::get('/disclaimer', function(){ return view('appl.pages.disclaimer');})->name('disclaimer');
Route::get('/contact', function(){ return view('appl.pages.contact');})->name('contact');

/* user verify routes */
Route::get('/activation', 'User\VerifyController@activation')->name('activation');
Route::post('/activation', 'User\VerifyController@activation')->name('activation');
Route::get('/activation/mail/{token}', 'User\VerifyController@email')->name('email.verify');

Route::post('/activation/phone', 'User\VerifyController@sms')->name('sms.verify');

/* Sample Routes */
Route::get('/listening', function(){
    return view('appl.pages.listening');
})->name('listening');

Route::get('/reading', function(){
    return view('appl.pages.reading')->with('reading',1);
})->name('reading');

Route::get('/pricing', function(){
    return view('appl.pages.pricing');
})->name('pricing');
