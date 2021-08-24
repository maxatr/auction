<?php

Route::get('/categories', 'CategoryIndexController');
Route::get('/items/{id}', 'ItemHighestBidController');

Route::post('/items', 'ItemStoreController');
Route::post('/bids', 'BidStoreController');
