<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\CampaignData;
use App\Models\CampaignDataDuplicateLog;
use Illuminate\Console\Command;

class GenerateCampaignAnalytics extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'analytics:campaign {campaignId}';

    /**
     * The console command description.
     */
    protected $description = 'Generate analytics for a campaign';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $campaignId = $this->argument('campaignId');

        $campaign = Campaign::with('client')->find($campaignId);

        if (! $campaign) {
            $this->error('Campaign not found.');
            return Command::FAILURE;
        }

        $totalRecords = CampaignData::where('campaign_id', $campaignId)->count();

        $uniqueUsers = CampaignData::where('campaign_id', $campaignId)
            ->distinct('user_id')
            ->count('user_id');

        $duplicateCount = CampaignDataDuplicateLog::where('campaign_id', $campaignId)->count();

        $customFieldKeys = [];

        $records = CampaignData::where('campaign_id', $campaignId)->get();

        foreach ($records as $record) {
            $fields = $record->custom_fields ?? [];

            foreach ($fields as $key => $value) {
                if (! isset($customFieldKeys[$key])) {
                    $customFieldKeys[$key] = 0;
                }

                $customFieldKeys[$key]++;
            }
        }

        $this->info('Campaign Analytics');
        $this->line('-------------------');
        $this->line("Campaign: {$campaign->name}");
        $this->line("Client: {$campaign->client->name}");
        $this->line("Total Records: {$totalRecords}");
        $this->line("Unique Users: {$uniqueUsers}");
        $this->line("Duplicate Attempts: {$duplicateCount}");

        $this->line("\nCustom Field Usage:");

        if (empty($customFieldKeys)) {
            $this->line('- None');
        } else {
            foreach ($customFieldKeys as $key => $count) {
                $this->line("- {$key}: {$count}");
            }
        }

        return Command::SUCCESS;
    }
}