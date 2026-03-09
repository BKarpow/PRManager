<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ConfigsUser;
use App\Http\Resources\ConfigResource;


class ConfigController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    function showConfigPage()
    {
        return view('config', [
            'configShop' => Auth::user()->configDefaultShop(),
            'configGroup' => Auth::user()->configDefaultGroup(),
            'groups' => (Auth::user()->defaultShop()) ? Auth::user()->defaultShop()->groups()->get() : [],
            'shops' => Shop::orderBy('name', 'asc')->get(),
        ]);
    }

    function saveConfig(Request $request)
    {
        $request->validate([
            'shop' => 'required|numeric|exists:shops,id',
            'daysex' => 'required|numeric|max:15|min:1',
            // 'group' => 'required|numeric|exists:group_products,id'
        ]);
        ConfigsUser::updateOrCreate(['user_id'=>Auth::id(),'key'=>User::CONF_KEY_SHOP], ['value'=>$request->shop]);
        ConfigsUser::updateOrCreate(['user_id'=>Auth::id(),'key'=>User::CONF_KEY_GROUP], ['value'=>$request->group ?? 0]);
        ConfigsUser::updateOrCreate(['user_id'=>Auth::id(),'key'=>User::CONF_KEY_EXPS_DAYS],
         ['value'=>$request->daysex ?? 0]);

        return redirect()->route('home')->withStatus('Налаштування збережені!');
    }

    public function configInfo()
    {
        Auth::check() || abort(403);
        return new ConfigResource(Auth::user());
    }
}
