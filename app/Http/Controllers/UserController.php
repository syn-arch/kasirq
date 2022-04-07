<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|same:konfirmasi_password',
            'konfirmasi_password' => 'required|same:password',
            'role' => 'required',
        ]));

        $input = $request->all();
        $input['password'] = bcrypt($request->password);

        User::create($input);

        return redirect('/users')->with('message', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required',
        ];

        if ($request->password) {
            $rules['password'] = 'required|same:konfirmasi_password';
            $rules['konfirmasi_password'] = 'required|same:password';
        }

        $request->validate($rules);

        $input = $request->all();

        if ($request->password) {
            $input['password'] = bcrypt($request->password);
        } else {
            unset($input['password']);
        }

        $user->update($input);

        return redirect('/users')->with('message', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect('/users')->with('message', 'Data berhasil dihapus');
    }
}
