@extends('layout.app')

@section('title', 'Settings')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Settings</h6>
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
                        <form action="{{ route('settings.update', $setting->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="printer">Printer</label>
                                <input type="text" class="form-control @error('printer') is-invalid @enderror"
                                    name="printer" id="printer" placeholder="Printer"
                                    value="{{ old('printer', $setting->printer) }}">
                                @error('printer')
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
