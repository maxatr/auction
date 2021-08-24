<?php

// Controller for getting an item by id that includes the price and user id of the highest bid

namespace App\Http\Controllers;

use App\Http\Requests\ItemHighestBidRequest;
use App\Models\Item;

class ItemHighestBidController extends Controller
{
    public function __invoke(ItemHighestBidRequest $request, int $itemId)
    {
        $result = NULL;

        $item = Item::with(['bids' => function ($query) {
            $query->orderBy('bid_amount', 'desc')
                ->orderBy('id', 'asc'); // sort by creation order
        }])->find($itemId);

        $highestBid = NULL;
        if ($item->bids) {
            $highestBid = $item->bids->first();
        }

        $result = [
            'item_id' => $item->id,
            'item_name' => $item->name,
            'min_price' => $item->min_price,
            'highest_bid' => $highestBid->bid_amount??NULL,
            'highest_bid_user_id' => $highestBid->user_id??NULL,
        ];

        return response()->json($result, 200, [], JSON_NUMERIC_CHECK);
    }
}
