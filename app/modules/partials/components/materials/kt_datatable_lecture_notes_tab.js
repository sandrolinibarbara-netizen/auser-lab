"use strict";
let _kt_datatable_lecture_notes_tab;
let currentLectureLesson;
let currentLectureCourse;
let currentLectureTeacher;
const KTDatatableRemoteAjaxDemoLectureNotesTab = function() {

    const kt_datatable_lecture_notes_tab = function() {
        _kt_datatable_lecture_notes_tab = $("#kt_datatable_lecture_notes_tab").DataTable({
            serverSide: true,
            ajax: {
                type: "POST",
                url: root + 'app/controllers/GeneralGetterController.php',
                data: function (d){
                    d.lessonName = $('#lecture-notes-lesson-names').val();
                    d.courseName = $('#lecture-notes-course-names').val();
                    d.teacherName = $('#lecture-notes-teachers').val();
                    d.action = 'getAllLectureNotes'
                },
                dataSrc: function (data) {
                    currentLectureLesson = $('#lecture-notes-lesson-names').val();
                    currentLectureCourse = $('#lecture-notes-course-names').val();
                    currentLectureTeacher = $('#lecture-notes-teachers').val();
                    const dropdownLessons = $("#lecture-notes-lesson-names");
                    const dropdownNames = $("#lecture-notes-course-names");
                    const dropdownTeachers = $("#lecture-notes-teachers")
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

                    $('#lecture-notes-lesson-names').val(currentLectureLesson);
                    $('#lecture-notes-course-names').val(currentLectureCourse);
                    $('#lecture-notes-teachers').val(currentLectureTeacher);

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
                                    : 'delete';
                            if(data.azioni[i]['nome'] === 'Elimina') {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<button id="' + data.azioni[i]['nome'] + '-lecture-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" data-bs-id="lecture-' + data.id +'" value="' + data.id +'" type="button" data-bs-toggle="modal" data-bs-target="#modal-remove" class="border-0 rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></button>' +
                                    '<span id="tooltip-' + data.azioni[i]['nome'] + '-lecture-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                            } else {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<a id="' + data.azioni[i]['nome'] + '-lecture-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" href="' + root + 'update-lecture-note?' + action + '=lecture-note&id=' + data.id + '&type=1" class="rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 ' + data.azioni[i]['icona'] + '"></a>' +
                                    '<span id="tooltip-' + data.azioni[i]['nome'] + '-lecture-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
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

        _kt_datatable_lecture_notes_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_lecture_notes_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_lecture_notes_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoLectureNotesTab.init();
}));