<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/assets');
Route::view('/assets', 'assets.index')->name('assets.index');
Route::view('/loans', 'loans.index')->name('loans.index');
