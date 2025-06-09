"use strict";
let _kt_datatable_my_events_tab;
let currentLocation;
const root = document.getElementById('root').getAttribute('value');
const KTDatatableRemoteAjaxDemoMyEventsTab = function() {

    const kt_datatable_my_events_tab = function() {
        _kt_datatable_my_events_tab = $("#kt_datatable_my_events_tab").DataTable({
            serverSide: true,
            ajax: {
                type: "POST",
                url: root + 'app/controllers/GeneralGetterController.php',
                data: function (d){
                    d.action = 'getEvents';
                    d.eventDate = $('#my-events-dates').val();
                    d.eventHour = $('#my-events-hours').val();
                    d.eventLoc = $('#my-events-location').val();
                },
                dataSrc: function (data) {
                    currentLocation = $('#my-events-location').val();
                    const dropdownLoc = $("#my-events-location");
                    dropdownLoc.empty();
                    const option = document.createElement('option');
                    dropdownLoc.append(option);
                    const locations = data.data.map((el) => el['luogo']).filter((el,i,arr) => arr.indexOf(el) === i);
                    locations.forEach((opt) => dropdownLoc.append($("<option />").val(opt).text(opt)));
                    $('#my-events-location').val(currentLocation);
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
                        if(data.data_inizio === '01/01/3000') {
                            return '-';
                        }
                        return data.data_inizio;
                    }},
                {data: null,
                    render: function(data) {
                        if(data.data_inizio === '01/01/3000') {
                            return '-';
                        }
                        return '<p class="mb-0">'+ data.orario_inizio + ' - ' + data.orario_fine +'</p>';
                    }},
                {data: null,
                    render: function(data) {
                        if(data.posti < 0) {
                            return '-';
                        }
                        return data.posti;
                    }},
                {data: 'luogo'},
                {data: null,
                    render: function(data) {
                        let buttons = '';
                        for(let i=0; i < data.azioni.length; i++) {
                            let href = root +'update-event?id=' + data.id +'&type=1';
                            if(data.data_inizio === '01/01/3000') href += '&ondemand=true';
                            if(data.azioni[i]['nome'] === 'Elimina') {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<button id="' + data.azioni[i]['nome'] + '-event-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" data-bs-id="event-' + data.id +'" value="' + data.id +'" type="button" data-bs-toggle="modal" data-bs-target="#modal-remove" class="border-0 rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></button>' +
                                    '<span id="tooltip-' + data.azioni[i]['nome'] + '-event-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                            } else if(data.azioni[i]['nome'] === 'Modifica') {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<a id="' + data.azioni[i]['nome'] + '-event-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" href="'+ href +'" class="rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></a>' +
                                    '<span id="tooltip-' + data.azioni[i]['nome'] + '-event-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                            } else if(data.azioni[i]['nome'] === 'Copia') {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<a id="' + data.azioni[i]['nome'] + '-event-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" href="' + root + 'evento?clone=event&id=' + data.id + '" class="rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 ' + data.azioni[i]['icona'] + '"></a>' +
                                    '<span id="tooltip-' + data.azioni[i]['nome'] + '-event-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                            } else if(data.azioni[i]['nome'] === 'Visualizza') {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<a id="' + data.azioni[i]['nome'] + '-event-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" href="' + root + 'evento?get=event&id=' + data.id + '" class="rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 ' + data.azioni[i]['icona'] + '"></a>' +
                                    '<span id="tooltip-' + data.azioni[i]['nome'] + '-event-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                            } else {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<button id="' + data.azioni[i]['nome'] + '-event-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" data-bs-id="event-' + data.id +'" value="' + data.id +'" type="button" data-bs-toggle="modal" data-bs-target="#modal-add-users" class="border-0 rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></button>' +
                                    '<span id="tooltip-' + data.azioni[i]['nome'] + '-event-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%); width: max-content">' + data.azioni[i]['nome'] + '</span></div>'
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
                    targets: [1, 2, 3, 4, 5],
                    className: 'text-center'
                }
            ]
        })

        _kt_datatable_my_events_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_my_events_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_my_events_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoMyEventsTab.init();
}));