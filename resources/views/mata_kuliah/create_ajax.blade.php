<form action="{{ url('/mata_kuliah/ajax') }}" method="POST" id="form-tambah-mata-kuliah">
    @csrf
    <div id="modal-mata-kuliah" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Mata Kuliah</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Mata Kuliah</label>
                    <input type="text" name="kode_mk" id="kode_mk" class="form-control" maxlength="20" required>
                    <small id="error-kode_mk" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Nama Mata Kuliah</label>
                    <input type="text" name="nama_mk" id="nama_mk" class="form-control" maxlength="100" required>
                    <small id="error-nama_mk" class="error-text form-text text-danger"></small>
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
    $("#form-tambah-mata-kuliah").validate({
        rules: {
            kode_mk: { required: true, maxlength: 20 },
            nama_mk: { required: true, maxlength: 100 }
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });

                        // Reload DataTable to show new data
                        dataMataKuliah.ajax.reload(); 
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
