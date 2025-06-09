let event;
const radioButtons = document.querySelectorAll('.radio-check');
const fileInput = document.getElementById('video-fileInput');
// EVENT LISTENERS CHE MODIFICANO LA VISIBILITA' DI ALTRI ELEMENTI
const onDemand = document.getElementById('on-demand-event');
const live = document.getElementById('live-event');
const detailsDiv = document.getElementById('event-live-details');
const timesDiv = document.getElementById('event-live-times');
const fee = document.getElementById('event-fee');
const maxAttendance = document.getElementById('event-live-participants');
const modeTypesDiv = document.getElementById('event-live-modes');

const startHour = new tempusDominus.TempusDominus(document.getElementById('orario-inizio-evento'), {
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

const endHour = new tempusDominus.TempusDominus(document.getElementById('orario-fine-evento'), {
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
[onDemand, live].forEach(el => el.addEventListener('change', () => {
    if($('input[name="on-demand"]:checked').val() === '1') {
        detailsDiv.classList.remove('row');
        detailsDiv.classList.add('d-none');
        timesDiv.classList.remove('row');
        timesDiv.classList.add('d-none');
        modeTypesDiv.classList.remove('row');
        modeTypesDiv.classList.add('d-none');
        maxAttendance.classList.add('d-none');
        fee.classList.remove('col-6');
        fee.classList.add('col-12');
        $('#data-evento').data('daterangepicker').setStartDate('01/01/3000');
        const onDemandDate = startHour.dates.parseInput(new Date(3000, 1, 1));
        startHour.dates.setValue(onDemandDate);
        endHour.dates.setValue(onDemandDate);
    } else {
        detailsDiv.classList.add('row');
        detailsDiv.classList.remove('d-none');
        timesDiv.classList.add('row');
        timesDiv.classList.remove('d-none');
        modeTypesDiv.classList.add('row');
        modeTypesDiv.classList.remove('d-none');
        maxAttendance.classList.remove('d-none');
        fee.classList.add('col-6');
        fee.classList.remove('col-12');
        $('#data-evento').data('daterangepicker').setStartDate(new Date());
    }
}))

radioButtons.forEach(el => {
    el.addEventListener('change', function() {
        document.getElementById('error-message').classList.add('d-none')
    })
})

stepper.on("kt.stepper.previous", function (stepper) {
    document.getElementById('publish-error-message').classList.add('d-none')
    const index = stepper.getCurrentStepIndex();
    const evento = $('#nome-evento').val();

    if(index === 2 && (evento === "" || !evento)) {
        document.getElementById('name-error-message').classList.remove('d-none')
        return;
    }

    if(index === 7 && userGroup === 3) {
        stepper.goTo(5)
    } else {
        stepper.goPrevious(); // go next step
    }
});

stepper.on("kt.stepper.change", function() {
    const index = stepper.getCurrentStepIndex();
    if(index === 2) {
        counterForm++;
        if(counterForm === 1) {
            saveEvent();
        } else {
            updateEvent();
        }
    }

    if(index === 3) {
        selectedSpeakers.length = 0;
        $('#speakersToSelect input:checked').each(function() {
            selectedSpeakers.push($(this).attr('value'));
        })
        console.log(selectedSpeakers);
        $.ajax({
            type: 'POST',
            data: {
                'selected': selectedSpeakers,
                'lesson': event,
                'action': 'updateDraftSpeakers'
            },
            url: root + 'app/controllers/LessonController.php',
            success: function() {
            }
        })
    }

    if(index === 4) {
        const link = $('#live-stream-link').val();
        const zoomMeeting = $('#zoom-meeting').val();
        const zoomPw = $('#zoom-pw').val();
        console.log(link);
        $.ajax({
            type: 'POST',
            url: root + 'app/controllers/LessonController.php',
            data: {
                'link': link,
                'lesson': event,
                'action': 'updateDraftLink',
                'zoomMeeting' : zoomMeeting,
                'zoomPw': zoomPw
            },
            success: function() {
            }
        })
    }

    if(index === 5) {
        selectedMaterials.length = 0;
        $('#materialsToSelect input:checked').each(function() {
            const ids = $(this).attr('value').split('-');
            selectedMaterials.push({'id_material': ids[0], 'id_type': ids[1]});
        })

        selectedSurveys.length = 0;
        $('#surveysToSelect input:checked').each(function () {
            selectedSurveys.push({'id_material': $(this).val()});
        })

        $.ajax({
            type: 'POST',
            data: {
                'selected': selectedMaterials,
                'surveys': selectedSurveys,
                'lesson': event,
                'action': 'updateDraftMaterials'
            },
            url: root + 'app/controllers/LessonController.php',
            success: function() {
            }
        })
    }

    if(index === 6) {
        selectedSponsors.length = 0;
        $('#sponsorsToSelect input:checked').each(function() {
            selectedSponsors.push($(this).attr('value'));
        })
        console.log(selectedSponsors);
        $.ajax({
            type: 'POST',
            data: {
                'selected': selectedSponsors,
                'lesson': event,
                'action': 'updateDraftSponsors'
            },
            url: root + 'app/controllers/LessonController.php',
        })
    }
});

function saveEvent() {
    const topic = $('input[name="argomento"]:checked').val() ?? '0';
    const evento = $('#nome-evento').val();
    const dataEvento = $('#data-evento').val();
    const luogo = $('#luogo-evento').val() === '' ? '-' : $('#luogo-evento').val();
    const inizio = $('#orario-inizio-evento').val();
    const fine = $('#orario-fine-evento').val();
    const descrizione = $('#descrizione-evento').val();
    const importo = $('#contributo-evento').val();
    let max = $('#max-evento').val();

    if(!checkDate(dataEvento)) {
        return;
    }

    let remoto;
    let presenza;

    if(dataEvento === '01/01/3000') {
        remoto = '1';
        presenza = '0';
        max = '-1';
    } else {

        switch ($('input[name="modalità"]:checked').val()) {
            case '1':
                remoto = '1';
                presenza = '0';
                break;
            case '2':
                remoto = '0';
                presenza = '1';
                break;
            default:
                remoto = '2';
                presenza = '2';
        }
    }

    const tesseramento = $('input[name="tesseramento"]:checked').val();
    const privato = $('input[name="visibilità"]:checked').val();

    const fd = new FormData();
    if($('#picInput')[0].files[0] !== undefined) {
        fd.append('file', $('#picInput')[0].files[0])
    }
    fd.append('topic', topic);
    fd.append('evento', evento);
    fd.append('data', dataEvento);
    fd.append('luogo', luogo);
    fd.append('inizio', inizio);
    fd.append('fine', fine);
    fd.append('descrizione', descrizione);
    fd.append('importo', importo)
    fd.append('max', max);
    fd.append('remoto', remoto);
    fd.append('presenza', presenza);
    fd.append('tesseramento', tesseramento);
    fd.append('privato', privato)
    fd.append('action', 'createEvent')

    $.ajax({
        type: 'POST',
        data: fd,
        url: root + 'app/controllers/CreationController.php',
        processData: false,
        contentType: false,
        success: function(data) {
            const parsed = JSON.parse(data);
            console.log(parsed);
            event = parsed.lastRow;
        }
    })
}
function speakersTab() {
    let _kt_datatable_speakers_toAdd;
    _kt_datatable_speakers_toAdd = $("#kt_datatable_speakers_toAdd").DataTable({
        serverSide: true,
        ajax: {
            type: "POST",
            url: root + 'app/controllers/GeneralGetterController.php',
            data: {'action': 'getSpeakers'},
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
                render: (data) => '<div class="form-check form-check-custom form-check-solid"><input id="checkbox-' + data.speaker + '" class="form-check-input mx-2" type="checkbox" name="speakers" value="'+ data.id +'"/></div>'},
            {data: null,
                render: (data) => '<img class="w-100px" alt="' + data.speaker + '-logo" src="' + (data.pic ? root + 'app/assets/uploaded-files/speakers-images/' + data.pic : data.avatar) + '"/>'},
            {data: null,
                render: (data) => '<label for="checkbox-' + data.speaker +'">' + data.speaker + '</label>'},
            {data: 'system_date_modified'}
        ],
        columnDefs: [
            {
                targets: '_all',
                orderable: false,
            },
        ]
    })

    _kt_datatable_speakers_toAdd.on('draw', function () {
        KTMenu.createInstances();
    });

}
function materialsTab() {
    let _kt_datatable_materials_event_toAdd;
    _kt_datatable_materials_event_toAdd = $("#kt_datatable_materials_event_toAdd").DataTable({
        serverSide: true,
        ajax: {
            type: "POST",
            url: root + 'app/controllers/GeneralGetterController.php',
            data: function(d) {
                d.action = 'getMaterials';
            },
            dataSrc: function (data) {
                return data.data;
            }
        },
        paging: false,
        info: false,
        columns: [
            {data: null,
                render: (data) => '<div class="form-check form-check-custom form-check-solid"><label><input class="form-check-input mx-2" type="radio" name="' + (data.id_tipologia === 6 ? 'lecture' : 'poll') + '" value="'+ data.id + '-' + data.id_tipologia +'"/>' + data.nome + '</label></div>'},
            {data: null,
                render: (data) => '<p class="mb-0">' + (data.id_tipologia == 6 ? 'Dispensa' : 'Quiz') + '</p>'},
            {data: 'data'}
        ],
        columnDefs: [
            {
                targets: '_all',
                orderable: false,
            },
        ]
    })

    _kt_datatable_materials_event_toAdd.on('draw', function () {
        KTMenu.createInstances();
    });

}
function surveysTab() {

    let _kt_datatable_surveys_toAdd;
    _kt_datatable_surveys_toAdd = $("#kt_datatable_surveys_event_toAdd").DataTable({
        serverSide: true,
        ajax: {
            type: "POST",
            url: root + 'app/controllers/LessonController.php',
            data: {'lesson': event, 'action': 'getSurveysDraft'},
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
    let _kt_datatable_sponsors_event_toAdd;
    _kt_datatable_sponsors_event_toAdd = $("#kt_datatable_sponsors_event_toAdd").DataTable({
        serverSide: true,
        ajax: {
            type: "POST",
            url: root + 'app/controllers/GeneralGetterController.php',
            data: function(d) {
                d.action = 'getSponsors'
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

    _kt_datatable_sponsors_event_toAdd.on('draw', function () {
        KTMenu.createInstances();
    });

}
fileInput.addEventListener('change', function() {
    readURL(this);
})

