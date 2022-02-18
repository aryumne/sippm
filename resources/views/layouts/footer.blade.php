    <!--   Core JS Files   -->
    <script src="{{ asset('/material/js/core/jquery.min.js') }}"></script>
    <script src="{{ asset('/material/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('/material/js/core/bootstrap-material-design.min.js') }}"></script>
    <script src="{{ asset('/material/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>
    <!-- Plugin for the momentJs  -->
    <script src="{{ asset('/material/js/plugins/moment.min.js') }}"></script>
    <!--  Plugin for Sweet Alert -->
    <script src="{{ asset('/material/js/plugins/sweetalert2.js') }}"></script>
    <!-- Forms Validations Plugin -->
    <script src="{{ asset('/material/js/plugins/jquery.validate.min.js') }}"></script>
    <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
    <script src="{{ asset('/material/js/plugins/jquery.bootstrap-wizard.js') }}"></script>
    <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
    <script src="{{ asset('/material/js/plugins/bootstrap-selectpicker.js') }}"></script>
    <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
    <script src="{{ asset('/material/js/plugins/bootstrap-datetimepicker.min.js') }}"></script>
    <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
    <script src="{{ asset('/material/js/plugins/jquery.dataTables.min.js') }}"></script>
    <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
    <script src="{{ asset('/material/js/plugins/bootstrap-tagsinput.js') }}"></script>
    <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
    <script src="{{ asset('/material/js/plugins/jasny-bootstrap.min.js') }}"></script>
    <!--  Plugin for the Bootstrap Tourist, full documentation here: https://github.com/IGreatlyDislikeJavascript/bootstrap-tourist -->
    <script src="{{ asset('/material/js/plugins/bootstrap-tourist.js') }}"></script>
    <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
    <script src="{{ asset('/material/js/plugins/fullcalendar.min.js') }}"></script>
    <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
    <script src="{{ asset('/material/js/plugins/jquery-jvectormap.js') }}"></script>
    <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
    <script src="{{ asset('/material/js/plugins/nouislider.min.js') }}"></script>
    <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
    <!-- Library for adding dinamically elements -->
    <script src="{{ asset('/material/js/plugins/arrive.min.js') }}"></script>
    <!--  Google Maps Plugin    -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCbVUXb1ZCXDbVu5V-0AjxpikPl6jmgpbQ"></script>
    <!-- Chartist JS -->
    <script src="{{ asset('/material/js/plugins/chartist.min.js') }}"></script>
    <!--  Notifications Plugin    -->
    <script src="{{ asset('/material/js/plugins/bootstrap-notify.js') }}"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('/material/js/material-dashboard.js?v=2.1.0" type="text/javascript') }}"></script>
    <script src="{{ asset('/material/js/application.js') }}"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/19.1.1/classic/ckeditor.js"></script>
    <script>
        $(document).ready(function() {
            md.checkFullPageBackgroundImage();
            setTimeout(function() {
                // after 1000 ms we add the class animated to the login/register card
                $('.card').removeClass('card-hidden');
            }, 700);
        });
    </script>

    <script>
        $(document).ready(function() {
            // Javascript method's body can be found in assets/js/demos.js
            md.initDashboardPageCharts();

            md.initVectorMap();

            $(document).ready(function() {
                // initialise Datetimepicker and Sliders
                md.initFormExtendedDatetimepickers();
                if ($('.slider').length != 0) {
                    md.initSliders();
                }
                // FileInput
                $('.form-file-simple .inputFileVisible').click(function() {
                    $(this).siblings('.inputFileHidden').trigger('click');
                });

                $('.form-file-simple .inputFileHidden').change(function() {
                    var filename = $(this).val().replace(/C:\\fakepath\\/i, '');
                    $(this).siblings('.inputFileVisible').val(filename);
                });

                $('.form-file-multiple .inputFileVisible, .form-file-multiple .input-group-btn').click(
                    function() {
                        $(this).parent().parent().find('.inputFileHidden').trigger('click');
                        $(this).parent().parent().addClass('is-focused');
                    });

                $('.form-file-multiple .inputFileHidden').change(function() {
                    var names = '';
                    for (var i = 0; i < $(this).get(0).files.length; ++i) {
                        if (i < $(this).get(0).files.length - 1) {
                            names += $(this).get(0).files.item(i).name + ',';
                        } else {
                            names += $(this).get(0).files.item(i).name;
                        }
                    }
                    $(this).siblings('.input-group').find('.inputFileVisible').val(names);
                });

                $('.form-file-multiple .btn').on('focus', function() {
                    $(this).parent().siblings().trigger('focus');
                });

                $('.form-file-multiple .btn').on('focusout', function() {
                    $(this).parent().siblings().trigger('focusout');
                });
            });

        });
    </script>
    <script>
        function setFormValidation(id) {
            $(id).validate({
                highlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-success').addClass('has-danger');
                    $(element).closest('.form-check').removeClass('has-success').addClass('has-danger');
                },
                success: function(element) {
                    $(element).closest('.form-group').removeClass('has-danger').addClass('has-success');
                    $(element).closest('.form-check').removeClass('has-danger').addClass('has-success');
                },
                errorPlacement: function(error, element) {
                    $(element).closest('.form-group').append(error);
                },
            });
        }

        $(document).ready(function() {
            setFormValidation('#AddProposalValidation');
            setFormValidation('#EditHakiValidation');
            setFormValidation('#AddHakiValidation');
            setFormValidation('#DeteleHakiValidation');
            setFormValidation('#EditPublikasiValidation');
            setFormValidation('#AddPublikasiValidation');
            setFormValidation('#DetelePublikasiValidation');
            setFormValidation('#EditProposalValidation');
            setFormValidation('#AddKemajuanValidation');
            setFormValidation('#EditKemajuanValidation');
            setFormValidation('#AddAkhirValidation');
            setFormValidation('#EditAkhirValidation');

        });
    </script>