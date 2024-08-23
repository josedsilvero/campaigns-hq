<?php

namespace App\Http\Controllers;

use App\Models\TikTokCampaign;
use Illuminate\Http\Request;

class TikTokCampaignController extends Controller
{
    public function index()
    {
        $campaigns = TikTokCampaign::latest()->paginate(200);
        return view('tiktok_campaign.index', compact('campaigns'));
    }

    public function exportCSV()
    {
        $filename = 'tiktok_campaigns-data.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($handle, [
                'Campaign Id',
                'Campaign Name',
                'Primary status',
                'Account Id',
                'Conversions',
                'Cost per conversion',
                'Cost',
                'CTR',
                'Clicks',
                'CPC',
                'Frequency',
                'Currency'
            ]);

            // Fetch and process data in chunks
            TikTokCampaign::chunk(25, function ($campaigns) use ($handle) {
                foreach ($campaigns as $campaign) {
                    // Extract data from each campaign.
                    $data = [
                        isset($campaign->campaign_id) ? $campaign->campaign_id : '',
                        isset($campaign->campaign_name) ? $campaign->campaign_name : '',
                        isset($campaign->primary_status) ? $campaign->primary_status : '',
                        isset($campaign->account_id) ? $campaign->account_id : '',
                        isset($campaign->conversions) ? $campaign->conversions : '',
                        isset($campaign->cost_per_conversion) ? $campaign->cost_per_conversion : '',
                        isset($campaign->cost) ? $campaign->cost : '',
                        isset($campaign->ctr) ? $campaign->ctr : '',
                        isset($campaign->clicks) ? $campaign->clicks : '',
                        isset($campaign->cpc) ? $campaign->cpc : '',
                        isset($campaign->frequency) ? $campaign->frequency : '',
                        isset($campaign->currency) ? $campaign->currency : ''
                    ];

                    // Write data to a CSV file.
                    fputcsv($handle, $data);
                }
            });

            // Close CSV file handle
            fclose($handle);
        }, 200, $headers);
    }

    public function importCSV(Request $request)
    {
        $request->validate([
            'import_csv' => 'required|mimes:csv',
        ]);
        //read csv file and skip data
        $file = $request->file('import_csv');
        $handle = fopen($file->path(), 'r');

        //skip the header row
        fgetcsv($handle);

        $chunksize = 25;
        while (!feof($handle)) {
            $chunkdata = [];

            for ($i = 0; $i < $chunksize; $i++) {
                $data = fgetcsv($handle);
                if ($data === false) {
                    break;
                }
                $chunkdata[] = $data;
            }

            $this->getchunkdata($chunkdata);
        }
        fclose($handle);

        return redirect()->route('tiktok_campaigns')->with('success', 'Data has been added successfully.');
    }

    public function getchunkdata($chunkdata)
    {
        foreach ($chunkdata as $column) {
            $campaign_id = $column[0];
            $campaign_name = $column[1];
            $primary_status = $column[2];
            $account_id = $column[3];
            $conversions = $column[4];
            $cost_per_conversion = $column[5];
            $cost = $column[6];
            $ctr = str_replace('%', '', $column[7]);
            $clicks = $column[8];
            $cpc = $column[9];
            $frequency = $column[10];
            $currency = $column[11];

            //create new campaign
            $campaign = TikTokCampaign::updateOrCreate(['campaign_id' => $campaign_id], [
                'campaign_name' => $campaign_name,
                'primary_status' => $primary_status,
                'account_id' => $account_id,
                'conversions' => $conversions,
                'cost_per_conversion' => $cost_per_conversion,
                'cost' => $cost,
                'ctr' => $ctr,
                'clicks' => $clicks,
                'cpc' => $cpc,
                'frequency' => $frequency,
                'currency' => $currency
            ]);
        }
    }
}
