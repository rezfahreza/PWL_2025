<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register Pengguna</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
</head>
<body class="hold-transition login-page">
  <div class="login-box">
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="{{ url('/') }}" class="h1"><b>Admin</b>LTE</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Register akun baru</p>
        <form id="form-register" method="POST" action="{{ url('register') }}">
          @csrf

          <div class="input-group mb-3">
            <select name="level_id" class="form-control">
              <option value="">Pilih Level</option>
              @foreach($levels as $level)
                <option value="{{ $level->level_id }}">{{ $level->level_nama }}</option>
              @endforeach
            </select>
            <small id="error-level_id" class="text-danger"></small>
          </div>

          <div class="input-group mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username">
            <div class="input-group-append">
              <div class="input-group-text"><span class="fas fa-user"></span></div>
            </div>
            <small id="error-username" class="text-danger"></small>
          </div>

          <div class="input-group mb-3">
            <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap">
            <div class="input-group-append">
              <div class="input-group-text"><span class="fas fa-address-card"></span></div>
            </div>
            <small id="error-nama" class="text-danger"></small>
          </div>

          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text"><span class="fas fa-lock"></span></div>
            </div>
            <small id="error-password" class="text-danger"></small>
          </div>

          <div class="input-group mb-3">
            <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password">
            <div class="input-group-append">
              <div class="input-group-text"><span class="fas fa-lock"></span></div>
            </div>
          </div>

          <div class="row">
            <div class="col-8"></div>
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Register</button>
            </div>
          </div>

          <div class="text-center mt-2">
            <a href="{{ url('login') }}">Sudah punya akun? Login</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
  <script>
    $.ajaxSetup({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $(document).ready(function () {
      $('#form-register').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
          url: $(this).attr('action'),
          method: 'POST',
          data: $(this).serialize(),
          success: function (res) {
            $('.text-danger').text('');
            if (res.status) {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: res.message
              }).then(() => {
                window.location.href = res.redirect;
              });
            } else {
              $.each(res.msgField, function (key, val) {
                $('#error-' + key).text(val[0]);
              });
              Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: res.message
              });
            }
          }
        });
      });
    });
  </script>
</body>
</html>