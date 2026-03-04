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

class DateProductController extends Controller
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
        $products = Auth::user()->expProductsAll();
        $exps = Auth::user()->beforeExpProductsAll();
        return view('exp.index', [
            'data' => $products,
            'exps' => $exps
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

    private function formatDateToDb(string $d) : string
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

        return redirect()->route('date.index')->withStatus('Додано термін: '.$d->product->name);
    }

    /**
     * Display the specified resource.
     */
    public function show(DateProduct $dateProduct)
    {
        return view('exp.show', [
            't' => DateProduct::select('*')->selectRaw('DATEDIFF(end, CURDATE()) as days_remaining')
            ->whereId($dateProduct->id)->first(),
            'products' => DateProduct::select('*')->selectRaw('DATEDIFF(end, CURDATE()) as days_remaining')

            ->where([
                ['user_id', '=', $dateProduct->user_id],
                ['product_id', '=', $dateProduct->product_id],
                ['end', '>=', now()->format('Y-m-d')],
                ['id', '!=', $dateProduct->id]
            ])
            ->get()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DateProduct $dateProduct)
    {
        $this->authorize('update', DateProduct::class);
        return view('exp.edit' , ['item' => $dateProduct]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDateProductRequest $request, DateProduct $dateProduct)
    {
        // dd($dateProduct);
        $this->authorize('update', DateProduct::class);
    $dateProduct->end = $request->end;
    $dateProduct->comment = $request->comment ?? '';
        $dateProduct->save();
        return redirect()->route('index')->withStatus('Оновлено термін');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DateProduct $dateProduct)
    {
        $this->authorize('delete', DateProduct::class);
        $dateProduct->delete();
        return redirect()->route('home')->withStatus("Продукт видалено.");
    }

    public function dateExists(Request $request)
    {
        $request->validate([
            'product_id' => 'required|numeric|exists:products,id',
            'group_id' => 'required|numeric|exists:group_products,id',
            'end' => 'required|date'
        ]);
        $d = DateProduct::where([
            ['product_id', '=', $request->product_id],
            ['group_id', '=', $request->group_id],
            ['end', '=', $request->end],
        ])->first();
        $exists = (bool)$d;
        return response()->json([
            'exists' => $exists,
            'dateProductId' => ($exists) ? $d->id : 0,
            'userNameCreateor' => $d->user->name,
            'userIdCreateor' => $d->user->id,
            'updatedAt' => $d->updated_at->format('d.m.Y')
        ]);
    }
}
