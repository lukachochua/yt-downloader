<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\YTDownloadController;

Route::get('/', function () {
    return view('download');
});

Route::post('/download', [YTDownloadController::class, 'download']);
