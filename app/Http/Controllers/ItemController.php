<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $searchTerm = $request->query('search');

        $items = Item::query()
            ->when($searchTerm, function ($query, $searchTerm) {
                $searchTerm = '%' . $searchTerm . '%';
                $query->where(function ($query) use ($searchTerm) {
                    $query->where('description', 'like', $searchTerm);
                });
            })
            ->get();

        return response()->json($items);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(Item::findOrFail($id));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'box_id' => 'required|integer',
            'photo_url' => 'string'
        ]);
        
        return response()->json(Item::create($validated));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'photo_url' => 'string'
        ]);
        
        $updatedItem = Item::findOrFail($id);

        $updatedItem->update($validated);
        
        return response()->json($updatedItem);
    }

    public function destroy(int $id): Response
    {
        Item::findOrFail($id)->delete();

        return response()->noContent();
    }
}
