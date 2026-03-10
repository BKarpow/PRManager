<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Http\Resources\ShopResource;
use Illuminate\Http\Request;
use App\Http\Requests\StoreShopRequest;
use App\Http\Requests\UpdateShopRequest;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        return view('shops.index', [
            'data' => Shop::orderBy('created_at', 'asc')->paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Shop::class);
        return view('shops.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShopRequest $request)
    {

        $this->authorize('create', Shop::class);
        $s = new Shop();
        $s->name = $request->name;
        $s->address = $request->address ?? '';
        $s->comment = $request->comment ?? '';
        $s->save();
        Auth::user()->shops()->attach([$s->id]);
        return redirect()->route('shop.index')->withStatus("Додано магазин");
    }

    /**
     * Display the specified resource.
     */
    public function show(Shop $shop)
    {
        return view('group.inshop', [
            'shop' => $shop,
            'data' => $shop->groups()->orderBy('name', 'asc')->paginate(25)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shop $shop)
    {
        $this->authorize('update', $shop);
        return view('shops.edit', ['shop' => $shop]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShopRequest $request, Shop $shop)
    {
       $this->authorize('update', $shop);
       $shop->name = $request->name;
        $shop->address = $request->address ?? '';
        $shop->comment = $request->comment ?? '';
        $shop->save();
        return redirect()->route('shop.index')->withStatus("Оновлено магазин");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop)
    {
        $this->authorize('delete', $shop);
        Auth::user()->shops()->detach($shop->id);
        $shop->delete();
        return redirect()->route('shop.index')->withStatus("Магазин видалено");
    }

    public function getShopsApi(Request $request)
    {
        return ShopResource::collection(Shop::orderBy('name', 'asc')->get());
    }
}
