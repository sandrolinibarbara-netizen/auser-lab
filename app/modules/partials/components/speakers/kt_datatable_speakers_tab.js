"use strict";
const root = document.getElementById('root').getAttribute('value');
let _kt_datatable_speakers_tab;
const KTDatatableRemoteAjaxDemoSpeakersTab = function() {

    const kt_datatable_speakers_tab = function() {
        _kt_datatable_speakers_tab = $("#kt_datatable_speakers_tab").DataTable({
            serverSide: true,
            ajax: {
                type: "POST",
                url: root + 'app/controllers/GeneralGetterController.php',
                data: function(d) {
                    d.action = 'getSpeakers';
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
                render: (data) => '<img class="w-100px h-100px rounded-circle" style="object-fit: cover; object-position: top" alt="' + data.nome + '-logo" src="' + (data.pic ? root + 'app/assets/uploaded-files/speakers-images/' + data.pic : data.avatar) + '"/>'
                },
                {data: 'speaker'},
                {data: 'system_date_modified'},
                {data: null,
                    render: function(data) {
                        let buttons = '';
                        for(let i=0; i < data.azioni.length; i++) {
                            if(data.azioni[i]['nome'] === 'Elimina') {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<button id="' + data.azioni[i]['nome'] + '-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" data-bs-id="speaker-' + data.id +'" value="' + data.id +'" type="button" data-bs-toggle="modal" data-bs-target="#modal-remove" class="border-0 rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></button>' +
                                    '<span id="tooltip-' + data.azioni[i]['nome'] + '-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                            } else {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<a id="' + data.azioni[i]['nome'] + '-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" href="' + root + 'update-speaker?update=speaker&id=' + data.id + '" class="rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 ' + data.azioni[i]['icona'] + '"></a>' +
                                    '<span id="tooltip-' + data.azioni[i]['nome'] + '-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
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
                },
                {
                    targets: 0,
                    className: "text-center"
                }
            ]
        })

        _kt_datatable_speakers_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_speakers_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_speakers_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoSpeakersTab.init();
}));