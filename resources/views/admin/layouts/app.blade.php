<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Curtains & Blinds | Dashboard')</title>
    
    <meta name="description" content="Your meta description here.">
    <meta name="keywords" content="Curtains, Blinds, Dashboard, Admin">
    
    <link rel="icon" type="image/x-icon" href="{{ asset('images/rooftop_logo.png') }}">

    @yield('seo')

    <!-- External CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css">

    <!-- Local CSS -->
    <link rel="stylesheet" href="{{ asset('assets/admin/CSS/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/CSS/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/CSS/mutliselectInput.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/CSS/seachableInput.css') }}">

    @yield('css')
</head>

<body>
    <div class="container-fluid p-0">
        <div class="d-flex justify-content-start">
            @include('admin.layouts.sidebar')

            <div class="w-100">
                @include('admin.layouts.nav')
                @include('admin.layouts.message')
                @yield('content')
            </div>
        </div>

        @include('admin.layouts.footer')

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <p>Are you sure you want to delete this item?</p>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="secondary-btn me-2" data-bs-dismiss="modal">Close</button>
                            <button type="button" id="confirmDelete" class="primary-btn">Yes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Local Scripts -->
    <script src="{{ asset('assets/admin/JS/datatable.js') }}"></script>
    <script src="{{ asset('assets/admin/JS/sidebar.js') }}"></script>
    <script src="{{ asset('assets/admin/JS/menu.js') }}"></script>
    <script src="{{ asset('assets/admin/JS/franchise.js') }}"></script>
    <script src="{{ asset('assets/admin/JS/multiselectInput.js') }}"></script>
    <script src="{{ asset('assets/admin/JS/searchableselect.js') }}"></script>
    <script src="{{ asset('assets/admin/JS/createquote.js') }}"></script>

    <!-- Custom Scripts -->
    <script>
        Fancybox.bind("[data-fancybox]", {});
        $('.select2').select2();
        $(".mySelect").select2({
            placeholder: "select",
            minimumResultsForSearch: 5
        });

        $(document).ready(function () {
            $('.toggle-password').on('click', function () {
                const target = $(this).data('target');
                const input = $('#' + target);
                const icon = $(this).find('i');
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('bi-eye-slash').addClass('bi-eye');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('bi-eye').addClass('bi-eye-slash');
                }
            });
        });

        function customformatDate(dateString) {
            const date = new Date(dateString);
            if (isNaN(date)) return 'Invalid Date';
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            let hours = date.getHours();
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12;
            return `${day}-${month}-${year}, ${String(hours).padStart(2, '0')}:${minutes} ${ampm}`;
        }
    </script>

    @yield('script')
</body>

</html>
