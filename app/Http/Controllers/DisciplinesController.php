<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDisciplineRequest;
use App\Http\Requests\UpdateDisciplineRequest;
use App\Models\Discipline;
use Illuminate\Http\JsonResponse;

class DisciplinesController extends Controller
{
    public function index(): JsonResponse
    {
        $disciplines = Discipline::orderBy('name')->get();
        
        return response()->json([
            'success' => true,
            'data' => $disciplines
        ]);
    }

    public function store(StoreDisciplineRequest $request): JsonResponse
    {
        $discipline = Discipline::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Discipline created successfully',
            'data' => $discipline
        ], 201);
    }

    public function show(Discipline $discipline): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $discipline
        ]);
    }

    public function update(UpdateDisciplineRequest $request, Discipline $discipline): JsonResponse
    {
        $discipline->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Discipline updated successfully',
            'data' => $discipline->fresh()
        ]);
    }

    public function destroy(Discipline $discipline): JsonResponse
    {
        $discipline->delete();

        return response()->json([
            'success' => true,
            'message' => 'Discipline deleted successfully'
        ]);
    }
}
