<?php

use Vendor\MediaManagerPro\Controllers\ImageController;

Route::get('image/{width}/{storage}/{model}/{id}/{filename}', [ImageController::class, 'getImage']);
