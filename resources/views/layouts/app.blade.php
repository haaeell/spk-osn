<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SMPN 1 Srumbung</title>
    <link rel="stylesheet" href="{{ asset('assets') }}/vendors/feather/feather.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/css/style.css">
    <link rel="shortcut icon" href="logo.png" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>
    <div class="container-scroller">
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row bg-white shadow-sm">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start px-3">
                <a class="navbar-brand brand-logo me-2" href="index.html">
                    <img src="{{ asset('logo.png') }}" alt="logo" style="height: 50px; ">
                </a>
                <span class="fw-bold fs-7 text-dark" style="font-size: 13px;">SMPN 1 SRUMBUNG</span>
            </div>
        </nav>
        <div class="container-fluid page-body-wrapper">
            @include('layouts.sidebar')
            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets') }}/vendors/js/vendor.bundle.base.js"></script>
    <script src="{{ asset('assets') }}/vendors/chart.js/chart.umd.js"></script>
    <script src="{{ asset('assets') }}/js/off-canvas.js"></script>
    <script src="{{ asset('assets') }}/js/template.js"></script>
    <script src="{{ asset('assets') }}/js/settings.js"></script>
    <script src="{{ asset('assets') }}/js/todolist.js"></script>
    <script src="{{ asset('assets') }}/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="{{ asset('assets') }}/js/dashboard.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @yield('scripts')
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
            $('.select2').select2();
        });
    </script>
    @if ($errors->any())
        <script>
            let errorMessages = '';
            @foreach ($errors->all() as $error)
                errorMessages += "{{ $error }}\n";
            @endforeach

            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: errorMessages,
            });
        </script>
    @endif
    @if (session('success') || session('error'))
        <script>
            $(document).ready(function() {
                var successMessage = "{{ session('success') }}";
                var errorMessage = "{{ session('error') }}";

                if (successMessage) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: successMessage,
                    });
                }

                if (errorMessage) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                    });
                }
            });
        </script>
    @endif
    @stack('scripts')
</body>

</html>
