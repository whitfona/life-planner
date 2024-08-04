<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Item::all());
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(Item::findOrFail($id));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'description' => 'required|string',
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
}
