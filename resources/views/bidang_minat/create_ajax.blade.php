<form action="{{ url('/bidang_minat/ajax') }}" method="POST" id="form-tambah-bidang-minat">
    @csrf
    <div id="modal-bidang-minat" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Bidang Minat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Bidang Minat</label>
                    <input type="text" name="kode_bidang_minat" id="kode_bidang_minat" class="form-control" maxlength="20" required>
                    <small id="error-kode_bidang_minat" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Nama Bidang Minat</label>
                    <input type="text" name="nama_bidang_minat" id="nama_bidang_minat" class="form-control" maxlength="100" required>
                    <small id="error-nama_bidang_minat" class="error-text form-text text-danger"></small>
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
    $("#form-tambah-bidang-minat").validate({
        rules: {
            kode_bidang_minat: { required: true, maxlength: 20 },
            nama_bidang_minat: { required: true, maxlength: 100 }
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#modal-bidang-minat').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });

                        // Reload DataTable to show new data
                        $('#table_bidang_minat').DataTable().ajax.reload(null, false); // Pass false to keep the current paging
                    } else {
                        $('.error-text').text('');
                        if (response.msgField) {
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message || 'Ada masalah saat menyimpan data.'
                        });
                    }
                }
            });
            return false; // Prevent default form submission
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
