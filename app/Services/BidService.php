<?php

namespace App\Services;

use App\Http\Requests\BidStoreRequest;
use App\Http\Resources\BidResource;
use App\Models\Bid;
use Illuminate\Support\Facades\DB;

class BidService
{
    // As long as it's a bidding system, probably the best approach to use Queues
    // also it might be a good idea to create a unique index for the bids table on item_id and bid_amount.
    // At this implementation validation should not allow the same/less bid amounts to proceed by sending DB query
    // checking that the bid amount is higher than min. price of the item, and is higher than existing bids for that item.

    public function handleBidStore(BidStoreRequest $request)
    {
        $bid = Bid::create($request->all());

        return new BidResource($bid);
    }

    // used in BidStoreRequest for custom rule ValidBidRule
    public function isValidBidAmount($bidAmount, $itemId)
    {
        return DB::table('items')
            ->leftJoin('bids', function ($join)  {
                $join->on('items.id', '=', 'bids.item_id');
            })
            ->where([
                ['items.id', '=', $itemId]
            ])
            ->where(function ($query) use ($bidAmount) {  // all cases that are not valid to make a bid
                $query->where('bids.bid_amount', '>=', $bidAmount)
                    ->orWhere('items.min_price', '>=', $bidAmount)
                    ->orWhereNotNull('items.deleted_at');
            })
            ->doesntExist();
    }
}
