<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElectionSetting;
use Illuminate\Http\Request;

class ElectionSettingController extends Controller
{
    /**
     * Show the election settings form.
     */
    public function edit()
    {
        $settings = ElectionSetting::current();

        return view('admin.settings.edit', compact('settings'));
    }

    /**
     * Update election settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'voting_open'  => ['nullable', 'boolean'],
            'voting_start' => ['nullable', 'date'],
            'voting_end'   => ['nullable', 'date', 'after_or_equal:voting_start'],
        ]);

        $settings = ElectionSetting::current();

        $settings->update([
            'title'        => $request->title,
            'voting_open'  => $request->boolean('voting_open'),
            'voting_start' => $request->voting_start,
            'voting_end'   => $request->voting_end,
        ]);

        return redirect()->back()->with('success', 'Election settings updated successfully.');
    }

    /**
     * Toggle the voting open/closed status.
     */
    public function toggleVoting(Request $request)
    {
        $settings = ElectionSetting::current();

        $settings->update([
            'voting_open' => ! $settings->voting_open,
        ]);

        $status = $settings->voting_open ? 'opened' : 'closed';

        return redirect()->back()->with('success', "Voting has been {$status}.");
    }
}
