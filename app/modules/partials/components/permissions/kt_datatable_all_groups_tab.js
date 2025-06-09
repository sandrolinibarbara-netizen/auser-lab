"use strict";
const root = document.getElementById('root').getAttribute('value');
let _kt_datatable_all_groups_tab;
const KTDatatableRemoteAjaxDemoAllGroupsTab = function() {

    const kt_datatable_all_groups_tab = function() {
        _kt_datatable_all_groups_tab = $("#kt_datatable_all_groups_tab").DataTable({
            serverSide: true,
            ajax: {
                type: "POST",
                url: root + 'app/controllers/GeneralGetterController.php',
                data: function(d) {
                    d.action = 'getAllGroups'
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
                    render: function(data) {
                        let buttons = '';
                        for(let i=0; i < data.azioni.length; i++) {
                            buttons += '<div class="position-relative d-inline-block">' +
                                '<a id="' + data.azioni[i]['nome'] + '-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" href="' + root + 'permessi/gruppo?id=' + data.id + '" class="rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></a>' +
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
            ]
        })

        _kt_datatable_all_groups_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_all_groups_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_all_groups_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoAllGroupsTab.init();
}));