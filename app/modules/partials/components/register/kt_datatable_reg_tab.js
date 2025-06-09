"use strict";
const root = document.getElementById('root').getAttribute('value');
let _kt_datatable_reg_tab;
const columns = [];

function getDatatable() {
    const search = window.location.search;
    const params = new URLSearchParams(search);
    const course = params.get("id");
    $.ajax({
        data: {'course': course, 'action': 'getRegister'},
        type: "POST",
        url: root + 'app/controllers/CourseController.php',
        success: function (data) {
            const parsed = JSON.parse(data);
            parsed.data.forEach((el, j) => {
                const keys = Object.keys(el);
                const dates = Object.values(el);
                dates.forEach((date, i) => {
                    let box;
                    let lesson;
                    let user;

                        if (!Array.isArray(date) && Number(date.split('/')[0]) == Number(date.split('/')[0])) {
                            lesson = Number(date.split('/')[1]);
                            user = Number(date.split('/')[2]);
                        }
                        switch (!Array.isArray(date) && Number(date.split('/')[0])) {
                            case 0:
                                if (parsed.group) {
                                    box = createSelect('A', user, lesson, true);
                                } else {
                                    box = createSelect('A', user, lesson);
                                }
                                break;
                            case 1:
                                if (parsed.group) {
                                    box = createSelect('P', user, lesson, true);
                                } else {
                                    box = createSelect('P', user, lesson);
                                }
                                break;
                            case 2:
                                if (parsed.group) {
                                    box = createSelect('NA', user, lesson, true);
                                } else {
                                    box = createSelect('NA', user, lesson);
                                }
                                break;
                            default:
                                box = document.createElement('div');
                                if(Array.isArray(date)) {
                                    date.forEach(el => {
                                        const div = document.createElement('div');
                                        div.classList.add('position-relative', 'd-inline-block');
                                        const span = document.createElement('span');
                                        span.classList.add('d-none', 'rounded', 'text-auser', 'p-2', 'bg-light', 'position-absolute', 'top-100', 'start-50');
                                        span.style.transform = 'translate(-50%, 7.5%)';
                                        span.textContent = el['nome'];
                                        const link = document.createElement('button');
                                        link.setAttribute('data-bs-ids', el["id_course"] + '-' + el["id_user"]);
                                        link.setAttribute('data-class', el['id_class']);
                                        link.setAttribute('data-bs-toggle', 'modal');
                                        link.addEventListener('mouseenter', function(e) {
                                            showTooltip(this);
                                        });
                                        link.addEventListener('mouseleave', function (e) {
                                            hideTooltip(this)
                                        });
                                        switch (el['nome']) {
                                            case 'Modifica':
                                                link.setAttribute('data-bs-target', '#move-user-modal');
                                                link.setAttribute('id', 'edit-' + el["id_course"] + '-' + el["id_user"]);
                                                span.setAttribute('id', 'tooltip-edit-' + el["id_course"] + '-' + el["id_user"])
                                                break;
                                            case 'Commenta':
                                                link.setAttribute('data-bs-target', '#message-user-modal');
                                                link.setAttribute('id', 'message-' + el["id_course"] + '-' + el["id_user"]);
                                                span.setAttribute('id', 'tooltip-message-' + el["id_course"] + '-' + el["id_user"])
                                                break;
                                            case 'Elimina':
                                                link.setAttribute('data-bs-target', '#remove-user-modal');
                                                link.setAttribute('id', 'delete-' + el["id_course"] + '-' + el["id_user"]);
                                                span.setAttribute('id', 'tooltip-delete-' + el["id_course"] + '-' + el["id_user"]);
                                                break;
                                            case 'Consegna':
                                                link.setAttribute('data-bs-target', '#reward-user-modal');
                                                link.setAttribute('id', 'reward-' + el["id_course"] + '-' + el["id_user"]);
                                                span.setAttribute('id', 'tooltip-reward-' + el["id_course"] + '-' + el["id_user"]);

                                                if (el["certificato"] === 1) {
                                                    link.setAttribute('disabled', 'disabled');
                                                }
                                                break;
                                            default:
                                                break;
                                        }
                                        link.classList.add('rounded', 'btn', 'text-auser', 'p-2', 'ki-outline', 'bg-light-bg', 'me-1', el["icona"]);
                                        div.append(link);
                                        div.append(span);
                                        box.append(div);
                                    })
                                } else {
                                    box.classList.add('text-start')
                                    box.textContent = date;
                                }
                        }
                        el[keys[i]] = box;
                })
            })
            console.log('Subbed: ', parsed.data )
            if(parsed.data.length === 0) {
                document.getElementById('register-card-table').classList.add('d-none');
                document.getElementById('download-reg').classList.add('d-none');
                document.getElementById('no-register-message').classList.remove('d-none');
                return;
            }
            if(parsed.data.length > 0) {
                const columnNames = Object.keys(parsed.data[0]);
                columnNames.forEach((el) => columns.push({data: el, title: el}));
                kt_datatable_reg_tab(parsed);
            }
        }
    })
    const kt_datatable_reg_tab = function(parsed) {
        _kt_datatable_reg_tab = $("#kt_datatable_reg_tab").DataTable({
            layout: {
                topStart: {
                    buttons: ['csvHtml5']
                }
            },
            paging: false,
            info: false,
            data: parsed.data,
            columns: columns,
            columnDefs: [
                {
                    targets: 0,
                    className: 'text-start align-bottom export'
                },
                {
                    targets: 1,
                    orderable: false,
                    className: 'text-center sorting_disabled'
                },
                {
                    targets: '_all',
                    orderable: false,
                    className: 'text-center sorting_disabled export'
                },
            ]
        })

        _kt_datatable_reg_tab.on('draw', function () {
            KTMenu.createInstances();
        });

    }
}


const KTDatatableRemoteAjaxDemoRegTab = function() {
    const reload = function(){
        _kt_datatable_reg_tab.ajax.reload();
    }

    return {
        init: function() {
            getDatatable();
        },
        reload: function () {
            reload();
        },
    };
}();

KTUtil.onDOMContentLoaded((function() {
    KTDatatableRemoteAjaxDemoRegTab.init();
}));

function createSelect(option, idUser, idLive, student = false) {
    const box = document.createElement('select');
    if(student) {
        box.setAttribute('disabled', 'disabled')
    }
    box.classList.add('form-select');
    box.classList.add('data-control', 'select2');
    box.setAttribute('id', idUser + '-' + idLive);
    box.setAttribute('name', idUser + '-' + idLive);
    const optionA = document.createElement('option');
    optionA.textContent = 'A';
    optionA.setAttribute('value', '0');
    const optionP = document.createElement('option');
    optionP.textContent = 'P';
    optionP.setAttribute('value', '1');
    const optionNA = document.createElement('option');
    optionNA.textContent = '-';
    optionNA.setAttribute('value', '2');
    if(option === 'A') {
        optionA.setAttribute('selected', 'selected')
    } else if(option === 'P') {
        optionP.setAttribute('selected', 'selected')
    } else {
        optionNA.setAttribute('selected', 'selected')
    }
    box.append(optionA);
    box.append(optionP);
    box.append(optionNA);

    box.addEventListener('change', function(e) {
        $.ajax({
            type: 'POST',
            url: root + 'app/controllers/UserController.php',
            data: {
                'action': 'updateRegister',
                'user': idUser,
                'lesson': idLive,
                'value': Number(e.target.value)
            },
            success: function() {}
        })
    })
    return box;
}