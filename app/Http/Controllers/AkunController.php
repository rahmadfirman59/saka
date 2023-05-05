<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AkunController extends Controller
{
    public function index()
    {
        $akun = DB::table('akun')->get();
        return view('akun.index')
            ->with('akun', $akun);
    }

    public function edit($id)
    {
        return view('akun.edit');
    }
}
