@extends('layout.app')

@section('title', 'Outlets')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Outlets</h6>
            </div>
            <div class="card-body">
                @if ($message = Session::get('message'))
                <div class="alert alert-success mt-4">
                    <strong>Berhasil</strong>
                    <p>{{$message}}</p>
                </div>
                @endif
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <form action="{{ route('outlets.update', $outlet->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                    id="name" placeholder="Name" value="{{ old('name', $outlet->name) }}">
                                @error('name')
                                <small style="color:red">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    name="phone" id="phone" placeholder="Phone"
                                    value="{{ old('phone', $outlet->phone) }}">
                                @error('phone')
                                <small style="color:red">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control @error('email') is-invalid @enderror"
                                    name="email" id="email" placeholder="Email"
                                    value="{{ old('email', $outlet->email) }}">
                                @error('email')
                                <small style="color:red">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror"
                                    name="address" id="address" placeholder="Address"
                                    value="{{ old('address', $outlet->address) }}">
                                @error('address')
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
