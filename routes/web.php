<?php

use App\Http\Controllers\CampaignHistoryController;
use App\Http\Controllers\CampaignNoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TikTokCampaignController;
use App\Http\Controllers\ViewTablaMatchCampaignController;
use App\Http\Controllers\ViewTikTokCampaignController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');
});

Route::get('campaigns', [ViewTablaMatchCampaignController::class, 'index'])->name('campaigns.index')->middleware('auth');
Route::resource('campaign_notes', CampaignNoteController::class);
Route::get('my_history', [CampaignHistoryController::class, 'index'])->name('my_history')->middleware('auth');

Route::get('tiktok_campaigns', [TikTokCampaignController::class, 'index'])->name('tiktok_campaigns')->middleware('is_admin');

Route::get('my_tiktok_campaigns', [ViewTikTokCampaignController::class, 'index'])->name('my_tiktok_campaigns')->middleware('auth');

Route::get('tiktok_campaigns-export', [TikTokCampaignController::class, 'exportCSV'])->name('export');
Route::post('tiktok_campaigns-import', [TikTokCampaignController::class, 'importCSV'])->name('import');

Route::get('/import-csv', function () {
    return view('tiktok_campaign.csvimport');
});

require __DIR__ . '/auth.php';
