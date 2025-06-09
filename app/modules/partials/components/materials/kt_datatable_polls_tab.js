"use strict";
let _kt_datatable_polls_tab;
let currentPollLesson;
let currentPollCourse;
let currentPollTeacher;
const KTDatatableRemoteAjaxDemoPollsTab = function() {
console.log($('#polls-course-names').val())
    const kt_datatable_polls_tab = function() {
        _kt_datatable_polls_tab = $("#kt_datatable_polls_tab").DataTable({
            serverSide: true,
            ajax: {
                type: "POST",
                url: root + 'app/controllers/GeneralGetterController.php',
                data: function (d){
                    d.lessonName = $('#polls-lesson-names').val();
                    d.courseName = $('#polls-course-names').val();
                    d.teacherName = $('#polls-teachers').val();
                    d.action = 'getAllPolls'
                },
                dataSrc: function (data) {
                    currentPollLesson = $('#polls-lesson-names').val();
                    currentPollCourse = $('#polls-course-names').val();
                    currentPollTeacher = $('#polls-teachers').val();
                    const dropdownLessons = $("#polls-lesson-names");
                    const dropdownNames = $("#polls-course-names");
                    const dropdownTeachers = $("#polls-teachers");
                    dropdownTeachers.empty();
                    dropdownLessons.empty();
                    dropdownNames.empty();
                    const optionLesson = document.createElement('option');
                    dropdownLessons.append(optionLesson);
                    const optionCourse = document.createElement('option');
                    dropdownNames.append(optionCourse);
                    const optionTeacher = document.createElement('option');
                    dropdownTeachers.append(optionTeacher);
                    const lessons = data.data.map((el) => (
                        {'nome': el['diretta'], 'id': el['id_diretta']}
                    )).filter((el,i,arr) => i === arr.findIndex((t) => (t.nome === el.nome && t.id === el.id)));
                    const courses = data.data.map((el) => (
                        {'nome': el['corso'], 'id': el['id_corso']}
                    )).filter((el,i,arr) => i === arr.findIndex((t) => (t.nome === el.nome && t.id === el.id)));
                    const teachers = data.allTeachers.map((el) => (
                        {'nome': el['insegnante'], 'id': el['id']}
                    )).filter((el,i,arr) => i === arr.findIndex((t) => (t.nome === el.nome && t.id === el.id)));
                    teachers.forEach((opt) => dropdownTeachers.append($("<option />").val(opt.id).text(opt.nome)));
                    lessons.forEach((opt) => dropdownLessons.append($("<option />").val(opt.id).text(opt.nome)));
                    courses.forEach((opt) => dropdownNames.append($("<option />").val(opt.id).text(opt.nome)));

                    $('#polls-lesson-names').val(currentPollLesson);
                    $('#polls-course-names').val(currentPollCourse);
                    $('#polls-teachers').val(currentPollTeacher);

                    data.data.forEach(el => {
                        document.addEventListener("click", function(e){
                            const target = e.target.closest(`#poll-${el['idPoll']}`);

                            if(target){
                                console.log(target.getAttribute('id'))
                                    $.ajax({
                                        type: 'POST',
                                        data: {
                                          'idPoll': target.getAttribute('id').split('-')[1],
                                            'action': 'downloadPoll'
                                        },
                                        url: root + 'app/controllers/PollController.php',
                                        success: function(data) {
                                            const parsed = JSON.parse(data);
                                            console.log(parsed)
                                            $('#poll-name').empty();
                                            $('#download-modal').modal('show');
                                            const linkBox = document.getElementById('poll-name');
                                            const link = document.createElement('a');
                                            const fileName = parsed.split('/');
                                            link.textContent = fileName[fileName.length - 1];
                                            link.classList.add('d-inline-block', 'fs-3')
                                            link.setAttribute('href', parsed);
                                            link.setAttribute('download', 'download');
                                            linkBox.append(link);
                                        }
                                    })

                            }
                        });
                    })
                    return data.data;
                }
            },
            paging: true,
            info: false,
            pageLength: 10,
            lengthChange: true,
            columns: [
                {data: 'nome'},
                {data: 'diretta'},
                {data: 'corso'},
                {data: 'data'},
                {data: null,
                    render: function(data) {
                        let buttons = '';
                        for(let i=0; i < data.azioni.length; i++) {
                            const action = data.azioni[i]['nome'] === 'Modifica'
                                ? 'update'
                                : data.azioni[i]['nome'] === 'Copia'
                                    ? 'clone'
                                    : data.azioni[i]['nome'] === 'Correggi'
                                        ? 'download'
                                        : 'delete';
                            if(data.azioni[i]['nome'] === 'Correggi') {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<button id="poll-' + data.idPoll +'" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" value="' + data.idPoll +'" type="button" class="border-0 rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></button>' +
                                    '<span id="tooltip-poll-' + data.idPoll + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                            } else if(data.azioni[i]['nome'] === 'Elimina') {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<button id="' + data.azioni[i]['nome'] + '-poll-' + data.idPoll + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" data-bs-id="poll-' + data.idPoll +'" value="' + data.idPoll +'" type="button" data-bs-toggle="modal" data-bs-target="#modal-remove" class="border-0 rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></button>' +
                                    '<span id="tooltip-' + data.azioni[i]['nome'] + '-poll-' + data.idPoll + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                            } else if(data.azioni[i]['nome'] === 'QR Code') {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<button id="' + data.azioni[i]['nome'] + '-poll-' + data.idPoll + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" data-bs-id="poll-' + data.idPoll +'" value="' + data.idPoll +'" type="button" data-bs-toggle="modal" data-bs-target="#qr-modal" class="border-0 rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></button>' +
                                    '<span id="tooltip-' + data.azioni[i]['nome'] + '-poll-' + data.idPoll + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%); width: max-content">' + data.azioni[i]['nome'] + '</span></div>'
                            } else {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<a id="' + data.azioni[i]['nome'] + '-poll-' + data.idPoll + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" href="' + root + 'update-poll?' + action + '=poll&id=' + data.idPoll +'&type=1" type="button" class="border-0 rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></a>' +
                                    '<span id="tooltip-' + data.azioni[i]['nome'] + '-poll-' + data.idPoll + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
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

        _kt_datatable_polls_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_polls_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_polls_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoPollsTab.init();
}));
