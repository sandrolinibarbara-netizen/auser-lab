const lesson = params.get("lesson");
let orarioInizio;
let orarioFine;

if(onDemand) {
    lessonName.classList.remove('col-6');
    lessonName.classList.add('col-12');
    lessonDate.classList.add('d-none');
    lessonTimes.classList.remove('row');
    lessonTimes.classList.add('d-none');
    lessonPlace.classList.remove('row');
    lessonPlace.classList.add('d-none');
}

document.getElementById('nome-lezione').addEventListener('input', function() {
    if($('#nome-lezione').val()) {
        document.getElementById('error-message').classList.add('d-none')
    }
})

$.ajax({
    type: 'POST',
    url: root + 'app/controllers/LessonController.php',
    data: {'course': course, 'lesson': lesson, 'resetSession': true, 'action': 'getDraftLesson'},
    success: function (data) {
        const parsed = JSON.parse(data);
        console.log(parsed);
        $('#nome-lezione').val(parsed.data[0]['nome']);
        $('#data-lezione').val(parsed.data[0]['data_inizio']);
        orarioInizio = parsed.data[0]['orario_inizio'];
        orarioFine = parsed.data[0]['orario_fine'];
        $('#luogo-lezione').val(parsed.data[0]['luogo']);
        $('#descrizione-lezione').val(parsed.data[0]['descrizione']);
        new tempusDominus.TempusDominus(document.getElementById('orario-inizio-lezione'), {
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
        new tempusDominus.TempusDominus(document.getElementById('orario-fine-lezione'), {
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

        //check se path_video ha un valore, per cui ha un video già associato, o no
        if(parsed.data[0]['path_video'] === null && parsed.data[0]['url'] === null && parsed.data[0]['zoom_meeting'] === null) {
            deployFileInput();
        } else if (parsed.data[0]['path_video'] !== null) {

            const videoBox = document.getElementById('uploaded-video');
            videoBox.classList.remove('d-none');
            const video = document.createElement('video');
            video.setAttribute('id', 'video-lesson-' + lesson);
            video.setAttribute('controls', 'controls');
            video.setAttribute('preload', 'auto');
            video.setAttribute('width', '640');
            video.setAttribute('height', '360');
            video.classList.add('video-js', 'vjs-default-skin', 'w-100', 'h-400px', 'rounded');
            const source = document.createElement('source');
            // source.setAttribute('src', root + 'app/assets/videos/' + parsed.data[0]['path_video']);
            source.setAttribute('src', 'https://storage.cloud.google.com/auser-zoom-meetings/' + lesson + '/' + parsed.data[0]['path_video'] + '?authuser=2');
            source.setAttribute('type', 'video/mp4');
            const fileName = document.createElement('p');
            fileName.setAttribute('id', 'video-fileName');
            fileName.textContent = parsed.data[0]['path_video'];
            fileName.classList.add('d-none');

            video.append(source);
            videoBox.append(fileName);
            videoBox.append(video);

            addMarkerButton.classList.remove('d-none');
            videoRemoveButton.classList.remove('d-none');

            player = videojs('video-lesson-' + lesson);
            player.on('seeked', function (event) {
                console.log(player.currentTime()) // get the currentTime of the video
                tempMarkerTime = player.currentTime();
                // const li = document.createElement('li');
                // li.classList.add('list-style-none')
                // const markerTime = document.createElement('p');
                // markerTime.textContent = currentTime;
                // li.append(markerTime);
                // list.append(li);
                //video.markers.add([{ time: currentTime, text: "I'm added"}]); //add markers dynamically
            });
            player.markers({
                markers: [],
                onMarkerReached: function (marker, index) {
                    console.log(marker.text);
                    player.pause();
                }
            });

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
stepper.on("kt.stepper.next", function (stepper) {
    const index = stepper.getCurrentStepIndex();
    const title = $('#nome-lezione').val();
    if(index === 1 && (title === "" || !title)) {
        document.getElementById('error-message').classList.remove('d-none')
        return;
    }

    if(index === 1) {
        updateLesson(stepper)
    } else {
        if (index === 4 && userGroup === 3) {
            stepper.goTo(6)
        } else {
            stepper.goNext();
        }
    }
});

stepper.on('kt.stepper.changed', function () {
    const index = stepper.getCurrentStepIndex();

    if (index === 2) {
        getMarkers();
    }

    if (index === 3) {
        counterMaterialsTab++;
        if (counterMaterialsTab === 1) {
            materialsTab();
            surveysTab();
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

    if (index === 5) {
        counterSponsorsTab++;
        if (counterSponsorsTab === 1) {
            sponsorsTab();
        }
    }

    if (index === 6) {
        counterRecap++;
        showRecap();
        if (counterRecap === 1) {
            if(userGroup === 1) sponsorsRecapTab();
            materialsRecapTab();
            homeworksRecapTab()
            surveysRecapTab()
        } else {
            if(userGroup === 1) reloadTable('#kt_datatable_sponsors_recap');
            reloadTable('#kt_datatable_materials_recap');
            reloadTable('#kt_datatable_homeworks_recap');
            reloadTable('#kt_datatable_surveys_recap');
        }
    }
})

function getMarkers() {
    $.ajax({
        type: 'POST',
        url: root + 'app/controllers/VideoEditorController.php',
        data: {'idLesson': lesson, 'action': 'getDraftMarkers'},
        success: function (data) {
            const parsed = JSON.parse(data);
            console.log(parsed);
            if (parsed.data.length !== 0) {
                markersTable.classList.remove('d-none');
            }
            const body = document.getElementById('markers-polls-list');
            if (body) body.remove();
            const tbody = document.createElement('tbody');
            tbody.classList.add('text-gray-600', 'fw-bold');
            tbody.setAttribute('id', 'markers-polls-list')
            markersTable.append(tbody);

            parsed.data.forEach(el => {
                const tr = document.createElement('tr');
                const markerTime = document.createElement('td');
                markerTime.classList.add('w-150px', 'pb-4', 'pe-4');
                const materialName = document.createElement('td');
                materialName.classList.add('w-275px', 'pb-4', 'pe-4');
                const materialType = document.createElement('td');
                materialType.classList.add('w-200px', 'pb-4', 'pe-4');
                const linkToActions = document.createElement('td');
                linkToActions.classList.add('w-125px', 'pb-4', 'pe-4');
                const idMarker = document.createElement('input');
                const id = el['id_categoriamateriale'] == 6 ? el['idDispensa'] : el['idPoll'];
                idMarker.setAttribute('id', id + '-' + el['id_categoriamateriale']);
                idMarker.setAttribute('value', el['idMarker']);
                idMarker.setAttribute('hidden', 'hidden');

                for (let i = 0; i < el['azioni'].length; i++) {
                    const button = document.createElement('button');
                    button.setAttribute('value', id + '-' + el['id_categoriamateriale']);
                    button.setAttribute('type', 'button');
                    button.classList.add('rounded', 'text-auser', 'text-decoration-none', 'p-2', 'ki-outline', 'bg-light-bg', 'me-1', 'border-0', el['azioni'][i]['icona'])
                    linkToActions.append(button);
                    if (el['azioni'][i]['metodo'] === 'DELETE') {
                        button.addEventListener('click', function (e) {
                            console.log(button.getAttribute('value'));
                            $.ajax({
                                type: 'POST',
                                url: root + 'app/controllers/VideoEditorController.php',
                                data: {
                                    'idMarker': idMarker.getAttribute('value'),
                                    'materialType': el['id_categoriamateriale'],
                                    'idMaterial': id,
                                    'action': 'deleteMarker'
                                },
                                success: function () {
                                    tr.remove();
                                    const arr = player.markers.getMarkers();
                                    console.log(arr)
                                    const deleted = arr.findIndex(marker => marker.time === Number(el['minutaggio']));
                                    player.markers.remove([deleted]);
                                }
                            })
                        })
                    } else if (el['azioni'][i]['metodo'] === 'PUT') {
                        button.setAttribute('data-bs-toggle', 'modal');
                        button.setAttribute('data-bs-target', "#video-modal");
                        button.setAttribute('data-bs-action', 'update');
                        button.setAttribute('data-bs-idMarker', idMarker.getAttribute('value'));
                    }
                }

                markerTime.textContent = convertTime(Number(el['minutaggio']));
                materialName.textContent = el['id_categoriamateriale'] === 6 ? el['dispensa'] : el['poll'];
                materialType.textContent = el['id_categoriamateriale'] === 6 ? 'Dispensa' : 'Quiz';
                tr.append(markerTime);
                tr.append(materialName);
                tr.append(materialType);
                tr.append(linkToActions);
                tr.append(idMarker);
                tbody.append(tr);

                player.markers.add([{
                    time: Number(el['minutaggio']),
                    text: el['id_categoriamateriale'] === 6 ? el['dispensa'] : el['poll']
                }]);
            })
        }
    })
}
function materialsTab() {
    let _kt_datatable_materials_toAdd;
    _kt_datatable_materials_toAdd = $("#kt_datatable_materials_toAdd").DataTable({
        serverSide: true,
        ajax: {
            type: "POST",
            url: root + 'app/controllers/LessonController.php',
            data: {'lesson': lesson, course: course, 'action': 'getDraftMaterials'},
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
                    return '<div class="form-check form-check-custom form-check-solid"><label><input ' + (data.checked === 1 ? 'checked ' : '') + 'class="form-check-input mx-2" type="checkbox" name="' + (data.id_tipologia === 6 ? 'lecture' : 'poll') + '" value="' + data.id + '-' + data.id_tipologia + '"/>' + data.nome + '</label></div>'
                }
            },
            {
                data: null,
                render: (data) => '<p class="mb-0">' + (data.id_tipologia === 6 ? 'Dispensa' : 'Quiz') + '</p>'
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
            url: root + 'app/controllers/LessonController.php',
            data: function(d) {
                d.lesson = lesson;
                d.course = course;
                d.action = 'getDraftHomeworks';
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
                    return '<div class="form-check form-check-custom form-check-solid"><label><input' + checked + ' class="form-check-input mx-2" type="checkbox" name="survey" value="' + data.id + '"/>' + data.nome + '</label></div>'
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
            url: root + 'app/controllers/LessonController.php',
            data: {'lesson': lesson, 'action': 'getDraftSponsors'},
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
            {
                data: null,
                render: (data) => {
                    const checked = data.checked === '1' ? ' checked' : ''
                    return '<div class="form-check form-check-custom form-check-solid"><input' + checked + ' id="checkbox-' + data.nome + '" class="form-check-input mx-2" type="checkbox" name="sponsors" value="' + data.id + '"/></div>'
                }
            },
            {
                data: null,
                render: (data) => '<img class="w-100px" alt="' + data.nome + '-logo" src="' + (data.pic ? root + 'app/assets/uploaded-files/sponsor-images/' + data.pic : data.logo) + '"/>'
            },
            {
                data: null,
                render: (data) => '<label for="checkbox-' + data.nome + '">' + data.nome + '</label>'
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

    _kt_datatable_sponsors_toAdd.on('draw', function () {
        KTMenu.createInstances();
    });

}