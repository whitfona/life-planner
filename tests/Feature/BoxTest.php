<?php

use App\Models\Box;
use Symfony\Component\HttpFoundation\Response;

it('can get a box', function () {
    $newBox = Box::factory()->create();

    $response = $this->getJson($this->getBaseBoxUrl());

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            [
                'id' => $newBox->id,
                'name' => $newBox->name,
                'description' => $newBox->description,
                'location' => $newBox->location,
                'created_at' => $newBox->created_at->toISOString(),
                'updated_at' => $newBox->updated_at->toISOString(),
            ]
        ]);

    expect(Box::count())->toBe(1);
});

it('can get multiple boxes', function () {
    $newBox = Box::factory()->count(3)->create();

    $response = $this->getJson($this->getBaseBoxUrl());

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            [
                'id',
                'name',
                'description',
                'location',
                'created_at',
                'updated_at',
            ]
        ]);

    expect(Box::count())->toBe(3);
});

it('can get a specific box', function() {
    Box::factory()->count(2)->create();

    $targetBox = Box::factory()->create(['name' => 'Target name', 'description' => 'Target description']);

    $response = $this->getJson("{$this->getBaseBoxUrl()}/{$targetBox->id}");

    expect(Box::count())->toBe(3);

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'id' => $targetBox->id,
            'name' => $targetBox->name,
            'description' => $targetBox->description
        ]);
});

it('throws an error if a box does not exist', function() {
    Box::factory()->count(2)->create();
    
    $nonExistentId = 3;

    $response = $this->getJson("{$this->getBaseBoxUrl()}/{$nonExistentId}");

    $response->assertStatus(Response::HTTP_NOT_FOUND);
});

it('can search for boxes', function() {
    $boxOne = Box::factory()->create(['name' => 'Can you find me']);
    Box::factory()->create();
    $boxTwo = Box::factory()->create(['description' => 'Did you find me?']);

    $searchWord = 'find';
    $response = $this->getJson("{$this->getBaseBoxUrl()}?search={$searchWord}");

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            [
                'id' => $boxOne->id,
                'name' => $boxOne->name,
                'description' => $boxOne->description,
            ],
            [
                'id' => $boxTwo->id,
                'name' => $boxTwo->name,
                'description' => $boxTwo->description,
            ]
        ]);

        expect(count($response->json()))->toBe(2);
});

it('can create a box', function() {
    $newBox = Box::factory()->make();

    $response = $this->postJson($this->getBaseBoxUrl(), $newBox->toArray());
    
    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'name' => $newBox->name,
            'description' => $newBox->description,
            'location' => $newBox->location,
        ]);
            
    expect(Box::count())->toBe(1);
});

it('validates when creating a new box', function ($field, $value) {
    $newBox = Box::factory()->make([$field => $value]);

    $response = $this->postJson($this->getBaseBoxUrl(), $newBox->toArray());

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonStructure([
            'message',
            'errors' => [
                $field
            ]
        ]);
})->with([
    'name cannot be null' => ['name', null],
    'name must be a string' => ['name', 1234],
    'description cannot be null' => ['description', null],
    'description must be a string' => ['description', 1234],
    'location cannot be null' => ['location', null],
    'location must be a string' => ['location', 1234],
]);

it('can update a box', function() {
    Box::factory()->create();
    $box = Box::factory()->create();

    expect (Box::count())->toBe(2);

    $newBoxData = [
        'name' => 'New name',
        'description' => 'New description',
        'location' => 'New location',
    ];

    $response = $this->patchJson("{$this->getBaseBoxUrl()}/{$box->id}", $newBoxData);

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'id' => $box->id,
            'name' => $newBoxData['name'],
            'description' => $newBoxData['description'],
            'location' => $newBoxData['location'],
        ]);

    expect (Box::count())->toBe(2);
});

it('validates when updating a box', function ($field, $value) {
    $box = Box::factory()->create();

    $response = $this->patchJson("{$this->getBaseBoxUrl()}/{$box->id}", [$field => $value]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonStructure([
            'message',
            'errors' => [
                $field
            ]
        ]);
})->with([
    'name cannot be null' => ['name', null],
    'name must be a string' => ['name', 1234],
    'description cannot be null' => ['description', null],
    'description must be a string' => ['description', 1234],
    'location cannot be null' => ['location', null],
    'location must be a string' => ['location', 1234],
]);

it('throws an error when updating a box that does not exist', function() {
    Box::factory()->count(2)->create();
    
    $nonExistentId = 3;
    $newBoxData = [
        'name' => 'New name'
    ];

    $response = $this->patchJson("{$this->getBaseBoxUrl()}/{$nonExistentId}", $newBoxData);

    $response->assertStatus(Response::HTTP_NOT_FOUND);
});

it('can delete a box', function() {
    Box::factory()->create();
    $boxToDelete = Box::factory()->create();

    expect(Box::count())->toBe(2);

    $response = $this->deleteJson("{$this->getBaseBoxUrl()}/{$boxToDelete->id}");

    $response->assertStatus(Response::HTTP_NO_CONTENT);

    expect(Box::count())->toBe(1);
});

it('throws an error when deleting a box that does not exist', function() {
    Box::factory()->count(2)->create();
    
    $nonExistentId = 3;

    $response = $this->deleteJson("{$this->getBaseBoxUrl()}/{$nonExistentId}");

    $response->assertStatus(Response::HTTP_NOT_FOUND);
});