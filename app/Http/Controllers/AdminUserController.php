<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.user.index', [
            'data' => User::paginate(50)
        ]);
    }
}
