"use strict";
const root = document.getElementById('root').getAttribute('value');
let _kt_datatable_all_pages_tab;
const search = window.location.search;
const params = new URLSearchParams(search);
const group = params.get("id");
const form = document.getElementById('pagesPermissions')
const selectedPages = [];

const KTDatatableRemoteAjaxDemoAllPagesTab = function() {
    const kt_datatable_all_pages_tab = function() {
        _kt_datatable_all_pages_tab = $("#kt_datatable_all_pages_tab").DataTable({
            serverSide: true,
            ajax: {
                type: "POST",
                url: root + 'app/controllers/GroupController.php',
                data: {'group': group, 'action': 'getSingleGroup'},
                dataSrc: function (data) {
                    $('#permission-title').text('Permessi ' + data.group)
                    console.log(data)
                    return data.data;
                }
            },
            paging: false,
            info: false,
            columns: [
                {data: null,
                    render: (data) => {
                        const checked = data.checked === '1' ? ' checked' : ''
                        return '<div class="form-check form-check-custom form-check-solid"><input' + checked +' id="checkbox-' + data.titolo + '" class="form-check-input mx-2" type="checkbox" name="sponsors" value="' + data.id + '"/></div>'
                    }},
                {data: 'titolo'},
            ],
            columnDefs: [
                {
                    targets: '_all',
                    orderable: false,
                },
            ]
        })

        _kt_datatable_all_pages_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_all_pages_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_all_pages_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoAllPagesTab.init();
}));

form.addEventListener('submit', function(e) {
    e.preventDefault();
    e.submitter.disabled = true;
    selectedPages.length = 0;
    $('#pagesToSelect input:checked').each(function() {
        selectedPages.push($(this).attr('value'));
    })
    $.ajax({
        type: 'POST',
        data: {'group': group, 'pages': selectedPages, 'action': 'savePermissions'},
        url: root + 'app/controllers/GroupController.php',
        success: function(data) {
            const parsed = JSON.parse(data);
            console.log(parsed)
            Swal.fire({
                icon: 'success',
                text: 'I permessi sono stati aggiornati.',
                showConfirmButton: true
            })
            e.submitter.disabled = false;
        }
    })
})

