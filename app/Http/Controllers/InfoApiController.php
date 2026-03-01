<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Http\Resources\ShopResource;
use App\Models\GroupProduct;
use App\Http\Resources\GroupResource;


class InfoApiController extends Controller
{
    public function getShops()
    {
        return ShopResource::collection(
            Shop::orderBy('name', 'asc')->get()
        );
    }

    public function getGroupsFromShop(Request $request)
    {
        $request->validate([
            'shopid' => 'required|numeric|exists:shops,id'
        ]);
        $shop = Shop::findOrFail($request->shopid);
        return GroupResource::collection(
            $shop->groups()->orderBy('name', 'asc')->get()
        );
    }
}
