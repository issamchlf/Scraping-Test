<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScrapingController;
use App\Http\Controllers\EsMadridScraperController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/scrape', [ScrapingController::class, 'scrape']);
Route::get('/scrape-events', [EsMadridScraperController::class, 'scrape']);
Route::get('/events', [EsMadridScraperController::class, 'index'])->name('events.index');