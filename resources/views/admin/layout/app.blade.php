<!DOCTYPE html>
<html lang="en">
<!-- index.html  21 Nov 2019 03:44:50 GMT -->

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Drivrr - @yield('title') </title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/app.min.css') }}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/components.css') }}">
    <!-- Custom style CSS -->
    {{-- <link rel="stylesheet" href="{{ asset('public/admin/assets/toastr/css/toastr.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/bundles/prism/prism.css') }}">
    <link rel='shortcut icon' type='image/x-icon' href='{{ asset('public/admin/assets/Fav Icon 2.png') }}' />
    <link rel="stylesheet"
        href="{{ asset('public/admin/assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/bundles/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/bundles/jquery-selectric/selectric.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('public/admin/assets/bundles/lightgallery/dist/css/lightgallery.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/toastr/toastr.css') }}">
</head>

<body>
    <div class="loader"></div>

    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            @include('admin.common.header')
            @include('admin.common.side_menu')
            @yield('content')
            @include('admin.common.footer')
        </div>
    </div>
    <!-- General JS Scripts -->
    <script src="{{ asset('public/admin/assets/js/app.min.js') }}"></script>
    <!-- JS Libraies -->
    <script src="{{ asset('public/admin/assets/bundles/apexcharts/apexcharts.min.js') }}"></script>
    <!-- Page Specific JS File -->
    <script src="{{ asset('public/admin/assets/js/page/index.js') }}"></script>
    <!-- Template JS File -->
    <script src="{{ asset('public/admin/assets/js/scripts.js') }}"></script>
    <!-- Custom JS File -->
    <script src="{{ asset('public/admin/assets/js/custom.js') }}"></script>
    <script src="{{ asset('public/toastr/toastr.js') }}"></script>
    {{-- <script src="{{ asset('public/admin/assets/toastr/js/toastr.min.js') }}"></script> --}}
    {{-- DataTbales --}}
    <script src="{{ asset('public/admin/assets/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('public/admin/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}">
    </script>
    <script src="{{ asset('public/admin/assets/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('public/admin/assets/js/page/datatables.js') }}"></script>
    <script src="{{ asset('public/admin/assets/bundles/prism/prism.js') }}"></script>
    <script src="{{ asset('public/admin/assets/bundles/jquery-selectric/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('public/admin/assets/bundles/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('public/admin/assets/bundles/lightgallery/dist/js/lightgallery-all.js') }}"></script>
    <script src="{{ asset('public/admin/assets/js/page/light-gallery.js') }}"></script>
    @yield('js')
    <script>
        toastr.options = {
            "closeButton": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
            "extendedTimeOut": "1000"
        };

        @if (session('message'))
            toastr.success("{{ session('message') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if (session('info'))
            toastr.info("{{ session('info') }}");
        @endif

        @if (session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif
    </script>
    {{-- License Approval  Counter Code --}}
    <script>
        $(document).ready(function() {
            function updatelisenceApprovel() {
                $.ajax({
                    url: "{{ route('lisenceApprovel.count') }}",
                    type: 'GET',
                    success: function(response) {
                        $('#lisenceApprovel').text(response.lisenceApprovel);
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr);
                    }
                });
            }
            updatelisenceApprovel();
            setInterval(updatelisenceApprovel, 1000);
        });
    </script>
    {{-- WithDrawal Request   Counter Code --}}
    <script>
        $(document).ready(function() {
            function updatePaymentRequest() {
                $.ajax({
                    url: "{{ route('paymentRequest.count') }}",
                    type: 'GET',
                    success: function(response) {
                        $('#paymentRequest').text(response.paymentRequest);
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr);
                    }
                });
            }
            updatePaymentRequest();
            setInterval(updatePaymentRequest, 1000);
        });
    </script>
</body>


<!-- index.html  21 Nov 2019 03:47:04 GMT -->

</html>
