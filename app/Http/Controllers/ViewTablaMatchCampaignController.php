<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\ViewTablaMatchCampaign;
use Illuminate\Support\Facades\DB;

class ViewTablaMatchCampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campaigns = ViewTablaMatchCampaign::latest()->paginate(10000);
        /*$user_name = Auth::user()->user_name;
        $campaigns = ViewTablaMatchCampaign::where('user_name', $user_name);*/
        $latestRecord = DB::table('campaign')->max('update_date');

        return view('campaigns.index', compact('campaigns'))->with('latestRecord', $latestRecord);
    }

    public function edit(ViewTablaMatchCampaign $campaign): View
    {
        return view('campaigns.edit', compact('campaign'));
    }
}
