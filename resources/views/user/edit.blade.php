@extends('layout.app')

@section('title', 'Data User')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Tambah User</h6>
            </div>
            <div class="card-body">
                 <a href="/users" class="btn btn-primary">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <form action="{{ route('users.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">Nama User</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" placeholder="Nama User" value="{{ old('name', $user->name) }}">
                                @error('name')
                                <small style="color:red">{{$message}}</small>
                                @enderror
                            </div>
                             <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="Email" value="{{ old('email', $user->email) }}">
                                @error('email')
                                <small style="color:red">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">Password (Isi untuk mengubah)</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Password">
                                @error('password')
                                <small style="color:red">{{$message}}</small>
                                @enderror
                            </div>
                             <div class="form-group">
                                <label for="konfirmasi_password">Konfirmasi Password</label>
                                <input type="password" class="form-control @error('konfirmasi_password') is-invalid @enderror" name="konfirmasi_password" id="konfirmasi_password" placeholder="Konfirmasi Password">
                                @error('konfirmasi_password')
                                <small style="color:red">{{$message}}</small>
                                @enderror
                            </div>
                             <div class="form-group">
                                <label for="role">Role</label>
                                <select name="role" id="role" class="form-control @error('role') is-invalid @enderror">
                                    <option value="">Pilih Role</option>
                                    <option {{ old('role', $user->role) == 'admin_kasir' ? 'selected' : '' }} value="admin_kasir">admin_kasir</option>
                                    <option {{ old('role', $user->role) == 'kasir' ? 'selected' : '' }} value="kasir">kasir</option>
                                </select>
                                @error('role')
                                <small style="color:red">{{$message}}</small>
                                @enderror
                            </div>
                             <div class="form-group">
                               <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
