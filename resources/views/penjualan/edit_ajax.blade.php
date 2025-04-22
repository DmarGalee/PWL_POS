
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kesalahan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                        Data yang Anda cari tidak ditemukan.
                    </div>
                    <a href="{{ url('/penjualan') }}" class="btn btn-warning">Kembali</a>
                </div>
            </div>
        </div>
    @else
        <form action="{{ url('/penjualan/' . $penjualan->id . '/update_ajax') }}" method="POST" id="form-edit-penjualan">
            @csrf
            @method('PUT')
    
            <div id="modal-master" class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Data Penjualan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Kode Barang</label>
                            <input type="text" name="kode_barang" id="kode_barang" class="form-control"
                                value="{{ $penjualan->id_barang }}" required maxlength="10"> 
                            <small id="error-kode_barang" class="error-text text-danger"></small>
                        </div>
    
                        <div class="form-group">
                            <label>Jumlah Stok</label>
                            <input type="number" name="jumlah_stok" id="jumlah_stok" class="form-control"
                                value="{{ $penjualan->jumlah_stok }}" required min="0">
                            <small id="error-jumlah_stok" class="error-text text-danger"></small>
                        </div>
                    </div>
    
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    
        <script>
            $(document).ready(function() {
                $("#form-edit-penjualan").validate({
                    rules: {
                        kode_barang: { required: true, minlength: 2, maxlength: 10 }, // Contoh validasi
                        jumlah_stok: { required: true, number: true, min: 0 }
                    },
                    submitHandler: function(form, event) {
                        event.preventDefault();
                        
                        $.ajax({
                            url: form.action,
                            type: "POST",
                            data: $(form).serialize() + "&_method=PUT",
                            dataType: \'json\',\n
                            success: function(response) {
                                if (response.status) {
                                    $('#myModal').modal('hide');
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: response.message
                                    });
                                    if (typeof dataPenjualan !== 'undefined' && typeof dataPenjualan.ajax !== 'undefined') {
                                        dataPenjualan.ajax.reload();
                                    }
                                } else {
                                    $('.error-text').text('');
                                    $.each(response.msgField, function(prefix, val) {
                                        $('#error-' + prefix).text(val[0]);
                                    });
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
                                    text: 'Gagal memperbarui data, periksa kembali inputan Anda.'
                                });
                            }
                        });
                        return false;
                    },
                    errorElement: 'span',
                    errorPlacement: function(error, element) {
                        error.addClass('invalid-feedback');
                        element.closest('.form-group').append(error);
                    },
                    highlight: function(element) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function(element) {
                        $(element).removeClass('is-invalid');
                    }
                });
            });
        </script>
    @endif