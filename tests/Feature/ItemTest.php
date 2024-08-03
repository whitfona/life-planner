<?php

use App\Models\Item;
use Symfony\Component\HttpFoundation\Response;

it('can get items', function () {
    Item::factory()->count(3)->create();

    $response = $this->getJson('/api/items');

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            [
                'id',
                'description',
                'photo_url',
                'created_at',
                'updated_at',
            ]
        ]);
});