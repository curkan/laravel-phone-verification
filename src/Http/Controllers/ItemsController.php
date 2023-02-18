<?php

namespace Curkan\LaravelPhoneVerification\Http\Controllers;

use Curkan\LaravelPhoneVerification\Models\Item;

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
