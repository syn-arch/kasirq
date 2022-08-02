<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $outlet = outlet::first();

        return view('outlet.index', compact('outlet'));
    }

    public function update(Request $request, outlet $outlet)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        $outlet->update($request->all());

        return redirect('/outlets')->with('message', 'Data berhasil diubah');
    }
}
