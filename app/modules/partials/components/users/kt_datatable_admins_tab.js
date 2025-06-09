"use strict";
let _kt_datatable_admins_tab;
const KTDatatableRemoteAjaxDemoAdminsTab = function() {

    const kt_datatable_admins_tab = function() {
        _kt_datatable_admins_tab = $("#kt_datatable_admins_tab").DataTable({
            serverSide: true,
            ajax: {
                type: "POST",
                url: root + 'app/controllers/GeneralGetterController.php',
                data: function(d) {
                    d.action = 'getAdmins'
                },
                dataSrc: function (data) {
                    console.log(data)
                    return data.data;
                }
            },
            paging: true,
            info: false,
            pageLength: 10,
            lengthChange: true,
            columns: [
                {data: null,
                    render: (data) => '<img class="w-50px h-50px rounded-circle" style="object-fit: cover; object-position: top" alt="' + data.admin + '-avatar" src="' + (data.immagine.split(':')[0] === 'http' || data.immagine.split(':')[0] === 'https' ? data.immagine : root + 'app/assets/uploaded-files/users-images/' + data.immagine) + '"/>'
                },
                {data: 'admin'},
                {data: 'data'},
            ],
            columnDefs: [
                {
                    targets: '_all',
                    orderable: false,
                }
            ]
        })

        _kt_datatable_admins_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_admins_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_admins_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoAdminsTab.init();
}));