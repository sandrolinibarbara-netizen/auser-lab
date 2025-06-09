"use strict";
let _kt_datatable_historyPay_tab;
const searchThree = window.location.search;
const paramsThree = new URLSearchParams(searchThree);
const userThree = paramsThree.get("id");
const KTDatatableRemoteAjaxDemoHistoryPayTab = function() {

    const kt_datatable_historyPay_tab = function() {
        _kt_datatable_historyPay_tab = $("#kt_datatable_historyPay_tab").DataTable({
            serverSide: true,
            ajax: {
                type: "POST",
                url: root + 'app/controllers/UserController.php',
                data: function(d) {
                    d.action = 'getOldPayments'
                    d.user = userThree;
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
                {data: 'nome'},
                {data: null,
                    render: (data) => (data.tipo === 'corso') ? '<p>Inizio corso: ' + data.periodo[0] + '<br/>Fine corso: ' + data.periodo[1] + '</p>': '<p>' + data.periodo[0] + ', ' + data.periodo[1] + '</p>'
                },
                {data: 'importo'},
            ],
            columnDefs: [
                {
                    targets: '_all',
                    orderable: false,
                },
                {
                    targets: 2,
                    className: "text-center"
                }
            ]
        })

        _kt_datatable_historyPay_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_historyPay_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_historyPay_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoHistoryPayTab.init();
}));