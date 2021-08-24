<?php

// Controller for making a bid for the item

namespace App\Http\Controllers;

use App\Services\BidService;
use App\Http\Requests\BidStoreRequest;

class BidStoreController extends Controller
{
    protected $bidService = NULL;

    public function __construct(BidService $bidService)
    {
        $this->bidService = $bidService;
    }

    public function __invoke(BidStoreRequest $request)
    {
        return $this->bidService->handleBidStore($request);
    }
}
