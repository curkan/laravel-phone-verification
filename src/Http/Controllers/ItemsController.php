<?php

namespace Gogain\LaravelPhoneVerification\Http\Controllers;

use Gogain\LaravelPhoneVerification\Models\Item;

class ItemsController
{
    public function index()
    {
        $items = Item::select(['name'])->get();

        return response()->json([
            'items' => $items,
        ]);
    }
}
