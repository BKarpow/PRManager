<?php

namespace App\Http\Controllers;

use App\Models\GroupProduct;
use App\Models\Shop;
use App\Http\Requests\StoreGroupProductRequest;
use App\Http\Requests\UpdateGroupProductRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\GroupResource;

class GroupProductController extends Controller
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
        return view('group.index', [
            'data' => Auth::user()->defaultShop()->groups()->orderBy('name', 'asc')->paginate(25),
            'shop' => Auth::user()->defaultShop(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('group.create', [
            'shops' => Shop::select('id', 'name')->orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGroupProductRequest $request)
    {
        $this->authorize('create', GroupProduct::class);
        $gp = new GroupProduct();
        $gp->user_id = Auth::user()->id;
        $gp->shop_id = $request->shop;
        $gp->name = $request->name;
        $gp->comment = $request->comment;
        $gp->save();
        return redirect()->back()->withStatus('Створено групу товарів');
    }

    /**
     * Display the specified resource.
     */
    public function show(GroupProduct $groupProduct)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GroupProduct $groupProduct)
    {

        return view('group.edit', [
            'item' => $groupProduct,
            'shops' => Shop::select('id', 'name')->orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGroupProductRequest $request, GroupProduct $groupProduct)
    {

        $groupProduct->name = $request->name;
        $groupProduct->comment = $request->comment;
        $groupProduct->save();
        return redirect()->route('group.index')->withStatus('Оновлено відділ!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GroupProduct $groupProduct)
    {
        $this->authorize('delete', $groupProduct);
        $groupProduct->delete();
        return redirect()->back()->withStatus('Видалено групу');
    }

    public function getGroups()
    {
        return GroupResource::collection(
            Auth::user()->defaultShop()->groups()->get()
        );
    }
}
