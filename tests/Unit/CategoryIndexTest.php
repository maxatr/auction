<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Item;
use Tests\TestCase;

class CategoryIndexTest extends TestCase
{
    public function test_it_returns_a_collection_of_categories()
    {
        $category = factory(Category::class)->create();

        $this->json('GET', 'api/categories')
            ->assertJsonFragment([
                'id' => $category->id, 'name' => $category->name
            ]);
    }

    public function test_it_returns_empty_result()
    {
        $this->json('GET', 'api/categories')->assertJsonFragment([
            'data' => []
        ]);
    }

    public function test_it_returns_a_collection_of_ordered_categories()
    {
        $category = factory(Category::class)->create(['name' => 'One']);
        $category2 = factory(Category::class)->create(['name' => 'Two']);
        $category3 = factory(Category::class)->create(['name' => 'Three']);

        $this->json('GET', 'api/categories')
            ->assertSeeInOrder([
                $category->name, $category3->name, $category2->name
            ]);
    }

    public function test_it_has_many_items()
    {
        $category = factory(Category::class)->create();

        $category->items()->save(
            factory(Item::class)->create([
                'category_id' => $category->id
            ])
        );

        $this->assertInstanceOf(Item::class, $category->items->first());
    }

    public function test_it_returns_a_collection_of_ordered_items()
    {
        $category = factory(Category::class)->create(['name' => 'One']);
        $category->items()->save(
            $item1 = factory(Item::class)->create([
                'min_price' => '90.20',
                'category_id' => $category->id
            ])
        );
        $category->items()->save(
            $item2 = factory(Item::class)->create([
                'min_price' => '80.59',
                'category_id' => $category->id
            ])
        );

        $this->json('GET', 'api/categories')
            ->assertSeeInOrder([
                $item2->id, $item1->id
            ]);
    }
}
