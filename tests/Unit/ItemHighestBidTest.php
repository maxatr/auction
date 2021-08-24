<?php

namespace Tests\Unit;

use App\Models\Bid;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Tests\TestCase;

class ItemHighestBidTest extends TestCase
{
    public function test_it_requires_valid_item_id()
    {
        $this->json('GET', 'api/items/12')
            ->assertJsonValidationErrors('id');
    }

    public function test_it_returns_item_highest_bid()
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
            'bid_amount' => $bidAmount = $item->min_price,
        ]);

        $user2 = factory(User::class)->create();

        factory(Bid::class)->create([
            'item_id' => $item->id,
            'user_id' => $user2->id,
            'bid_amount' => $bidAmount2 = $bidAmount + 1,
        ]);
        $this->json('GET', 'api/items/'.$item->id)
            ->assertJsonFragment([
                'item_id' => $item->id,
                'min_price' => $item->min_price,
                'highest_bid_user_id' => $user2->id,
                'highest_bid' => $bidAmount2
            ]);
    }
}
