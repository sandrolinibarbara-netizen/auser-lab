"use strict";
let _kt_datatable_events_tab;
let currentLocEvent;
const root = document.getElementById('root').getAttribute('value');
const KTDatatableRemoteAjaxDemoEventTab = function() {

    const kt_datatable_events_tab = function() {
        _kt_datatable_events_tab = $("#kt_datatable_events_tab").DataTable({
            serverSide: true,
            ajax: {
                type: "POST",
                url: root + 'app/controllers/FutureEventsController.php',
                data: function (d){
                    d.action = 'getFutureEvents';
                    d.eventDate = $('#events-dates').val();
                    d.eventHour = $('#events-hours').val();
                    d.eventLoc = $('#events-location').val();
                },
                dataSrc: function (data) {
                    currentLocEvent = $('#events-location').val();
                    const dropdownLoc = $("#events-location");
                    dropdownLoc.empty();
                    const optionLoc = document.createElement('option');
                    dropdownLoc.append(optionLoc);
                    const locations = data.data.map((el) => el['luogo']).filter((el,i,arr) => arr.indexOf(el) === i);
                    locations.forEach((opt) => dropdownLoc.append($("<option />").val(opt).text(opt)));
                    $('#events-location').val(currentLocEvent);
                    return data.data;
                }
            },
            paging: true,
            info: false,
            pageLength: 10,
            lengthChange: true,
            columns: [
                {data: 'nome'},
                {data: 'data_inizio'},
                {data: null,
                render: (data) => '<p class="mb-0">'+ data.orario_inizio + ' - ' + data.orario_fine +'</p>'},
                {data: 'posti'},
                {data: 'luogo'},
                {data: null,
                    render: function(data) {
                        if(data.url){
                            let buttons = '';
                            for (let i = 0; i < data.azioni.length; i++) {
                                buttons += '<div class="position-relative">' +
                                    '<a id="' + data.azioni[0]['nome'] + '-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" href="' + root + 'watch?live=event&id=' + data.id + '" class="rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></a>' +
                                    '<span id="tooltip-' + data.azioni[0]['nome'] + '-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[0]['nome'] + '</span></div>'
                            }
                            return buttons;
                        } if(data.zoom_meeting){
                            let buttons = '';
                            for (let i = 0; i < data.azioni.length; i++) {
                                buttons += '<div class="position-relative">' +
                                    '<a id="' + data.azioni[0]['nome'] + '-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" href="' + root + 'watch?live=event&id=' + data.id + '" class="rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></a>' +
                                    '<span id="tooltip-' + data.azioni[0]['nome'] + '-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[0]['nome'] + '</span></div>'
                            }
                            return buttons;
                        } else {
                            return '<p>-</p>'
                        }
                    }
                }
            ],
            columnDefs: [
                {
                    targets: '_all',
                    orderable: false,
                },
                {
                    targets: [3, 5],
                    className: 'text-center'
                }
            ]
        })

        _kt_datatable_events_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_events_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_events_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoEventTab.init();
}));