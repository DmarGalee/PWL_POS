@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body">
        @empty($barang)
        <div class="alert alert-danger alert-dismissible">
            <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
            Data yang Anda cari tidak ditemukan.
        </div>
        @else
        <table class="table table-bordered table-striped table-hover table-sm">
            <tr>
                <th>ID</th>
                <td>{{ $barang->id }}</td>
            </tr>
            <tr>
                <th>Nama Barang</th>
                <td>{{ $barang->nama_barang }}</td>
            </tr>
            <tr>
                <th>Deskripsi Barang</th>
                <td>{{ $barang->deskripsi_barang }}</td>
            </tr>
            <tr>
                <th>Harga Barang</th>
                <td>{{ $barang->harga_barang }}</td>
            </tr>
            <tr>
                <th>ID Kategori</th>
                <td>{{ $barang->id_kategori }}</td>
            </tr>
            <tr>
                <th>ID Supplier</th>
                <td>{{ $barang->id_supplier }}</td>
            </tr>
        </table>
        @endempty
        <a href="{{ url('barang') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
    </div>
</div>
@endsection
@push('css')
@endpush
@push('js')
@endpush