<?php

namespace Tests\Unit\Models;

use App\Models\Bid;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Tests\TestCase;

class BidStoreTest extends TestCase
{
    public function test_it_requires_user_id()
    {
        $this->json('POST', 'api/bids')
            ->assertJsonValidationErrors('user_id');
    }

    public function test_it_requires_higher_bid_amount()
    {
        $user = factory(User::class)->create();
        $category = factory(Category::class)->create();

        $category->items()->save(
            $item = factory(Item::class)->create([
                'category_id' => $category->id
            ])
        );

        factory(Bid::class)->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'bid_amount' => $bidAmount = 100,
        ]);

        $user2 = factory(User::class)->create();

        $this->json('POST', 'api/bids', [
            'item_id' => $item->id,
            'user_id' => $user2->id,
            'bid_amount' => $bidAmount - 20,
        ])
            ->assertJsonValidationErrors('item_id');
    }

    public function test_it_requires_higher_than_min_price()
    {
        $user = factory(User::class)->create();
        $category = factory(Category::class)->create();

        $category->items()->save(
            $item = factory(Item::class)->create([
                'category_id' => $category->id
            ])
        );

        $this->json('POST', 'api/bids', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'bid_amount' => $item->min_price - 20,
        ])
            ->assertJsonValidationErrors('item_id');
    }

    public function test_it_creates_bid()
    {
        $user = factory(User::class)->create();
        $category = factory(Category::class)->create();

        $category->items()->save(
            $item = factory(Item::class)->create([
                'category_id' => $category->id
            ])
        );

        $this->json('POST', 'api/bids', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'bid_amount' => $item->min_price + 1,
        ]);

        $this->assertDatabaseHas('bids', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'bid_amount' => $item->min_price + 1,
        ]);
    }

    public function test_it_returns_bid()
    {
        $user = factory(User::class)->create();
        $category = factory(Category::class)->create();

        $category->items()->save(
            $item = factory(Item::class)->create([
                'category_id' => $category->id
            ])
        );

        $this->json('POST', 'api/bids', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'bid_amount' => $item->min_price + 1,
        ])->assertJsonFragment([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'bid_amount' => $item->min_price + 1,
        ]);
    }
}
