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

it('can create an item', function () {
    $newItem = Item::factory()->make();

    $response = $this->postJson('/api/items', $newItem->toArray());

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'description' => $newItem->description,
            'photo_url' => $newItem->photo_url,
        ]);

    expect(Item::count())->toBe(1);
});

it('validates when creating a new item', function ($field, $value) {
    $newItem = Item::factory()->make([$field => $value]);

    $response = $this->postJson('/api/items', $newItem->toArray());

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonStructure([
            'message',
            'errors' => [
                $field
            ]
        ]);
})->with([
    'description cannot be null' => ['description', null],
    'description must be a string' => ['description', 1234],
    'photo_url must be a string' => ['photo_url', 1234],
]);