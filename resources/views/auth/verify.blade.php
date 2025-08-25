<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Email - SMPN 1 Srumbung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            background-color: #f8f9fa;
        }

        .left-panel {
            background-color: #80acc4;
            color: white;
            padding: 40px;
            text-align: center;
        }

        .left-panel h4 {
            font-weight: bold;
            text-transform: uppercase;
        }

        .left-panel img {
            width: 300px;
            margin-top: 30px;
        }

        .welcome-badge {
            background-color: #80acc4;
            color: white;
            border-radius: 30px;
            padding: 5px 25px;
            font-size: 1.1rem;
            font-weight: 500;
            display: inline-block;
            margin-top: 20px;
        }

        .login-box {
            padding: 60px;
        }

        .btn-login {
            background-color: #80acc4;
            color: white;
        }

        .btn-login:hover {
            background-color: #6c9bb2;
        }
    </style>
</head>

<body>
    <div class="container-fluid h-100">
        <div class="row h-100">
            <!-- Left Panel -->
            <div class="col-md-6 left-panel d-flex flex-column justify-content-center align-items-center">
                <h4>Sistem Pemilihan Peserta<br>Olimpiade Sains Nasional<br>SMP Negeri 1 Srumbung</h4>
                <hr class="w-50 my-4" style="border-color: #fff;">
                <img src="{{ asset('logo.png') }}" alt="Logo Sekolah">
            </div>

            <!-- Right Panel -->
            <div class="col-md-6 d-flex flex-column justify-content-center">
                <div class="login-box mx-auto w-75">
                    <span class="welcome-badge">Verifikasi Email</span>
                    <h3 class="mt-4 mb-3 fw-bold">Cek Email Kamu</h3>
                    <p class="text-muted">
                        Sebelum melanjutkan, silakan cek email untuk link verifikasi.<br>
                        Jika tidak menerima email, klik tombol di bawah.
                    </p>

                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            Link verifikasi baru sudah dikirim ke email kamu.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-login w-100">Kirim Ulang Email Verifikasi</button>
                    </form>

                    <div class="mt-3">
                        <a href="{{ route('login') }}" class="text-decoration-none text-muted small">
                            ← Kembali ke login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
