<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Website;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(Website $website)
    {
        return $website->pages;
    }

    public function store(Request $request, Website $website)
    {
        $validated = $request->validate([
            'url' => 'required|url',
        ]);

        return $website->pages()->create($validated);
    }

    public function show(Page $page)
    {
        return $page->load('checks');
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'url' => 'sometimes|url',
        ]);

        $page->update($validated);

        return $page;
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return response()->noContent();
    }
}
