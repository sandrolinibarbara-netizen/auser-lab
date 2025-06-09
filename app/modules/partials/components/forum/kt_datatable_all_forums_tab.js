"use strict";
const root = document.getElementById('root').getAttribute('value');
let _kt_datatable_all_forums_tab;
const search = window.location.search;
const params = new URLSearchParams(search);
const user = params.get("id");
const selectCourse = document.getElementById('single-course-selection')

const KTDatatableRemoteAjaxDemoAllForumsTab = function() {
    const kt_datatable_all_forums_tab = function() {
        _kt_datatable_all_forums_tab = $("#kt_datatable_all_forums_tab").DataTable({
            serverSide: true,
            ajax: {
                type: "POST",
                url: root + 'app/controllers/ForumController.php',
                data: function (d){
                    d.allForumCreation = $('#all-forum-creation').val();
                    d.user = user;
                    d.action = 'getAllForums'
                },
                dataSrc: function (data) {
                    $('#all-forum-creation').val('').trigger('change');
                    if(data.dataCourses) {
                        data.dataCourses.forEach(el => {
                            const option = document.createElement('option');
                            option.setAttribute('value', el['id']);
                            option.textContent = el['nome'];
                            selectCourse.append(option);
                        })
                    }
                    return data.data;
                }
            },
            paging: false,
            info: false,
            lengthChange: false,
            columns: [
                {data: 'nome'},
                {data: 'data_creazione'},
                {data: 'numero_discussioni'},
                {data: 'numero_post'},
                {data: 'ultimo_post'},
                {data: null,
                    render: function(data) {
                        let buttons = '';
                        for(let i=0; i < data.azioni.length; i++) {
                            buttons += '<div class="position-relative d-inline-block">' +
                                '<a id="' + data.azioni[i]['nome'] + '-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" href="' + root + 'forum/corso?id=' + data.id + '" class="rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></a>' +
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
                    targets: [2, 3],
                    className: 'text-center'
                }
            ]
        })

        _kt_datatable_all_forums_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_all_forums_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_all_forums_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoAllForumsTab.init();
}));