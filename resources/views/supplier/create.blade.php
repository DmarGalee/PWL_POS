@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('supplier') }}" class="form-horizontal">
            @csrf
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Nama Supplier</label>
                <div class="col-11">
                    <input type="text" class="form-control" id="nama_supplier" name="nama_supplier" value="{{ old('nama_supplier') }}" required>
                    @error('nama_supplier')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Alamat Supplier</label>
                <div class="col-11">
                    <input type="text" class="form-control" id="alamat_supplier" name="alamat_supplier" value="{{ old('alamat_supplier') }}" required>
                    @error('alamat_supplier')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label">Telepon Supplier</label>
                <div class="col-11">
                    <input type="text" class="form-control" id="telepon_supplier" name="telepon_supplier" value="{{ old('telepon_supplier') }}" required>
                    @error('telepon_supplier')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <label class="col-1 control-label col-form-label"></label>
                <div class="col-11">
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                    <a class="btn btn-sm btn-default ml-1" href="{{ url('supplier') }}">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('css')
@endpush
@push('js')
@endpush