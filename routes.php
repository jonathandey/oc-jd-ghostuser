<?php

Route::get('/jd/ghostuser/logout', function () {
	if(BackendAuth::check() === false) {
		abort(401);
	}

	Session::forget('jd.ghostuser.ghosting');
	Auth::logout();

	$backendUri = config('cms.backendUri');
	return Redirect::to('/' . $backendUri . '/rainlab/user/users');
});