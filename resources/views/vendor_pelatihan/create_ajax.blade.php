<form action="{{ url('/vendor_pelatihan/ajax') }}" method="POST" id="form-tambah-vendor-pelatihan">
    @csrf
    <div id="modal-vendor-pelatihan" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Vendor Pelatihan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Vendor</label>
                    <input type="text" name="nama" id="nama" class="form-control" maxlength="100" required>
                    <small id="error-nama" class="error-text form-text text-danger"></small>
                </div>
                
                <div class="form-group">
                    <label>No Telepon</label>
                    <input type="text" name="no_telepon" id="no_telepon" class="form-control" maxlength="20">
                    <small id="error-no_telepon" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Website</label>
                    <input type="text" name="website" id="website" class="form-control" maxlength="100">
                    <small id="error-website" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Kota</label>
                    <input type="text" name="kota" id="kota" class="form-control" maxlength="50">
                    <small id="error-kota" class="error-text form-text text-danger"></small>
                </div>
                
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control"></textarea>
                    <small id="error-alamat" class="error-text form-text text-danger"></small>
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
    $("#form-tambah-vendor-pelatihan").validate({
        rules: {
            nama: { required: true, maxlength: 100 },
            kota: { maxlength: 50 },
            no_telepon: { maxlength: 20 },
            website: { maxlength: 100 }
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
                        dataVendorPelatihan.ajax.reload(); // Keep the current paging
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
