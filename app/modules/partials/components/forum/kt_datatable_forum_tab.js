"use strict";
const root = document.getElementById('root').getAttribute('value');
let _kt_datatable_forum_tab;
const search = window.location.search;
const params = new URLSearchParams(search);
const course = params.get("id");
const KTDatatableRemoteAjaxDemoForumTab = function() {

    const kt_datatable_forum_tab = function() {
        _kt_datatable_forum_tab = $("#kt_datatable_forum_tab").DataTable({
            serverSide: true,
            ajax: {
                type: "POST",
                url: root + 'app/controllers/ForumController.php',
                data: function (d){
                    d.forumCreation = $('#forum-creation').val();
                    d.course = course;
                    d.action = 'getSingleForum'
                },
                dataSrc: function (data) {
                    console.log(data)
                    const answers = data.data[0]['risposte'].split('-')[0];
                    const group = data.data[0]['risposte'].split('-')[1];
                    if(answers === '0' && group === '2') {
                        document.getElementById('add-thread-button').classList.add('d-none')
                    }
                    $('#forum-creation').val('').trigger('change');
                    $('#forum_title').text(data.data[0]['corso']);
                    return data.data;
                }
            },
            paging: true,
            info: false,
            pageLength: 10,
            lengthChange: true,
            columns: [
                {data: null,
                    render: (data) => `<h3>${data.titolo}</h3><p>${data.descrizione}</p>`
                },
                {data: 'data_creazione'},
                {data: 'numero_post'},
                {data: 'ultimo_post'},
                {data: null,
                    render: function(data) {
                        let buttons = '';
                        for(let i=0; i < data.azioni.length; i++) {
                            if(data.azioni[i]['nome'] === 'Elimina') {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<button id="' + data.azioni[i]['nome'] + '-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" data-bs-id="thread-' + data.id + '-' + course + '" value="' + data.id +'" type="button" data-bs-toggle="modal" data-bs-target="#modal-remove" class="border-0 rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></button>' +
                                    '<span id="tooltip-' + data.azioni[i]['nome'] + '-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                            } else {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<a id="' + data.azioni[i]['nome'] + '-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" href="' + root + 'forum/corso/thread?course=' + course + '&thread=single&id=' + data.id + '" class="rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></a>' +
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
                    targets: 2,
                    className: 'text-center'
                }
            ]
        })

        _kt_datatable_forum_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_forum_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_forum_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoForumTab.init();
}));