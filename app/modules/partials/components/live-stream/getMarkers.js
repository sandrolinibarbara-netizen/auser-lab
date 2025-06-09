let player;
const markers = [];
const pollVideoModal = document.getElementById('poll-video-modal');
let currentMarker;
let currentMarkerIndex;
let currentPoll;

if(document.querySelector('video')) {

    $.ajax({
        type: 'POST',
        url: root + 'app/controllers/LiveController.php',
        data: {'idLesson': lesson, 'action': 'getMarkers'},
        success: function(data) {
            const parsed = JSON.parse(data);
            if (parsed.data.length === 0) {
                document.getElementById('waiting-div').classList.add('d-none')
                document.getElementById('embedded-video').classList.remove('d-none')
                return
            }
            $('#modal-lecture-note-video-title').text(parsed.data[0]['nome']);
            parsed.data.forEach(el => {
                markers.push({
                    time: Number(el['minutaggio']),
                    text: el['id_materiale'] + '-' + el['id_categoriamateriale']
                });
            })
            setMarkers();
            document.getElementById('waiting-div').classList.add('d-none')
            document.getElementById('embedded-video').classList.remove('d-none')
        }
    })
    function setMarkers() {
        const video = document.getElementById('embedded-video');
        if(video) {
            player = videojs('embedded-video');
            player.markers({
                markerStyle: {
                    'width':'10px',
                    'border-radius': '0%',
                    'background-color': 'red'
                },
                markers: markers,
                onMarkerReached: function (marker, index) {

                    player.pause();

                    // if(Math.round(player.currentTime()) > Math.round(marker.time)) {
                    //     player.play();
                    //     return;
                    // }

                    currentMarker = marker.time;
                    currentMarkerIndex = index;
                    console.log(marker.text.split('-'))
                    if(marker.text.split('-').length === 1) {
                        $('#poll-completed-modal').modal('show');
                    }
                    const materialId = Number(marker.text.split('-')[0]);
                    const materialType = Number(marker.text.split('-')[1]);
                    if(materialType === 6) {
                        $('#lecture-note-video-modal').modal('show');
                        $('#modal-lecture-note-video-body').empty();
                        $.ajax({
                            type: 'POST',
                            url: root + 'app/controllers/LiveController.php',
                            data: {'idDispensa': materialId, 'action': 'getLectureNoteLive'},
                            success: function(data) {
                                const parsed = JSON.parse(data);
                                console.log(parsed);
                                $('#modal-lecture-note-video-title').text(parsed.data[0]['nomeLectureNote']);
                                $('#modal-lecture-note-video-description').text(parsed.data[0]['descrizioneLectureNote']);
                                const body = document.getElementById('modal-lecture-note-video-body');
                                parsed.data.forEach(el => {
                                    const div = document.createElement('div');
                                    const bg = el['ordine'] % 2 === 0 ? 'bg-gray-200' : 'bg-light-bg'
                                    div.classList.add('mb-8', bg, 'p-7', 'rounded');
                                    const sectionTitle = document.createElement('h4');
                                    sectionTitle.textContent = el['titoloSezione'];
                                    const sectionDescription = document.createElement('p');
                                    sectionDescription.textContent = el['descrizioneSezione'];
                                    sectionDescription.classList.add('px-4', 'py-2');
                                    const separator = document.createElement('div');
                                    separator.classList.add('separator', 'my-4', 'border-auser');

                                    const downloadBox = document.createElement('div');
                                    downloadBox.classList.add('w-100', 'd-flex', 'flex-column', 'gap-4', 'align-items-center')

                                    const img = document.createElement('img');
                                    img.setAttribute('src', root + 'app/assets/images/pdf.png');
                                    img.setAttribute('alt', 'pdf placeholder');
                                    img.classList.add('h-100px', 'rounded');
                                    const download = document.createElement('a');
                                    download.setAttribute('download', parsed.data[0]['file']);
                                    download.setAttribute('href', root + 'app/assets/uploaded-files/lecture-notes-pdfs/' + parsed.data[0]['file']);
                                    download.textContent = 'Scarica ' + parsed.data[0]['file'];

                                    div.append(sectionTitle);
                                    div.append(separator);
                                    div.append(sectionDescription);
                                    downloadBox.append(img);
                                    downloadBox.append(download);
                                    div.append(downloadBox);
                                    body.append(div);
                                })
                            }
                        })
                    } else if(materialType === 7) {
                        currentPoll = materialId;
                        $('#poll-video-modal').modal('show');
                        $('#modal-poll-video-body').empty();
                        $.ajax({
                            type: 'POST',
                            url: root + 'app/controllers/LiveController.php',
                            data: {'idPoll': materialId, 'action': 'getPollLive'},
                            success: function(data) {
                                const parsed = JSON.parse(data);
                                console.log(parsed);
                                $('#modal-poll-video-title').text(parsed.data[0]['nomePoll']);
                                $('#modal-poll-video-description').text(parsed.data[0]['descrizionePoll']);
                                const body = document.getElementById('modal-poll-video-body');
                                parsed.data.forEach(el => {
                                    const div = document.createElement('div');
                                    const imgBox = document.createElement('div');
                                    imgBox.classList.add('w-75', 'mx-auto', 'pb-4');
                                    const bg = el['ordine'] % 2 === 0 ? 'bg-gray-200' : 'bg-light-bg'
                                    div.classList.add('mb-8', bg, 'p-7', 'rounded');
                                    const questionTitle = document.createElement('h4');
                                    const punti = el['punti'] ? ` (${el['punti']} punti)` : ''
                                    const obbligatoria = el['obbligatoria'] ? ' (obbligatoria)' : '';
                                    questionTitle.textContent = el['titoloDomanda'] + punti + obbligatoria;
                                    const questionDescription = document.createElement('p');
                                    questionDescription.textContent = el['descrizioneDomanda'];
                                    questionDescription.classList.add('py-2')
                                    const separator = document.createElement('div');
                                    separator.classList.add('separator', 'my-4', 'border-auser');
                                    const answersBox = document.createElement('div');
                                    answersBox.classList.add('px-4', 'w-100');
                                    answersBox.append(questionDescription)
                                    answersBox.append(imgBox)
                                    div.append(questionTitle);
                                    div.append(separator);
                                    div.append(answersBox);
                                    body.append(div);

                                    switch(el['id_tipologia']) {
                                        case 1:
                                            const textarea = document.createElement('textarea');
                                            textarea.classList.add('form-control', 'form-control-solid');
                                            textarea.setAttribute('id', 'type-' + el['id_tipologia'] + '-question-' + el['idDomanda'] + '-answer')
                                            if(el['obbligatoria']) {
                                                textarea.classList.add('area-group');
                                            }
                                            answersBox.append(textarea);
                                            if(parsed.done === 1){
                                                parsed.writtenAnswers.forEach(answer => {
                                                    if(answer['id_domanda'] === el['idDomanda']) {
                                                        textarea.value = answer['risposta'];
                                                        textarea.setAttribute('disabled', 'disabled')
                                                    }
                                                })
                                            }
                                            const imgOpen = document.createElement('img');
                                            if(el['pic']) {
                                                imgOpen.setAttribute('src', root + 'app/assets/uploaded-files/polls-images/' + el['pic']);
                                            }
                                            imgOpen.classList.add('w-100')
                                            imgBox.append(imgOpen);
                                            break;
                                        case 2:
                                            const radioGroup = document.createElement('div');
                                            answersBox.append(radioGroup);
                                            if(el['obbligatoria']) {
                                                radioGroup.classList.add('radio-group');
                                            }
                                            el['answers'].forEach(answer => {
                                                const box = document.createElement('div');
                                                const label = document.createElement('label');
                                                label.classList.add('form-label', 'ms-4');
                                                label.setAttribute('for', 'type-' + el['id_tipologia'] + '-question-' + el['idDomanda'] + '-answer-' + answer['id']);
                                                label.textContent = answer['risposta'];
                                                const input = document.createElement('input');
                                                input.classList.add('form-check-input', 'bg-gray-100');
                                                input.setAttribute('type', 'radio');
                                                input.setAttribute('id', 'type-' + el['id_tipologia'] + '-question-' + el['idDomanda'] + '-answer-' + answer['id']);
                                                input.setAttribute('name', 'type-' + el['id_tipologia'] + '-question-' + el['idDomanda'] + '-answer');
                                                // input.classList.add('bg-white');
                                                box.append(input);
                                                box.append(label);
                                                radioGroup.append(box);
                                                if(parsed.done === 1){
                                                    parsed.checkedAnswers.forEach(checkedAnswer => {
                                                        if(checkedAnswer['id_risposta'] === answer['id']) {
                                                            input.setAttribute('checked', 'checked')
                                                        }
                                                    })
                                                    input.setAttribute('disabled', 'disabled')
                                                }
                                            })

                                            const imgRadio = document.createElement('img');
                                            if(el['pic']) {
                                                imgRadio.setAttribute('src', root + 'app/assets/uploaded-files/polls-images/' + el['pic']);
                                            }
                                            imgRadio.classList.add('w-100')
                                            imgBox.append(imgRadio);
                                            break;
                                        case 3:
                                            const checkboxGroup = document.createElement('div');
                                            answersBox.append(checkboxGroup);
                                            if(el['obbligatoria']) {
                                                checkboxGroup.classList.add('checkbox-group');
                                            }
                                            el['answers'].forEach(answer => {
                                                const box = document.createElement('div');
                                                const label = document.createElement('label');
                                                label.classList.add('form-label', 'ms-4');
                                                label.setAttribute('for', 'type-' + el['id_tipologia'] + '-question-' + el['idDomanda'] + '-answer-' + answer['id']);
                                                label.textContent = answer['risposta'];
                                                const input = document.createElement('input');
                                                input.classList.add('form-check-input', 'bg-gray-100');
                                                input.setAttribute('type', 'checkbox');
                                                input.setAttribute('id', 'type-' + el['id_tipologia'] + '-question-' + el['idDomanda'] + '-answer-' + answer['id']);
                                                input.setAttribute('name', 'type-' + el['id_tipologia'] + '-question-' + el['idDomanda'] + '-answer-' + answer['id']);
                                                // input.classList.add('bg-white');
                                                box.append(input);
                                                box.append(label);
                                                checkboxGroup.append(box);
                                                if(parsed.done === 1){
                                                    parsed.checkedAnswers.forEach(checkedAnswer => {
                                                        if(checkedAnswer['id_risposta'] === answer['id']) {
                                                            input.setAttribute('checked', 'checked')
                                                        }
                                                    })
                                                    input.setAttribute('disabled', 'disabled')
                                                }
                                            })
                                            const imgCheckbox = document.createElement('img');
                                            if(el['pic']) {
                                                imgCheckbox.setAttribute('src', root + 'app/assets/uploaded-files/polls-images/' + el['pic']);
                                            }
                                            imgCheckbox.classList.add('w-100')
                                            imgBox.append(imgCheckbox);
                                            break;
                                        case 4:
                                            const link = document.createElement('a');
                                            link.setAttribute('href', el['link']);
                                            link.textContent = el['link'];
                                            answersBox.append(link);
                                            break;
                                        case 5:
                                            const imgText = document.createElement('img');
                                            if(el['pic']) {
                                                imgText.setAttribute('src', root + 'app/assets/uploaded-files/polls-images/' + el['pic']);
                                            }
                                            imgText.classList.add('w-100')
                                            imgBox.append(imgText);
                                            break;
                                        case 6:
                                            const downloadBox = document.createElement('div');
                                            downloadBox.classList.add('w-100', 'd-flex', 'flex-column', 'gap-4', 'align-items-center')

                                            const imgFile = document.createElement('img');
                                            imgFile.setAttribute('src', root + 'app/assets/images/pdf.png');
                                            imgFile.setAttribute('alt', 'pdf placeholder');
                                            imgFile.classList.add('h-100px', 'rounded');
                                            const download = document.createElement('a');
                                            download.setAttribute('download', el['file']);
                                            download.setAttribute('href', root + 'app/assets/uploaded-files/lecture-notes-pdfs/' + el['file']);
                                            download.textContent = 'Scarica ' + el['file'];
                                            downloadBox.append(img);
                                            downloadBox.append(download);
                                            div.append(downloadBox);
                                            break;
                                        default:
                                            break;
                                    }
                                })
                            }
                        })
                    }
                }
            });
        }
    }
    pollVideoModal.addEventListener('submit', function(e) {
        e.preventDefault();
        e.submitter.disabled = true;

        let goOn = true;

        $('.checkbox-group').each(function() {
            if($(this).find('input:checked').length === 0) {
                goOn = false;
                e.submitter.disabled = false;
            }
        })

        $('.radio-group').each(function() {
            if($(this).find('input:checked').length === 0) {
                goOn = false;
                e.submitter.disabled = false;
            }
        })

        $('.area-group').each(function() {
            if($(this).val() === "") {
                goOn = false;
                e.submitter.disabled = false;
            }
        })

        if(goOn) {
            const answers = [];
            $('#poll-video-modal' + ' textarea').each(function () {
                const textarea = {};
                const ids = $(this).attr('id').split('-');
                textarea.questionType = ids[1];
                textarea.idQuestion = ids[3];
                textarea.value = $(this).val();
                answers.push(textarea)
            })

            $('#poll-video-modal' + ' input').each(function () {
                if ($(this).prop('checked')) {
                    const input = {};
                    const ids = $(this).attr('id').split('-');
                    input.questionType = ids[1];
                    input.idQuestion = ids[3];
                    input.idAnswer = ids[5];
                    answers.push(input)
                }
            })
            console.log(answers)
            $.ajax({
                type: 'POST',
                url: root + 'app/controllers/LiveController.php',
                data: {'answers': answers, 'action': 'submitPoll', idPoll: currentPoll},
                success: function () {
                    $('#poll-video-modal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        text: 'Il quiz è stato inviato con successo. Ricorda che non puoi più cambiare le risposte date.',
                        showConfirmButton: true
                    })

                    player.markers.remove([currentMarkerIndex]);
                    player.markers.add([{time: currentMarker, text: 'This poll has already been completed!'}])
                }
            })
        } else {
            document.getElementById('error-poll-video').classList.remove('d-none')
        }
    })
}


