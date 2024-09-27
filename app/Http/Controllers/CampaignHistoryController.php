<?php

namespace App\Http\Controllers;

use App\Models\CampaignHistory;
use Illuminate\Http\Request;

class CampaignHistoryController extends Controller
{
    public function index()
    {
        $campaigns = CampaignHistory::latest()->paginate(1000);

        return view('history', compact('campaigns'));
    }
}
