<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $levels = Level::withCount('users')
            ->orderBy('level_number')
            ->paginate(20);

        return Inertia::render('dashboard/levels/index', [
            'levels' => $levels
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('dashboard/levels/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'level_number' => 'required|integer|unique:levels,level_number|min:1',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'min_xp' => 'required|integer|min:0',
            'max_xp' => 'required|integer|gt:min_xp',
            'color' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
            'icon' => 'nullable|string|max:10',
        ]);

        Level::create($validated);

        return redirect()->route('dashboard.levels.index')
            ->with('success', 'Level created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Level $level)
    {
        $level->loadCount('users');
        
        return Inertia::render('dashboard/levels/show', [
            'level' => $level
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Level $level)
    {
        return Inertia::render('dashboard/levels/edit', [
            'level' => $level
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Level $level)
    {
        $validated = $request->validate([
            'level_number' => 'required|integer|min:1|unique:levels,level_number,' . $level->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'min_xp' => 'required|integer|min:0',
            'max_xp' => 'required|integer|gt:min_xp',
            'color' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
            'icon' => 'nullable|string|max:10',
        ]);

        $level->update($validated);

        return redirect()->route('dashboard.levels.index')
            ->with('success', 'Level updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Level $level)
    {
        $level->delete();

        return redirect()->route('dashboard.levels.index')
            ->with('success', 'Level deleted successfully.');
    }
}
