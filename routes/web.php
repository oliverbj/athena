<?php

use Illuminate\Support\Facades\Route;

Route::get('/check-oip-fields', function () {
    $request = \App\Models\OIPRequest::first();
    $fields = $request->fields;
    dd($fields);
});