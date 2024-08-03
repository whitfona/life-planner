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

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'photo_url' => 'string'
        ]);
        
        return response()->json(Item::create($validated));
    }
}
