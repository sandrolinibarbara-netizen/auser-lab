"use strict";
const root = document.getElementById('root').getAttribute('value');
let _kt_datatable_all_users_tab;
const KTDatatableRemoteAjaxDemoAllUsersTab = function() {

    const kt_datatable_all_users_tab = function() {
        _kt_datatable_all_users_tab = $("#kt_datatable_all_users_tab").DataTable({
            serverSide: true,
            ajax: {
                type: "POST",
                url: root + 'app/controllers/GeneralGetterController.php',
                data: function(d) {
                    d.action = 'getAllUsers'
                },
                dataSrc: function (data) {
                    console.log(data.data)
                    return data.data;
                }
            },
            paging: true,
            info: false,
            pageLength: 10,
            lengthChange: true,
            columns: [
                {data: null,
                    render: (data) => '<img class="w-50px h-50px rounded-circle" style="object-fit: cover; object-position: top" alt="' + data.nome + '-avatar" src="' + (data.immagine.split(':')[0] === 'http' || data.immagine.split(':')[0] === 'https' ? data.immagine : root + 'app/assets/uploaded-files/users-images/' + data.immagine) + '"/>'
                },
                {data: 'nome'},
                {data: 'system_date_created'},
                {data: null,
                render: (data) => '<p class="py-2 px-4 rounded d-inline-block text-white ' + (data.tesseramento == 1 ? 'bg-success' : data.tesseramento == 0 ? 'bg-danger' : 'bg-warning') + '">' + (data.tesseramento == 1 ? 'Sì' : data.tesseramento == 0 ? 'No' : 'In attesa') + '</p>' },
                {data: null,
                    render: (data) => data.contributi.length === 0 ? '<p>-</p>' : '<a href="/" class="bg-danger rounded w-25px h-25px p-2 text-decoration-none"><i class="ki-outline ki-information-5 text-white"></i></a>'},
                {data: null,
                    render: function(data) {
                        let buttons = '';
                        for(let i=0; i < data.azioni.length; i++) {
                            buttons += '<div class="position-relative d-inline-block">' +
                                '<a id="' + data.azioni[i]['nome'] + '-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" href="' + root + 'utente?utente=infos&id=' + data.id + '" class="rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></a>' +
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
                    targets: [0, 3, 4, 5],
                    className: 'text-center'
                }
            ]
        })

        _kt_datatable_all_users_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_all_users_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_all_users_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoAllUsersTab.init();
}));