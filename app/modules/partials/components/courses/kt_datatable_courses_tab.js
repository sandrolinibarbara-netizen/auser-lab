"use strict";
let _kt_datatable_courses_tab;
let currentTeacher;
const KTDatatableRemoteAjaxDemoCoursesTab = function() {

    const kt_datatable_courses_tab = function() {
        _kt_datatable_courses_tab = $("#kt_datatable_courses_tab").DataTable({
            serverSide: true,
            ajax: {
                type: "POST",
                url: root + 'app/controllers/UserController.php',
                data: function (d){
                    d.action = 'getCourses';
                    d.courseCreation = $('#courses-creation').val();
                    d.courseStart = $('#courses-start').val();
                    d.courseEnd = $('#courses-end').val();
                    d.courseTeacher = $('#courses-teachers').val();
                },
                dataSrc: function (data) {
                    currentTeacher = $('#courses-teachers').val();
                    const dropdownNames = $("#courses-teachers");
                    dropdownNames.empty();
                    const option = document.createElement('option');
                    dropdownNames.append(option);
                    const teachers = data.allTeachers.map((el) => (
                        {'nome': el['insegnante'], 'id': el['id']}
                    )).filter((el,i,arr) => i === arr.findIndex((t) => (t.nome === el.nome && t.id === el.id)));
                    teachers.forEach((opt) => dropdownNames.append($("<option />").val(opt.id).text(opt.nome)));
                    $('#courses-teachers').val(currentTeacher);
                    console.log(data.data)
                    return data.data;
                }
            },
            paging: true,
            info: false,
            pageLength: 10,
            lengthChange: true,
            columns: [
                {data: 'nome'},
                {data: 'data_creazione'},
                {data: null,
                    render: function(data) {
                        if(data.data_inizio === '01/01/3000') {
                            return '-';
                        }
                        return data.data_inizio;
                    }},
                {data: null,
                    render: function(data) {
                        if(data.data_fine === '01/01/3000') {
                            return '-';
                        }
                        return data.data_fine;
                    }},
                {data: null,
                    render: function(data) {
                        if(data.minimo_studenti === 0 && data.massimo_studenti === 0) {
                            return '-';
                        }
                        return data.minimo_studenti;
                    }},
                {data: null,
                    render: function(data) {
                        if(data.minimo_studenti === 0 && data.massimo_studenti === 0) {
                            return '-';
                        }
                        return data.massimo_studenti;
                    }},
                {data: null,
                    render: function(data) {
                        let buttons = '';
                        for(let i=0; i < data.azioni.length; i++) {
                            const action = data.azioni[i]['nome'] === 'Visualizza'
                                ? 'get'
                                : data.azioni[i]['nome'] === 'Copia'
                                    ? 'clone'
                                    : 'delete';
                            if(data.azioni[i]['nome'] === 'Elimina') {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<button id="' + data.azioni[i]['nome'] + '-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" data-bs-id="course-' + data.id +'" value="' + data.id +'" type="button" data-bs-toggle="modal" data-bs-target="#modal-remove" class="border-0 rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></button>' +
                                    '<span id="tooltip-' + data.azioni[i]['nome'] + '-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                            } else if(data.azioni[i]['nome'] === 'Copia') {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<button id="' + data.azioni[i]['nome'] + '-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" data-bs-id="course-' + data.id +'" value="' + data.id +'" type="button" data-bs-toggle="modal" data-bs-target="#clone-course-modal" class="border-0 rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></button>' +
                                    '<span id="tooltip-' + data.azioni[i]['nome'] + '-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                            } else {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<a id="' + data.azioni[i]['nome'] + '-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" href="' + root + 'corso?' + action + '=course&id=' + data.id + '" class="rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 ' + data.azioni[i]['icona'] + '"></a>' +
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
                    targets: [1, 2, 3, 4, 5],
                    className: 'text-center'
                }
            ]
        })

        _kt_datatable_courses_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_courses_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_courses_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoCoursesTab.init();
}));