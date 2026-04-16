<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCampaignDataRequest;
use App\Jobs\ProcessCampaignData;
use App\Models\Campaign;
use Illuminate\Http\JsonResponse;

class CampaignDataController extends Controller
{
    public function store(StoreCampaignDataRequest $request, Campaign $campaign): JsonResponse
    {
        ProcessCampaignData::dispatch($campaign, $request->validated());

        return response()->json([
            'message' => 'Campaign data accepted for processing.',
        ], 202);
    }
}