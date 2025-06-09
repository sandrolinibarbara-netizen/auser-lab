"use strict";
let _kt_datatable_categories_tab;
const root = document.getElementById('root').getAttribute('value');
const KTDatatableRemoteAjaxDemoCategoriesTab = function() {

    const kt_datatable_categories_tab = function() {
        _kt_datatable_categories_tab = $("#kt_datatable_categories_tab").DataTable({
            serverSide: true,
            ajax: {
                type: "POST",
                url: root + 'app/controllers/GeneralGetterController.php',
                data: function(d) {
                  d.action = 'getCategories'
                },
                dataSrc: function (data) {
                    return data.data;
                }
            },
            paging: true,
            info: false,
            pageLength: 10,
            lengthChange: true,
            columns: [
                {data: null,
                    render: (data) => '<img class="w-100px" alt="' + data.nome + '-logo" src="' + (data.immagine.split(':')[0] === 'http' || data.immagine.split(':')[0] === 'https' ? data.immagine : root + 'app/assets/uploaded-files/category-images/' + data.immagine) + '"/>'
                },
                {data: null,
                    render: (data) => '<div class="d-flex align-items-center"><div class="w-15px h-15px rounded-circle" style="display: inline-block ; background-color: '+ data.colore +'" alt="' + data.nome + '-logo" src="' + data.immagine + '"/><p style="display: inline-block; padding-left: 26px; min-width: 150px">'+ data.nome +'</p></div>'
                },
                {data: 'system_date_created'},
                {data: null,
                    render: function(data) {
                        let buttons = '';
                        for(let i=0; i < data.azioni.length; i++) {
                            if(data.azioni[i]['nome'] === 'Elimina') {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<button id="' + data.azioni[i]['nome'] + '-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" data-bs-id="category-' + data.id +'" value="' + data.id +'" type="button" data-bs-toggle="modal" data-bs-target="#modal-remove" class="border-0 rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 '+ data.azioni[i]['icona'] +'"></button>' +
                                    '<span id="tooltip-' + data.azioni[i]['nome'] + '-' + data.id + '" class="d-none rounded text-auser p-2 bg-light position-absolute top-100 start-50 z-index-3" style="transform: translate(-50%, 7.5%)">' + data.azioni[i]['nome'] + '</span></div>'
                            } else {
                                buttons += '<div class="position-relative d-inline-block">' +
                                    '<a id="' + data.azioni[i]['nome'] + '-' + data.id + '" onmouseenter="showTooltip(this)" onmouseleave="hideTooltip(this)" href="' + root + 'update-category?update=category&id=' + data.id + '" class="rounded text-auser text-decoration-none p-2 ki-outline bg-light-bg me-1 ' + data.azioni[i]['icona'] + '"></a>' +
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
                    targets: 0,
                    className: "text-center"
                }
            ]
        })

        _kt_datatable_categories_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_categories_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_categories_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoCategoriesTab.init();
}));