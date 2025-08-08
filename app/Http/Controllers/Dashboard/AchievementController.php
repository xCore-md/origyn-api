<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AchievementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $achievements = Achievement::withCount('users')
            ->orderBy('order')
            ->orderBy('created_at')
            ->paginate(20);

        return Inertia::render('dashboard/achievements/index', [
            'achievements' => $achievements
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('dashboard/achievements/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:achievements,key|max:255',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:10',
            'color' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
            'xp_reward' => 'required|integer|min:0',
            'type' => 'required|in:streak,level,general',
            'criteria' => 'nullable|array',
            'is_hidden' => 'boolean',
            'order' => 'required|integer|min:0',
        ]);

        Achievement::create($validated);

        return redirect()->route('dashboard.achievements.index')
            ->with('success', 'Achievement created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Achievement $achievement)
    {
        $achievement->loadCount('users');
        
        return Inertia::render('dashboard/achievements/show', [
            'achievement' => $achievement
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Achievement $achievement)
    {
        return Inertia::render('dashboard/achievements/edit', [
            'achievement' => $achievement
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Achievement $achievement)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:achievements,key,' . $achievement->id,
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:10',
            'color' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
            'xp_reward' => 'required|integer|min:0',
            'type' => 'required|in:streak,level,general',
            'criteria' => 'nullable|array',
            'is_hidden' => 'boolean',
            'order' => 'required|integer|min:0',
        ]);

        $achievement->update($validated);

        return redirect()->route('dashboard.achievements.index')
            ->with('success', 'Achievement updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Achievement $achievement)
    {
        $achievement->delete();

        return redirect()->route('dashboard.achievements.index')
            ->with('success', 'Achievement deleted successfully.');
    }
}
