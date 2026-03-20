<?php

namespace App\Http\Controllers;

use App\Models\ImageProduct;
use App\Models\Product;
use App\Http\Requests\StoreImageProductRequest;
use App\Http\Requests\UpdateImageProductRequest;
use App\Rules\Ean13;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageProductController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreImageProductRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ImageProduct $imageProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ImageProduct $imageProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateImageProductRequest $request, ImageProduct $imageProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ImageProduct $imageProduct)
    {
        //
    }


    public function isExistsImage(Request $request)
    {
        $request->validate(
            [
                'barcode' => ['required', new Ean13]
            ]
        );
        $product = Product::whereBarcode($request->barcode)->first();
        return response()->json([
            'existsImage' => (bool)$product,
            'urlImage' => (!$product) ? asset('storage/products/no-image.png') : $product->mainImg()
        ]);

    }


    public function storeApi(Request $request)
    {
        // Валідація
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
            'product_id' => 'required|numeric'
        ]);

        try {


            // Отримуємо файл
            $image = $request->file('image');

            // Генеруємо унікальне ім'я файлу
            $fileName = 'product_' . $request->product_id . '.' . $image->getClientOriginalExtension();

            // Зберігаємо оригінальне зображення
            $imagePath = $image->storeAs('products', $fileName, 'public');

            // Опціонально: створюємо thumbnail

            $path = $image->storeAs('products', $fileName, 'public');
            // Повертаємо відповідь
            return response()->json([
                'success' => true,
                'id' => $request->product_id,
                'url' => '/storage/' . $path,
                'file_name' => $fileName,
                'message' => 'Фото успішно завантажено'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Помилка завантаження фото: ' . $e->getMessage()
            ], 500);
        }
    }


}
