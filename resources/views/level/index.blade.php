@extends('layouts.template')
@section('content')

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a href="{{ url('/level/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Level</a> 
            <button onclick="modalAction('{{ url('/level/import') }}')" class="btn btn-info">Import Level</button>
            <a href="{{ url('/level/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Level</a>
            <button onclick="modalAction('{{ url('/level/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered table-striped table-hover table-sm" id="table_level">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Level</th>
                    <th>Nama Level</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
<!-- Tambahkan CSS tambahan jika diperlukan -->
@endpush

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }
    var dataLevel;
    $(document).ready(function() {
        dataLevel = $('#table_level').DataTable({
            processing: true, // Menampilkan indikator loading
            serverSide: true,
            ajax: {
                url: "{{ url('level/list') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
            },
            order: [[0, 'desc']], // Mengurutkan ID secara default
            columns: [
                {
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "level_kode",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "level_nama",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "aksi",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }
            ]
        });
    });
</script>
@endpush
