const questionsList = document.getElementById('new-poll-questions');
let sectionNum = Number(questionsList.lastElementChild.id.split('-')[1]);
pollInfo.addEventListener('submit', function(e) {
    e.preventDefault();
    const title = $('#poll-title').val();
    if(title === "" || !title) {
        return;
    }
    const description = $('#poll-description').val();
    const idPoll = $('#last-poll-added').val();
    const data = {'idPoll': idPoll, 'titolo': title, 'descrizione': description, 'action': 'updatePoll'};
    console.log(data)
    $.ajax({
        type: 'POST',
        url: root + 'app/controllers/PollController.php',
        data: data
    })
})
//IMPORTANTE!! Ciclo per appioppare ai bottoni degli elementi recuperati dal db i loro eventListener
//Questo e il diverso sectionNum sono in realtà le uniche 2 cose che differenziano questo script da new-quiz.js,
//quindi è verosimile accorpare i 2 script in uno solo
for(let i = 1; i < sectionNum; i++) {
    const questionType = Number($('#section-' + i + '-questionType').val());
    const currentSection = i;
    const form = document.getElementById('section-' + i);
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        console.log(questionType)
        const title = $('#section-' + i + '-titleQuestion').val();
        const text = $('#section-' + i + '-text').val();
        const lastPoll = $('#last-poll-added').val();
        const idQuestion = $('#section-' + i + '-order').val();
        const order = $('#section-' + i + '-title').text().split('.')[0];
        const url = root + 'app/controllers/SectionController.php'

        if(questionType === 2 ||questionType === 3) {

                const points = $('#section-' + i + '-points').val();
                const mandatory = $('#section-' + i + '-mandatory').prop('checked') ? 1 : 0;
                const answers = [];
                $('#section-' + i + '-answersBox' + ' li').each(function() {
                    const inputs = {};
                    $(this).find('input').each(function() {
                        if($(this).is(':radio') || $(this).is(':checkbox')) {
                            inputs.value = $(this).prop('checked') ? 1 : 0
                        } else if($(this).is('input[type="number"]')) {
                            inputs.id = $(this).val();
                        } else {
                            inputs.nome = $(this).val();
                        }
                    });
                    answers.push(inputs)
                })

            const fd = new FormData(this);
            if ($('#section-'+ i + '-picInput')[0].files[0] !== undefined) {
                fd.append('file', $('#section-'+ i + '-picInput')[0].files[0])
            }
            fd.append('numeroDomanda', order);
            fd.append('tipologia', Number(questionType));
            fd.append('titolo', title);
            fd.append('descrizione', text);
            fd.append('punti', Number(points));
            fd.append('obbligatoria', mandatory);
            fd.append('risposte', JSON.stringify(answers));
            fd.append('idPoll', lastPoll);
            fd.append('idSection', idQuestion);
            fd.append('action', 'updateSection');
            fd.append('type', 'poll')

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        const parsed = JSON.parse(data);
                        console.log(parsed);
                        if(parsed.error) {
                            Swal.fire({
                                icon: 'error',
                                text: parsed.error,
                                showConfirmButton: false
                            })
                        } else {
                            Swal.fire({
                                icon: 'success',
                                text: 'Sezione salvata con successo!',
                                showConfirmButton: true
                            })
                            parsed.forEach(el => {
                                $('#section-' + i + '-answersBox' + ' li').each(function() {
                                    $(this).find('input').each(function() {
                                           if(Number($(this).val()) === Number(el['oldId'])) {
                                               $(this).attr('value', Number(el['newId']));
                                           }
                                    })
                                })
                            })
                        }
                    }
                })

        }
        if(questionType === 1) {

                const minLines = $('#section-' + i + '-minLines').val();
                const maxLines = $('#section-' + i + '-maxLines').val();
                const points = $('#section-' + i + '-points').val();
                const mandatory = $('#section-' + i + '-mandatory').prop('checked') ? 1 : 0;

            const fd = new FormData(this);
            if ($('#section-'+ i + '-picInput')[0].files[0] !== undefined) {
                fd.append('file', $('#section-'+ i + '-picInput')[0].files[0])
            }
            fd.append('numeroDomanda', order);
            fd.append('tipologia', Number(questionType));
            fd.append('titolo', title);
            fd.append('descrizione', text);
            fd.append('punti', Number(points));
            fd.append('minCaratteri', minLines);
            fd.append('maxCaratteri', maxLines);
            fd.append('obbligatoria', mandatory);
            fd.append('idPoll', lastPoll);
            fd.append('idSection', idQuestion);

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        const parsed = JSON.parse(data);
                        console.log(parsed);
                        if(parsed.error) {
                            Swal.fire({
                                icon: 'error',
                                text: parsed.error,
                                showConfirmButton: false
                            })
                        } else {
                            Swal.fire({
                                icon: 'success',
                                text: 'Sezione salvata con successo!',
                                showConfirmButton: true
                            })
                        }
                    }
                })
        }
        if(questionType === 4) {

                const link = $('#section-' + i + '-link').val();

                const data = {
                    'numeroDomanda': order,
                    'tipologia': Number(questionType),
                    'titolo': title,
                    'descrizione': text,
                    'link': link,
                    'idPoll': lastPoll,
                    'idSection': idQuestion
                }
                console.log(data);

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    success: function(data) {
                        const parsed = JSON.parse(data);
                        console.log(parsed);
                        if(parsed.error) {
                            Swal.fire({
                                icon: 'error',
                                text: parsed.error,
                                showConfirmButton: false
                            })
                        } else {
                            Swal.fire({
                                icon: 'success',
                                text: 'Sezione salvata con successo!',
                                showConfirmButton: true
                            })
                        }
                    }
                })
        }
        if(questionType === 5 || questionType === 6) {

            const fd = new FormData(this);

            let uploadType;
            if(questionType === 5) {
                uploadType = '-picInput';
                if ($('#section-'+ i + uploadType)[0].files[0] !== undefined) {
                    fd.append('file', $('#section-'+ i + uploadType)[0].files[0])
                }
            } else if(questionType === 6) {
                uploadType = '-fileInput';
                if ($('#section-'+ i + uploadType)[0].files[0] === undefined || !$('#section-'+ i + uploadType)[0].files[0]) {
                    return;
                }
                fd.append('file', $('#section-'+ i + uploadType)[0].files[0]);
            }

            fd.append('numeroDomanda', order);
            fd.append('tipologia', $('#section-' + i + '-questionType').val());
            fd.append('titolo', title);
            fd.append('descrizione', text);
            fd.append('idPoll', lastPoll);
            fd.append('idSection', idQuestion);
            fd.append('action', 'updateSection');
            fd.append('type', 'poll');

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        const parsed = JSON.parse(data);
                        console.log(parsed);
                        if(parsed.error) {
                            Swal.fire({
                                icon: 'error',
                                text: parsed.error,
                                showConfirmButton: false
                            })
                        } else {
                            Swal.fire({
                                icon: 'success',
                                text: 'Sezione salvata con successo!',
                                showConfirmButton: true
                            })
                        }
                    }
                })
        }
    })
    const addButton = document.getElementById('section-' + i + '-addAnswerButton');
    if(questionType === 2 || questionType === 3) {
        addButton.addEventListener('click', function () {
            const answersBox = document.getElementById('section-' + i + '-answersBox');
            const nextNum = Number(answersBox.lastElementChild.getAttribute('id').split('-')[3]) + 1;
            const type = questionType === 2 ? 'radio' : 'checkbox';
            $.ajax({
                type: 'POST',
                data: {action: 'addSectionChoice', idSection: $('#' + 'section-' + i + '-order').val()},
                url: root + 'app/controllers/SectionController.php',
                success: function(data) {
                    const parsed = JSON.parse(data);
                    addAnswer(type, nextNum, i, false, {inputValue: '', answerIdValue: parsed.lastRow}, true);
                }
            })
        })
        const answersBox = document.getElementById('section-' + i + '-answersBox')
        const buttons = answersBox.querySelectorAll('button')
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                const idQuestion = $('#section-' + i + '-order').val();
                const currentValue = e.currentTarget.value;
                console.log(currentValue, idQuestion)
                $.ajax({
                    type: 'POST',
                    data: {'idAnswer': currentValue, 'idSection': idQuestion, 'action': 'deleteAnswer'},
                    url: root + 'app/controllers/SectionController.php',
                    success: function(data) {
                        const parsed = JSON.parse(data);
                        console.log(parsed)
                        const answersBox = document.getElementById('section-' + i + '-answersBox');
                        while(answersBox.firstElementChild) {
                            answersBox.firstElementChild.remove();
                        }
                        parsed.data.forEach((el, index) => {
                            const type = el['type'] == 2 ? 'radio' : 'checkbox';
                            const i = index + 1;
                            const checked = el['corretta'] == 1;
                            const answerContent = !el['titoloRisposta'] || el['titoloRisposta'] === null ? '' : el['titoloRisposta'];
                            const options = {inputValue: answerContent, answerIdValue: el['idRisposta']}
                            addAnswer(type, i, currentSection, checked, options, true)
                        })

                    }
                })
            })
        })
    }
    const deleteSection = document.getElementById('section-' + i + '-removeButton');
    deleteSection.addEventListener('click', function(e) {
        e.preventDefault();
        const idQuestion = $('#section-' + i + '-order').val();

        const data = {
            'idSection': idQuestion,
            'action': 'deleteSection',
            'type': 'poll'
        }

        $.ajax({
            type: 'POST',
            url: root + 'app/controllers/SectionController.php',
            data: data,
            success: function() {
                form.remove();
                $("#new-poll-questions form").each(function(i, elm) {
                    $elm = $(elm);
                    x = $elm.find('h3');
                    x.text(($elm.index("#new-poll-questions form")+1) + '. ' + x.text().split('.')[1].trim())
                });
                updateOrder('save');
            }
        })
    })

    const input = questionType === 6 ? document.getElementById('section-' + i + '-fileInput') : document.getElementById('section-' + i + '-picInput')
    if(input){
        input.addEventListener('change', function () {
            questionType === 6 ? readURL(this, i, 'file') : readURL(this, i, 'image')
        })
    }
}
function addSectionFile() {
    const currentSection = sectionNum;
    const [input, baseId] = addFile();

    input.addEventListener('change', function() {
        readURL(this, currentSection, 'file');
    })
}
