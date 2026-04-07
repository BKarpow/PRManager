<?php

namespace App\Http\Controllers;

use App\Models\WightProduct;
use Illuminate\Http\Request;
use App\Http\Requests\StoreWightProductRequest;
use App\Http\Requests\UpdateWightProductRequest;
use App\Http\Resources\WproductResource;

class WightProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('wproduct.index', [
            'data' => WightProduct::orderBy('name', 'asc')->paginate(50)
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function import()
    {
        return view('wproduct.import');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'js' => 'required|json',
        ]);

        $data = json_decode($request->js, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'status' => 'error',
                'message' => 'Некоректний формат JSON: ' . json_last_error_msg()
            ], 422);
        }
        $i = 0;
        foreach($data as $item) {
            WightProduct::updateOrCreate([
                'name' => $item['name']
            ],[
                'name' => $item['name'],
                'barcode' => $item['barcode'],
                'plu'  ?? null=> $item['plu'] ?? null,
            ]);
            $i++;
        }
        return redirect()->route('admin.index')->withStatus('Імпортовано '.$i.' вагових продуктів.');
    }

    public function getList()
    {
        return WproductResource::collection(
            WightProduct::orderBy('name', 'asc')->get()
        );
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
    public function store(StoreWightProductRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(WightProduct $wightProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WightProduct $wightProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWightProductRequest $request, WightProduct $wightProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WightProduct $wightProduct)
    {
        //
    }

    public function getResourceAllWproducts()
    {
        return WproductResource::collection(
            WightProduct::orderBy('name', 'asc')->get()
        );
    }
}
