<!-- Bootstrap JS -->
<script src="{{ asset('template') }}/assets/js/bootstrap.bundle.min.js"></script>
<!--plugins-->
<script src="{{ asset('template') }}/assets/js/jquery.min.js"></script>
<script src="{{ asset('template') }}/assets/plugins/simplebar/js/simplebar.min.js"></script>
<script src="{{ asset('template') }}/assets/plugins/metismenu/js/metisMenu.min.js"></script>
<script src="{{ asset('template') }}/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
<script src="{{ asset('template') }}/assets/plugins/select2/js/select2.min.js"></script>
<script src="https://unpkg.com/feather-icons"></script>
<script>
    feather.replace()
</script>
{{-- Data Table --}}
<script src="{{ asset('template') }}/assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('template') }}/assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<script src="{{ asset('template') }}/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
<script src="{{ asset('template') }}/assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="{{ asset('template') }}/assets/plugins/chartjs/js/Chart.min.js"></script>
<script src="{{ asset('template') }}/assets/plugins/chartjs/js/Chart.extension.js"></script>
<script src="{{ asset('template') }}/assets/js/index.js"></script>
<script src="{{ asset('template') }}/assets/plugins/fancy-file-uploader/jquery.ui.widget.js"></script>
<script src="{{ asset('template') }}/assets/plugins/fancy-file-uploader/jquery.fileupload.js"></script>
<script src="{{ asset('template') }}/assets/plugins/fancy-file-uploader/jquery.iframe-transport.js"></script>
<script src="{{ asset('template') }}/assets/plugins/fancy-file-uploader/jquery.fancy-fileupload.js"></script>
<script src="{{ asset('template') }}/assets/plugins/Drag-And-Drop/dist/imageuploadify.min.js"></script>
<!--notification js -->
<script src="{{ asset('template') }}/assets/plugins/notifications/js/lobibox.min.js"></script>
<script src="{{ asset('template') }}/assets/plugins/notifications/js/notifications.min.js"></script>
<script src="{{ asset('template') }}/assets/plugins/notifications/js/notification-custom-script.js"></script>
{{-- Input Mask --}}
<script src="{{ asset('template') }}/assets/plugins/input-tags/js/tagsinput.js"></script>
<!--app JS-->
<script src="{{ asset('template') }}/assets/js/app.js"></script>
<script type="text/javascript" src="{{ asset('template') }}/assets/js/jquery.printPage.js"></script>
<script>
    $('.single-select').select2({
        theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
    });
    $('.multiple-select').select2({
        theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
    });

    function formatCurrency(num) {
        if (num != "" || num != "undefined") {
            //                    num = num.replace(/\$|\,00/g, '').replace(/\$|\./g, '');
            num = num.replace(/\$|\,00/g, '').replace(/\$|\./g, '');
            sign = (num == (num = Math.abs(num)));
            num = Math.floor(num * 100 + 0.50000000001);
            cents = num % 100;
            num = Math.floor(num / 100).toString();
            if (cents < 10)
                cents = "0" + cents;
            for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
                num = num.substring(0, num.length - (4 * i + 3)) + '.' + num.substring(num.length - (4 * i + 3));
            return (((sign) ? '' : '-') + num);
            //                    return (((sign) ? '' : '-') + num + ',' + cents);
            /*			}*/
        }
    }

    /* Fungsi */
    const formatRupiah = (money) => {
        return new Intl.NumberFormat('id-ID', {
            // style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(money);
    }
</script>
@yield('js')
