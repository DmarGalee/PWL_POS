@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('barang/create') }}">Tambah</a>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="filter_kategori">Filter Kategori:</label>
                <select id="filter_kategori" class="form-control">
                    <option value="">- Semua Kategori -</option>
                    @foreach($kategori as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <table class="table table-bordered table-striped table-hover table-sm" id="table_barang">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Barang</th>
                    <th>Deskripsi Barang</th>
                    <th>Harga Barang</th>
                    <th>Nama Kategori</th>  <th>Nama Supplier</th>  <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection
@push('css')
@endpush
@push('js')
<script>
    $(document).ready(function() {
    var dataBarang = $('#table_barang').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: "{{ url('barang/list') }}",
            type: "POST",
            data: function(d) {
                d.id_kategori = $('#filter_kategori').val();
            }
        },
        columns: [
            { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
            { data: "nama_barang", orderable: true, searchable: true },
            { data: "deskripsi_barang", orderable: true, searchable: true },
            { data: "harga_barang", orderable: true, searchable: true },
            { data: "nama_kategori", orderable: true, searchable: true },
            { data: "nama_supplier", orderable: true, searchable: true },
            { data: "aksi", orderable: false, searchable: false }
        ]
    });

    // Event listener untuk filter
    $('#filter_kategori').on('change', function() {
        dataBarang.ajax.reload();
    });
    });
</script>
@endpush