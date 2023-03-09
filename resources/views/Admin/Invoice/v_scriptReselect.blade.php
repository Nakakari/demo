<script type="text/javascript"
    src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
<script type="text/javascript">
    let list_penjualan = [];

    const table = $("#penjualan-dt").DataTable({
        "pageLength": 50,
        "lengthMenu": [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, 'All Data']
        ],
        "bLengthChange": true,
        "bFilter": true,
        "bInfo": true,
        "processing": true,
        "bServerSide": true,
        "order": [
            [1, "asc"]
        ],
        "scrollX": true,
        "ajax": {
            url: "{{ url('') }}/reselect_inv_pelanggan/{{ base64_encode($id_pelanggan) }}",
            type: "POST",
            data: function(d) {
                d._token = "{{ csrf_token() }}",
                    d.dari = $("#dari_tanggal").val(),
                    d.sampai = $("#sampai_tanggal").val(),
                    d.kondisi = $("#filter-kondisi").val()
            }
        },
        "columnDefs": [{
            "targets": 0,
            "data": "id_pengiriman",
            "sortable": false,
            'checkboxes': {
                'selectRow': true
            },
        }, {
            "targets": 1,
            "data": "id_pengiriman",
            "sortable": false,
            "render": function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        }, {
            "targets": 2,
            "data": "tgl_masuk",
            "sortable": false,
            "render": function(data, type, row, meta) {
                list_penjualan[row.id_pengiriman] = row;
                return data;
            }
        }, {
            "targets": 3,
            "data": "no_resi",
            "sortable": false,
            "render": function(data, type, row, meta) {
                return data;
            }
        }, {
            "targets": 4,
            "data": "no_resi_manual",
            "sortable": false,
            "render": function(data, type, row, meta) {
                return data;
            }
        }, {
            "targets": 5,
            "data": "nama_pengirim",
            "sortable": false,
            "render": function(data, type, row, meta) {
                return data;
            }
        }, {
            "targets": 6,
            "data": "nama_penerima",
            "sortable": false,
            "render": function(data, type, row, meta) {
                return data;
            }
        }, {
            "targets": 7,
            "data": "kota_penerima",
            "sortable": false,
            "render": function(data, type, row, meta) {
                return data;
            }
        }, {
            "targets": 8,
            "data": "kilo",
            "sortable": false,
            "render": function(data, type, row, meta) {
                var nilai = new Intl.NumberFormat(['ban', 'id']).format(data);
                if (nilai == 0) {
                    return '';
                } else {
                    return nilai;
                }
            }
        }, {
            "targets": 9,
            "data": "koli",
            "sortable": false,
            "render": function(data, type, row, meta) {
                var nilai = new Intl.NumberFormat(['ban', 'id']).format(data);
                if (nilai == 0) {
                    return '';
                } else {
                    return nilai;
                }
            }
        }, {
            "targets": 10,
            "data": "cash",
            "sortable": false,
            "render": function(data, type, row, meta) {
                var nilai = new Intl.NumberFormat(['ban', 'id']).format(data);
                if (nilai == 0) {
                    return '';
                } else {
                    return nilai;
                }
            }
        }, {
            "targets": 11,
            "data": "cod",
            "sortable": false,
            "render": function(data, type, row, meta) {
                var nilai = new Intl.NumberFormat(['ban', 'id']).format(data);
                if (nilai == 0) {
                    return '';
                } else {
                    return nilai;
                }
            }
        }, {
            "targets": 12,
            "data": "tagihan",
            "sortable": false,
            "render": function(data, type, row, meta) {
                var nilai = new Intl.NumberFormat(['ban', 'id']).format(data);
                if (nilai == 0) {
                    return '';
                } else {
                    return nilai;
                }
            }
        }, {
            "targets": 13,
            "data": "tgl",
            "sortable": false,
            "render": function(data, type, row, meta) {
                return data;
            }
        }, {
            "targets": 14,
            "data": "ket",
            "sortable": false,
            "render": function(data, type, row, meta) {
                return data;
            }
        }],
        'select': {
            'style': 'multi'
        },
        "initComplete": function(row, data, dataIndex) {
            let selected = {{ json_encode($selected) }}
            let arr = this.api().data();
            let dataku = []

            for (let i = 0; i < arr.length; i++) {
                dataku.push(arr[i]['id_pengiriman'])
            }
            let indexes = []

            dataku.forEach((item, index) => {
                if (selected.includes(item)) {
                    indexes.push(index)
                }
            })
            this.api().rows(indexes).select();
        },
        "createdRow": function(row, data, dataIndex) {
            if (data["tgl"] == null && data['ket'] == null) {
                $('td', row).eq(14).css("background-color", "#F47174");
                $('td', row).eq(13).css("background-color", "#F47174");
            } else if (data['tgl'] !== null && data['ket'] == null) {
                $('td', row).eq(14).css("background-color", "#F47174");
            } else if (data['tgl'] == null && data['ket'] !== null) {
                $('td', row).eq(13).css("background-color", "#F47174");
            } else {

            }
        },
        "footerCallback": function(row, data, start, end, display) {
            var api = this.api(),
                data;
            let arr = []
            let ary = []
            for (let i = 0; i < data.length; i++) {
                if (data[i]['is_buat'] == 0) {
                    arr.push(data[i]);
                }
                if (data[i]['is_kondisi_resi'] == 1) {
                    ary.push(data[i]);
                }
            }
            // console.log(arr)
            var intVal = function(i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                    i : 0;
            };

            // computing column Total of the complete result 
            var resi = table.page.info().recordsTotal;

            var kilo = api
                .column(8)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            var koli = api
                .column(9)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            var cash = api
                .column(10)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            var cod = api
                .column(11)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            var tagihan = api
                .column(12)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            var cumulatif = String((tagihan + cod + cash)).replace(/(.)(?=(\d{3})+$)/g, '$1.');
            var cash_total = String(cash).replace(/(.)(?=(\d{3})+$)/g, '$1.');
            var cod_total = String(cod).replace(/(.)(?=(\d{3})+$)/g, '$1.');
            var tagihan_total = String(tagihan).replace(/(.)(?=(\d{3})+$)/g, '$1.');
            var total_kilo = String(kilo).replace(/(.)(?=(\d{3})+$)/g, '$1.');
            var total_koli = String(koli).replace(/(.)(?=(\d{3})+$)/g, '$1.');

            $(api.column(0).footer()).html('');
            $(api.column(1).footer()).html('');
            $(api.column(2).footer()).html('');
            $(api.column(3).footer()).html('');
            $(api.column(4).footer()).html('');
            $(api.column(5).footer()).html('');
            $(api.column(6).footer()).html('');
            $(api.column(7).footer()).html('Total');
            $(api.column(8).footer()).html(total_kilo);
            $(api.column(9).footer()).html(total_koli);
            $(api.column(10).footer()).html(cash_total);
            $(api.column(11).footer()).html(cod_total);
            $(api.column(12).footer()).html(tagihan_total);
            $(api.column(13).footer()).html('');
            $('.sum').html(cumulatif);
            $('#transaksi').html(arr.length);
            $('#tonase').html('Rp' + tagihan_total);
            $('#koli').html();
            $('#srt_blm_kmbli').html(ary.length);

        },
    });
    console.log(table.rows({
        selected: true
    }).ids().toArray())

    function filter() {
        table.ajax.reload(null, false)
    }

    function tambahData() {
        window.location.href = "/add_transaksi_penjualan";
    }

    table.on('click', 'td:first-child', e => {
        let all_checkbox = $('#penjualan-dt tbody .dt-checkboxes:checked')
        let status = (all_checkbox.length > 0)
        $("#proses-data").prop('disabled', !status)
    });

    $('.dt-checkboxes-select-all').on('click', function() {
        var isChecked = $("#penjualan-dt thead input[type='checkbox']").prop('checked')
        $("#proses-data").prop('disabled', isChecked)
    });

    function prosesData(id_invoice) {
        let all_id_pengiriman = []
        var rows_selected = table.column(0).checkboxes.selected();
        $.each(rows_selected, function(index, rowId) {
            all_id_pengiriman.push(rowId)
        })
        const sum = all_id_pengiriman.length
        if (sum != 0) {
            window.location.href = '/edit_invoice/' + btoa(id_invoice) + '?sum=' + btoa(sum) + '&id=' + btoa(
                all_id_pengiriman)
        } else {
            warning_noti()
            $('#head-cb').prop('checked', false)
        }

    }

    function submitForm() {
        let id_pelanggan = null;
        let id_pengiriman = document.getElementById("id_pengiriman").value;
        const url = "{{ url('ubahstatus_trans_pelanggan') }}";
        $.ajax({
            url,
            method: "POST",
            data: {
                id_pengiriman: id_pengiriman,
                id_pelanggan: id_pelanggan,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response == true) {
                    $('#modal-batal').modal('hide')
                    table.ajax.reload(null, false)
                }
            },
            error: function(e) {
                alert('Whops!')
            }
        })
    }
</script>
