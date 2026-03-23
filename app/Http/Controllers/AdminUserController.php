<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\CacheHandleTrait;


class AdminUserController extends Controller
{

    use CacheHandleTrait;

    public function index(Request $request)
    {
        return view('admin.user.index', [
            'data' => User::paginate(50)
        ]);
    }

    public function clearCache(Request $request)
    {
        $request->validate([
            'user' => 'required|numeric'
        ]);
        $this->clearAllCacheUser($request->user);
        return redirect()->route('admin.user.index')->withStatus("Кеш очищено");
    }
}
