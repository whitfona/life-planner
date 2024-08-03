<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Box;

class BoxController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $searchTerm = $request->query('search');

        $boxes = Box::query()
            ->when($searchTerm, function ($query, $searchTerm) {
                $searchTerm = '%' . $searchTerm . '%';
                $query->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', $searchTerm)
                        ->orWhere('description', 'like', $searchTerm);
                });
            })
            ->get();

        return response()->json($boxes);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(Box::findOrFail($id));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'location' => 'required|string'
        ]);

        return response()->json(Box::create($validated));
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
            'location' => 'sometimes|required|string'
        ]);

        $updatedBox = Box::findOrFail($request->id);

        $updatedBox->update($validated);
    
        return response()->json($updatedBox);
    }

    public function destroy(int $id): Response
    {
        Box::findOrFail($id)->delete();

        return response()->noContent();
    }
}
