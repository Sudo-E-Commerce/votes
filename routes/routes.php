<?php
App::booted(function() {
	$namespace = 'Sudo\Vote\Http\Controllers';
	
	Route::namespace($namespace)->name('admin.')->prefix(config('app.admin_dir'))->middleware(['web', 'auth-admin'])->group(function() {
		Route::resource('votes', 'Admin\VoteController');
		Route::post('/votes/get-typeid', 'Admin\VoteController@getTypeId');
	});
	
	Route::namespace($namespace)->name('web.')->group(function() {
		Route::post('/ajax/votes-star', 'Web\VoteController@vote')->name('vote.post');
	});
	
});