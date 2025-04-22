@extends('layouts.template')
@section('content')

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a href="{{ url('/penjualan/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export PDF</a> 
            <button onclick="modalAction('{{ url('/penjualan/import') }}')" class="btn btn-info">Import Penjualan</button>
            <a href="{{ url('/penjualan/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Excel</a>
            <button onclick="modalAction('{{ url('/penjualan/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Penjualan</button>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Filter:</label>
                    <div class="col-3">
                        <select class="form-control" id="user_id" name="user_id">
                            <option value="">- Semua Kasir -</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->user_id }}">{{ $user->nama_lengkap }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Kasir</small>
                    </div>
                    <div class="col-3">
                        <input type="date" class="form-control" id="start_date" name="start_date">
                        <small class="form-text text-muted">Tanggal Mulai</small>
                    </div>
                    <div class="col-3">
                        <input type="date" class="form-control" id="end_date" name="end_date">
                        <small class="form-text text-muted">Tanggal Akhir</small>
                    </div>
                </div>
            </div>
        </div>
        
        <table class="table table-bordered table-striped table-hover table-sm" id="table_penjualan">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor Penjualan</th>
                    <th>Tanggal</th>
                    <th>Kasir</th>
                    <th>Total Item</th>
                    <th>Total Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" 
     data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>

@endsection

@push('css')
@endpush

@push('js')
<script>
    function modalAction(url = ''){
        $('#myModal').load(url,function(){
            $('#myModal').modal('show');
        });
    }

    var dataPenjualan;
    $(document).ready(function() {
        dataPenjualan = $('#table_penjualan').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('penjualan/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d) {
                    d.user_id = $('#user_id').val();
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                }
            },
            columns: [
                {
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "nomor_penjualan", // Menggunakan accessor dari model
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "tanggal_penjualan",
                    className: "text-center",
                    orderable: true,
                    render: function(data) {
                        return new Date(data).toLocaleDateString('id-ID');
                    }
                },
                {
                    data: "user.nama_lengkap",
                    className: "",
                    orderable: false,
                    defaultContent: '-'
                },
                {
                    data: "total_item", // Sesuai dengan response dari controller
                    className: "text-center",
                    orderable: false,
                    render: function(data) {
                        return data || '0';
                    }
                },
                {
                    data: "total_harga", // Sesuai dengan response dari controller
                    className: "text-right",
                    orderable: false,
                    render: function(data) {
                        return data ?  data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") : 'Rp 0';
                    }
                },
                {
                    data: "aksi",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }
            ],
            order: [[2, 'desc']] // Default urutkan berdasarkan tanggal terbaru
        });

        $('#user_id, #start_date, #end_date').on('change', function() {
            dataPenjualan.ajax.reload();
        });
    });
</script>
@endpush