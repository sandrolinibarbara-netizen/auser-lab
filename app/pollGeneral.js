const root = document.getElementById('root').getAttribute('value');
const questionMultiple = document.getElementById('question-multiple');
const questionSingle = document.getElementById('question-single');
const questionOpen = document.getElementById('question-open');
const slideText = document.getElementById('slide-text');
const slideLink = document.getElementById('slide-link');
const slidePDF = document.getElementById('slide-pdf');
const pollInfo = document.getElementById('new-poll-info');
const save = document.getElementById('poll-saveButton');
const publish = document.getElementById('poll-publishButton');

// per ognuno dei tipi di domanda, si fa una chiamata ajax per creare la riga nel db, che poi si updata quando si salva la sezione
questionMultiple.addEventListener('click', function() {
    $.ajax({
        type: 'POST',
        data: {action: 'createSectionPoll', idPoll: $('#last-poll-added').val(), order: (document.querySelectorAll('form').length - 1), idType: 3},
        url: root + 'app/controllers/PollController.php',
        success: function(data) {
            const parsed = JSON.parse(data);
            document.getElementById('section-' + sectionNum + '-order').setAttribute('value', parsed.lastRow)
            getSectionSelection( 'Domanda a scelta multipla');
            addSectionBodyText(3);
            addSectionChoices('checkbox', parsed);
            addSectionNewChoice('checkbox');
            addSectionImage();
            addSectionFooter('checkbox', parsed);
        }
    })
});
questionSingle.addEventListener('click', function() {
    $.ajax({
        type: 'POST',
        data: {action: 'createSectionPoll', idPoll: $('#last-poll-added').val(), order: (document.querySelectorAll('form').length - 1), idType: 2},
        url: root + 'app/controllers/PollController.php',
        success: function(data) {
            const parsed = JSON.parse(data);
            console.log(data)
            document.getElementById('section-' + sectionNum + '-order').setAttribute('value', parsed.lastRow)
            getSectionSelection('Domanda a scelta singola');
            addSectionBodyText(2);
            addSectionChoices('radio', parsed);
            addSectionNewChoice('radio');
            addSectionImage();
            addSectionFooter('radio', parsed);
        }
    })
})
questionOpen.addEventListener('click', function() {
    $.ajax({
        type: 'POST',
        data: {action: 'createSectionPoll', idPoll: $('#last-poll-added').val(), order: (document.querySelectorAll('form').length - 1), idType: 1},
        url: root + 'app/controllers/PollController.php',
        success: function(data) {
            const parsed = JSON.parse(data);
            document.getElementById('section-' + sectionNum + '-order').setAttribute('value', parsed.lastRow)
            getSectionSelection('Domanda a risposta aperta');
            addSectionBodyText(1);
            addSectionImage();
            addSectionFooter('open', parsed);
        }
    })
})
slideText.addEventListener('click', function() {
    $.ajax({
        type: 'POST',
        data: {action: 'createSectionPoll', idPoll: $('#last-poll-added').val(), order: (document.querySelectorAll('form').length - 1), idType: 5},
        url: root + 'app/controllers/PollController.php',
        success: function(data) {
            const parsed = JSON.parse(data);
            document.getElementById('section-' + sectionNum + '-order').setAttribute('value', parsed.lastRow)
            getSectionSelection('Testo');
            addSectionBodyText(5);
            addSectionImage();
            addSectionFooter('text', parsed);
        }
    })
})
slideLink.addEventListener('click', function() {
    $.ajax({
        type: 'POST',
        data: {action: 'createSectionPoll', idPoll: $('#last-poll-added').val(), order: (document.querySelectorAll('form').length - 1), idType: 4},
        url: root + 'app/controllers/PollController.php',
        success: function(data) {
            const parsed = JSON.parse(data);
            document.getElementById('section-' + sectionNum + '-order').setAttribute('value', parsed.lastRow)
            getSectionSelection('Link');
            addSectionBodyText(4);
            addSectionLink();
            addSectionFooter('link', parsed);
        }
    })
})
slidePDF.addEventListener('click', function() {
    $.ajax({
        type: 'POST',
        data: {action: 'createSectionPoll', idPoll: $('#last-poll-added').val(), order: (document.querySelectorAll('form').length - 1), idType: 6},
        url: root + 'app/controllers/PollController.php',
        success: function(data) {
            const parsed = JSON.parse(data);
            document.getElementById('section-' + sectionNum + '-order').setAttribute('value', parsed.lastRow)
            getSectionSelection('PDF');
            addSectionBodyText(6);
            addSectionFile();
            addSectionFooter('file', parsed);
        }
    })
})

if(save) {
    save.addEventListener('click', function () {
        updateOrder('save', true);
    })
}
publish.addEventListener('click', function() {
    updateOrder('publish', true);
})
function getSectionSelection(sectionType) {
    const baseId = 'section-' + sectionNum;
    const drawerButton = document.getElementById(baseId + '-showButton');
    drawerButton.remove();
    const sectionHeader = document.getElementById(baseId + '-header');
    const headerButton  = document.createElement('button');
    headerButton.classList.add('accordion-button');
    headerButton.setAttribute('type', 'button');
    headerButton.setAttribute('data-bs-toggle', 'collapse');
    headerButton.setAttribute('data-bs-target', '#' + baseId + '-body');
    headerButton.setAttribute('aria-controls', '#' + baseId + '-body');
    headerButton.setAttribute('aria-expanded', 'true');

    const sectionTitle = document.getElementById(baseId + '-title');
    sectionTitle.textContent = (document.querySelectorAll('form').length - 1) + '. ' + sectionType;
    sectionTitle.classList.remove('px-7')
    headerButton.append(sectionTitle);
    sectionHeader.append(headerButton);
    KTDrawer.hideAll();
}
function addSectionBodyText(questionType) {
    const baseId = 'section-' + sectionNum;
    const section = document.getElementById(baseId + '-item');
    const type = document.createElement('input');
    type.setAttribute('value', questionType);
    type.setAttribute('id', baseId + '-questionType');
    type.setAttribute('hidden', 'true');
    type.setAttribute('readonly', 'true');
    section.append(type)

    const body = document.createElement('div');
    body.setAttribute('id', baseId + '-body');
    body.setAttribute('aria-labelledby', baseId + '-header')
    body.classList.add('accordion-collapse', 'collapse', 'show');
    body.setAttribute('data-bs-parent', '#' + baseId)
    section.append(body);

    const innerBody = document.createElement('div');
    innerBody.classList.add('accordion-body', 'pb-0');
    innerBody.setAttribute('id', baseId + '-innerBody')
    body.append(innerBody)

    const labelTitle = document.createElement('label');
    labelTitle.setAttribute('for', baseId + '-titleQuestion');
    labelTitle.classList.add('form-label');
    labelTitle.textContent = 'Inserisci il titolo della domanda';
    const inputTitle = document.createElement('input');
    inputTitle.setAttribute('id', baseId + '-titleQuestion');
    inputTitle.classList.add('form-control', 'form-control-solid');
    innerBody.append(labelTitle);
    innerBody.append(inputTitle);

    const labelText = document.createElement('label');
    labelText.setAttribute('for', baseId + '-text');
    labelText.classList.add('form-label', 'mt-4');
    labelText.textContent = 'Inserisci il testo della domanda';
    const textarea = document.createElement('textarea');
    textarea.setAttribute('id', baseId + '-text');
    textarea.classList.add('form-control', 'form-control-solid');
    innerBody.append(labelText);
    innerBody.append(textarea);

    if(questionType === 1 || questionType === 2 || questionType === 3) {
        const box = document.createElement('div');
        questionType === 1 ? box.classList.add('row', 'mt-4') : box.classList.add('row', 'mt-4', 'gap-8');
        const pointsBox = document.createElement('div');
        questionType === 1 ? pointsBox.classList.add('col-2') : pointsBox.classList.add('col-5');
        const mandatoryBox = document.createElement('div');
        questionType === 1 ? mandatoryBox.classList.add('col-2', 'form-check', 'form-check-custom', 'form-check-solid', 'gap-2', 'd-flex', 'flex-column', 'justify-content-start', 'align-items-start') : mandatoryBox.classList.add('col-5', 'form-check', 'form-check-custom', 'form-check-solid', 'gap-2', 'pt-2', 'd-flex', 'flex-column', 'justify-content-start', 'align-items-start')

        if(questionType === 1) {
            const minLinesBox = document.createElement('div');
            minLinesBox.classList.add('col-4');
            const maxLinesBox = document.createElement('div');
            maxLinesBox.classList.add('col-4');
            box.append(minLinesBox);
            box.append(maxLinesBox);

            const labelMinLines = document.createElement('label');
            labelMinLines.setAttribute('for', baseId + '-minLines');
            labelMinLines.classList.add('form-label');
            labelMinLines.textContent = 'Min caratteri';
            const inputMinLines = document.createElement('input');
            inputMinLines.setAttribute('id', baseId + '-minLines');
            inputMinLines.setAttribute('name', baseId + '-minLines');
            inputMinLines.classList.add('form-control', 'form-control-solid');
            minLinesBox.append(labelMinLines);
            minLinesBox.append(inputMinLines);

            const labelMaxLines = document.createElement('label');
            labelMaxLines.setAttribute('for', baseId + '-maxLines');
            labelMaxLines.classList.add('form-label');
            labelMaxLines.textContent = 'Max caratteri';
            const inputMaxLines = document.createElement('input');
            inputMaxLines.setAttribute('id', baseId + '-maxLines');
            inputMaxLines.setAttribute('name', baseId + '-maxLines');
            inputMaxLines.classList.add('form-control', 'form-control-solid');
            maxLinesBox.append(labelMaxLines);
            maxLinesBox.append(inputMaxLines);
        }

        box.append(pointsBox);
        box.append(mandatoryBox);
        innerBody.append(box);

        const labelPoints = document.createElement('label');
        labelPoints.setAttribute('for', baseId + '-points');
        labelPoints.classList.add('form-label');
        labelPoints.textContent = 'Punti';
        const inputPoints = document.createElement('input');
        inputPoints.setAttribute('id', baseId + '-points');
        inputPoints.setAttribute('name', baseId + '-points');
        inputPoints.classList.add('form-control', 'form-control-solid');
        pointsBox.append(labelPoints);
        pointsBox.append(inputPoints);

        const mandatoryLabel = document.createElement('label');
        mandatoryLabel.setAttribute('for', baseId + '-mandatory');
        mandatoryLabel.classList.add('form-label', 'mb-0');
        mandatoryLabel.textContent = 'Obbligatoria';
        const mandatoryRadio = document.createElement('input');
        mandatoryRadio.classList.add('form-check-input');
        mandatoryRadio.setAttribute('id', baseId + '-mandatory');
        mandatoryRadio.setAttribute('name', baseId + '-mandatory');
        mandatoryRadio.setAttribute('type', 'checkbox');
        mandatoryBox.append(mandatoryLabel);
        mandatoryBox.append(mandatoryRadio);
    }

    if(questionType !== 4){
        const flexBox = document.createElement('div');
        flexBox.classList.add('d-flex', 'gap-8', 'mt-8');
        innerBody.append(flexBox);
        const imageBox = document.createElement('div');
        imageBox.setAttribute('id', baseId + '-imageBox');

        if(questionType === 2 || questionType === 3){
            const answersBox = document.createElement('div');
            answersBox.classList.add('d-flex', 'flex-column', 'w-50');
            answersBox.setAttribute('id', baseId + '-answersBox');
            flexBox.append(answersBox);
            imageBox.classList.add('d-flex', 'flex-column', 'w-50');
        } else {
            imageBox.classList.add('d-flex', 'flex-column', 'w-100');
        }

        flexBox.append(imageBox)
    }
}
function addSectionChoices(type, idsArray) {
    const startingAnswers = type === 'radio' ? 2 : 3;

    for(let i = 0; i < startingAnswers; i++) {
        const nextNum = i + 1;
        addAnswer(type, nextNum, sectionNum, false, {inputValue: '', answerIdValue: idsArray.answers[i]}, true);
    }
}
function addSectionNewChoice(type) {
    const baseId = 'section-' + sectionNum;
    const currentSection = sectionNum;
    const body = document.getElementById(baseId + '-innerBody');
    const addAnswerButton = document.createElement('button');
    addAnswerButton.setAttribute('id', baseId + '-addButton');
    addAnswerButton.classList.add('btn', 'btn-secondary');
    addAnswerButton.setAttribute('type', 'button')
    addAnswerButton.textContent = 'Aggiungi una risposta';
    body.append(addAnswerButton);
    addAnswerButton.addEventListener('click', function() {
        const answersBox = document.getElementById(baseId + '-answersBox');
        const nextNum = Number(answersBox.lastElementChild.getAttribute('id').split('-')[3]) + 1;
        $.ajax({
            type: 'POST',
            data: {action: 'addSectionChoice', idSection: $('#' + baseId + '-order').val()},
            url: root + 'app/controllers/SectionController.php',
            success: function(data) {
                const parsed = JSON.parse(data);
                //qua non può essere sectionNum, perché deve essere la sezione a cui è legato
                addAnswer(type, nextNum, currentSection, false, {inputValue: '', answerIdValue: parsed.lastRow}, true);
            }
        })
    })
}
function addSectionImage() {
    const baseId = 'section-' + sectionNum;
    const imageBox = document.getElementById(baseId + '-imageBox');

    const div = document.createElement('div');
    div.classList.add('w-100', 'text-start')
    const chooseImage = document.createElement('p');
    chooseImage.classList.add('form-label');
    chooseImage.textContent = 'Se necessaria, scegli un\'immagine';

    const imageInput = document.createElement('div');
    imageInput.classList.add('image-input', 'image-input-empty', 'bg-light-bg', 'w-100', 'h-150px', 'd-flex', 'justify-content-center', 'align-items-center');
    imageInput.setAttribute('data-kt-image-input', 'true');

    const imageWrapper = document.createElement('img');
    imageWrapper.classList.add('h-75');
    imageWrapper.setAttribute('id', baseId + '-pic');

    const label = document.createElement('label');
    label.classList.add('btn', 'btn-icon', 'btn-circle', 'btn-color-muted', 'btn-active-color-primary', 'w-25px', 'h-25px', 'bg-body', 'shadow');
    label.setAttribute('data-kt-image-input-action', 'change');
    label.setAttribute('data-bs-toggle', 'tooltip');
    label.setAttribute('data-bs-dismiss', 'click');
    label.setAttribute('title', "Scegli un'immagine");

    const icon = document.createElement('i');
    icon.classList.add('ki-duotone', 'ki-pencil', 'fs-6');
    const path1 = document.createElement('span');
    path1.classList.add('path1')
    const path2 = document.createElement('span');
    path2.classList.add('path2')
    icon.append(path1);
    icon.append(path2);

    const input = document.createElement('input');
    input.setAttribute('id', baseId + '-picInput');
    input.setAttribute('name', baseId + '-pic');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', '.png, .jpg, .jpeg');

    label.append(icon);
    label.append(input);
    imageInput.append(label);
    imageInput.append(imageWrapper);
    div.append(chooseImage);
    imageBox.append(div);
    imageBox.append(imageInput);

    input.addEventListener('change', function() {
        readURL(this, Number(baseId.split('-')[1]), 'image');
    })
}
function addFile() {
    const baseId = 'section-' + sectionNum;
    const imageBox = document.getElementById(baseId + '-imageBox');

    const div = document.createElement('div');
    div.classList.add('w-100', 'text-start')
    const chooseFile = document.createElement('p');
    chooseFile.classList.add('form-label');
    chooseFile.textContent = 'Aggiungi un file';
    const fileInput = document.createElement('div');
    fileInput.classList.add('image-input', 'image-input-empty', 'bg-light-bg', 'w-100', 'h-150px', 'd-flex', 'justify-content-center', 'align-items-center', 'gap-4');
    fileInput.setAttribute('data-kt-image-input', 'true');
    const fileWrapper = document.createElement('img');
    fileWrapper.setAttribute('id', baseId + '-file');
    fileWrapper.classList.add('h-75');
    const fileName = document.createElement('p');
    fileName.setAttribute('id', baseId + '-fileName');
    fileName.classList.add('mb-0');
    const label = document.createElement('label');
    label.classList.add('btn', 'btn-icon', 'btn-circle', 'btn-color-muted', 'btn-active-color-primary', 'w-25px', 'h-25px', 'bg-body', 'shadow');
    label.setAttribute('data-kt-image-input-action', 'change');
    label.setAttribute('data-bs-toggle', 'tooltip');
    label.setAttribute('data-bs-dismiss', 'click');
    label.setAttribute('title', "Scegli un file");
    const icon = document.createElement('i');
    icon.classList.add('ki-duotone', 'ki-pencil', 'fs-6');
    const path1 = document.createElement('span');
    path1.classList.add('path1')
    const path2 = document.createElement('span');
    path2.classList.add('path2')
    icon.append(path1);
    icon.append(path2);
    const input = document.createElement('input');
    input.setAttribute('id', baseId + '-fileInput');
    input.setAttribute('name', baseId + '-file');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', '.pdf');

    label.append(icon);
    label.append(input);
    fileInput.append(label);
    fileInput.append(fileWrapper);
    fileInput.append(fileName);
    div.append(chooseFile);
    imageBox.append(div);
    imageBox.append(fileInput);

    return [input, baseId];
}
function addSectionLink() {
    const baseId = 'section-' + sectionNum;
    const innerBody = document.getElementById(baseId + '-innerBody');
    const labelLink = document.createElement('label');
    labelLink.setAttribute('for', baseId + '-link');
    labelLink.classList.add('form-label', 'mt-4');
    labelLink.textContent = 'Inserisci il link';
    const inputLink = document.createElement('input');
    inputLink.setAttribute('id', baseId + '-link');
    inputLink.classList.add('form-control', 'form-control-solid');
    innerBody.append(labelLink);
    innerBody.append(inputLink);
}
function addAnswer(type, number, sectionNum, checked = false, options = { inputValue: '', answerIdValue: ''}, saved = false) {
    const baseId = 'section-' + sectionNum;
    const answersBox = document.getElementById(baseId + '-answersBox');
    const answersInfo = document.createElement('li');
    answersInfo.setAttribute('id', baseId + '-answerInfo-' + number);
    answersInfo.classList.add('d-flex', 'align-items-center', 'gap-6', 'w-100', 'list-group-item');
    const answers = document.createElement('div');
    answers.classList.add('pb-4');
    const input = document.createElement('input');
    input.setAttribute('type', 'text');
    input.setAttribute('id', baseId + '-answerText-' + number);
    input.classList.add('form-control', 'form-control-solid');
    const label = document.createElement('label');
    label.classList.add('form-label');
    label.textContent = 'Risposta ' + number;
    label.setAttribute('for', baseId + '-answerText-' + number);
    answers.append(label);
    answers.append(input);

    const correctness = document.createElement('div');
    correctness.classList.add('form-check', 'form-check-custom', 'form-check-solid', 'gap-2', 'pt-2');
    const labelCheck = document.createElement('label');
    labelCheck.textContent = 'Corretta';
    const check = document.createElement('input');
    check.classList.add('form-check-input');
    check.setAttribute('value', '1');
    check.setAttribute('id', baseId + '-answerCorrect-' + number);
    label.setAttribute('for', baseId + '-answerCorrect-' + number);

    if (type === 'radio') {
        check.setAttribute('type', 'radio');
        check.setAttribute('name', baseId + '-answerCorrect');
    } else {
        check.setAttribute('type', 'checkbox');
        check.setAttribute('name', baseId + '-answerCorrect-' + number);
    }

    if(checked) {
        check.setAttribute('checked', 'checked')
    }

    const answerId = document.createElement('input');
    answerId.setAttribute('id', baseId + '-answerId-' + number);
    answerId.setAttribute('type', 'number');
    answerId.setAttribute('hidden', 'hidden');
    answerId.setAttribute('readonly', 'true');

    correctness.append(labelCheck);
    correctness.append(check);

    answersInfo.append(answerId);
    answersInfo.append(answers);
    answersInfo.append(correctness);

    answersBox.append(answersInfo);

    if(saved) {

        input.setAttribute('value', options.inputValue)
        answerId.setAttribute('value', options.answerIdValue);

        const button = document.createElement('button');
        button.classList.add('btn', 'pb-2', 'pt-4');
        button.setAttribute('id', baseId + '-answerId-' + number + '-removeAnswer');
        button.setAttribute('type', 'button');

        button.setAttribute('value', options.answerIdValue)
        const icon = document.createElement('i');
        icon.classList.add('ki-outline', 'ki-cross', 'fs-1', 'p-0');
        button.append(icon);

        answersInfo.append(button);

        button.addEventListener('click', function(e) {
            const idQuestion = $('#' + baseId + '-order').val();
            const currentValue = e.currentTarget.value;
            console.log(currentValue, idQuestion)
            $.ajax({
                type: 'POST',
                data: {'idAnswer': currentValue, 'idSection': idQuestion, 'action': 'deleteAnswer'},
                url: root + 'app/controllers/SectionController.php',
                success: function(data) {
                    const parsed = JSON.parse(data);
                    console.log(parsed)
                    while(answersBox.firstElementChild) {
                        answersBox.firstElementChild.remove();
                    }
                    parsed.data.forEach((el, index) => {
                        const type = el['type'] === 2 ? 'radio' : 'checkbox';
                        const i = index + 1;
                        const checked = el['corretta'] === 1;
                        const answerContent = !el['titoloRisposta'] || el['titoloRisposta'] === null ? '' : el['titoloRisposta'];
                        const options = {inputValue: answerContent, answerIdValue: el['idRisposta']}
                        addAnswer(type, i, sectionNum, checked, options, true)
                    })

                }
            })
        })
    }

}
function addSectionFooter(type, id) {
    const baseId = 'section-' + sectionNum;
    const section = document.getElementById(baseId)
    const innerBody = document.getElementById(baseId + '-innerBody');
    const separator = document.createElement('div');
    separator.classList.add('separator', 'my-7');
    const footer = document.createElement('div');
    footer.classList.add('card-footer', 'text-end');
    const saveButton = document.createElement('button');
    saveButton.setAttribute('id', baseId + '-saveButton');
    saveButton.setAttribute('type', 'submit');
    saveButton.classList.add('btn', 'btn-primary');
    saveButton.textContent = 'Salva sezione';
    footer.append(saveButton);
    innerBody.append(separator);
    innerBody.append(footer);

    setDeleteButton(section, id, baseId, footer);

    if(type === 'radio' || type === 'checkbox') {
        section.addEventListener('submit', function(e) {
            e.preventDefault();

            const questionType = $('#' + baseId + '-questionType').val();
            const title = $('#' + baseId + '-titleQuestion').val();
            const text = $('#' + baseId + '-text').val();
            const lastPoll = $('#last-poll-added').val();
            const order = $('#' + baseId + '-title').text().split('.')[0];
            const points = $('#' + baseId + '-points').val();
            const mandatory = $('#' + baseId + '-mandatory').prop('checked') ? 1 : 0;
            const answers = [];
            const idQuestion = $('#' + baseId + '-order').val();
            const url = root + 'app/controllers/SectionController.php'

            $('#' + baseId + '-answersBox' + ' li').each(function() {
                const inputs = {};
                $(this).find('input').each(function() {
                    if($(this).attr('hidden')) {
                        inputs.id = $(this).attr('value');
                    }
                    if($(this).is(`:${type}`)) {
                        inputs.value = $(this).prop('checked') ? 1 : 0
                    } else {
                        inputs.nome = $(this).val()
                    }
                });
                answers.push(inputs)
            })

            const fd = new FormData(this);
            if ($('#'+ baseId + '-picInput')[0].files[0] !== undefined) {
                fd.append('file', $('#'+ baseId + '-picInput')[0].files[0])
            }
            fd.append('numeroDomanda', order);
            fd.append('tipologia', Number(questionType));
            fd.append('titolo', title);
            fd.append('descrizione', text);
            fd.append('punti', Number(points));
            fd.append('obbligatoria', mandatory);
            fd.append('risposte', JSON.stringify(answers));
            fd.append('idPoll', lastPoll);
            fd.append('idSection', idQuestion)
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
                        parsed.forEach((el, index) => {
                            $('#section-' + (index + 1) + '-answersBox' + ' li').each(function() {
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


        })
    }
    if(type === 'open') {
        section.addEventListener('submit', function(e) {
            e.preventDefault();
            const questionType = $('#' + baseId + '-questionType').val();
            const title = $('#' + baseId + '-titleQuestion').val();
            const text = $('#' + baseId + '-text').val();
            const lastPoll = $('#last-poll-added').val();
            const order = $('#' + baseId + '-title').text().split('.')[0];
            const minLines = $('#' + baseId + '-minLines').val();
            const maxLines = $('#' + baseId + '-maxLines').val();
            const points = $('#' + baseId + '-points').val();
            const mandatory = $('#' + baseId + '-mandatory').prop('checked') ? 1 : 0;
            const idQuestion = $('#' + baseId + '-order').val();
            const url = root + 'app/controllers/SectionController.php'

            const fd = new FormData(this);
            if ($('#'+ baseId + '-picInput')[0].files[0] !== undefined) {
                fd.append('file', $('#'+ baseId + '-picInput')[0].files[0])
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
            fd.append('idSection', idQuestion)
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
                        })}
                }
            })


        })
    }
    if(type === 'link') {
        section.addEventListener('submit', function(e) {
            e.preventDefault();
            const questionType = $('#' + baseId + '-questionType').val();
            const title = $('#' + baseId + '-titleQuestion').val();
            const text = $('#' + baseId + '-text').val();
            const lastPoll = $('#last-poll-added').val();
            const order = $('#' + baseId + '-title').text().split('.')[0];
            const link = $('#' + baseId + '-link').val();
            const idQuestion = $('#' + baseId + '-order').val();
            const url = root + 'app/controllers/SectionController.php'

            const data = {
                'numeroDomanda': order,
                'tipologia': Number(questionType),
                'titolo': title,
                'descrizione': text,
                'link': link,
                'idPoll': lastPoll,
                'idSection': idQuestion,
                'action': 'updateSection',
                'type': 'poll',
            }
            console.log(data);

            $.ajax({
                type: 'POST',
                'url': url,
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
        })
    }
    if(type === 'text' || type === 'file') {
        section.addEventListener('submit', function(e) {
            e.preventDefault();
            const questionType = $('#' + baseId + '-questionType').val();
            const title = $('#' + baseId + '-titleQuestion').val();
            const text = $('#' + baseId + '-text').val();
            const lastPoll = $('#last-poll-added').val();
            const order = $('#' + baseId + '-title').text().split('.')[0];
            const idQuestion = $('#' + baseId + '-order').val();
            const url = root + 'app/controllers/SectionController.php'

            const fd = new FormData(this);

            let uploadType;
            if(type === 'text') {
                uploadType = '-picInput';
                if ($('#'+ baseId + uploadType)[0].files[0] !== undefined) {
                    fd.append('file', $('#'+ baseId + uploadType)[0].files[0])
                }
            } else if(type === 'file') {
                uploadType = '-fileInput';
                if ($('#'+ baseId + uploadType)[0].files[0] === undefined || !$('#'+ baseId + uploadType)[0].files[0]) {
                    return;
                }
                fd.append('file', $('#'+ baseId + uploadType)[0].files[0]);
            }

            fd.append('numeroDomanda', order);
            fd.append('tipologia', Number(questionType));
            fd.append('titolo', title);
            fd.append('descrizione', text);
            fd.append('idPoll', lastPoll);
            fd.append('idSection', idQuestion)
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
                    }
                }
            })
        })
    }
}
function newSection() {
    sectionNum++;
    const pollBox = document.getElementById('new-poll-questions');

    const form = document.createElement('form');
    form.setAttribute('enctype', 'multipart/form-data');
    form.setAttribute('method', 'post');
    form.setAttribute('id', 'section-' + sectionNum);
    form.classList.add('accordion', 'w-75', 'my-8', 'mx-auto');

    const inputOrder = document.createElement('input');
    inputOrder.setAttribute('id', 'section-' + sectionNum + '-order');
    inputOrder.setAttribute('hidden', 'hidden');
    inputOrder.classList.add('value');
    form.append(inputOrder)

    const item = document.createElement('div');
    item.setAttribute('id', 'section-' + sectionNum + '-item');
    item.classList.add('accordion-item', 'p-7');
    const header = document.createElement('div');
    header.setAttribute('id', 'section-' + sectionNum + '-header');
    header.classList.add('accordion-header', 'd-flex', 'align-items-center', 'justify-content-between', 'px-7')
    const h3 = document.createElement('h3');
    h3.setAttribute('id', 'section-' + sectionNum + '-title');
    h3.classList.add('mb-0', 'px-7');
    h3.textContent = `${(document.querySelectorAll('form').length)}. Nuova sezione`;
    const icon = document.createElement('i');
    icon.setAttribute('id', 'section-' + sectionNum + '-showButton');
    icon.setAttribute('data-kt-drawer-show', 'true');
    icon.setAttribute('data-kt-drawer-target', '#question-types');
    icon.classList.add('ki-outline', 'fs-1', 'ki-plus-circle', 'fw-bold', 'text-auser', 'cursor-pointer', 'pe-7');

    header.append(h3);
    header.append(icon);
    item.append(header);
    form.append(item);
    pollBox.append(form);
}
function updateOrder(action, redirect = false) {
    const title = $('#poll-title').val();
    if(title === "" || !title) {
        return;
    }
    const url = root + 'app/controllers/PollController.php'
    const lastPoll = $('#last-poll-added').val();
    const answerOrder = [];

    $("#new-poll-questions form").each(function(i, elm) {
        $elm = $(elm);
        const y = $elm.find('.value');
        const id = y.val();
        if(id !== '') {
            const x = $elm.find('h3');
            const order = x.text().split('.')[0];
            answerOrder.push({id: id, order: order});
        }
    });

    const data = {
        'ordine': answerOrder,
        'idPoll': lastPoll,
        'action': action === 'save' ? 'savePoll' : 'publishPoll'
    }

    $.ajax({
        type:'POST',
        url: url,
        data: data,
        success: function() {
            if(redirect) {
                Swal.fire({
                    icon: 'success',
                    text: 'Quiz creato con successo!',
                    showConfirmButton: false
                })
                const redirectUrl = root + 'materiali'
                setTimeout(() => window.location.assign(redirectUrl), 2000)
            }
        }
    })
}
function setDeleteButton(section, parsed, baseId, footer) {
    $('#' + baseId + '-order').val(parsed.lastRow);
    const button = document.createElement('button')
    button.setAttribute('id', baseId + '-removeButton');
    button.setAttribute('type', 'click');
    button.classList.add('btn', 'btn-secondary', 'me-4');
    button.textContent = 'Rimuovi sezione';
    footer.prepend(button);
    newSection();

    button.addEventListener('click', function(e) {
        e.preventDefault();
        const idQuestion = $('#' + baseId + '-order').val();

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
                section.remove();
                $("#new-poll-questions form").each(function(i, elm) {
                    $elm = $(elm);
                    x = $elm.find('h3');
                    x.text(($elm.index("#new-poll-questions form")+1) + '. ' + x.text().split('.')[1].trim())
                });
                updateOrder('save');
            }
        })
    })
}
function readURL(input, i, type) {
    const baseId = 'section-' + i;

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            if(type=== 'file') {
                $('#' + baseId + '-file').attr('src', root + 'app/assets/images/pdf.png');
                $('#' + baseId + '-fileName').text(input.files[0].name);
            } else {
                $('#' + baseId + '-pic').attr('src', e.target.result);
            }
        }

        reader.readAsDataURL(input.files[0]);
    }
}