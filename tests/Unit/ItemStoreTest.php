<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use Tests\TestCase;

class ItemStoreTest extends TestCase
{
    public function test_it_requires_a_min_price()
    {
        $this->json('POST', 'api/items')
            ->assertJsonValidationErrors('min_price');
    }

    public function test_it_creates_an_item()
    {
        $category = factory(Category::class)->create();

        $this->json('POST', 'api/items', [
            'name' => $name = 'Item 1',
            'min_price' => $minPrice = 258.23,
            'category_id' => $category->id
        ]);

        $this->assertDatabaseHas('items', [
            'name' => $name,
            'min_price' => $minPrice,
            'category_id' => $category->id,
        ]);
    }

    public function test_it_returns_an_item()
    {
        $category = factory(Category::class)->create();

        $this->json('POST', 'api/items', [
            'name' => $name = 'Item 2',
            'min_price' => $minPrice = 123,
            'category_id' => $category->id
        ])->assertJsonFragment([
            'name' => $name,
            'min_price' => $minPrice,
        ]);
    }
}
