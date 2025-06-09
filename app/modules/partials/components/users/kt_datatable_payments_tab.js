"use strict";
let _kt_datatable_payments_tab;
const search = window.location.search;
const params = new URLSearchParams(search);
const user = params.get("id");

const KTDatatableRemoteAjaxDemoPaymentsTab = function() {

    const kt_datatable_payments_tab = function() {
        _kt_datatable_payments_tab = $("#kt_datatable_payments_tab").DataTable({
            serverSide: true,
            ajax: {
                type: "POST",
                url: root + 'app/controllers/UserController.php',
                data: function(d) {
                    d.action = 'getPayments'
                    d.user = user;
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
                {data: 'nome'},
                {data: null,
                    render: (data) => (data.tipo === 'corso') ? '<p>Inizio corso: ' + data.periodo[0] + '<br/>Fine corso: ' + data.periodo[1] + '</p>': '<p>' + data.periodo[0] + ', ' + data.periodo[1] + '</p>'
                },
                {data: 'importo'},
                {data: null,
                    render: (data) => {
                        const id = data.idCorso ? '1-' + data.idCorso : '2-' + data.idEvento
                        const payment = 'approval-' + user + '-' + id;
                        return '<div id="payButtons-' + id + '" class="d-flex gap-2 flex-wrap"><label class="btn rounded '  + (data.approvazione === 1 ? 'btn-success activePay' : "btn-color-muted bg-surface") + '">Sì<input id="yes-' + id + '" onclick="changePaymentStatus(this.id)" name="' + payment + '" class="d-none" type="radio" '  + (data.approvazione === 1 ? 'checked' : "") + ' value="1"/></label>' +
                        '<label class="btn rounded '  + (data.approvazione === 0 ? 'btn-danger activePay' : "btn-color-muted bg-surface") + '">No<input id="no-' + id + '" onclick="changePaymentStatus(this.id)" name="' + payment + '" class="d-none" type="radio" ' + (data.approvazione === 0 ? 'checked' : "") + ' value="0"/></label>' +
                        '<label class="btn rounded '  + (data.approvazione === 2 ? 'btn-warning activePay' : "btn-color-muted bg-surface") + '">In attesa<input id="maybe-' + id + '" onclick="changePaymentStatus(this.id)" name="' + payment + '" class="d-none" type="radio" ' + (data.approvazione === 2 ? 'checked' : "") + ' value="2"/></label></div>'
                    }
                },
            // form-control-solid form-check-input mx-2
            ],
            columnDefs: [
                {
                    targets: '_all',
                    orderable: false,
                },
            ]
        })

        _kt_datatable_payments_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }

    const reload = function(){
        _kt_datatable_payments_tab.ajax.reload();
    }

    return {
        init: function() {
            kt_datatable_payments_tab();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoPaymentsTab.init();
}));

function changePaymentStatus(id) {
    const element = document.getElementById(id).value
    const div = document.getElementById(id).parentElement.parentElement;
    const labels = div.querySelectorAll('label')
    labels.forEach(label => {
        label.classList.remove('activePay');
        label.classList.add('btn-color-muted', 'bg-surface');
    })
    for(let i= 0; i < labels.length; i++) {
        const inputValue = labels[i].children[0].value;
            if(inputValue === element && element === '0') {
                labels[i].classList.remove('btn-color-muted', 'bg-surface');
                labels[i].classList.add('btn-danger', 'activePay');
                return;
            } else if(inputValue === element && element === '1') {
                labels[i].classList.remove('btn-color-muted', 'bg-surface');
                labels[i].classList.add('btn-success', 'activePay');
                return;
            } else if(inputValue === element && element === '2'){
                labels[i].classList.remove('btn-color-muted', 'bg-surface');
                labels[i].classList.add('btn-warning', 'activePay');
                return;
            }
    }
}