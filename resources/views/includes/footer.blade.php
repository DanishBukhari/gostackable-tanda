<!-- latest jquery-->
<script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>
<!-- Bootstrap js-->
<script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
<!-- feather icon js-->
<script src="{{ asset('assets/js/icons/feather-icon/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/icons/feather-icon/feather-icon.js') }}"></script>
<!-- scrollbar js-->
<script src="{{ asset('assets/js/scrollbar/simplebar.js') }}"></script>
<script src="{{ asset('assets/js/scrollbar/custom.js') }}"></script>
<!-- Sidebar jquery-->
<script src="{{ asset('assets/js/config.js') }}"></script>

<!-- Plugins JS start-->
<script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
<script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
<script src="{{ asset('assets/js/chart/chartist/chartist.js') }}"></script>
<script src="{{ asset('assets/js/chart/chartist/chartist-plugin-tooltip.js') }}"></script>
<script src="{{ asset('assets/js/chart/knob/knob.min.js') }}"></script>
<script src="{{ asset('assets/js/chart/knob/knob-chart.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
<script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
<script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('assets/js/dashboard/default.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/handlebars.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/typeahead.bundle.js') }}"></script>
<script src="{{ asset('assets/js/typeahead/typeahead.custom.js') }}"></script>
<script src="{{ asset('assets/js/typeahead-search/handlebars.js') }}"></script>
<script src="{{ asset('assets/js/typeahead-search/typeahead-custom.js') }}"></script>



<!-- Plugins JS Ends-->
<!-- Theme js-->
<script src="{{ asset('assets/js/script.js') }}"></script>
<script src="{{ asset('assets/js/theme-customizer/customizer.js') }}"></script>
<script type="text/javascript">
    "use strict";

    $(document).ready(function () {
        const BASE_URL = "{{ config('app.url') }}";

        // Load modal from a datatable from the class
        $(document).on('click', '.commonModal', function (ev) {
            ev.preventDefault();
            $(".modal-body").html('');

            let title = $(this).data('title') ? $(this).data('title') : '';
            $('.modal-title').text(title);
            // $(".modal-body").load($(this).data('view'));
            $.ajax({
                url: $(this).data('view'),
                type: 'GET',
                beforeSend: function() {
                    $(".modal-body").html('<div class=" card">' +
                        '<h2>Loading...</h2>' +
                        '</div>');
                },
                success: function (response) {
                    $(".modal-body").html(response);
                }
            });

            $("#commonModal").modal({backdrop: 'static', keyboard: true});
        });

        $('body').on('focus',".datepicker", function(){
            $(this).datepicker({
                format:"yyyy-mm-dd",
                clearBtn:!0,
                autoclose:!0,
            });
        });

        // Dynamic ajax endpoint for deleting data
        $(document).on('click', '.deleteRecord', function () {
            var url = $(this).data("url");
            var returnUrl = $(this).data('return_url');
            var token = $("meta[name='csrf-token']").attr("content");

            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    "_token": token,
                },
                success: function (response) {
                    $.notify(`<i class="fa fa-bell-o"></i><strong>${response.message}</strong>`, {
                        type: 'theme',
                        allow_dismiss: true,
                        delay: 5000,
                        showProgressbar: true,
                        timer: 300,
                        animate:{
                            enter:'animated fadeInDown',
                            exit:'animated fadeOutUp'
                        }
                    });

                    setTimeout(() => {
                        window.location.href = returnUrl;
                    }, 5000)
                }
            });

        });

    });
</script>
