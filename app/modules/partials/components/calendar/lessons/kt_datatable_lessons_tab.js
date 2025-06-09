"use strict";
let _kt_datatable_lessons_tab;
let currentLocation;
let currentCourse;
const KTDatatableRemoteAjaxDemoLessonsTab = function() {

    const kt_datatable_lessons_tab = function() {
        _kt_datatable_lessons_tab = $("#kt_datatable_lessons_tab").DataTable({
            serverSide: true,
            ajax: {
                type: "POST",
                url: root + 'app/controllers/FutureEventsController.php',
                data: function (d){
                    d.action = 'getFutureLessons';
                    d.courseName = $('#courses-names').val();
                    d.lessonDate = $('#lessons-dates').val();
                    d.lessonHour = $('#lessons-hours').val();
                    d.lessonLoc = $('#lessons-location').val();
                },
                dataSrc: function (data) {
                    currentCourse = $('#courses-names').val();
                    currentLocation = $('#lessons-location').val();
                    const dropdownNames = $("#courses-names");
                    const dropdownLoc = $("#lessons-location");
                    dropdownNames.empty();
                    dropdownLoc.empty();
                    const optionLoc = document.createElement('option');
                    dropdownLoc.append(optionLoc);
                    const optionCourse = document.createElement('option');
                    dropdownNames.append(optionCourse);
                    const locations = data.data.map((el) => el['luogo']).filter((el,i,arr) => arr.indexOf(el) === i);
                    const courses = data.data.map((el) => (
                        {'nome': el['nome_corso'], 'id': el['id_corso']}
                    )).filter((el,i,arr) => i === arr.findIndex((t) => (t.nome === el.nome && t.id === el.id)));
                    courses.forEach((opt) => dropdownNames.append($("<option />").val(opt.id).text(opt.nome)));
                    locations.forEach((opt) => dropdownLoc.append($("<option />").val(opt).text(opt)));
                    $('#courses-names').val(currentCourse);
                    $('#lessons-location').val(currentLocation);
                    console.log(data.data)
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
                {data: 'data_inizio'},
                {data: 'orario_inizio'},
                {data: 'luogo'},
                {data: null,
                    render: function(data) {
                        if(data.url || data.path_video || data.zoom_meeting) {
                            let buttons = '';
                            for(let i=0; i < data.azioni.length; i++) {
                                buttons += '<div class="position-relative">' +
                                    '<a id="' + data.azioni[0]['nome'] + '-' + data.idDiretta + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" href="' + root + 'watch?live=stream&id=' + data.idDiretta + '" class="rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></a>' +
                                    '<span id="tooltip-' + data.azioni[0]['nome'] + '-' + data.idDiretta + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[0]['nome'] + '</span></div>'
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
                    targets: [2, 3, 5],
                    className: 'text-center'
                }
            ]
        })

        _kt_datatable_lessons_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_lessons_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_lessons_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoLessonsTab.init();
}));