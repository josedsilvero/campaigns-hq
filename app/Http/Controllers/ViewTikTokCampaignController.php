<?php

namespace App\Http\Controllers;

use App\Models\ViewTikTokCampaign;

class ViewTikTokCampaignController extends Controller
{
    public function index()
    {
        $campaigns = ViewTikTokCampaign::latest()->paginate(10000);

        return view('tiktok_campaign.list', compact('campaigns'));
    }
}
