<?php

namespace App\Http\Controllers;

use App\Models\DateProduct;
use App\Http\Requests\StoreDateProductRequest;
use App\Http\Requests\UpdateDateProductRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ConfigsUser;
use App\Models\Product;
use App\Models\GroupProduct;
use App\Rules\Ean13;
use App\Services\BarcodeHandle;
use App\Http\Resources\SearchForBarcodeResource;

class DateProductController extends Controller
{
    use BarcodeHandle;

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Auth::user()->expProductsAll();
        $exps = Auth::user()->beforeExpProductsAll();
        return view('exp.index', [
            'data' => $products,
            'exps' => $exps
        ]);
    }

    public function userExps(User $user, Request $request)
    {
        $products = $user->expProductsAll();
        $exps = $user->beforeExpProductsAll();
        return view('exp.userExps', [
            'data' => $products,
            'exps' => $exps,
            'user' => $user
        ]);
    }

    public function expired()
    {

        return view('exp.expired', [
            'data' => Auth::user()->beforeExpProductsAll()
        ]);
    }

    public function expiredGroup(GroupProduct $group)
    {

        $products = DateProduct::query()
            ->select('*')
            ->selectRaw('DATEDIFF(end, CURDATE()) as days_remaining')
            ->orderBy('days_remaining', 'desc')
            ->where('group_id', $group->id)
            ->having('days_remaining', '<', 0)
            ->paginate(100);
        return view('exp.expired', [
            'data' => $products,
            'group' => $group
        ]);
    }

    public function indexGroup(GroupProduct $group)
    {

        $products = DateProduct::query()
            ->select('*')
            ->selectRaw('DATEDIFF(end, CURDATE()) as days_remaining')
            ->orderBy('days_remaining', 'asc')
            ->where('group_id', $group->id)
            ->having('days_remaining', '>=', 0)
            ->paginate(100);

        $exps = DateProduct::query()
            ->select('*')
            ->selectRaw('DATEDIFF(end, CURDATE()) as days_remaining')
            ->orderBy('days_remaining', 'desc')
            ->where('group_id', $group->id)
            ->having('days_remaining', '<', 0)
            ->get();

        return view('exp.groupExp', [
            'data' => $products,
            'group' => $group,
            'exps' => $exps
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('exp.create', [

            'configGroup' => Auth::user()->configDefaultGroup(),
            'groups' => (Auth::user()->defaultShop()) ? Auth::user()->defaultShop()->groups()->get() : [],

        ]);
    }

    private function formatDateToDb(string $d): string
    {
        return date('Y.m.d', strtotime($d . "00:00:00"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDateProductRequest $request)
    {
        $p = Product::whereBarcode($request->barcode)->first();
        if (!$p) {
            $p = new Product();
            $p->name = $request->name;
            $p->barcode = $request->barcode;
            $p->description = $request->comment ?? "";
            $p->save();
        }
        if ($request->isEditProductInfo == "true") {
            $p->name = $request->name;
            $p->description = $request->comment ?? "";
            $p->save();
        }
        $d = new DateProduct();
        $d->group_id = $request->group;
        $d->product_id = $p->id;
        $d->user_id = Auth::id();
        $d->start = $request->start;
        $d->end = $request->end;
        $d->count = $request->count ?? 1;
        $d->save();

        return redirect()->route('date.index')->withStatus('Додано термін: ' . $d->product->name);
    }

    /**
     * Display the specified resource.
     */
    public function show(DateProduct $dateProduct)
    {
        // dd($this->saveBarcodeToFile($dateProduct->product->barcode));
        return view('exp.show', [
            't' => DateProduct::select('*')->selectRaw('DATEDIFF(end, CURDATE()) as days_remaining')
                ->whereId($dateProduct->id)->first(),
            'products' => DateProduct::select('*')->selectRaw('DATEDIFF(end, CURDATE()) as days_remaining')

                ->where([

                    ['product_id', '=', $dateProduct->product_id],
                    ['end', '>=', now()->format('Y-m-d')],
                    ['id', '!=', $dateProduct->id]
                ])
                ->get(),
            'pathSvg' => $this->saveBarcodeToFile($dateProduct->product->barcode)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DateProduct $dateProduct)
    {
        $this->authorize('update', $dateProduct);
        return view(
            'exp.edit',
            [
                'item' => $dateProduct,
                'groups' => Auth::user()->defaultShop()->groups()->get()
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDateProductRequest $request, DateProduct $dateProduct)
    {

        $this->authorize('update', $dateProduct);
        $dateProduct->end = $request->end;
        $dateProduct->group_id = $request->group;
        $dateProduct->save();
        return redirect()->route('index')->withStatus('Оновлено термін');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DateProduct $dateProduct)
    {
        $this->authorize('delete', $dateProduct);
        $dateProduct->delete();
        return redirect()->route('home')->withStatus("Термін видалено.");
    }

    public function delImg(DateProduct $dateProduct)
    {
        $this->authorize('delete', $dateProduct);
        $res = $dateProduct->product->deleteMainImage();
        return redirect()->back()->withStatus("Видалено збраження товару!");
    }

    public function dateExists(Request $request)
    {
        $request->validate([
            'product_id' => 'required|numeric|exists:products,id',
            'group_id' => 'required|numeric|exists:group_products,id',
            'end' => 'required|date'
        ]);
        $de = date('Y-m-d', strtotime($request->end . " 00:00:00"));

        $d = DateProduct::where([
            ['product_id', '=', $request->product_id],
            ['group_id', '=', $request->group_id],
            ['end', '=', $de],
        ])->first();
        $exists = (bool)$d;
        return response()->json([
            'exists' => $exists,
            'dateProductId' => ($exists) ? $d->id : 0,
            'userNameCreateor' => ($exists) ? $d->user->name : 0,
            'userIdCreateor' => ($exists) ?  $d->user->id : 0,
            'updatedAt' => ($exists) ? $d->updated_at->format('d.m.Y') : 0
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $expiries = DateProduct::with('product')
            ->when($search, function ($query, $search) {
                $query->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                });
            })
            ->where('group_id', '=', Auth::user()->configDefaultGroup())
            ->whereRaw('`end` >= CURDATE()')
            ->orderBy('end', 'asc')
            ->paginate(10); // Повертає об'єкт LengthAwarePaginator

        return response()->json($expiries);
    }

    public function searchForBarcode(Request $request)
    {

        $request->validate([
            'barcode' => ['required', 'numeric', new Ean13]
        ]);
        $search = $request->barcode;

        $expiries = DateProduct::with('product')
            ->when($search, function ($query, $search) {
                $query->whereHas('product', function ($q) use ($search) {
                    $q->where('barcode', '=', $search);
                });
            })
            // ->where('group_id', '=', Auth::user()->configDefaultGroup())
            ->whereRaw('`end` >= CURDATE()')
            ->orderBy('end', 'asc')
            ->limit(15)->get(); // Повертає об'єкт LengthAwarePaginator
        return SearchForBarcodeResource::collection($expiries);
    }
}
