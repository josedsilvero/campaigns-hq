<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\ViewTablaMatchCampaign;

class ViewTablaMatchCampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campaigns = ViewTablaMatchCampaign::latest()->paginate(100);
        /*$user_name = Auth::user()->user_name;
        $campaigns = ViewTablaMatchCampaign::where('user_name', $user_name);*/

        return view('campaigns.index', compact('campaigns'));
    }

    public function edit(ViewTablaMatchCampaign $campaign): View
    {
        return view('campaigns.edit', compact('campaign'));
    }
}
