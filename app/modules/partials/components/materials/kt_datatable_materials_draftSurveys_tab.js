"use strict";
let _kt_datatable_materials_draftSurveys_tab;
const KTDatatableRemoteAjaxDemoMaterialsDraftSurveysTab = function() {

    const kt_datatable_materials_draftSurveys_tab = function() {
        _kt_datatable_materials_draftSurveys_tab = $("#kt_datatable_materials_draftSurveys_tab").DataTable({
            serverSide: true,
            ajax: {
                type: "POST",
                url: root + 'app/controllers/GeneralGetterController.php',
                data: function(d) {
                    d.action = 'getDraftSurveys';
                },
                dataSrc: function (data) {
                    return data.data;
                }
            },
            paging: false,
            info: false,
            columns: [
                {data: 'nome'},
                {data: 'data'},
                {data: null,
                    render: function(data) {
                        let buttons = '';
                        for(let i=0; i < data.azioni.length; i++) {
                            if(data.azioni[i]['nome'] === 'Elimina') {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<button id="' + data.azioni[i]['nome'] + '-survey-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" data-bs-id="survey-' + data.id + '" value="' + data.id + '" type="button" data-bs-toggle="modal" data-bs-target="#modal-remove" class="border-0 rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 ' + data.azioni[i]['icona'] + '"></button>' +
                                    '<span id="tooltip-' + data.azioni[i]['nome'] + '-survey-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                            } else {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<a id="' + data.azioni[i]['nome'] + '-survey-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" href="' + root + 'update-survey?update=survey&id=' + data.id +'" class="rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></a>' +
                                    '<span id="tooltip-' + data.azioni[i]['nome'] + '-survey-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                            }
                        }
                        return buttons;
                    }
                }
            ],
            columnDefs: [
                {
                    targets: '_all',
                    orderable: false,
                }
            ]
        })

        _kt_datatable_materials_draftSurveys_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_materials_draftSurveys_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_materials_draftSurveys_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoMaterialsDraftSurveysTab.init();
}));