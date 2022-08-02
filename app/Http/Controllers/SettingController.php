<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setting = Setting::first();

        return view('setting.index', compact('setting'));
    }

    public function update(Request $request, Setting $setting)
    {
        $request->validate([
            'printer' => 'required',
        ]);

        $setting->update($request->all());

        return redirect()->route('settings.index');
    }
}
