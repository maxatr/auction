<?php

// Controller to add an item

namespace App\Http\Controllers;

use App\Http\Requests\ItemStoreRequest;
use App\Http\Resources\ItemResource;
use App\Models\Item;

class ItemStoreController extends Controller
{
    public function __invoke(ItemStoreRequest $request)
    {
        $item = Item::create($request->all());

        return new ItemResource($item);
    }
}
