<?php

namespace App\Http\Controllers;

use App\Models\NameProductUserAlias;
use App\Http\Requests\StoreNameProductUserAliasRequest;
use App\Http\Requests\UpdateNameProductUserAliasRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NameProductUserAliasController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    private function updateAliasListFromUserId(int $userId)
    {
        \App\Models\User::findOrFail($userId);
        foreach(Product::all() as $item) {
            NameProductUserAlias::updateOrCreate([
                'user_id' => $userId,
                'product_id' => $item->id,
            ], [
                'user_id' => Auth::id(),
                'product_id' => $item->id,
                'name' => $item->name
            ]);
        }

    }

    public function updateAliasThisUser()
    {
        $this->updateAliasListFromUserId(Auth::id());
        return redirect()->back()->withStatus("Оновлено аліаси продуктів для мене");

    }

    public function updateAliasUser(Request $request)
    {
        $request->validate([
            'userId' => 'required|numeric|exists:users,id',
        ]);
        $this->updateAliasListFromUserId((int)$request->userId);
        return redirect()->back()->withStatus("Оновлено аліаси продуктів для мене");

    }

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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNameProductUserAliasRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(NameProductUserAlias $nameProductUserAlias)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NameProductUserAlias $nameProductUserAlias)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNameProductUserAliasRequest $request, NameProductUserAlias $nameProductUserAlias)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NameProductUserAlias $nameProductUserAlias)
    {
        //
    }
}
