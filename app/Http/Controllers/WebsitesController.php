<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;

class WebsitesController extends Controller
{
    public function index()
    {
        return Website::with('pages')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'base_url' => 'nullable|url',
        ]);

        $validated['user_id'] = $request->user()->id;

        return Website::create($validated);
    }

    public function show(Website $website)
    {
        return $website->load('pages');
    }

    public function update(Request $request, Website $website)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'base_url' => 'nullable|url',
        ]);

        $website->update($validated);

        return $website;
    }

    public function destroy(Website $website)
    {
        $website->delete();
        return response()->noContent();
    }
}
