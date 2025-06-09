"use strict";
if($("#kt_datatable_users_private_tab")) {
    let _kt_datatable_users_private_tab;
    const KTDatatableRemoteAjaxDemoAllUsersTab = function () {
        const search = window.location.search;
        const params = new URLSearchParams(search);
        const course = params.get("id");
        const kt_datatable_users_private_tab = function () {
            _kt_datatable_users_private_tab = $("#kt_datatable_users_private_tab").DataTable({
                serverSide: true,
                ajax: {
                    type: "POST",
                    url: root + 'app/controllers/CourseController.php',
                    data: function (d) {
                        d.action = 'getSubbedPrivateStudents';
                        d.course = course;
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
                    {
                        data: null,
                        render: (data) => '<img class="w-50px rounded-circle" alt="' + data.nome + '-avatar" src="' + (data.immagine.split(':')[0] === 'http' || data.immagine.split(':')[0] === 'https' ? data.immagine : root + 'app/assets/uploaded-files/users-images/' + data.immagine) + '"/>'
                    },
                    {data: 'user'},
                ],
                columnDefs: [
                    {
                        targets: '_all',
                        orderable: false,
                    },
                ]
            })

            _kt_datatable_users_private_tab.on('draw', function () {
                KTMenu.createInstances();
            });

        }

        const reload = function () {
            _kt_datatable_users_private_tab.ajax.reload();
        }

        return {
            init: function () {
                kt_datatable_users_private_tab();
            },
            reload: function () {
                reload();
            },
        };
    }();

    KTUtil.onDOMContentLoaded((function () {
        KTDatatableRemoteAjaxDemoAllUsersTab.init();
    }));
}