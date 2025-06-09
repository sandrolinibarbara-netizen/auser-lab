"use strict";
let _kt_datatable_homeworks_tab;
const KTDatatableRemoteAjaxDemoHomeworksTab = function() {
    const kt_datatable_homeworks_tab = function() {
        _kt_datatable_homeworks_tab = $("#kt_datatable_homeworks_tab").DataTable({
            serverSide: true,
            ajax: {
                type: "POST",
                url: root + 'app/controllers/UserController.php',
                data: function (d){
                    d.lessonName = $('#homeworks-lesson-names').val();
                    d.courseName = $('#homeworks-course-names').val();
                    d.action = 'getAllHomeworks'
                },
                dataSrc: function (data) {
                    const $dropdownLessons = $("#homeworks-lesson-names");
                    const $dropdownNames = $("#homeworks-course-names");
                    $dropdownLessons.empty();
                    $dropdownNames.empty();
                    const lessons = data.data.map((el) => (
                        {'nome': el['diretta'], 'id': el['id_diretta']}
                    )).filter((el,i,arr) => i === arr.findIndex((t) => (t.nome === el.nome && t.id === el.id)));
                    console.log(lessons);
                    const courses = data.data.map((el) => (
                        {'nome': el['corso'], 'id': el['id_corso']}
                    )).filter((el,i,arr) => i === arr.findIndex((t) => (t.nome === el.nome && t.id === el.id)));
                    lessons.forEach((opt) => $dropdownLessons.append($("<option />").val(opt.id).text(opt.nome)));
                    courses.forEach((opt) => $dropdownNames.append($("<option />").val(opt.id).text(opt.nome)));
                    $('#homeworks-lesson-names').val('').trigger('change');
                    $('#homeworks-course-names').val('').trigger('change');
                    return data.data;
                }
            },
            paging: true,
            info: false,
            pageLength: 10,
            lengthChange: true,
            columns: [
                {data: 'nome'},
                {
                    data: null,
                    render: (data) => '<p>' + (data.id_tipologia == 6 ? 'Dispensa' : 'Quiz') + '</p>'
                },
                {data: 'diretta'},
                {data: 'corso'},
                {data: 'data'},
                {data: null,
                    render: function(data) {
                        let buttons = '';
                        for(let i=0; i < data.azioni.length; i++) {
                            if(data.azioni[i]['nome'] === 'Visualizza' && data.id_tipologia === 6) {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<button id="view-lecture-'+ data.id +'" data-bs-target="#lecture-note-modal" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" data-bs-toggle="modal" data-bs-idDispensa="' + data.id + '" value="' + data.id + '-' + data.id_tipologia +'" type="button" class="border-0 rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></button>' +
                                    '<span id="tooltip-view-lecture-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                            } else if(data.azioni[i]['nome'] === 'Visualizza' && data.id_tipologia === 7) {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<button '+ (data.done ? "" : "disabled ") +'id="view-poll-'+ data.id +'" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" data-bs-target="#poll-modal" data-bs-toggle="modal" data-bs-idPoll="' + data.id +'" value="' + data.id +'" type="button" class="border-0 rounded text-auser text-decoration-none p-2 ki-outline '+ (data.done ? "bg-light-bg" : "bg-gray-200") +' me-1 '+ data.azioni[i]['icona'] +'"></button>' +
                                    '<span id="tooltip-view-poll-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                            } else if(data.azioni[i]['nome'] === 'Correggi') {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<button '+ (data.done ? "disabled " : "") +'id="fill-poll-'+ data.id +'" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" data-bs-target="#poll-modal" data-bs-toggle="modal" data-bs-idPoll="' + data.id +'" value="' + data.id +'" type="button" class="border-0 rounded text-auser text-decoration-none p-2 ki-outline '+ (data.done ? "bg-gray-200" : "bg-light-bg") +' me-1 '+ data.azioni[i]['icona'] +'"></button>' +
                                    '<span id="tooltip-fill-poll-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">Compila</span></div>'
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

        _kt_datatable_homeworks_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_homeworks_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_homeworks_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoHomeworksTab.init();
}));
