<!doctype html>
<html lang="en">
@include('includes.header-meta')
@yield('styles')
<body>
<!-- tap on top starts-->
<div class="tap-top"><i data-feather="chevrons-up"></i></div>
<!-- Begin page -->
<div class="page-wrapper compact-wrapper" id="pageWrapper">

    <!-- Start Side bar -->
@if(!is_null(auth()->user()))
    @include('includes.top-bar')
    <!-- Page Body Start-->
    <div class="page-body-wrapper">
        @include('includes.sidebar')

        @include('includes.modals')
        <!-- Start Content -->
            <div class="page-body">
                @yield('content')
            </div>
            <!-- End Content-->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 footer-copyright text-center">
                            <p class="mb-0">Copyright 2021 &copy; Tanda Finance </p>
                        </div>
                    </div>
                </div>
            </footer>
            @else
                @yield('content')
    </div>
@endif
@include('includes.footer')
@yield('script')
</body>
</html>
