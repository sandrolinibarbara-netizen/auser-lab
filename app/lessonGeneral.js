const root = document.getElementById('root').getAttribute('value');
const userGroup = Number(document.getElementById('user-permission').value);
let counterMaterialsTab = 0;
let counterHomeworksTab = 0;
let counterSponsorsTab = 0;
let counterRecap = 0;
let counterForm = 0;
const selectedMaterials = [];
const selectedHomeworks = [];
const selectedSurveys = [];
const selectedSponsors = [];
const search = window.location.search;
const params = new URLSearchParams(search);
const course = params.get("id");
const onDemand = params.get("ondemand") ?? null;

const element = document.querySelector("#kt_stepper_draft_lesson") ?? document.querySelector("#kt_stepper_lesson");
const form = document.getElementById('kt_stepper_draft_lesson_form') ?? document.getElementById('kt_stepper_lesson_form');
const stepper = new KTStepper(element);

const modalHeader = document.getElementById('modal-video-header');
const modalBreakPoint = document.getElementById('modal-video-breakpoint');
const modalTable = document.getElementById('modal-video-table');
const modalBody = document.getElementById('modal-video-body');
const modalSave = document.getElementById('modal-video-save');

let player;
let tempMarkerTime;
const markersTable = document.getElementById('markers-table');
const addTable = document.getElementById('kt_datatable_materials_video');
const videoModal = document.getElementById('video-modal');

let modalCounter = 0;
const selectedMaterialsVideo = [];

const videoButton = document.getElementById('video-upload-button');
const videoRemoveButton = document.getElementById('video-remove-button');
const addMarkerButton = document.getElementById('add-marker-button');
const streamLink = document.getElementById('live-stream-link');
const zoomMeeting = document.getElementById('zoom-meeting');
const zoomPw = document.getElementById('zoom-pw');
const videoBox = document.getElementById('video-box');
const linkBox = document.getElementById('link-box');
const zoomBox = document.getElementById('zoom-box');

const lessonName= document.getElementById('lesson-name');
const lessonDate= document.getElementById('lesson-date');
const lessonTimes= document.getElementById('lesson-times');
const lessonPlace= document.getElementById('lesson-place');

streamLink.addEventListener('input', function(e) {
    if(document.getElementById('video-lesson-' + lesson)) {
        deleteVideo();
    }
    disableZoom();
    disableVideo();

    if(streamLink.value === '') {
        enableVideo();
        enableZoom();
    }
})
zoomMeeting.addEventListener('input', function(e) {
    if(document.getElementById('video-lesson-' + lesson)) {
        deleteVideo();
    }
    disableLink();
    disableVideo();

    if(zoomMeeting.value === '' && zoomPw.value === '') {
        enableVideo();
        enableLink();
    }
})
zoomPw.addEventListener('input', function(e) {
    if(document.getElementById('video-lesson-' + lesson)) {
        deleteVideo();
    }
    disableLink();
    disableVideo();

    if(zoomMeeting.value === '' && zoomPw.value === '') {
        enableVideo();
        enableLink();
    }
})
videoRemoveButton.addEventListener('click', deleteVideo);
videoButton.addEventListener('click', function (e) {
    e.preventDefault();

    const reader = new FileReader();
    reader.readAsDataURL($('#video-fileInput')[0].files[0]);
    reader.addEventListener('load', () => {
        // const fd = {
        //     'idLesson': lesson.toString(),
        //     'action': 'uploadVideo',
        //     'fileName': $('#video-fileInput')[0].files[0].name,
        //     'blob': reader.result
        // }
        const fd = new FormData();
        fd.append('file', $('#video-fileInput')[0].files[0]);
        fd.append('idLesson', lesson);
        fd.append('action', 'uploadVideo');
        fd.append('fileName', $('#video-fileInput')[0].files[0].name);

        $.ajax({
            type: 'POST',
            data: fd,
            url: root + 'app/controllers/VideoEditorController.php',
            processData: false,
            contentType: false,
            success: function (data) {
                const parsed = JSON.parse(data);
                disableLink();
                disableZoom();

                const chooseVideo = document.getElementById('choose-video');
                chooseVideo.classList.add('d-none');
                videoButton.classList.add('d-none');
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
                source.setAttribute('src', root + 'app/assets/videos/' + parsed.url);
                // source.setAttribute('src', parsed.url);
                source.setAttribute('type', 'video/mp4');

                video.append(source);
                videoBox.append(video);

                addMarkerButton.classList.remove('d-none');
                videoRemoveButton.classList.remove('d-none');

                player = videojs('video-lesson-' + lesson);
                console.log(player)
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
            }
        })
    })

})
videoModal.addEventListener('show.bs.modal', function (e) {
    player.pause();
    console.log(modalCounter);

    const button = e.relatedTarget;
    const action = button.getAttribute('data-bs-action');
    console.log(action)

    if (action === 'add') {

        if (tempMarkerTime) {
            const p = document.getElementById('tempText');
            if (p) {
                p.remove();
            }
            $('#breakpoint-time').text(convertTime(tempMarkerTime));
            modalHeader.classList.remove('d-none');
            modalBreakPoint.classList.remove('d-none');
            modalTable.classList.remove('d-none');
            modalBody.classList.remove('fs-1', 'w-100', 'text-center');
            modalSave.classList.remove('d-none');
            addTable.classList.remove('d-none')
            const saveButton = document.getElementById('modal-video-save');
            saveButton.setAttribute('value', '1');


            $.ajax({
                type: 'POST',
                url: root + 'app/controllers/VideoEditorController.php',
                data: {'action': 'getAvailableMaterials', 'course': course},
                success: function (data) {
                    const parsed = JSON.parse(data);
                    console.log(parsed);
                    if(parsed.data.length === 0) {
                        $('#modal-video-body').empty();
                        modalHeader.classList.add('d-none');
                        modalBreakPoint.classList.add('d-none');
                        modalTable.classList.add('d-none');
                        modalSave.classList.add('d-none');
                        addTable.classList.add('d-none')
                        const p = document.createElement('p');
                        p.setAttribute('id', 'tempText');
                        p.textContent = 'Non ci sono materiali disponibili'
                        p.classList.add('fs-1', 'w-100', 'text-center');
                        modalBody.append(p);
                        return;
                    }
                    const body = document.getElementById('materialsToSelectVideo');
                    if (body) body.remove();
                    const tbody = document.createElement('tbody');
                    tbody.classList.add('text-gray-600', 'fw-bold');
                    tbody.setAttribute('id', 'materialsToSelectVideo')
                    addTable.append(tbody);
                    parsed.data.forEach(el => {
                        const tr = document.createElement('tr');
                        const div = document.createElement('td');
                        div.classList.add('form-check', 'form-check-custom', 'form-check-solid');
                        const label = document.createElement('label');
                        const input = document.createElement('input');
                        input.classList.add('form-check-input');
                        input.setAttribute('type', 'radio');
                        input.setAttribute('name', 'materials');
                        input.setAttribute('value', el['id'] + '-' + el['id_tipologia']);
                        label.append(input);
                        div.append(label)

                        const materialName = document.createElement('td');
                        const materialType = document.createElement('td');
                        const materialDate = document.createElement('td');

                        materialName.textContent = el['nome'];
                        materialType.textContent = el['id_tipologia'] === 6 ? 'Dispensa' : 'Quiz';
                        materialDate.textContent = el['data'];
                        tr.append(div)
                        tr.append(materialName);
                        tr.append(materialType);
                        tr.append(materialDate)
                        tbody.append(tr);
                        markersTable.classList.remove('d-none')
                    })
                }
            })

            modalCounter++;

        } else {
            // $('#modal-video-body').empty();
            modalHeader.classList.add('d-none');
            modalBreakPoint.classList.add('d-none');
            modalTable.classList.add('d-none');
            modalSave.classList.add('d-none');
            addTable.classList.add('d-none')
            const p = document.createElement('p');
            p.setAttribute('id', 'tempText');
            p.textContent = 'Scegli prima un punto in cui inserire il materiale'
            p.classList.add('fs-1', 'w-100', 'text-center');
            modalBody.append(p);
        }
    }
    if (action === 'update') {
        const p = document.getElementById('tempText');
        if (p) {
            p.remove();
        }
        modalHeader.classList.remove('d-none');
        modalBreakPoint.classList.remove('d-none');
        modalTable.classList.remove('d-none');
        modalBody.classList.remove('fs-1', 'w-100', 'text-center');
        modalSave.classList.remove('d-none');
        addTable.classList.remove('d-none')
        const saveButton = document.getElementById('modal-video-save');
        saveButton.setAttribute('value', '2');
        const idMarker = button.getAttribute('data-bs-idMarker');

        $.ajax({
            type: 'POST',
            url: root + 'app/controllers/VideoEditorController.php',
            data: {
                'idMarker': idMarker,
                'action': 'getSavedMaterials'
            },
            success: function (data) {
                const parsed = JSON.parse(data);
                console.log(parsed);
                const body = document.getElementById('materialsToSelectVideo');
                if (body) body.remove();
                const tbody = document.createElement('tbody');
                tbody.classList.add('text-gray-600', 'fw-bold');
                tbody.setAttribute('id', 'materialsToSelectVideo')
                addTable.append(tbody);
                parsed.data.forEach(el => {
                    if (el['idMarker'] === Number(idMarker)) {
                        $('#breakpoint-time').text(convertTime(el['minutaggio']));
                        const hiddenInput = document.getElementById('marker-id');
                        if (hiddenInput) hiddenInput.remove();
                        const footerInput = document.createElement('input');
                        footerInput.setAttribute('hidden', 'hidden');
                        footerInput.setAttribute('id', 'marker-id');
                        footerInput.setAttribute('value', idMarker);
                        const footer = document.getElementById('modal-video-footer');
                        footer.append(footerInput);
                    }
                    const tr = document.createElement('tr');
                    const div = document.createElement('td');
                    div.classList.add('form-check', 'form-check-custom', 'form-check-solid');
                    const label = document.createElement('label');
                    const input = document.createElement('input');
                    input.classList.add('form-check-input');
                    input.setAttribute('type', 'radio');
                    input.setAttribute('name', 'materials');
                    input.setAttribute('value', el['id'] + '-' + el['id_tipologia']);
                    if (el['idMarker'] === Number(idMarker)) {
                        input.setAttribute('checked', 'checked');
                    }
                    label.append(input);
                    div.append(label)

                    const materialName = document.createElement('td');
                    const materialType = document.createElement('td');
                    const materialDate = document.createElement('td');

                    materialName.textContent = el['nome'];
                    materialType.textContent = el['id_tipologia'] == 6 ? 'Dispensa' : 'Quiz';
                    materialDate.textContent = el['data'];
                    tr.append(div)
                    tr.append(materialName);
                    tr.append(materialType);
                    tr.append(materialDate)
                    tbody.append(tr);
                })
            }
        })
    }
})
videoModal.addEventListener('submit', function (e) {
    e.preventDefault();
    if (e.submitter.value === "1") {
        selectedMaterialsVideo.length = 0;
        $('#materialsToSelectVideo input:checked').each(function () {
            const ids = $(this).attr('value').split('-');
            selectedMaterialsVideo.push({'id_material': ids[0], 'id_type': ids[1]});
        })
        console.log(selectedMaterialsVideo);
        $.ajax({
            type: 'POST',
            data: {
                'selected': selectedMaterialsVideo,
                'course': course,
                'lesson': lesson,
                'markerTime': tempMarkerTime,
                'action': 'createMarker'
            },
            url: root + 'app/controllers/VideoEditorController.php',
            success: function (data) {
                const parsed = JSON.parse(data);
                console.log(parsed);
                markersTable.classList.remove('d-none');
                const body = document.getElementById('markers-polls-list');
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
                    idMarker.setAttribute('id', el['materialId'] + '-' + el['materialType']);
                    idMarker.setAttribute('value', el['markerId']);
                    idMarker.setAttribute('hidden', 'hidden');

                    for (let i = 0; i < el['azioni'].length; i++) {
                        const button = document.createElement('button');
                        button.setAttribute('value', el['materialId'] + '-' + el['materialType']);
                        button.classList.add('rounded', 'text-auser', 'text-decoration-none', 'p-2', 'ki-outline', 'bg-light-bg', 'me-1', 'border-0', el['azioni'][i]['icona'])
                        linkToActions.append(button);

                        if (el['azioni'][i]['metodo'] === 'DELETE') {
                            button.addEventListener('click', function (e) {
                                console.log(button.getAttribute('value'));
                                const materialType = button.getAttribute('value').split('-')[1];
                                const materialId = button.getAttribute('value').split('-')[0];
                                $.ajax({
                                    type: 'POST',
                                    url: root + 'app/controllers/VideoEditorController.php',
                                    data: {
                                        'idMarker': idMarker.getAttribute('value'),
                                        'materialType': materialType,
                                        'idMaterial': materialId,
                                        'action': 'deleteMarker'
                                    },
                                    success: function () {
                                        tr.remove();
                                        const arr = player.markers.getMarkers();
                                        console.log(arr)
                                        const deleted = arr.findIndex(marker => marker.time === Number(el['markerTime']));
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

                    markerTime.textContent = convertTime(Number(el['markerTime']));
                    materialName.textContent = el['materialName']['nome'];
                    materialType.textContent = el['materialType'] === 6 ? 'Dispensa' : 'Quiz';
                    tr.append(markerTime);
                    tr.append(materialName);
                    tr.append(materialType);
                    tr.append(linkToActions);
                    tr.append(idMarker);
                    body.append(tr);
                    player.markers.add([{time: Number(tempMarkerTime), text: el['materialName']['nome']}]);

                })
            }
        })
    }
    if (e.submitter.value === "2") {
        console.log('quaso')
        selectedMaterialsVideo.length = 0;
        $('#materialsToSelectVideo input').each(function () {
            const ids = $(this).attr('value').split('-');
            selectedMaterialsVideo.push({
                'id_material': ids[0],
                'id_type': ids[1],
                'checked': $(this).prop('checked') ? '1' : '0'
            });
        })
        console.log(selectedMaterialsVideo);
        const input = document.getElementById('marker-id');
        const idMarker = input.getAttribute('value');
        $.ajax({
            type: 'POST',
            data: {
                'selected': selectedMaterialsVideo,
                'course': course,
                'lesson': lesson,
                'idMarker': idMarker,
                'action': 'updateMarker'
            },
            url: root + 'app/controllers/VideoEditorController.php',
            success: function (data) {
                const parsed = JSON.parse(data);
                console.log(parsed);
                parsed.data.forEach(el => {
                    const rightRowIdMarker = document.querySelectorAll(`tr`);
                    rightRowIdMarker.forEach(input => {
                        if (input.querySelector(`input[value="${el['markerId']}"]`)) {
                            const hiddenInput = input.querySelector('input');
                            hiddenInput.setAttribute('id', el['materialId'] + '-' + el['materialType'])
                            const cells = input.querySelectorAll('td');
                            console.log(cells)
                            cells[1].textContent = el['materialName']['nome'];
                            cells[2].textContent = el['materialType'] === 6 ? 'Dispensa' : 'Quiz';
                            cells[3].querySelectorAll('button').forEach(button => {
                                button.setAttribute('value', el['materialId'] + '-' + el['materialType'])
                            })
                        }
                    })


                })
            }
        })
    }
})

stepper.on("kt.stepper.previous", function (stepper) {
    document.getElementById('publish-error-message').classList.add('d-none')
    const index = stepper.getCurrentStepIndex();
    if(index === 6 && userGroup === 3) {
        stepper.goTo(4)
    } else {
        stepper.goPrevious(); // go next step
    }
});

stepper.on("kt.stepper.change", function () {
    const index = stepper.getCurrentStepIndex();

    if (index === 2) {
        const link = $('#live-stream-link').val();
        const zoomMeeting = $('#zoom-meeting').val();
        const zoomPw = $('#zoom-pw').val();
        $.ajax({
            type: 'POST',
            url: root + 'app/controllers/LessonController.php',
            data: {
                'link': link,
                'course': course,
                'lesson': lesson,
                'action': 'updateDraftLink',
                'zoomMeeting' : zoomMeeting,
                'zoomPw': zoomPw
            }
        })
    }

    if (index === 3) {
        selectedMaterials.length = 0;
        $('#materialsToSelect input:checked').each(function () {
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
                'course': course,
                'lesson': lesson,
                'action': 'updateDraftMaterials'
            },
            url: root + 'app/controllers/LessonController.php',
        })
    }

    if(index === 4) {
        selectedHomeworks.length = 0;
        $('#homeworksToSelect input:checked').each(function() {
            const ids = $(this).attr('value').split('-');
            selectedHomeworks.push({'id_material': ids[0], 'id_type': ids[1]});
        })

        $.ajax({
            type: 'POST',
            data: {
                'selected': selectedHomeworks,
                'course': course,
                'lesson': lesson,
                'action': 'updateDraftHomeworks'
            },
            url: root + 'app/controllers/LessonController.php',
        })
    }

    if (index === 5) {
        selectedSponsors.length = 0;
        $('#sponsorsToSelect input:checked').each(function () {
            selectedSponsors.push($(this).attr('value'));
        })
        console.log(selectedSponsors);
        $.ajax({
            type: 'POST',
            data: {
                'selected': selectedSponsors,
                'course': course,
                'lesson': lesson,
                'action': 'updateDraftSponsors'
            },
            url: root + 'app/controllers/LessonController.php',
        })
    }
});

$("#data-lezione").daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    minYear: 2020,
    maxYear: parseInt(moment().format("YYYY"), 12),
    locale: {
        format: "DD/MM/YYYY",
        applyLabel: "Applica",
        cancelLabel: "Indietro",
        daysOfWeek: [
            "Dom",
            "Lun",
            "Mar",
            "Mer",
            "Gio",
            "Ven",
            "Sab"
        ],
        monthNames: [
            "Gennaio",
            "Febbraio",
            "Marzo",
            "Aprile",
            "Maggio",
            "Giugno",
            "Luglio",
            "Agosto",
            "Settembre",
            "Ottobre",
            "Novembre",
            "Dicembre"
        ],
        "firstDay": 1
    }
});

function updateLesson(stepper) {

    const title = $('#nome-lezione').val();
    const date = $('#data-lezione').val();
    const start = $('#orario-inizio-lezione').val();
    const end = $('#orario-fine-lezione').val();
    const location = $('#luogo-lezione').val();
    const description = $('#descrizione-lezione').val();

    if(!checkDate(date)) {
        return;
    }

    $.ajax({
        type: 'POST',
        data: {
            'action': 'updateLesson',
            'nome': title,
            'data': date,
            'inizio': start,
            'fine': end,
            'luogo': location,
            'descrizione': description,
            'lesson': lesson,
            'idCorso': course
        },
        url: root + 'app/controllers/LessonController.php',
        success: function () {
            stepper.goNext();
        },
        error: function() {
            document.getElementById('date-error-message').classList.remove('d-none');
        }
    })
}
function showRecap() {

    $.ajax({
        type: 'POST',
        url: root + 'app/controllers/LessonController.php',
        data: {'lesson': lesson, 'resetSession': false, 'action': 'getDraftLesson'},
        success: function (data) {
            const parsed = JSON.parse(data);
            $('#recap-nome').text('Nome delle lezione: ' + parsed.data[0]['nome']);
            $('#recap-descrizione').text('Descrizione: ' + parsed.data[0]['descrizione']);

            if(parsed.data[0]['data_inizio'] !== '01/01/3000') {
                document.getElementById('var-data').classList.remove('d-none');
                $('#recap-data').text('Data: ' + parsed.data[0]['data_inizio']);
                $('#recap-inizio').text('Orario di inizio: ' + parsed.data[0]['orario_inizio']);
                $('#recap-fine').text('Orario di fine: ' + parsed.data[0]['orario_fine']);
                $('#recap-luogo').text('Luogo: ' + parsed.data[0]['luogo']);
            }
        }
    })
}
function materialsRecapTab() {
    let _kt_datatable_materials_recap;
    _kt_datatable_materials_recap = $("#kt_datatable_materials_recap").DataTable({
        serverSide: true,
        ajax: {
            type: "POST",
            url: root + 'app/controllers/LessonController.php',
            data: function(d){
                d.action = 'getRecapMaterials';
                d.lesson = lesson
            },
            dataSrc: function (data) {
                console.log(data)
                return data.data;
            }
        },
        info: false,
        paging: false,
        columns: [
            {data: 'nome'},
            {
                data: null,
                render: (data) => '<p>' + (data.categoria === 6 ? 'Dispensa' : 'Quiz') + '</p>'
            },
        ],
        columnDefs: [
            {
                targets: '_all',
                orderable: false,
            },
        ]
    })

    _kt_datatable_materials_recap.on('draw', function () {
        KTMenu.createInstances();
    });
}
function homeworksRecapTab() {
    let _kt_datatable_homeworks_recap;
    _kt_datatable_homeworks_recap = $("#kt_datatable_homeworks_recap").DataTable({
        serverSide: true,
        ajax: {
            type: "POST",
            url: root + 'app/controllers/LessonController.php',
            data: function(d){
                d.action = 'getRecapHomeworks';
                d.lesson = lesson
            },
            dataSrc: function (data) {
                console.log(data)
                return data.data;
            }
        },
        info: false,
        paging: false,
        columns: [
            {data: 'nome'},
            {data: null,
                render: (data) => '<p>' + (data.categoria === 6 ? 'Dispensa' : 'Quiz') + '</p>'},
        ],
        columnDefs: [
            {
                targets: '_all',
                orderable: false,
            },
        ]
    })

    _kt_datatable_homeworks_recap.on('draw', function () {
        KTMenu.createInstances();
    });
}
function surveysRecapTab() {
    let _kt_datatable_surveys_recap;
    _kt_datatable_surveys_recap = $("#kt_datatable_surveys_recap").DataTable({
        serverSide: true,
        ajax: {
            type: "POST",
            url: root + 'app/controllers/LessonController.php',
            data: function(d){
                d.action = 'getRecapSurveys';
                d.lesson = lesson
            },
            dataSrc: function (data) {
                console.log(data)
                return data.data;
            }
        },
        info: false,
        paging: false,
        columns: [
            {data: 'nome'},
        ],
        columnDefs: [
            {
                targets: '_all',
                orderable: false,
            },
        ]
    })

    _kt_datatable_surveys_recap.on('draw', function () {
        KTMenu.createInstances();
    });
}
function sponsorsRecapTab() {
    let _kt_datatable_sponsors_recap;
    _kt_datatable_sponsors_recap = $("#kt_datatable_sponsors_recap").DataTable({
        serverSide: true,
        ajax: {
            type: "POST",
            url: root + 'app/controllers/LessonController.php',
            data: function(d) {
                d.action = "getRecapSponsors";
                d.lesson = lesson;
            },
            dataSrc: function (data) {
                console.log(data)
                return data.data;
            }
        },
        info: false,
        paging: false,
        columns: [
            {
                data: null,
                render: (data) => '<img class="w-50px" alt="' + data.nome + '-logo" src="' + (data.pic ? root + 'app/assets/uploaded-files/sponsor-images/' + data.pic : data.logo) + '"/>'
            },
            {data: 'nome'},
        ],
        columnDefs: [
            {
                targets: '_all',
                orderable: false,
            },
        ]
    })

    _kt_datatable_sponsors_recap.on('draw', function () {
        KTMenu.createInstances();
    });

}
function deleteVideo() {
    $.ajax({
        type:'POST',
        url: root + 'app/controllers/LessonController.php',
        // data: {'lesson': lesson, 'action': 'deleteVideo', 'fileName': $('#video-fileName').text()},
        data: {'lesson': lesson, 'action': 'deleteVideo'},
        success: function(data) {
            const video = document.getElementById('video-lesson-' + lesson);
            video.remove();
            player.markers.destroy();
            $('#choose-video').empty();
            deployFileInput();
            document.getElementById('markers-table').classList.add('d-none');
            const markersList = document.getElementById("markers-polls-list");
            while (markersList.firstChild) {
                markersList.removeChild(markersList.lastChild);
            }
            enableLink();
            enableZoom();
            enableVideo();
        }
    })
}
function readURL(input) {

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            $('#video-fileName').text(input.files[0].name);
        }

        reader.readAsDataURL(input.files[0]);
        const button = document.getElementById('video-upload-button');
        button.classList.remove('d-none');
    }
}
function deployFileInput() {
    const box = document.getElementById('choose-video');
    box.classList.remove('d-none')
    const div = document.createElement('div');
    div.classList.add('w-100', 'text-start')
    const chooseFile = document.createElement('p');
    chooseFile.classList.add('form-label');
    chooseFile.textContent = 'Scegli un video (massimo 100MB in formato mp4)';
    const fileInput = document.createElement('div');
    fileInput.classList.add('image-input', 'image-input-empty', 'bg-light-bg', 'w-100', 'h-150px', 'd-flex', 'justify-content-center', 'align-items-center');
    fileInput.setAttribute('data-kt-image-input', 'true');
    const fileName = document.createElement('p');
    fileName.setAttribute('id', 'video-fileName');
    fileName.classList.add('mb-0');
    const fileWrapper = document.createElement('div');
    fileWrapper.classList.add('h-75');
    const label = document.createElement('label');
    label.classList.add('btn', 'btn-icon', 'btn-circle', 'btn-color-muted', 'btn-active-color-primary', 'w-25px', 'h-25px', 'bg-body', 'shadow');
    label.setAttribute('data-kt-image-input-action', 'change');
    label.setAttribute('data-bs-toggle', 'tooltip');
    label.setAttribute('data-bs-dismiss', 'click');
    label.setAttribute('title', "Scegli un video (massimo 100MB in formato mp4)");
    const icon = document.createElement('i');
    icon.classList.add('ki-duotone', 'ki-pencil', 'fs-6');
    const path1 = document.createElement('span');
    path1.classList.add('path1')
    const path2 = document.createElement('span');
    path2.classList.add('path2')
    icon.append(path1);
    icon.append(path2);
    const input = document.createElement('input');
    input.setAttribute('id', 'video-fileInput');
    input.setAttribute('name', 'video-file');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', '.mp4');

    label.append(icon);
    label.append(input);
    fileInput.append(label);
    fileInput.append(fileWrapper);
    fileInput.append(fileName);
    div.append(chooseFile);

    box.append(div);
    box.append(fileInput);

    addMarkerButton.classList.add('d-none');
    videoRemoveButton.classList.add('d-none');

    input.addEventListener('change', function() {
        readURL(this);
    })
}
function convertTime(input, fps = 24) {
    function pad(input) {
        return (input < 10) ? "0" + input : input;
    }

    return [
        pad(Math.floor(input / 3600)),
        pad(Math.floor(input % 3600 / 60)),
        pad(Math.floor(input % 60)),
        pad(Math.floor(input * fps % fps))
    ].join(':');
}
function isFormFilled() {
    const title = $('#nome-lezione').val();
    const date = $('#data-lezione').val();
    const start = $('#orario-inizio-lezione').val();
    const end = $('#orario-fine-lezione').val();
    const location = $('#luogo-lezione').val() === '' ? '-' : $('#luogo-lezione').val();


    if(title && date && start && end && location) {
        return true;
    }

    return false;
}
function checkDate(date) {
    document.getElementById('another-date-message').classList.add('d-none');
    if(date === '01/01/3000') {
        return true;
    }
    const now = new Date();
    let day = now.getDate();
    if(day < 10) {
        day = '0' + day.toString();
    }
    let month = now.getMonth() + 1;
    if(month < 10) {
        month = '0' + month.toString();
    }
    const bArr = date.split('/');
    const lessonDate = Date.parse(bArr[2] + '/' + bArr[1] + '/' + bArr[0]);
    const today = Date.parse(`${now.getFullYear()}/${month}/${day}`);

    if(lessonDate < today) {
        document.getElementById('another-date-message').classList.remove('d-none');
        return false;
    }
    return true;
}
document.addEventListener('click', function(e) {
    const targetPoll = e.target.closest('input[name="poll"]')
    if(targetPoll) {
        if(targetPoll.checked) {
            document.querySelectorAll('input[name="poll"]').forEach(el => {
                el.checked = false;
            })
            targetPoll.checked = true;
        }
    }

    const targetLecture = e.target.closest('input[name="lecture"]')
    if(targetLecture) {
        if(targetLecture.checked) {
            document.querySelectorAll('input[name="lecture"]').forEach(el => {
                el.checked = false;
            })
            targetLecture.checked = true;
        }
    }

    const targetSurvey = e.target.closest('input[name="survey"]')
    if(targetSurvey) {
        if(targetSurvey.checked) {
            document.querySelectorAll('input[name="survey"]').forEach(el => {
                el.checked = false;
            })
            targetSurvey.checked = true;
        }
    }
});
form.addEventListener('submit', function(e){
    e.preventDefault();
    if(e.submitter.value === "2") {
        $.ajax({
            type:'POST',
            url: root + 'app/controllers/LessonController.php',
            data: {'lesson': lesson, 'action': 'saveLesson', 'type': 'lesson'},
            success: function() {
                Swal.fire({
                    icon: 'success',
                    text: 'Lezione salvata con successo!',
                    showConfirmButton: false
                })
                const url = root + 'corso?get=course&id=' + course

                setTimeout(() => window.location.assign(url), 2000)
            }
        })
    }
    if(e.submitter.value === "1") {

        if(!isFormFilled()) {
            document.getElementById('publish-error-message').classList.remove('d-none');
            return;
        }

        $.ajax({
            type:'POST',
            url: root + 'app/controllers/LessonController.php',
            data: {'lesson': lesson, 'action': 'publishLesson', 'type': 'lesson', 'course': course},
            success: function() {
                Swal.fire({
                    icon: 'success',
                    text: 'Lezione pubblicata con successo!',
                    showConfirmButton: false
                })
                const url = root + 'corso?get=course&id=' + course

                setTimeout(() => window.location.assign(url), 2000)
            },
        })
    }
    console.log(e.submitter.value)
})