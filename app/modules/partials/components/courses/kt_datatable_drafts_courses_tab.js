"use strict";
let _kt_datatable_drafts_courses_tab;
const KTDatatableRemoteAjaxDemoDraftsCoursesTab = function() {
    const kt_datatable_drafts_courses_tab = function() {
        _kt_datatable_drafts_courses_tab = $("#kt_datatable_drafts_courses_tab").DataTable({
            serverSide: true,
            ajax: {
                type: "POST",
                url: root + 'app/controllers/UserController.php',
                data: function(d) {
                    d.action = "getDraftCourses"
                },
                dataSrc: function (data) {
                    console.log(data)
                    return data.data;
                }
            },
            paging: false,
            info: false,
            columns: [
                {data: 'nome'},
                {data: null,
                render: function(data) {
                    if(data.id_categoria === 2) {
                        return '<p class="mb-0">Evento</p>'
                    } else {
                        return '<p class="mb-0">Corso</p>'
                    }
                }},
                {data: 'data'},
                {data: null,
                render: function(data) {
                    let buttons = '';
                    for(let i=0; i < data.azioni.length; i++) {

                        if(data.azioni[i]['nome'] === 'Elimina') {
                            if(data.id_categoria === 2) {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<button id="' + data.azioni[i]['nome'] + '-event-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" data-bs-id="event-' + data.id +'" value="' + data.id +'" type="button" data-bs-toggle="modal" data-bs-target="#modal-remove" class="border-0 rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></button>' +
                                    '<span id="tooltip-' + data.azioni[i]['nome'] + '-event-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                            } else {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<button id="' + data.azioni[i]['nome'] + '-course-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" data-bs-id="course-' + data.id +'" value="' + data.id +'" type="button" data-bs-toggle="modal" data-bs-target="#modal-remove" class="border-0 rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></button>' +
                                    '<span id="tooltip-' + data.azioni[i]['nome'] + '-course-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                            }
                        } else {
                            if(data.id_categoria === 2) {
                                let href = root +'update-event?id=' + data.id;
                                if(data.data_inizio === '3000-01-01') href += '&ondemand=true'
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<a id="' + data.azioni[i]['nome'] + '-event-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" href="'+ href +'" class="rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></a>' +
                                    '<span id="tooltip-' + data.azioni[i]['nome'] + '-event-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                            } else {
                                let href = root +'update-course?' + (data.azioni[i]['nome'] === 'Modifica' ? 'update' : '') + '=course&id=' + data.id;
                                if(data.data_inizio === '3000-01-01') href += '&ondemand=true';
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<a id="' + data.azioni[i]['nome'] + '-event-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" href="'+ href + '" class="rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></a>' +
                                    '<span id="tooltip-' + data.azioni[i]['nome'] + '-event-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                            }
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

        _kt_datatable_drafts_courses_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_drafts_courses_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_drafts_courses_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoDraftsCoursesTab.init();
}));