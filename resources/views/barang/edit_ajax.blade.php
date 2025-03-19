@empty($barang)
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
                <a href="{{ url('/barang') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/barang/' . $barang->id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')

        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" name="nama_barang" id="nama_barang" class="form-control"
                            value="{{ $barang->nama_barang }}" required maxlength="100">
                        <small id="error-nama_barang" class="error-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi Barang</label>
                        <textarea name="deskripsi_barang" id="deskripsi_barang" class="form-control" required>{{ $barang->deskripsi_barang }}</textarea>
                        <small id="error-deskripsi_barang" class="error-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Harga Barang</label>
                        <input type="number" name="harga_barang" id="harga_barang" class="form-control"
                            value="{{ $barang->harga_barang }}" required>
                        <small id="error-harga_barang" class="error-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="id_kategori" id="id_kategori" class="form-control" required>
                            <option value="">Pilih Kategori</option>
                            @foreach ($kategori as $item)
                                <option value="{{ $item->id }}" {{ $barang->id_kategori == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        <small id="error-id_kategori" class="error-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Supplier</label>
                        <select name="id_supplier" id="id_supplier" class="form-control" required>
                            <option value="">Pilih Supplier</option>
                            @foreach ($supplier as $item)
                                <option value="{{ $item->id }}" {{ $barang->id_supplier == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_supplier }}
                                </option>
                            @endforeach
                        </select>
                        <small id="error-id_supplier" class="error-text text-danger"></small>
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
                    nama_barang: { required: true, minlength: 3, maxlength: 100 },
                    deskripsi_barang: { required: true, minlength: 5 },
                    harga_barang: { required: true, number: true },
                    id_kategori: { required: true },
                    id_supplier: { required: true }
                },
                submitHandler: function(form, event) {
                    event.preventDefault();

                    $.ajax({
                        url: form.action,
                        type: "POST",
                        data: $(form).serialize() + "&_method=PUT",
                        dataType: 'json',
                        success: function(response) {
                            if (response.status) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });

                                if (typeof dataBarang !== 'undefined' && typeof dataBarang.ajax !== 'undefined') {
                                    dataBarang.ajax.reload();
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