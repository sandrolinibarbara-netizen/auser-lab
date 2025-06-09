"use strict";
const root = document.getElementById('root').getAttribute('value');
let _kt_datatable_drafts_lessons_tab;
const KTDatatableRemoteAjaxDemoDraftsLessonsTab = function() {
    const search = window.location.search;
    const params = new URLSearchParams(search);
    const course = params.get("id");
    const kt_datatable_drafts_lessons_tab = function() {
        _kt_datatable_drafts_lessons_tab = $("#kt_datatable_drafts_lessons_tab").DataTable({
            serverSide: true,
            ajax: {
                type: "POST",
                url: root + 'app/controllers/CourseController.php',
                data: function(d){
                    d.course = course;
                    d.action = 'getDraftLessons';
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
                        let href = data.azioni[i]['nome'] === 'Modifica'
                                    ? root + "update-lesson?id=" + course + "&lesson=" + data.id
                                    : root + "update-lesson/delete?delete=lesson&id=" + data.id
                        if(data.azioni[i]['nome'] === 'Modifica' && data.data_inizio === '3000-01-01') href += '&ondemand=true'
                        if(data.azioni[i]['nome'] === 'Elimina') {
                            buttons += '<div class="position-relative d-inline-block">' +
                                '<button id="' + data.azioni[i]['nome'] + '-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" data-bs-id="lesson-' + data.id +'" value="' + data.id +'" type="button" data-bs-toggle="modal" data-bs-target="#modal-remove" class="border-0 rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></button>' +
                                '<span id="tooltip-' + data.azioni[i]['nome'] + '-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                        } else {
                            buttons += '<div class="position-relative d-inline-block">' +
                                '<a id="' + data.azioni[i]['nome'] + '-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" href="'+ href +'" class="rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 ' + data.azioni[i]['icona'] + '"></a>' +
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
            ]
        })

        _kt_datatable_drafts_lessons_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_drafts_lessons_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_drafts_lessons_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoDraftsLessonsTab.init();
}));