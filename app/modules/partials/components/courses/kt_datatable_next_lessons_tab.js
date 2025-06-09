"use strict";
let _kt_datatable_next_lessons_tab;
const KTDatatableRemoteAjaxDemoNextLessonsTab = function() {
    const search = window.location.search;
    const params = new URLSearchParams(search);
    const course = params.get("id");
    const kt_datatable_next_lessons_tab = function() {
        _kt_datatable_next_lessons_tab = $("#kt_datatable_next_lessons_tab").DataTable({
            serverSide: true,
            ajax: {
                type: "POST",
                url: root + 'app/controllers/FutureEventsController.php',
                data: function (d){
                    d.action = "getAllLessons"
                    d.courseName = $('#courses-names').val();
                    d.lessonDate = $('#lessons-dates').val();
                    d.lessonHour = $('#lessons-hours').val();
                    d.lessonLoc = $('#lessons-location').val();
                    d.course = course;
                },
                dataSrc: function (data) {
                    const $dropdownNames = $("#courses-names");
                    const $dropdownLoc = $("#lessons-location");
                    $dropdownNames.empty();
                    $dropdownLoc.empty();
                    const locations = data.data.map((el) => el['luogo']).filter((el,i,arr) => arr.indexOf(el) === i);
                    const courses = data.data.map((el) => (
                        {'nome': el['nome_corso'], 'id': el['id_corso']}
                    )).filter((el,i,arr) => i === arr.findIndex((t) => (t.nome === el.nome && t.id === el.id)));
                    console.log(data)
                    courses.forEach((opt) => $dropdownNames.append($("<option />").val(opt.id).text(opt.nome)));
                    locations.forEach((opt) => $dropdownLoc.append($("<option />").val(opt).text(opt)));
                    $('#courses-names').val('').trigger('change');
                    $('#lessons-dates').val('').trigger('change');
                    $('#lessons-hours').val('').trigger('change');
                    $('#lessons-location').val('').trigger('change');
                    return data.data;
                }
            },
            paging: true,
            info: false,
            pageLength: 10,
            lengthChange: true,
            columns: [
                {data: 'nome_lezione'},
                {data: 'nome_corso'},
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
                        return data.orario_inizio;
                    }},
                {data: null,
                    render: function(data) {
                        if(data.data_inizio === '01/01/3000') {
                            return '-';
                        }
                        return data.luogo;
                    }},
                {data: null,
                render: function(data) {
                    let buttons = '';
                    for(let i=0; i < data.azioni.length; i++) {
                        let href = data.azioni[i]['nome'] === 'Stream'
                                                                            ? root + "watch?live=stream&id=" + data.idDiretta
                                                                            : data.azioni[i]['nome'] === 'Modifica'
                                                                                ? root + "update-lesson?id=" + course + "&lesson=" + data.idDiretta + "&type=1"
                                                                                : data.azioni[i]['nome'] === 'Copia'
                                                                                    ? root + "update-lesson/update?clone=lesson&id=" + data.idDiretta
                                                                                    : root + "update-lesson/delete?delete=lesson&id=" + data.idDiretta
                        if(data.azioni[i]['nome'] === 'Modifica' && data.data_inizio === '01/01/3000') href += '&ondemand=true'
                        if(data.azioni[i]['nome'] === 'Copia'){
                            buttons += '<div class="position-relative d-inline-block">' +
                                '<button id="' + data.azioni[i]['nome'] + '-' + data.idDiretta + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" value="' + course + '-' + data.idDirettaDiretta + '" data-bs-toggle="modal" data-bs-target="#clone-lesson-modal" class="border-0 rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 ' + data.azioni[i]['icona'] + '"></button>' +
                            '<span id="tooltip-' + data.azioni[i]['nome'] + '-' + data.idDiretta + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                        } else if(data.azioni[i]['nome'] === 'Elimina') {
                            buttons += '<div class="position-relative d-inline-block">' +
                                '<button id="' + data.azioni[i]['nome'] + '-' + data.idDiretta + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" data-bs-id="lesson-' + data.idDirettaDiretta +'" value="' + data.idDirettaDiretta +'" type="button" data-bs-toggle="modal" data-bs-target="#modal-remove" class="border-0 rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></button>' +
                            '<span id="tooltip-' + data.azioni[i]['nome'] + '-' + data.idDiretta + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                        } else if(data.azioni[i].length === 0) {
                            buttons += '<p class="position-relative d-inline-block">-</p>'
                        } else {
                            buttons += '<div class="position-relative d-inline-block">' +
                                '<a id="' + data.azioni[i]['nome'] + '-' + data.idDiretta + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" href="' + href + '" class="rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 ' + data.azioni[i]['icona'] + '"></a>' +
                            '<span id="tooltip-' + data.azioni[i]['nome'] + '-' + data.idDiretta + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
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
                    targets: [2, 3, 4, 5],
                    className: 'text-center'
                }
            ]
        })

        _kt_datatable_next_lessons_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_next_lessons_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_next_lessons_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoNextLessonsTab.init();
}));