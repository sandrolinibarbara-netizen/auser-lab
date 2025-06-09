"use strict";
let _kt_datatable_st_courses_tab;
let currentTeach;
const KTDatatableRemoteAjaxStCoursesTab = function() {

    const kt_datatable_st_courses_tab = function() {
        _kt_datatable_st_courses_tab = $("#kt_datatable_st_courses_tab").DataTable({
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
                    currentTeach = $('#courses-teachers').val();
                    const dropdownNames = $("#courses-teachers");
                    dropdownNames.empty();
                    const option = document.createElement('option');
                    dropdownNames.append(option);
                    const teachers = data.allTeachers.map((el) => (
                        {'nome': el['insegnante'], 'id': el['id']}
                    )).filter((el,i,arr) => i === arr.findIndex((t) => (t.nome === el.nome && t.id === el.id)));
                    teachers.forEach((opt) => dropdownNames.append($("<option />").val(opt.id).text(opt.nome)));
                    $('#courses-teachers').val(currentTeach);
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
                        let buttons = '';
                        for(let i=0; i < data.azioni.length; i++) {
                            let href = root + 'corso?get=course&id=' + data.id;
                            if(data.data_inizio === '01/01/3000') href += '&ondemand=true';
                            buttons += '<div class="position-relative d-inline-block">' +
                                    '<a id="' + data.azioni[i]['nome'] + '-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" href="' + href + '" class="rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 ' + data.azioni[i]['icona'] + '"></a>' +
                                    '<span id="tooltip-' + data.azioni[i]['nome'] + '-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
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
                    targets: [1, 2, 3],
                    className: 'text-center'
                }
            ]
        })

        _kt_datatable_st_courses_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_st_courses_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_st_courses_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxStCoursesTab.init();
}));