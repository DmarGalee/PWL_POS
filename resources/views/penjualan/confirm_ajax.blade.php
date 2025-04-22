@empty($penjualan)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                Data penjualan yang Anda cari tidak ditemukan.
            </div>
            <a href="{{ url('/penjualan') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/penjualan/' . $penjualan->id . '/delete_ajax') }}" method="POST" id="form-delete">
    @csrf
    @method('DELETE')

    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Data Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
                    Apakah Anda yakin ingin menghapus data penjualan berikut?
                </div>
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">Nomor Penjualan:</th>
                        <td class="col-9">{{ $penjualan->no_penjualan }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal:</th>
                        <td class="col-9">{{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Kasir:</th>
                        <td class="col-9">{{ $penjualan->user->nama_lengkap ?? 'Tidak diketahui' }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Total Item:</th>
                        <td class="col-9">{{ $penjualan->details->sum('jumlah_barang') }} item</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Total Harga:</th>
                        <td class="col-9">
                            Rp {{ number_format($penjualan->details->sum(function($detail) {
                                return $detail->jumlah_barang * $detail->harga_satuan;
                            }), 0, ',', '.') }}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Ya, Hapus</button>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    $("#form-delete").validate({
        rules: {}, // Tidak ada rules khusus
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#modal-master').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Reload DataTables penjualan
                        if (typeof dataPenjualan !== 'undefined' && typeof dataPenjualan.ajax !== 'undefined') {
                            dataPenjualan.ajax.reload(null, false); // Reload tanpa reset pagination
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal menghapus data penjualan, coba lagi.'
                    });
                }
            });
            return false;
        }
    });
});
</script>
@endempty