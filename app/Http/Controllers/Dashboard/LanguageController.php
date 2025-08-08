<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $languages = Language::withCount('users')
            ->orderBy('language')
            ->paginate(20);

        // Get global statistics
        $stats = [
            'total' => Language::count(),
            'active' => Language::where('is_active', true)->count(),
            'inactive' => Language::where('is_active', false)->count(),
        ];

        return Inertia::render('dashboard/languages/index', [
            'languages' => $languages,
            'stats' => $stats
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('dashboard/languages/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'language' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:languages,code',
            'emoji' => 'nullable|string|max:10',
            'is_active' => 'boolean',
        ]);

        Language::create($validated);

        return redirect()->route('dashboard.languages.index')
            ->with('success', 'Language created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Language $language)
    {
        $language->loadCount('users');
        
        return Inertia::render('dashboard/languages/show', [
            'language' => $language
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Language $language)
    {
        return Inertia::render('dashboard/languages/edit', [
            'language' => $language
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Language $language)
    {
        $validated = $request->validate([
            'language' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:languages,code,' . $language->id,
            'emoji' => 'nullable|string|max:10',
            'is_active' => 'boolean',
        ]);

        $language->update($validated);

        return redirect()->route('dashboard.languages.index')
            ->with('success', 'Language updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Language $language)
    {
        // Prevent deletion if users are using this language
        if ($language->users()->count() > 0) {
            return redirect()->route('dashboard.languages.index')
                ->with('error', 'Cannot delete language that is assigned to users.');
        }

        $language->delete();

        return redirect()->route('dashboard.languages.index')
            ->with('success', 'Language deleted successfully.');
    }

    /**
     * Toggle the active status of a language.
     */
    public function toggle(Language $language)
    {
        $language->update([
            'is_active' => !$language->is_active
        ]);

        $status = $language->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('dashboard.languages.index')
            ->with('success', "Language {$status} successfully.");
    }
}
