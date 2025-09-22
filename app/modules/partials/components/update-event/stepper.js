const event = params.get("id");

let orarioInizio;
let orarioFine;

if(ondemand) {
    eventDetails.classList.add('d-none');
    eventTimes.classList.add('d-none');
    eventMax.classList.add('d-none');
    eventFee.classList.add('col-12');
    eventFee.classList.remove('col-6');
    eventModes.classList.add('d-none');
}

$.ajax({
    type: 'POST',
    url: root + 'app/controllers/LessonController.php',
    data: {'lesson': event, 'action': 'getDraftEvent'},
    success: function(data) {
        const parsed = JSON.parse(data);
        console.log(parsed.data)
        console.log(parsed.topics);
        const list = document.getElementById('topics-list');
        parsed.topics.data.forEach(el => {
            const div = document.createElement('div');
            div.classList.add('col-3');
            const label = document.createElement('label');
            label.classList.add('form-check-image');

            const wrapper = document.createElement('div');
            wrapper.classList.add('form-check-wrapper', 'w-150px', 'h-150px');
            wrapper.setAttribute('style', 'background-color:' + el['colore']);
            const img = document.createElement('img');
            const src = el['immagine'].split(':')[0] === 'http' || el['immagine'].split(':')[0] === 'https' ? el['immagine'] : root + 'app/assets/uploaded-files/category-images/' + el['immagine']
            img.setAttribute('src', src);

            wrapper.append(img);
            label.append(wrapper);

            const divInput = document.createElement('div');
            divInput.classList.add('form-check', 'form-check-custom', 'form-check-solid', 'd-flex', 'justify-content-center', 'w-100');
            const input = document.createElement('input');
            input.classList.add('form-check-input', 'radio-check');
            input.setAttribute('type', 'radio');
            input.setAttribute('name', 'argomento');
            input.setAttribute('value', el['id']);
            if(Number(parsed.data[0]['argomento']) === Number(el['id'])) {
                input.checked = true;
            }
            const inputLabel = document.createElement('div');
            inputLabel.classList.add('form-check-label');
            inputLabel.textContent = el['nome'];

            divInput.append(input);
            divInput.append(inputLabel);
            label.append(divInput);

            div.append(label);
            list.append(div);
        })

        $('#nome-evento').val(parsed.data[0]['evento']);
        $('#data-evento').val(parsed.data[0]['data_inizio']);
        orarioInizio = parsed.data[0]['orario_inizio'];
        orarioFine = parsed.data[0]['orario_fine'];
        $('#luogo-evento').val(parsed.data[0]['luogo']);
        $('#descrizione-evento').val(parsed.data[0]['descrizione']);
        $('#live-stream-link').val(parsed.data[0]['url']);
        $('#zoom-meeting').val(parsed.data[0]['zoom_meeting']);
        $('#zoom-pw').val(parsed.data[0]['zoom_pw']);
        $('#contributo-evento').val(parsed.data[0]['importo']);
        $('#max-evento').val(parsed.data[0]['posti']);
        document.getElementById('pic').setAttribute('src', root + 'app/assets/uploaded-files/heros-images/' + parsed.data[0]['immagine'])

        if(Number(parsed.data[0]['remoto']) === 1) {
            $('#remoto-evento').prop('checked', true)
        } else if(Number(parsed.data[0]['presenza']) === 1) {
            $('#presenza-evento').prop('checked', true)
        } else {
            $('#mod-boh-evento').prop('checked', true)
        }

        if(Number(parsed.data[0]['tesseramento']) === 1) {
            $('#tess-yes-evento').prop('checked', true)
        } else if(Number(parsed.data[0]['tesseramento']) === 0) {
            $('#tess-no-evento').prop('checked', true)
        }

        if(Number(parsed.data[0]['privato']) === 1) {
            $('#privato-evento').prop('checked', true)
        } else {
            $('#pubblico-evento').prop('checked', true)
        }

        if(parsed.data[0]['hero'] !== null) {
            const pic = document.getElementById('pic');
            pic.setAttribute('src', root + 'app/assets/uploaded-files/heros-images/' + parsed.data[0]['hero'])
        }
        new tempusDominus.TempusDominus(document.getElementById('orario-inizio-evento'), {
            localization: {
                hourCycle: 'h23',
                format: 'HH:mm'
            },
            defaultDate: orarioInizio,
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
        new tempusDominus.TempusDominus(document.getElementById('orario-fine-evento'), {
            localization: {
                hourCycle: 'h23',
                format: 'HH:mm'
            },
            defaultDate: orarioFine,
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

        const radioButtons = document.querySelectorAll('.radio-check');
        radioButtons.forEach(el => {
            el.addEventListener('change', function() {
                document.getElementById('error-message').classList.add('d-none')
            })
        })

        //check se path_video ha un valore, per cui ha un video già associato, o no
        if(parsed.data[0]['path_video'] === null && parsed.data[0]['url'] === null && parsed.data[0]['zoom_meeting'] === null) {
            deployFileInput();
        } else if (parsed.data[0]['path_video'] !== null) {

            const videoBox = document.getElementById('uploaded-video');
            videoBox.classList.remove('d-none');
            const video = document.createElement('video');
            video.setAttribute('id', 'video-lesson-' + event);
            video.setAttribute('controls', 'controls');
            video.setAttribute('preload', 'auto');
            video.setAttribute('width', '640');
            video.setAttribute('height', '360');
            video.classList.add('video-js', 'vjs-default-skin', 'w-100', 'h-400px', 'rounded');
            const source = document.createElement('source');
            // source.setAttribute('src', root + 'app/assets/videos/' + parsed.data[0]['path_video']);
            source.setAttribute('src', 'https://storage.googleapis.com/auser-zoom-meetings/' + event + '/' + parsed.data[0]['path_video']);
            source.setAttribute('type', 'video/mp4');
            const fileName = document.createElement('p');
            fileName.setAttribute('id', 'video-fileName');
            fileName.textContent = parsed.data[0]['path_video'];
            fileName.classList.add('d-none');

            video.append(source);
            videoBox.append(fileName);
            videoBox.append(video);

            videoRemoveButton.classList.remove('d-none');

            player = videojs('video-lesson-' + event);

            disableLink();
            disableZoom();

        } else if (parsed.data[0]['url'] !== null) {
            deployFileInput();
            $('#live-stream-link').val(parsed.data[0]['url']);
            disableZoom();
            disableVideo();

        } else if(parsed.data[0]['zoom_meeting'] !== null) {
            deployFileInput();
            $('#zoom-meeting').val(parsed.data[0]['zoom_meeting']);
            $('#zoom-pw').val(parsed.data[0]['zoom_pw']);
            disableLink();
            disableVideo();
        }

    }
})

stepper.on("kt.stepper.previous", function (stepper) {
    document.getElementById('publish-error-message').classList.add('d-none')
    const index = stepper.getCurrentStepIndex();
    if(index === 7 && userGroup === 3) {
        stepper.goTo(5)
    } else {
        stepper.goPrevious(); // go next step
    }
});

stepper.on("kt.stepper.change", function() {
    const index = stepper.getCurrentStepIndex();
    if(index === 2) {
       updateEvent();
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
            success: function() {
            }
        })
    }
});

function speakersTab() {
    let _kt_datatable_speakers_toAdd;
    _kt_datatable_speakers_toAdd = $("#kt_datatable_speakers_toAdd").DataTable({
        serverSide: true,
        ajax: {
            type: "POST",
            url: root + 'app/controllers/LessonController.php',
            data: {'lesson': event, 'action': 'getDraftSpeakers'},
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
                render: (data) => {
                    const checked = data.checked === '1' ? ' checked' : ''
                    return '<div class="form-check form-check-custom form-check-solid"><input' + checked +' id="checkbox-' + data.nome + '" class="form-check-input mx-2" type="checkbox" name="speakers" value="' + data.id + '"/></div>'
                }},
            {data: null,
                render: (data) => '<img class="w-100px" alt="' + data.speaker + '-logo" src="' + (data.logo ? root + 'app/assets/uploaded-files/speakers-images/' + data.logo : data.pic) + '"/>'},
            {data: null,
                render: (data) => '<label for="checkbox-' + data.speaker +'">' + data.speaker + '</label>'},
            {data: 'data'}
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
            url: root + 'app/controllers/LessonController.php',
            data: {'lesson': event, 'action': 'getDraftMaterials'},
            dataSrc: function (data) {
                console.log(data.data)
                return data.data;
            }
        },
        paging: false,
        info: false,
        pageLength: 10,
        lengthChange: true,
        columns: [
            {
                data: null,
                render: (data) => {
                    const checked = data.id_diretta !== null ? ' checked' : ''
                    return '<div class="form-check form-check-custom form-check-solid"><label><input' + checked + ' class="form-check-input mx-2" type="radio" name="' + (data.id_tipologia === 6 ? 'lecture' : 'poll') + '" value="' + data.id + '-' + data.id_tipologia + '"/>' + data.nome + '</label></div>'
                }
            },
            {
                data: null,
                render: (data) => '<p class="mb-0">' + (data.id_tipologia == 6 ? 'Dispensa' : 'Quiz') + '</p>'
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
            url: root + 'app/controllers/LessonController.php',
            data: {'lesson': event, 'action': 'getDraftSponsors'},
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
                render: (data) => {
                    const checked = data.checked === '1' ? ' checked' : ''
                    return '<div class="form-check form-check-custom form-check-solid"><input' + checked +' id="checkbox-' + data.nome + '" class="form-check-input mx-2" type="checkbox" name="sponsors" value="' + data.id + '"/></div>'
                }},
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

