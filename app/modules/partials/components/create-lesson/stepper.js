let lesson;

const fileInput = document.getElementById('video-fileInput');

const startHour = new tempusDominus.TempusDominus(document.getElementById('orario-inizio-lezione'), {
    localization: {
        hourCycle: 'h23',
        format: 'HH:mm'
    },
    stepping: 15,
    display: {
        viewMode: "clock",
        components: {
            decades: false,
            year: false,
            month: false,
            date: false,
            hours: true,
            minutes: true,
            seconds: false
        }
    }
});
const endHour = new tempusDominus.TempusDominus(document.getElementById('orario-fine-lezione'), {
    localization: {
        hourCycle: 'h23',
        format: 'HH:mm'
    },
    stepping: 15,
    display: {
        viewMode: "clock",
        components: {
            decades: false,
            year: false,
            month: false,
            date: false,
            hours: true,
            minutes: true,
            seconds: false
        }
    }
});

if(onDemand) {
    const onDemandDate = startHour.dates.parseInput(new Date(3000, 1, 1));
    lessonName.classList.remove('col-6');
    lessonName.classList.add('col-12');
    lessonDate.classList.add('d-none');
    lessonTimes.classList.remove('row');
    lessonTimes.classList.add('d-none');
    lessonPlace.classList.remove('row');
    lessonPlace.classList.add('d-none');
    $('#data-lezione').data('daterangepicker').setStartDate('01/01/3000');
    startHour.dates.setValue(onDemandDate);
    endHour.dates.setValue(onDemandDate);
}

fileInput.addEventListener('change', function() {
    readURL(this);
})

document.getElementById('nome-lezione').addEventListener('input', function() {
    if($('#nome-lezione').val()) {
        document.getElementById('error-message').classList.add('d-none')
    }
})
stepper.on("kt.stepper.next", function (stepper) {
    const index = stepper.getCurrentStepIndex();
    const title = $('#nome-lezione').val();
    if(index === 1 && (title === "" || !title)) {
        document.getElementById('error-message').classList.remove('d-none')
        return;
    }

    if(index === 1) {
        counterForm++;
        if(counterForm === 1) {
            saveLesson(stepper);
        } else {
            updateLesson(stepper)
        }
    } else {
        if (index === 4 && userGroup === 3) {
            stepper.goTo(6)
        } else {
            stepper.goNext();
        }
    }
});
stepper.on('kt.stepper.changed', function() {
    const index = stepper.getCurrentStepIndex();

    if(index === 3) {
        counterMaterialsTab++;
        if(counterMaterialsTab === 1) {
            materialsTab();
            surveysTab()
        } else {
            reloadTable('#kt_datatable_materials_toAdd');
        }
    }

    if(index === 4) {
        counterHomeworksTab++;
        if(counterHomeworksTab === 1) {
            homeworksTab();
        } else {
            reloadTable('#kt_datatable_homeworks_toAdd');
        }
    }

    if(index === 5) {
        counterSponsorsTab++;
        if(counterSponsorsTab === 1) {
            sponsorsTab();
        }
    }

    if(index === 6) {
        counterRecap++;
        showRecap();
        if(counterRecap === 1) {
            if(userGroup === 1) sponsorsRecapTab();
            materialsRecapTab();
            homeworksRecapTab()
            surveysRecapTab();
        } else {
            if(userGroup === 1) reloadTable('#kt_datatable_sponsors_recap');
            reloadTable('#kt_datatable_materials_recap');
            reloadTable('#kt_datatable_homeworks_recap');
            reloadTable('#kt_datatable_surveys_recap');
        }
    }
})

function saveLesson(stepper) {
    const title = $('#nome-lezione').val();
    const date = $('#data-lezione').val();
    const start = $('#orario-inizio-lezione').val();
    const end = $('#orario-fine-lezione').val();
    const location = $('#luogo-lezione').val() === '' ? '-' : $('#luogo-lezione').val();
    const description = $('#descrizione-lezione').val();
    $('#lezione_titolo').text(title);

    if(!checkDate(date)) {
        return;
    }

    $.ajax({
        type: 'POST',
        data: {
            'action': 'createLesson',
            'nome': title,
            'data': date,
            'inizio': start,
            'fine': end,
            'luogo': location,
            'descrizione': description,
            'idCorso' : course,
        },
        url: root + 'app/controllers/CourseController.php',
        success: function(data) {
            document.getElementById('date-error-message').classList.add('d-none');
            const parsed = JSON.parse(data);
            lesson = parsed.lesson;
            stepper.goNext();
        },
        error: function() {
            document.getElementById('date-error-message').classList.remove('d-none');
            counterForm--;
        }
    })
}
function materialsTab() {
    let _kt_datatable_materials_toAdd;
    _kt_datatable_materials_toAdd = $("#kt_datatable_materials_toAdd").DataTable({
        serverSide: true,
        ajax: {
            type: "POST",
            url: root + 'app/controllers/GeneralGetterController.php',
            data: function(d) {
                d.action = 'getMaterials';
                d.course = course;
            },
            dataSrc: function (data) {
                console.log(data)
                return data.data;
            }
        },
        paging: false,
        info: false,
        columns: [
            {data: null,
            render: (data) => '<div class="form-check form-check-custom form-check-solid"><label><input ' + (data.checked === 1 ? 'checked ' : '') + 'class="form-check-input mx-2" type="radio" name="' + (data.id_tipologia === 6 ? 'lecture' : 'poll') + '" value="'+ data.id + '-' + data.id_tipologia +'"/>' + data.nome + '</label></div>'},
            {data: null,
            render: (data) => '<p class="mb-0">' + (data.id_tipologia === 6 ? 'Dispensa' : 'Quiz') + '</p>'},
            {data: 'data'}
        ],
        columnDefs: [
            {
                targets: '_all',
                orderable: false,
            },
        ]
    })

    _kt_datatable_materials_toAdd.on('draw', function () {
        KTMenu.createInstances();
    });

}
function homeworksTab() {
    let _kt_datatable_homeworks_toAdd;
    _kt_datatable_homeworks_toAdd = $("#kt_datatable_homeworks_toAdd").DataTable({
        serverSide: true,
        ajax: {
            type: "POST",
            url: root + 'app/controllers/GeneralGetterController.php',
            data: function(d) {
                d.action = 'getHomeworks';
                d.course = course;
            },
            dataSrc: function (data) {
                console.log(data)
                return data.data;
            }
        },
        paging: false,
        info: false,
        columns: [
            {data: null,
                render: (data) => '<div class="form-check form-check-custom form-check-solid"><label><input ' + (data.checked === 1 ? 'checked ' : '') + 'class="form-check-input mx-2" type="checkbox" name="homeworks" value="'+ data.id + '-' + data.id_tipologia +'"/>' + data.nome + '</label></div>'},
            {data: null,
                render: (data) => '<p>' + (data.id_tipologia === 6 ? 'Dispensa' : 'Quiz') + '</p>'},
            {data: 'data'}
        ],
        columnDefs: [
            {
                targets: '_all',
                orderable: false,
            },
        ]
    })

    _kt_datatable_homeworks_toAdd.on('draw', function () {
        KTMenu.createInstances();
    });

}
function surveysTab() {
    console.log(lesson)
    let _kt_datatable_surveys_toAdd;
    _kt_datatable_surveys_toAdd = $("#kt_datatable_surveys_toAdd").DataTable({
        serverSide: true,
        ajax: {
            type: "POST",
            url: root + 'app/controllers/LessonController.php',
            data: {'lesson': lesson, 'action': 'getSurveysDraft', course: course},
            dataSrc: function (data) {
                console.log(data)
                return data.data;
            }
        },
        paging: false,
        info: false,
        columns: [
            {
                data: null,
                render: (data) => {
                    const checked = data.id_diretta !== null ? ' checked' : ''
                    return '<div class="form-check form-check-custom form-check-solid"><label><input' + checked + ' class="form-check-input mx-2" type="radio" name="survey" value="' + data.id + '"/>' + data.nome + '</label></div>'
                }
            },
            {data: 'data'}
        ],
        columnDefs: [
            {
                targets: '_all',
                orderable: false,
            },
        ]
    })

    _kt_datatable_surveys_toAdd.on('draw', function () {
        KTMenu.createInstances();
    });

}
function sponsorsTab() {
    let _kt_datatable_sponsors_toAdd;
    _kt_datatable_sponsors_toAdd = $("#kt_datatable_sponsors_toAdd").DataTable({
        serverSide: true,
        ajax: {
            type: "POST",
            url: root + 'app/controllers/GeneralGetterController.php',
            data: function(d) {
                d.action = 'getSponsors';
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
                render: (data) => '<div class="form-check form-check-custom form-check-solid"><input id="checkbox-' + data.nome + '" class="form-check-input mx-2" type="checkbox" name="sponsors" value="'+ data.id +'"/></div>'},
            {data: null,
            render: (data) => '<img class="w-100px" alt="' + data.nome + '-logo" src="' + (data.pic ? root + 'app/assets/uploaded-files/sponsor-images/' + data.pic : data.logo) + '"/>'},
            {data: null,
            render: (data) => '<label for="checkbox-' + data.nome +'">' + data.nome + '</label>'},
            {data: 'data'}
        ],
        columnDefs: [
            {
                targets: '_all',
                orderable: false,
            },
        ]
    })

    _kt_datatable_sponsors_toAdd.on('draw', function () {
        KTMenu.createInstances();
    });

}