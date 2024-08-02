<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCampaignNoteRequest;
use App\Http\Requests\UpdateCampaignNoteRequest;
use App\Models\CampaignNote;

class CampaignNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('campaign_notes.index', [
            'campaign_notes' => CampaignNote::latest()->paginate(4)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('campaign_notes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCampaignNoteRequest $request)
    {
        CampaignNote::create($request->validated());

        return redirect()->route('campaigns.index')
            ->withSuccess('New note is added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CampaignNote $campaignNote)
    {
        return view('campaign_notes.show', compact('campaign_note'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CampaignNote $campaignNote)
    {
        return view('campaign_notes.edit', compact('campaignNote'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCampaignNoteRequest $request,  string $id)
    {
        $campaignNote = CampaignNote::find($id);

        $campaignNote->cpa = $request->cpa;

        $campaignNote->update($request->validated());

        return redirect()->route('campaigns.index')
            ->withSuccess('Note is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CampaignNote $campaignNote)
    {
        $campaignNote->delete();

        return redirect()->route('campaign_notes.index')
            ->withSuccess('Note is deleted successfully.');
    }
}
