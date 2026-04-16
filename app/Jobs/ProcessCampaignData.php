<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Models\CampaignData;
use App\Models\CampaignDataDuplicateLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessCampaignData implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Campaign $campaign,
        public array $campaignDataItems
    ) {
    }

    public function handle(): void
    {
        foreach ($this->campaignDataItems as $item) {
            $existingRecord = CampaignData::where('campaign_id', $this->campaign->id)
                ->where('user_id', $item['user_id'])
                ->first();

            if ($existingRecord) {
                $oldValues = [
                    'video_url' => $existingRecord->video_url,
                    'custom_fields' => $existingRecord->custom_fields,
                ];

                $newCustomFields = $item['custom_fields'] ?? [];
                $existingCustomFields = $existingRecord->custom_fields ?? [];

                $existingRecord->update([
                    'video_url' => $item['video_url'],
                    'custom_fields' => array_merge($existingCustomFields, $newCustomFields),
                ]);

                CampaignDataDuplicateLog::create([
                    'campaign_id' => $this->campaign->id,
                    'user_id' => $item['user_id'],
                    'action' => 'updated',
                    'details' => [
                        'old_values' => $oldValues,
                        'new_values' => [
                            'video_url' => $item['video_url'],
                            'custom_fields' => array_merge($existingCustomFields, $newCustomFields),
                        ],
                    ],
                ]);

                continue;
            }

            CampaignData::create([
                'campaign_id' => $this->campaign->id,
                'user_id' => $item['user_id'],
                'video_url' => $item['video_url'],
                'custom_fields' => $item['custom_fields'] ?? null,
            ]);
        }
    }
}