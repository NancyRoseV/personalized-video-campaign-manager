<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\CampaignDataController;

Route::post('/campaigns', [CampaignController::class, 'store']);
Route::post('/campaigns/{campaign}/data', [CampaignDataController::class, 'store']);