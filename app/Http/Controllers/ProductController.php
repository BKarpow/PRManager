<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Rules\Ean13;
use App\Models\ImageProduct;

class ProductController extends Controller
{
    private function getToApi(int $code)
    {
        $res = Product::where('barcode', $code)->first();
        if ($res !== null) {
            return [
                'status_code' => 200,
                'isDB' => true,
                'json_response' => ['name' => $res->name, 'id' => $res->id, 'comment' => $res->comment], // якщо очікується JSON
            ];
        }
        $url = "https://world.openfoodfacts.org/api/v2/product/{$code}.json";
        $response = Http::withHeaders([
            'User-Agent' => 'LaravelApp - Version 1.0 - ' . config('app.url')
        ])->timeout(5)->get($url);

        // Перевіряємо, чи сервер взагалі відповів успішно (код 200)
        if ($response->failed()) {
            return [
                'status_code' => 502,
                'response' => "",
                'isDB' => false,
                'json_response' => ['name' => ''], // якщо очікується JSON
            ];
        }
        $data = $response->json();
        if (isset($data['status']) && $data['status'] === 1) {
            $product = $data['product'];
            $productName = $product['product_name_uk']
                ?? $product['product_name']
                ?? '';

            $p = new Product();
            $p->barcode = $code;
            $p->name = $productName;
            $p->save();

            return [
                'status_code' => $response->status(),
                'response' => $response->body(),
                'isDB' => false,
                'json_response' => ['name' => $productName], // якщо очікується JSON
            ];
        } else {
            return [
                'status_code' => 205,
                'response' => "",
                'isDB' => false,
                'json_response' => ['name' => ''], // якщо очікується JSON
            ];
        }
    }

    public function getProductName(Request $request)
    {
        $d = $request->validate([
            'barcode' => ['required', new Ean13]
        ]);
        return response()->json($this->getToApi((int) $d['barcode']));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('product.index', [
            'data' => Product::orderBy('name', 'asc')->paginate(50),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.create');
    }

    private function stringToBoolConv(string $val): bool
    {
        if ($val === "true") return true;
        elseif ($val === "false") return false;
        else return false;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        // dd($request->all());
        if ($this->stringToBoolConv($request->isExistsProduct)) {
            $p = Product::findOrFail((int)$request->idExistsProduct);
            if (!$p) abort(404);
        } else {
            $p = new Product();
            $p->barcode = $request->barcode;
        }
        $p->name = $request->name;
        $p->description = $request->comment ?? '';
        $p->save();
        if ($this->stringToBoolConv($request->isExistsImage)) {
            $request->validate([
                'imageName' => 'required|string|max:250|min:7',
            ]);
            $i = new ImageProduct();
            $i->product_id = $p->id;
            $i->path = $request->imageName;
            $i->save();
        }
        return redirect()->route('product.index')->withStatus("Product created!");
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('product.edit', ['item' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->name = $request->name;
        $product->save();
        return redirect()->route('product.index')->withStatus("Продукт оновлено!{$product->id}: {$product->name}");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
