<form action="{{ url('/penjualan/simpan') }}" method="POST" id="form-tambah_penjualan">
            @csrf
            <div id="modal-master" class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Data Penjualan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Barang</label>
                            <select name="id_barang" id="id_barang" class="form-control" required>
                                <option value="">Pilih Barang</option>
                                @foreach($barang as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_barang }}</option>
                                @endforeach
                            </select>
                            <small id="error-id_barang" class="error-text form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label>Jumlah Stok</label>
                            <input type="number" name="stok" id="stok" class="form-control" min="0" required>
                            <small id="error-stok" class="error-text form-text text-danger"></small>
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
                $("#form-tambah_penjualan").validate({
                    rules: {
                        id_barang: { required: true },
                        stok: { required: true, min: 0, digits: true }
                    },
                    messages: {
                        stok: {
                            digits: "Hanya angka yang diperbolehkan"
                        }
                    },
                    submitHandler: function(form, event) {
                        event.preventDefault();
                        $.ajax({
                            url: "{{ url('/penjualan/simpan') }}",
                            type: form.method,
                            data: $(form).serialize(),
                            success: function(response) {
                                if (response.status) {
                                    $('#form-tambah_penjualan')[0].reset();
                                    $('#modal-master').modal('hide');
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: response.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                    if (typeof dataPenjualan !== 'undefined') {
                                        dataPenjualan.ajax.reload(null, false);
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
                                    title: 'Error',
                                    text: 'Terjadi kesalahan pada server'
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
                    highlight: function(element, errorClass, validClass) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function(element, errorClass, validClass) {
                        $(element).removeClass('is-invalid');
                    }
                });
            });
        </script>
    