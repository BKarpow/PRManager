<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use App\Http\Resources\InventoryResource;
use Illuminate\Http\Request;
use App\Models\InventoryWightProduct;
use App\Http\Resources\InventoryWProductResource;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('inventory.create');
    }

    public function storeInventoryItem(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|numeric|exists:inventories,id',
            'wproduct_id' => 'required|numeric|exists:wight_products,id',
            'value' => 'required|string',
        ]);
        $v = trim($request->value);
        $v = str_replace(',', '.', $v);
        $v = (float)$v;
        $i = InventoryWightProduct::where([
            ['inventory_id', '=', $request->inventory_id],
            ['wproduct_id', '=', $request->wproduct_id]
        ])->first();
        if ($i) {
            $i->value = (string)round((round((float)$i->value, 1) + round($v, 1)), 1);
            $i->save();
        } else {
            $i = new InventoryWightProduct();
            $i->inventory_id = $request->inventory_id;
            $i->wproduct_id = $request->wproduct_id;
            $i->user_id = $request->user()->id;
            $i->value = $request->value;
            $i->save();
        }
        return new InventoryWProductResource($i);
    }

    public function getInventoryItems(Request $request) {
        $request->validate([
            'inventory_id' => 'required|numeric|exists:inventories,id',
        ]);
        return InventoryWProductResource::collection(
            InventoryWightProduct::where("inventory_id", $request->inventory_id)->get()
        );

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInventoryRequest $request)
    {
        $i = new Inventory();
        $i->name = $request->name;
        $i->save();
        return new InventoryResource($i);
    }

    public function getLastInventory()
    {
        return InventoryResource::collection(
            Inventory::orderBy('created_at', 'asc')->limit(25)->get()
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInventoryRequest $request, Inventory $inventory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        //
    }
}
