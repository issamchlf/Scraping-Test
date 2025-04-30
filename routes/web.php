<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScrapingController;
use App\Http\Controllers\EsMadridScraperController;
use App\Http\Controllers\MonumentosScrapingController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/scrape', [ScrapingController::class, 'scrape']);
Route::get('/scrape-monumentos', [MonumentosScrapingController::class, 'scrape'])->name('scrape-monumentos');
Route::get('/scrape-events', [EsMadridScraperController::class, 'scrape']);
Route::get('/events', [EsMadridScraperController::class, 'index'])->name('events.index');