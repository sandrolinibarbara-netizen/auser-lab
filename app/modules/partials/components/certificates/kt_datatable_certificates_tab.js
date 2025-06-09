"use strict";
let _kt_datatable_certificates_tab;
const root = document.getElementById('root').getAttribute('value');
const search = window.location.search;
const params = new URLSearchParams(search);
const user = params.get("id");
const KTDatatableRemoteAjaxDemoCertificatesTab = function() {

    const kt_datatable_certificates_tab = function() {
        _kt_datatable_certificates_tab = $("#kt_datatable_certificates_tab").DataTable({
            serverSide: true,
            ajax: {
                type: "POST",
                url: root + 'app/controllers/UserController.php',
                data: function(d) {
                  d.action = 'getCertificates'
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
                    render: (data) => '<img class="w-100px rounded" alt="' + data.nome + '-attestato" src="' + data.path + '"/>'
                },
                {data: 'nome'},
                {data: null,
                    render: (data) => '<p>Inizio: '+ data.data_inizio +'<br/>Fine: '+ data.data_fine +'</p>'
                },
                {data: null,
                    render: function(data) {
                        let buttons = '';
                        for(let i=0; i < data.azioni.length; i++) {
                            buttons += '<div class="position-relative d-inline-block">' +
                                '<a id="' + data.azioni[i]['nome'] + '-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" href="'+ root + 'download?attestato=corso&id=' + data.id + '" class="rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></a>' +
                                '<span id="tooltip-' + data.azioni[i]['nome'] + '-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                        }
                        return buttons;
                    }
                }

            ],
            columnDefs: [
                {
                    targets: '_all',
                    orderable: false,
                },
                {
                    targets: 0,
                    className: "text-center"
                }
            ]
        })

        _kt_datatable_certificates_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_certificates_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_certificates_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoCertificatesTab.init();
}));