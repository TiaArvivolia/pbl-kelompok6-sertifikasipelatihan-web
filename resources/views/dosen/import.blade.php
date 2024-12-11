<!-- resources/views/dosen/import.blade.php -->
<form action="{{ url('/dosen/import_ajax') }}" method="POST" id="form-import" enctype="multipart/form-data">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Data Dosen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- <div class="form-group">
                    <label>Download Template</label>
                    <a href="{{ asset('template_dosen.xlsx') }}" class="btn btn-info btn-sm" download>
                        <i class="fa fa-file-excel"></i> Download
                    </a>
                    <small id="error-id_pengguna" class="error-text form-text text-danger"></small>
                </div> --}}
                <div class="form-group">
                    <label>Pilih File</label>
                    <input type="file" name="file_dosen" id="file_dosen" class="form-control" required>
                    <small id="error-file_dosen" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    $("#form-import").validate({
        rules: {
            file_dosen: { required: true, extension: "xlsx" } // Validation rule for file
        },
        submitHandler: function(form) {
            var formData = new FormData(form); // Convert form to FormData to handle file
            $.ajax({
                url: form.action,
                type: form.method,
                data: formData,
                processData: false, // Set processData to false to handle file
                contentType: false, // Set contentType to false to handle file
                success: function(response) {
                    if (response.status) { // If successful
                        $('#modal-master').modal('hide'); // Hide the modal
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        tabledosen.ajax.reload(); // Reload DataTable
                    } else { // If error
                        $('.error-text').text(''); // Clear previous error messages
                        $.each(response.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]); // Display error messages in related elements
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Log error for debugging
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal mengupload file. Silakan coba lagi.'
                    });
                }
            });
            return false; // Prevent default form submission
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback'); // Add class for error
            element.closest('.form-group').append(error); // Insert error message into form group
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid'); // Add is-invalid class to input
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid'); // Remove is-invalid class from input
        }
    });
});
</script>