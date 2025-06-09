const beet = document.getElementById('root').value
const poll = document.getElementById('poll-qr') ?? document.getElementById('poll-modal');
const modal = document.getElementById('poll-modal') ? true : false;
let activePoll;

if(!modal) {
    const search = window.location.search;
    const params = new URLSearchParams(search);
    activePoll = params.get("id");
    populatePoll(activePoll, true);
}

if(modal) {
    poll.addEventListener('show.bs.modal', function (e) {
        const button = e.relatedTarget;
        const idPoll = button.getAttribute('data-bs-idPoll');
        activePoll = idPoll;
        populatePoll(idPoll);
    })
}

poll.addEventListener('submit', function(e) {
    e.preventDefault();
    e.submitter.disabled = true;
    let goOn = true;

    let form;
    if(modal) {
        form = '#poll-modal';
    } else {
        form = '#poll-qr';
    }

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
        $(form + ' textarea').each(function () {
            const textarea = {};
            const ids = $(this).attr('id').split('-');
            textarea.questionType = ids[1];
            textarea.idQuestion = ids[3];
            textarea.value = $(this).val();
            answers.push(textarea)
        })

        $(form + ' input').each(function () {
            if ($(this).prop('checked')) {
                const input = {};
                const ids = $(this).attr('id').split('-');
                input.questionType = ids[1];
                input.idQuestion = ids[3];
                input.idAnswer = ids[5];
                answers.push(input)
            }
        })
        $.ajax({
            type: 'POST',
            url: beet + 'app/controllers/LiveController.php',
            data: {'answers': answers, 'idPoll': activePoll, 'action': 'submitPoll'},
            success: function() {
                if(modal) {
                    $('#poll-modal').modal('hide');
                    const viewButton = document.getElementById('view-poll-' + activePoll);
                    if(viewButton) {
                        const fillButton = document.getElementById('fill-poll-' + activePoll);
                        fillButton.setAttribute('disabled', 'disabled');
                        fillButton.classList.remove('bg-light-bg');
                        fillButton.classList.add('bg-gray-200');
                        viewButton.removeAttribute('disabled');
                        viewButton.classList.remove('bg-gray-200');
                        viewButton.classList.add('bg-light-bg');
                    }
                } else {
                    document.getElementById('action-buttons').classList.add('d-none')
                }
                Swal.fire({
                    customClass: {
                        container: 'super-z'
                    },
                    icon: 'success',
                    text: 'Il quiz è stato inviato con successo. Ricorda che non puoi più cambiare le risposte date.',
                    showConfirmButton: true
                })
            }
        })

    } else {
        if(modal) {
            document.getElementById('error-poll').classList.remove('d-none')
        } else {
            document.getElementById('error-poll-qr').classList.remove('d-none')
        }
    }
})

function populatePoll(idPoll, qr = false) {
    let idTitle;
    let idBody;
    let idDescription;

    if(!qr) {
        idTitle = 'modal-poll-title';
        idBody = 'modal-poll-body';
        idDescription = 'modal-poll-description';
    } else {
        idTitle = 'poll-qr-title';
        idBody = 'poll-qr-body';
        idDescription = 'poll-qr-description';
    }

    $.ajax({
        type: 'POST',
        url: beet + 'app/controllers/LiveController.php',
        data: {'idPoll': idPoll, 'action': 'getPollLive'},
        success: function(data) {
            const parsed = JSON.parse(data);
            console.log(parsed);
            $('#' + idTitle).text(parsed.data[0]['nomePoll']);
            $('#' + idDescription).text(parsed.data[0]['descrizionePoll']);
            const body = document.getElementById(idBody);
            $('#' + idBody).empty();
            parsed.data.forEach(el => {
                const div = document.createElement('div');
                const bg = el['ordine'] % 2 === 0 ? 'bg-gray-200' : 'bg-light-bg'
                div.classList.add('mb-8', bg, 'p-7', 'rounded');
                const questionTitle = document.createElement('h4');
                const punti = el['punti'] ? ` (${el['punti']} punti)` : '';
                const obbligatoria = el['obbligatoria'] ? ' (obbligatoria)' : '';
                questionTitle.textContent = el['titoloDomanda'] + punti + obbligatoria;
                const questionDescription = document.createElement('p');
                questionDescription.textContent = el['descrizioneDomanda'];
                questionDescription.classList.add('py-2', 'fs-6', 'mb-2')
                const separator = document.createElement('div');
                separator.classList.add('separator', 'my-4', 'border-auser');
                const answersBox = document.createElement('div');
                answersBox.classList.add('px-4', 'w-100');
                answersBox.append(questionDescription);

                if(el['pic']){
                    const imgBox = document.createElement('div');
                    imgBox.classList.add('w-75', 'mx-auto', 'pb-4');
                    answersBox.append(imgBox);
                }

                div.append(questionTitle);
                div.append(separator);
                div.append(answersBox);
                body.append(div);

                switch(el['id_tipologia']) {
                    case 1:
                        const textarea = document.createElement('textarea');
                        textarea.classList.add('form-control', 'form-control-solid');
                        textarea.setAttribute('id', 'type-' + el['id_tipologia'] + '-question-' + el['idDomanda'] + '-answer');
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
                        if(el['pic']) {
                            const imgOpen = document.createElement('img');
                                imgOpen.setAttribute('src', beet + 'app/assets/uploaded-files/polls-images/' + el['pic']);
                            imgOpen.classList.add('w-100')
                            imgBox.append(imgOpen);
                        }
                        break;
                    case 2:
                        const radioGroup = document.createElement('div');
                        answersBox.append(radioGroup);
                        if(el['obbligatoria']) {
                            radioGroup.classList.add('radio-group');
                        }
                        el['answers'].forEach(answer => {
                            const box = document.createElement('div');
                            box.classList.add('d-flex', 'align-items-center', 'my-1');
                            const label = document.createElement('label');
                            label.classList.add('form-label', 'ms-4', 'mb-0');
                            label.setAttribute('for', 'type-' + el['id_tipologia'] + '-question-' + el['idDomanda'] + '-answer-' + answer['id']);
                            label.textContent = answer['risposta'];
                            const input = document.createElement('input');
                            input.classList.add('form-check-input', 'bg-gray-100', 'w-25px', 'h-25px');
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
                        if(el['pic']) {
                            const imgRadio = document.createElement('img');
                                imgRadio.setAttribute('src', beet + 'app/assets/uploaded-files/polls-images/' + el['pic']);
                            imgRadio.classList.add('w-100')
                            imgBox.append(imgRadio);
                        }
                        break;
                    case 3:
                        const checkboxGroup = document.createElement('div');
                        answersBox.append(checkboxGroup);
                        if(el['obbligatoria']) {
                            checkboxGroup.classList.add('checkbox-group');
                        }
                        el['answers'].forEach(answer => {
                            const box = document.createElement('div');
                            box.classList.add('d-flex', 'align-items-center', 'my-1');
                            const label = document.createElement('label');
                            label.classList.add('form-label', 'ms-4', 'mb-0');
                            label.setAttribute('for', 'type-' + el['id_tipologia'] + '-question-' + el['idDomanda'] + '-answer-' + answer['id']);
                            label.textContent = answer['risposta'];
                            const input = document.createElement('input');
                            input.classList.add('form-check-input', 'bg-gray-100', 'w-25px', 'h-25px');
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
                        if(el['pic']) {
                            const imgCheckbox = document.createElement('img');
                                imgCheckbox.setAttribute('src', beet + 'app/assets/uploaded-files/polls-images/' + el['pic']);
                            imgCheckbox.classList.add('w-100')
                            imgBox.append(imgCheckbox);
                        }
                        break;
                    case 4:
                        const link = document.createElement('a');
                        link.setAttribute('href', el['link']);
                        link.setAttribute('target', '_blank');
                        link.textContent = el['link'];
                        answersBox.append(link);
                        break;
                    case 5:
                        if(el['pic']) {
                            const imgText = document.createElement('img');
                                imgText.setAttribute('src', beet + 'app/assets/uploaded-files/polls-images/' + el['pic']);
                            imgText.classList.add('w-100')
                            imgBox.append(imgText);
                        }
                        break;
                    case 6:
                        const downloadBox = document.createElement('div');
                        downloadBox.classList.add('w-100', 'd-flex', 'flex-column', 'gap-4', 'align-items-center')

                        const imgFile = document.createElement('img');
                        imgFile.setAttribute('src', beet + 'app/assets/images/pdf.png');
                        imgFile.setAttribute('alt', 'pdf placeholder');
                        imgFile.classList.add('h-100px', 'rounded');
                        const download = document.createElement('a');
                        download.setAttribute('download', el['file']);
                        download.setAttribute('href', beet + 'app/assets/uploaded-files/lecture-notes-pdfs/' + el['file']);
                        download.textContent = 'Scarica ' + el['file'];
                        downloadBox.append(imgFile);
                        downloadBox.append(download);
                        div.append(downloadBox);
                        break;
                    default:
                        break;
                }
            })
            if(parsed.done === 1 && modal) {
                const actionButtons = document.querySelector('.modal-footer');
                actionButtons.setAttribute('id', 'action-buttons')
                const actionButton = document.getElementById('action-buttons');
                actionButton.classList.add('d-none');
            }
            if(parsed.done === 1 && !modal) {
                const actionButton = document.getElementById('action-buttons');
                actionButton.classList.add('d-none');
            }
        }
    })
}