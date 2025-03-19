<form action="{{ url('/barang/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Barang</label>
                    <input value="" type="text" name="nama_barang" id="nama_barang" class="form-control" required>
                    <small id="error-nama_barang" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Deskripsi Barang</label>
                    <textarea name="deskripsi_barang" id="deskripsi_barang" class="form-control" required></textarea>
                    <small id="error-deskripsi_barang" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Harga Barang</label>
                    <input value="" type="number" name="harga_barang" id="harga_barang" class="form-control" required>
                    <small id="error-harga_barang" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="id_kategori" id="id_kategori" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        @foreach ($kategori as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_kategori }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_kategori" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Supplier</label>
                    <select name="id_supplier" id="id_supplier" class="form-control" required>
                        <option value="">Pilih Supplier</option>
                        @foreach ($supplier as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_supplier }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_supplier" class="error-text form-text text-danger"></small>
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
        $("#form-tambah").validate({
            rules: {
                nama_barang: { required: true, minlength: 3, maxlength: 100 },
                deskripsi_barang: { required: true, minlength: 5 },
                harga_barang: { required: true, number: true },
                id_kategori: { required: true },
                id_supplier: { required: true }
            },
            submitHandler: function(form, event) {
                event.preventDefault(); // Mencegah reload halaman
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#form-tambah')[0].reset(); // Reset form setelah sukses
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataBarang.ajax.reload(); // Reload DataTables
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