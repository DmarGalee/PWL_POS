@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Barang</h3>
            <div class="card-tools">
                <a href="{{ url('/barang/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Barang</a> 
                <button onclick="modalAction('{{ url('/barang/import') }}')" class="btn btn-info">Import Barang</button>
                <a href="{{ url('/barang/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Barang</a>
                <button onclick="modalAction('{{ url('/barang/create_ajax') }}')" class="btn btn-success">Tambah Data (Ajax)</button>
            </div>
        </div>
        <div class="card-body">
            <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-sm row text-sm mb-0">
                            <label for="filter_date" class="col-md-1 col-form-label">Filter</label>
                            <div class="col-md-3">
                                <select name="filter_kategori" class="form-control form-control-sm filter_kategori">
                                    <option value="">- Semua -</option>
                                    @foreach($kategori as $l)
                                        <option value="{{ $l->id_kategori }}">{{ $l->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Kategori Barang</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-sm table-striped table-hover" id="table-barang">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Deskripsi Barang</th>
                        <th>Harga Barang</th>
                        <th>Nama Kategori</th>
                        <th>Nama Supplier</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false" data-width="75%"></div> 
@endsection

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }

        var tableBarang;
        $(document).ready(function () {
            tableBarang = $('#table-barang').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{{ url('barang/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        d.filter_kategori = $('.filter_kategori').val();
                    }
                },
                columns: [
                    {data: "DT_RowIndex", name: "DT_RowIndex", orderable: false, searchable: false}, // nomor otomatis
                    {data: "nama_barang", name: "nama_barang"},
                    {data: "deskripsi_barang", name: "deskripsi_barang"},
                    {
                        data: "harga_barang",
                        name: "harga_barang",
                        render: function (data, type, row) {
                            return new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            }).format(data);
                        }
                    },
                    {data: "nama_kategori", name: "kategori.nama_kategori"}, // Relasi ke kategori
                    {data: "nama_supplier", name: "supplier.nama_supplier"}, // Relasi ke supplier
                    {
                        data: "aksi",
                        className: "text-center",
                        width: "14%",
                        orderable: false,
                        searchable: false
                    }
                ],
                // Definisi kolom yang digunakan untuk relasi
                columnDefs: [
                    {
                        targets: 4,
                        render: function (data, type, row) {
                            return row.nama_kategori; // Akses nama_kategori dari tabel terkait
                        },
                        name: 'kategori.nama_kategori' // Membuatnya dapat dicari dan diurutkan
                    },
                    {
                        targets: 5,
                        render: function (data, type, row) {
                            return row.nama_supplier; // Akses nama_supplier dari tabel terkait
                        },
                        name: 'supplier.nama_supplier' // Membuatnya dapat dicari dan diurutkan
                    }
                ],
                order: [[1, 'asc']], // Urutkan berdasarkan nama barang
            });

            // Menambahkan event listener untuk pencarian
            $('#table-barang_filter input').unbind().bind().on('keyup', function (e) {
                if (e.keyCode == 13) { // Tombol enter
                    tableBarang.search(this.value).draw();
                }
            });

            // Menambahkan event listener untuk filter kategori
            $('.filter_kategori').change(function () {
                tableBarang.draw();
            });
        });
    </script>
@endpush





{{-- // @extends('layouts.template')
// @section('content')
// <div class="card card-outline card-primary">
//     <div class="card-header">
//         <h3 class="card-title">{{ $page->title }}</h3>
//         <div class="card-tools">
//            <button onclick="modalAction('{{ url('/barang/import') }}')" class="btn btn-info">Import Barang</button>
                <a href="{{ url('/barang/create') }}" class="btn btn-primary">Tambah Data</a>
                <button onclick="modalAction('{{ url('/barang/create_ajax') }}')" class="btn btn-success">Tambah Data (Ajax)</button>
//         </div>
//     </div>
//     <div class="card-body">
//         @if (session('success'))
//         <div class="alert alert-success">{{ session('success') }}</div>
//         @endif

//         @if (session('error'))
//         <div class="alert alert-danger">{{ session('error') }}</div>
//         @endif
        
//         <div class="row mb-3">
//             <div class="col-md-3">
//                 <label for="filter_kategori">Filter Kategori:</label>
//                 <select id="filter_kategori" class="form-control">
//                     <option value="">- Semua Kategori -</option>
//                     @foreach($kategori as $item)
//                         <option value="{{ $item->id }}">{{ $item->nama_kategori }}</option>
//                     @endforeach
//                 </select>
//             </div>
//         </div>
//         <table class="table table-bordered table-striped table-hover table-sm" id="table_barang">
//             <thead>
//                 <tr>
//                     <th>ID</th>
//                     <th>Nama Barang</th>
//                     <th>Deskripsi Barang</th>
//                     <th>Harga Barang</th>
//                     <th>Nama Kategori</th>  <th>Nama Supplier</th>  <th>Aksi</th>
//                 </tr>
//             </thead>
//         </table>
//     </div>
// </div>
// <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>

// @endsection
// @push('css')
// @endpush
// @push('js')
// <script>
//     function modalAction(url = ''){
// $('#myModal').load(url,function(){
// $('#myModal').modal('show');
// });
// }
// var dataBarang;
//     $(document).ready(function() {
//     dataBarang = $('#table_barang').DataTable({
//         serverSide: true,
//         processing: true,
//         ajax: {
//             url: "{{ url('barang/list') }}",
//             type: "POST",
//             data: function(d) {
//                 d.id_kategori = $('#filter_kategori').val();
//             }
//         },
//         columns: [
//             { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
//             { data: "nama_barang", orderable: true, searchable: true },
//             { data: "deskripsi_barang", orderable: true, searchable: true },
//             { data: "harga_barang", orderable: true, searchable: true },
//             { data: "nama_kategori", orderable: true, searchable: true },
//             { data: "nama_supplier", orderable: true, searchable: true },
//             { data: "aksi", orderable: false, searchable: false }
//         ]
//     });

//     // Event listener untuk filter
//     $('#filter_kategori').on('change', function() {
//         dataBarang.ajax.reload();
//     });
//     });
// </script>
// @endpush --}}