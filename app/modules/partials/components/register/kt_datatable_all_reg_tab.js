"use strict";
const root = document.getElementById('root').getAttribute('value');
let _kt_datatable_all_reg_tab;
const KTDatatableRemoteAjaxDemoAllRegTab = function() {

    const kt_datatable_all_reg_tab = function() {
        _kt_datatable_all_reg_tab = $("#kt_datatable_all_reg_tab").DataTable({
            serverSide: true,
            ajax: {
                type: "POST",
                url: root + 'app/controllers/UserController.php',
                data: function (d){
                    d.action = "getRegisters"
                    d.allRegCreation = $('#all-reg-creation').val();
                    d.allRegStart = $('#all-reg-start').val();
                    d.allRegEnd = $('#all-reg-end').val();
                },
            },
            paging: true,
            info: false,
            pageLength: 10,
            lengthChange: true,
            columns: [
                {data: 'nome'},
                {data: 'data_creazione'},
                {data: 'data_inizio'},
                {data: 'data_fine'},
                {data: null,
                    render: function(data) {
                        let buttons = '';
                        for(let i=0; i < data.azioni.length; i++) {
                            buttons += '<div class="position-relative d-inline-block">' +
                                '<a id="' + data.azioni[i]['nome'] + '-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" href="' + root + 'app/modules/register/single-register.php?id=' + data.id + '" class="rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></a>' +
                                '<span id="tooltip-' + data.azioni[i]['nome'] + '-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                        }
                        return buttons;
                    }
                },

            ],
        })

        _kt_datatable_all_reg_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_all_reg_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_all_reg_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoAllRegTab.init();
}));