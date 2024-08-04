<?php

use App\Models\Item;
use Symfony\Component\HttpFoundation\Response;

it('can get items', function () {
    Item::factory()->count(3)->create();

    $response = $this->getJson($this->getBaseItemUrl());

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

it('can get a specific item', function() {
    Item::factory()->count(2)->create();

    $targetItem = Item::factory()->create(['description' => 'Target description', 'photo_url' => 'target-photo-url']);

    $response = $this->getJson("{$this->getBaseItemUrl()}/{$targetItem->id}");

    expect(Item::count())->toBe(3);

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'id' => $targetItem->id,
            'description' => $targetItem->description,
            'photo_url' => $targetItem->photo_url
        ]);
});

it('throws an error if an item does not exist', function() {
    Item::factory()->count(2)->create();
    
    $nonExistentId = 3;

    $response = $this->getJson("{$this->getBaseItemUrl()}/{$nonExistentId}");

    $response->assertStatus(Response::HTTP_NOT_FOUND);
});

it('can search for items', function() {
    $itemOne = Item::factory()->create(['description' => 'Can you find me']);
    Item::factory()->create();
    $itemTwo = Item::factory()->create(['description' => 'Did you find me?']);

    $searchWord = 'find';
    $response = $this->getJson("{$this->getBaseItemUrl()}?search={$searchWord}");

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            [
                'id' => $itemOne->id,
                'description' => $itemOne->description,
            ],
            [
                'id' => $itemTwo->id,
                'description' => $itemTwo->description,
            ]
        ]);

    expect(count($response->json()))->toBe(2);
});

it('can create an item', function () {
    $newItem = Item::factory()->make();

    $response = $this->postJson($this->getBaseItemUrl(), $newItem->toArray());

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'description' => $newItem->description,
            'photo_url' => $newItem->photo_url,
            'box_id' => $newItem->box->id
        ]);

    expect(Item::count())->toBe(1);
});

it('validates when creating a new item', function ($field, $value) {
    $newItem = Item::factory()->make([$field => $value]);

    $response = $this->postJson($this->getBaseItemUrl(), $newItem->toArray());

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

it('can update an item', function () {
    Item::factory()->create();
    $item = Item::factory()->create();
    
    expect(Item::count())->toBe(2);

    $newItemData = [
        'description' => 'New description',
        'photo_url' => 'new-photo-url'
    ];

    $response = $this->patchJson("{$this->getBaseItemUrl()}/{$item->id}", $newItemData);

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'description' => $newItemData['description'],
            'photo_url' => $newItemData['photo_url'],
        ]);

    expect(Item::count())->toBe(2);
});

it('validates when updating an item', function ($field, $value) {
    $item = Item::factory()->create();

    $response = $this->patchJson("{$this->getBaseItemUrl()}/{$item->id}", [$field => $value]);

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

it('throws an error when updating an item that does not exist', function() {
    Item::factory()->count(2)->create();
    
    $nonExistentId = 3;
    $newItemData = [
        'description' => 'New description'
    ];

    $response = $this->patchJson("api/items/{$nonExistentId}", $newItemData);

    $response->assertStatus(Response::HTTP_NOT_FOUND);
});

it('can delete an item', function() {
    Item::factory()->create();
    $itemToDelete = Item::factory()->create();

    expect(Item::count())->toBe(2);

    $response = $this->deleteJson("{$this->getBaseItemUrl()}/{$itemToDelete->id}");

    $response->assertStatus(Response::HTTP_NO_CONTENT);

    expect(Item::count())->toBe(1);
});

it('throws an error when deleting an item that does not exist', function() {
    Item::factory()->count(2)->create();
    
    $nonExistentId = 3;

    $response = $this->deleteJson("{$this->getBaseItemUrl()}/{$nonExistentId}");

    $response->assertStatus(Response::HTTP_NOT_FOUND);
});