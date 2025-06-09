"use strict";
let _kt_datatable_historySub_tab;
const searchFour = window.location.search;
const paramsFour = new URLSearchParams(searchFour);
const userFour = paramsFour.get("id");
const KTDatatableRemoteAjaxDemoHistorySubTab = function() {

    const kt_datatable_historySub_tab = function() {
        _kt_datatable_historySub_tab = $("#kt_datatable_historySub_tab").DataTable({
            serverSide: true,
            ajax: {
                type: "POST",
                url: root + 'app/controllers/UserController.php',
                data: function(d) {
                    d.action = 'getOldSubs'
                    d.user = userFour;
                },
                dataSrc: function (data) {
                    return data.data;
                }
            },
            paging: true,
            info: false,
            pageLength: 10,
            lengthChange: true,
            columns: [
                {data: null,
                    render: (data) => {
                        const year = data.data_fine.split('/')[2];
                        return '<p>Tesseramento ' + year + '</p>'
                    }
                },
                {data: 'data_creazione'},
                {data: null,
                    render: (data) => '<p>'+ data.data_inizio + ' - ' + data.data_fine+'</p>'},
            ],
            columnDefs: [
                {
                    targets: '_all',
                    orderable: false,
                },
            ]
        })

        _kt_datatable_historySub_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_historySub_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_historySub_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoHistorySubTab.init();
}));