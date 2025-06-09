const root = document.getElementById('root').getAttribute('value');
const surveyModal = document.getElementById('survey-modal');
let buttonId;
surveyModal.addEventListener('show.bs.modal', function(e) {

    const button = e.relatedTarget;
    buttonId = button;
    const idSurvey = button.getAttribute('data-bs-idSurvey');
    $.ajax({
        type: 'POST',
        url: root + 'app/controllers/LiveController.php',
        data: {'idSurvey': idSurvey, 'action': 'getSurveyLive'},
        success: function(data) {
            const parsed = JSON.parse(data);
            console.log(parsed);
            if(parsed.done) {
                $('#modal-survey-title').empty();
                $('#modal-survey-description').empty();
                $('#modal-survey-body').empty();
                const header = surveyModal.getElementsByClassName('modal-header');
                header[0].style.border = 'white';
                const footer = surveyModal.getElementsByClassName('modal-footer');
                footer[0].style.border = 'white';
                const body = document.getElementById('modal-survey-body');
                const paragraph = document.createElement('p');
                paragraph.textContent = 'Hai già compilato questo sondaggio!';
                paragraph.classList.add('fs-5', 'text-center')
                body.append(paragraph)
                const submitButton = document.getElementById('survey-submit-button');
                submitButton.classList.add('d-none');
            } else {
                const submitButton = document.getElementById('survey-submit-button');
                submitButton.classList.remove('d-none');
                const header = surveyModal.getElementsByClassName('modal-header');
                header[0].style.border = '#dee2e6';
                const footer = surveyModal.getElementsByClassName('modal-footer');
                footer[0].style.border = '#dee2e6';
                $('#modal-survey-title').text(parsed.data[0]['nomeSurvey']);
                $('#modal-survey-description').text(parsed.data[0]['descrizioneSurvey']);
                const body = document.getElementById('modal-survey-body');
                $('#modal-survey-body').empty();
                parsed.data.forEach(el => {
                    const div = document.createElement('div');
                    const bg = el['ordine'] % 2 === 0 ? 'bg-gray-200' : 'bg-light-bg'
                    div.classList.add('mb-8', bg, 'p-7', 'rounded');
                    const questionTitle = document.createElement('h4');
                    questionTitle.textContent = el['titoloDomanda'];
                    const questionDescription = document.createElement('p');
                    questionDescription.textContent = el['descrizioneDomanda'];
                    questionDescription.classList.add('py-2', 'fs-6', 'mb-2')
                    const separator = document.createElement('div');
                    separator.classList.add('separator', 'my-4', 'border-auser');
                    const answersBox = document.createElement('div');
                    answersBox.classList.add('px-4', 'w-100');
                    answersBox.append(questionDescription)
                    div.append(questionTitle);
                    div.append(separator);
                    div.append(answersBox);
                    body.append(div);

                    switch(el['id_tipologia']) {
                        case 1:
                            const textarea = document.createElement('textarea');
                            textarea.classList.add('form-control', 'form-control-solid');
                            textarea.setAttribute('id', el['idDomanda'] + '-valueAnswer')
                            answersBox.append(textarea);
                            break;
                        case 2:
                            const pointsBox = document.createElement('div');
                            pointsBox.classList.add('d-flex','gap-4', 'w-100');
                            answersBox.append(pointsBox);

                            for(let i = 1; i <= 5; i++) {
                                    const div = document.createElement('div');
                                    div.classList.add('d-flex', 'align-items-center')
                                    const labelTitle = document.createElement('label');
                                    labelTitle.setAttribute('for', el['idDomanda'] + '-valueAnswer' + i);
                                    labelTitle.classList.add('form-label', 'mb-0');
                                    labelTitle.textContent = i;
                                    const inputValue = document.createElement('input');
                                    inputValue.setAttribute('id', el['idDomanda'] + '-valueAnswer' + i);
                                    inputValue.setAttribute('value', i);
                                    inputValue.setAttribute('type', 'radio');
                                    inputValue.setAttribute('name', el['idDomanda'] + '-points');
                                    inputValue.classList.add('form-check-input', 'bg-gray-100', 'mx-2', 'w-25px', 'h-25px');

                                    div.append(labelTitle);
                                    div.append(inputValue);
                                    pointsBox.append(div)
                                }
                            break;
                        default:
                            break;
                    }
                })
            }
        }
    })
})

surveyModal.addEventListener('submit', function(e) {
    e.preventDefault();
    e.submitter.disabled = true;

    const answers = [];
    $('#survey-modal' + ' textarea').each(function() {
            const textarea = {};
            const ids = $(this).attr('id').split('-');
            textarea.idQuestion = ids[0];
            textarea.valueAnswer = $(this).val();
            answers.push(textarea)
    })

    $('#survey-modal' + ' input').each(function() {
            if($(this).prop('checked')) {
                const input = {};
                const ids = $(this).attr('id').split('-');
                input.idQuestion = ids[0];
                input.valueAnswer = $(this).val();
                answers.push(input)
            }
    })

    const idSurvey = buttonId.getAttribute('data-bs-idSurvey');

    $.ajax({
        type: 'POST',
        url: root + 'app/controllers/LiveController.php',
        data: {idSurvey: idSurvey, 'answers': answers, 'action': 'submitSurvey'},
        success: function() {
            Swal.fire({
                customClass: {
                    container: 'super-z'
                },
                icon: 'success',
                text: 'Il sondaggio è stato inviato con successo. Ricorda che non puoi più cambiare le risposte date.',
                showConfirmButton: true
            })
        }
    })
})