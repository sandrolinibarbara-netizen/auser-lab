const root = document.getElementById('root').getAttribute('value');
const userGroup = Number(document.getElementById('user-permission').value);
let counterMaterialsTab = 0;
let counterSpeakersTab = 0;
let counterForm = 0;
let counterSponsorsTab = 0;
let counterRecap = 0;

const selectedSpeakers = [];
const selectedMaterials = [];
const selectedSurveys = [];
const selectedSponsors = [];

const search = window.location.search;
const params = new URLSearchParams(search);
const ondemand = params.get("ondemand") ?? null;

const element = document.querySelector("#kt_stepper_event") ?? document.querySelector("#kt_stepper_draft_event");
const form = document.getElementById('kt_stepper_event_form') ?? document.getElementById('kt_stepper_draft_event_form');

const stepper = new KTStepper(element);
const input = document.getElementById('picInput');
const videoButton = document.getElementById('video-upload-button');
const videoRemoveButton = document.getElementById('video-remove-button');
const streamLink = document.getElementById('live-stream-link');
const zoomMeeting = document.getElementById('zoom-meeting');
const zoomPw = document.getElementById('zoom-pw');
const videoBox = document.getElementById('video-box');
const linkBox = document.getElementById('link-box');
const zoomBox = document.getElementById('zoom-box');

const eventDetails= document.getElementById('event-details');
const eventTimes= document.getElementById('event-times');
const eventFee= document.getElementById('event-fee');
const eventMax= document.getElementById('event-max');
const eventModes= document.getElementById('event-modes');

videoButton.addEventListener('click', function (e) {
    e.preventDefault();

    const file = $('#video-fileInput')[0].files[0]; // Ottiene il file selezionato

    if (!file) {
        return;
    }

    // Dimensione di ogni chunk (10 MB). Puoi aumentarla o diminuirla.
    // Chunk più grandi riducono il numero di richieste ma aumentano il rischio di fallimenti per timeout.
    const chunkSize = 1024 * 256; // 256KB in byte

    // Calcola il numero totale di chunk necessari
    const totalChunks = Math.ceil(file.size / chunkSize);
    let chunkIndex = 0; // Inizia dal primo chunk

    // Funzione ricorsiva per caricare i chunk uno alla volta
    function uploadChunk() {
        // Calcola l'inizio e la fine del chunk corrente
        const videoLoader = document.getElementById('video-loader');
        const uploadButton = document.getElementById('video-upload-button');
        const uploadProgress = document.getElementById('upload-progress');
        if(videoLoader.classList.contains('d-none')) videoLoader.classList.remove('d-none');
        if(uploadProgress.classList.contains('d-none')) uploadProgress.classList.remove('d-none');
        if(!uploadButton.classList.contains('d-none')) uploadButton.classList.add('d-none');

        const start = chunkIndex * chunkSize;
        const end = Math.min(start + chunkSize, file.size);
        const chunk = file.slice(start, end); // Estrae il chunk dal file
        console.log(start, end, chunk);
        // Crea un oggetto FormData per inviare i dati via POST
        const formData = new FormData();
        formData.append('chunk', chunk); // Il chunk del file
        formData.append('fileName', file.name); // Nome originale del file
        formData.append('chunkIndex', chunkIndex.toString()); // Indice del chunk corrente
        formData.append('totalChunks', totalChunks.toString()); // Numero totale di chunk
        formData.append('action', 'uploadVideo'); // action
        formData.append('id', event.toString());

        // Invia il chunk al backend PHP
        const xhr = new XMLHttpRequest();
        xhr.open('POST', root + 'app/controllers/VideoEditorController.php', true); // Metodo POST, URL dello script PHP, asincrono

        // Gestione della risposta dal server
        xhr.onload = function() {
            if (xhr.status === 200) {
                chunkIndex++; // Passa al prossimo chunk
                console.log('Loading chunk: ', chunkIndex, totalChunks);
                if (chunkIndex < totalChunks) {
                    const progress = (chunkIndex / (totalChunks - 1)) * 100;
                    if(chunkIndex === totalChunks - 1) {
                        uploadProgress.textContent = 'Salvataggio su Google Cloud Storage'
                    } else {
                        uploadProgress.textContent = 'Loading... ' + Math.trunc(progress) + '%';
                    }
                    uploadChunk(); // Chiama la funzione per caricare il prossimo chunk
                } else {
                    videoLoader.classList.add('d-none');
                    uploadProgress.classList.add('d-none');
                    disableLink();
                    disableZoom();
                    const chooseVideo = document.getElementById('choose-video');
                    chooseVideo.classList.add('d-none');
                    videoButton.classList.add('d-none');
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
                    // source.setAttribute('src', root + 'app/assets/videos/' + parsed.url);
                    source.setAttribute('src', 'https://storage.cloud.google.com/auser-zoom-meetings/' + event.toString() + '/' + $('#video-fileInput')[0].files[0].name + '?authuser=2');
                    source.setAttribute('type', 'video/mp4');

                    video.append(source);
                    videoBox.append(video);

                    addMarkerButton.classList.remove('d-none');
                    videoRemoveButton.classList.remove('d-none');

                    player = videojs('video-lesson-' + event);
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
            } else {
                // Gestione degli errori dal server
                console.error('Server response error:', xhr.responseText);
            }
        };

        // Gestione degli errori di rete
        xhr.onerror = function() {
            console.error('Network error during upload.');
        };

        // Invia la richiesta con il FormData
        xhr.send(formData);
    }

    uploadChunk(); // Avvia il processo di caricamento

})
videoRemoveButton.addEventListener('click', deleteVideo);
streamLink.addEventListener('input', function(e) {
    if(document.getElementById('video-lesson-' + event)) {
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
    if(document.getElementById('video-lesson-' + event)) {
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
    if(document.getElementById('video-lesson-' + event)) {
        deleteVideo();
    }
    disableLink();
    disableVideo();

    if(zoomMeeting.value === '' && zoomPw.value === '') {
        enableVideo();
        enableLink();
    }
})
document.getElementById('nome-evento').addEventListener('input', function() {
    if($('#nome-evento').val()) {
        document.getElementById('name-error-message').classList.add('d-none')
    }
})
input.addEventListener('change', function() {
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            $('#pic').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
});
stepper.on("kt.stepper.next", function (stepper) {
    const index = stepper.getCurrentStepIndex();
    const evento = $('#nome-evento').val();
    const topic = $('input[name="argomento"]:checked').val();
    if(index === 1 && (topic === "" || !topic)) {
        document.getElementById('error-message').classList.remove('d-none')
        return;
    }
    if(index === 2 && (evento === "" || !evento)) {
        document.getElementById('name-error-message').classList.remove('d-none')
        return;
    }
    if(index === 2 && !checkDate($('#data-evento').val())) {
        document.getElementById('another-date-message').classList.remove('d-none')
        return;
    }
    if(index === 5 && userGroup === 3) {
        stepper.goTo(7)
    } else {
        stepper.goNext();
    }

});
stepper.on('kt.stepper.changed', function() {
    const index = stepper.getCurrentStepIndex();

    if(index === 3) {
        counterSpeakersTab++;
        if(counterSpeakersTab === 1) {
            speakersTab();
        }
    }

    if(index === 5) {
        counterMaterialsTab++;
        if(counterMaterialsTab === 1) {
            materialsTab();
            surveysTab();
        }
    }

    if(index === 6) {
        counterSponsorsTab++;
        if(counterSponsorsTab === 1) {
            sponsorsTab();
        }
    }

    if(index === 7) {
        counterRecap++;
        showRecap();
        if(counterRecap === 1) {
            speakersRecapTab()
            materialsRecapTab();
            if(userGroup === 1) sponsorsRecapTab();
            surveysRecapTab();
        } else {
            reloadTable('#kt_datatable_speakers_event_recap')
            reloadTable('#kt_datatable_materials_event_recap')
            if(userGroup === 1) reloadTable('#kt_datatable_sponsors_event_recap');
            reloadTable('#kt_datatable_surveys_event_recap');
        }
    }
})

$("#data-evento").daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    minYear: 2020,
    maxYear: parseInt(moment().format("YYYY"),12),
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
function updateEvent() {

    const topic = $('input[name="argomento"]:checked').val() ?? '0';
    const evento = $('#nome-evento').val();
    const dataEvento = $('#data-evento').val();
    const luogo = $('#luogo-evento').val();
    const inizio = $('#orario-inizio-evento').val();
    const fine = $('#orario-fine-evento').val();
    const descrizione = $('#descrizione-evento').val();
    const importo = $('#contributo-evento').val();
    const max = $('#max-evento').val();

    if(!checkDate(dataEvento)) {
        return;
    }

    let remoto;
    let presenza;

    switch($('input[name="modalità"]:checked').val()) {
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
    fd.append('privato', privato);
    fd.append('lesson', event);
    fd.append('action', 'updateEvent')

    $.ajax({
        type: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        url: root + 'app/controllers/LessonController.php'
    })
}
function showRecap() {

    $.ajax({
        type: 'POST',
        url: root + 'app/controllers/LessonController.php',
        data: {'lesson': event, 'action': 'getDraftEvent'},
        success: function(data) {
            const parsed = JSON.parse(data);
            console.log(parsed);
            $('#recap-event-nome').text('Nome dell\' evento: ' + parsed.data[0]['evento']);
            $('#recap-event-descrizione').text('Descrizione: ' + parsed.data[0]['descrizione']);
            $('#recap-event-importo').text('Contributo: ' + parsed.data[0]['importo']  + '€');

            if(parsed.data[0]['data_inizio'] !== '01/01/3000') {
                document.getElementById('var-data').classList.remove('d-none');
                $('#recap-event-data').text('Data: ' + parsed.data[0]['data_inizio']);
                $('#recap-event-inizio').text('Orario di inizio: ' + parsed.data[0]['orario_inizio']);
                $('#recap-event-fine').text('Orario di fine: ' + parsed.data[0]['orario_fine']);
                $('#recap-event-luogo').text('Luogo: ' + parsed.data[0]['luogo']);

            }}
    })
}
function speakersRecapTab() {
    let _kt_datatable_speakers_event_recap;
    _kt_datatable_speakers_event_recap = $("#kt_datatable_speakers_event_recap").DataTable({
        serverSide: true,
        ajax: {
            type: "POST",
            url: root + 'app/controllers/LessonController.php',
            data: function(d) {
                d.action = 'getRecapSpeakers';
                d.lesson = event;
            },
            dataSrc: function (data) {
                console.log(data)
                return data.data;
            }
        },
        info: false,
        paging: false,
        columns: [
            {data: null,
                render: (data) => '<img class="w-50px" alt="' + data.nome + '-logo" src="' + (root + 'app/assets/uploaded-files/speakers-images/' + data.pic) + '"/>'},
            {data: null,
                render: (data) => data.nome + ' ' + data.cognome
            },
        ],
        columnDefs: [
            {
                targets: '_all',
                orderable: false,
            },
        ]
    })

    _kt_datatable_speakers_event_recap.on('draw', function () {
        KTMenu.createInstances();
    });

}
function materialsRecapTab() {
    let _kt_datatable_materials_event_recap;
    _kt_datatable_materials_event_recap = $("#kt_datatable_materials_event_recap").DataTable({
        serverSide: true,
        ajax: {
            type: "POST",
            url: root + 'app/controllers/LessonController.php',
            data: function(d){
                d.action = 'getRecapMaterials';
                d.lesson = event
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

    _kt_datatable_materials_event_recap.on('draw', function () {
        KTMenu.createInstances();
    });
}
function sponsorsRecapTab() {
    let _kt_datatable_sponsors_event_recap;
    _kt_datatable_sponsors_event_recap = $("#kt_datatable_sponsors_event_recap").DataTable({
        serverSide: true,
        ajax: {
            type: "POST",
            url: root + 'app/controllers/LessonController.php',
            data: {'lesson': event, 'action': 'getRecapSponsors'},
            dataSrc: function (data) {
                console.log(data)
                return data.data;
            }
        },
        info: false,
        paging: false,
        columns: [
            {data: null,
                render: (data) => '<img class="w-50px" alt="' + data.nome + '-logo" src="' + (data.pic ? root + 'app/assets/uploaded-files/sponsor-images/' + data.pic : data.logo) + '"/>'},
            {data: 'nome'},
        ],
        columnDefs: [
            {
                targets: '_all',
                orderable: false,
            },
        ]
    })

    _kt_datatable_sponsors_event_recap.on('draw', function () {
        KTMenu.createInstances();
    });

}
function surveysRecapTab() {
    let _kt_datatable_surveys_recap;
    _kt_datatable_surveys_recap = $("#kt_datatable_surveys_event_recap").DataTable({
        serverSide: true,
        ajax: {
            type: "POST",
            url: root + 'app/controllers/LessonController.php',
            data: function(d){
                d.action = 'getRecapSurveys';
                d.lesson = event
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
function isFormFilled() {
    const topic = $('input[name="argomento"]:checked').val() ?? '0';
    const evento = $('#nome-evento').val();
    const dataEvento = $('#data-evento').val();
    const luogo = $('#luogo-evento').val() === '' ? '-' : $('#luogo-evento').val();
    const inizio = $('#orario-inizio-evento').val();
    const fine = $('#orario-fine-evento').val();
    const importo = $('#contributo-evento').val();
    let max = $('#max-evento').val();

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
    console.log(topic, evento, dataEvento, luogo, inizio, fine, importo, max, tesseramento, privato)

    if(topic && evento && dataEvento && luogo && inizio && fine && importo && max && tesseramento && privato) {
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
function deleteVideo() {
    const fileName = document.getElementById('video-fileName').textContent;
    $.ajax({
        type:'POST',
        url: root + 'app/controllers/LessonController.php',
        data: {'lesson': event, 'action': 'deleteVideo', 'fileName': fileName},
        // data: {'lesson': event, 'action': 'deleteVideo'},
        success: function(data) {
            const video = document.getElementById('video-lesson-' + event);
            video.remove();
            $('#choose-video').empty();
            deployFileInput();
            enableLink();
            enableZoom();
            enableVideo();
        }
    })
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

    videoRemoveButton.classList.add('d-none');

    input.addEventListener('change', function() {
        readURL(this);
    })
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
    e.submitter.disabled = true;
    if(e.submitter.value === "2") {
        $.ajax({
            type:'POST',
            url: root + 'app/controllers/LessonController.php',
            data: {'lesson': event, 'action': 'saveLesson', 'type': 'event'},
            success: function() {
                Swal.fire({
                    icon: 'success',
                    text: 'Evento salvato con successo!',
                    showConfirmButton: false
                })
                const url = root + 'corsi-eventi'

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
            data: {'lesson': event, 'action': 'publishLesson', 'type': 'event'},
            success: function() {
                Swal.fire({
                    icon: 'success',
                    text: 'Evento pubblicato con successo!',
                    showConfirmButton: false
                })
                const url = root + 'corsi-eventi'

                setTimeout(() => window.location.assign(url), 2000)
            }
        })
    }
})