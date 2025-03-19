@empty($level)
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
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/level') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/level/' . $level->level_id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')

        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Level</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode Level</label>
                        <input type="text" name="level_kode" id="level_kode" class="form-control"
                            value="{{ $level->level_kode }}" required maxlength="10">
                        <small id="error-level_kode" class="error-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Nama Level</label>
                        <input type="text" name="level_nama" id="level_nama" class="form-control"
                            value="{{ $level->level_nama }}" required maxlength="100">
                        <small id="error-level_nama" class="error-text text-danger"></small>
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
            $("#form-edit").validate({
                rules: {
                    level_kode: { required: true, minlength: 2, maxlength: 10 },
                    level_nama: { required: true, minlength: 3, maxlength: 100 }
                },
                submitHandler: function(form, event) {
                    event.preventDefault(); // Mencegah reload halaman
                    
                    $.ajax({
                        url: form.action,
                        type: "POST", // Gunakan POST karena ada _method=PUT
                        data: $(form).serialize() + "&_method=PUT",
                        dataType: 'json',
                        success: function(response) {
                            if (response.status) {
                                $('#myModal').modal('hide'); // Tutup modal
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });

                                // Reload DataTables jika ada
                                if (typeof dataLevel !== 'undefined' && typeof dataLevel.ajax !== 'undefined') {
                                    dataLevel.ajax.reload();
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
