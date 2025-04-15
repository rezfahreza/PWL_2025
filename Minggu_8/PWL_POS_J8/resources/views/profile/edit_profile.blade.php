<form action="{{ route('profile.update') }}" method="POST" id="form-edit-profile" enctype="multipart/form-data">
    @csrf
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" value="{{ $user->username }}" class="form-control" required>
                    <small id="error-username" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ $user->nama }}" class="form-control" required>
                    <small id="error-nama" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group text-center">
                    <img src="{{ asset('profile_images/' . ($user->photo ?? 'default.png')) }}" id="profile-preview"
                         class="img-thumbnail mb-3"
                         style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
                    <div>
                        <label for="photo">Pilih Foto Profile Baru</label>
                        <input type="file" name="photo" id="photo" class="form-control-file" onchange="previewImage(this)">
                        <small id="error-photo" class="error-text form-text text-danger"></small>
                    </div>
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
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#profile-preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).ready(function () {
        // Validasi form sebelum pengiriman
        $("#form-edit-profile").validate({
            rules: {
                username: {
                    required: true,
                    maxlength: 255
                },
                nama: {
                    required: true,
                    maxlength: 255
                },
                photo: {
                    extension: "jpg|jpeg|png"
                }
            },
            submitHandler: function (form) {
                var formData = new FormData(form);

                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        // Tutup modal jika berhasil
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message || 'Profil berhasil diperbarui.'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function (xhr) {
                        // Kosongkan pesan error dan tampilkan error dari server
                        $('.error-text').text('');
                        if (xhr.responseJSON?.msgField) {
                            $.each(xhr.responseJSON.msgField, function (key, val) {
                                $('#error-' + key).text(val[0]);
                            });
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat memperbarui profil.'
                        });
                    }
                });

                return false;
            }
        });
    });
</script>