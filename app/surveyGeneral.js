const root = document.getElementById('root').getAttribute('value');
const questionSingle = document.getElementById('question-single');
const questionOpen = document.getElementById('question-open');
const surveyInfo = document.getElementById('new-survey-info');
const save = document.getElementById('survey-saveButton');
const publish = document.getElementById('survey-publishButton');

questionSingle.addEventListener('click', function() {
    $.ajax({
        type: 'POST',
        data: {action: 'createSectionSurvey', idSurvey: $('#last-survey-added').val(), order: (document.querySelectorAll('form').length - 1), idType: 2},
        url: root + 'app/controllers/SurveyController.php',
        success: function(data) {
            const parsed = JSON.parse(data);
            document.getElementById('section-' + sectionNum + '-order').setAttribute('value', parsed.lastRow)
            getSectionSelection('Valutazione');
            addSectionBodyText(2);
            addSectionFooter('radio', parsed);
        }
    })
})
questionOpen.addEventListener('click', function() {
    $.ajax({
        type: 'POST',
        data: {action: 'createSectionSurvey', idSurvey: $('#last-survey-added').val(), order: (document.querySelectorAll('form').length - 1), idType: 1},
        url: root + 'app/controllers/SurveyController.php',
        success: function(data) {
            const parsed = JSON.parse(data);
            document.getElementById('section-' + sectionNum + '-order').setAttribute('value', parsed.lastRow)
            getSectionSelection('Opinione');
            addSectionBodyText(1);
            addSectionFooter('open', parsed);
        }
    })
})
if(save) {
    save.addEventListener('click', function() {
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

    if(type === 'radio') {
        section.addEventListener('submit', function(e) {
            e.preventDefault();

            const questionType = $('#' + baseId + '-questionType').val();
            const title = $('#' + baseId + '-titleQuestion').val();
            const text = $('#' + baseId + '-text').val();
            const lastSurvey = $('#last-survey-added').val();
            const order = $('#' + baseId + '-title').text().split('.')[0];
            const idQuestion = $('#' + baseId + '-order').val();
            const url = root + 'app/controllers/SectionController.php'

            const fd = new FormData(this);
            fd.append('numeroDomanda', order);
            fd.append('tipologia', questionType);
            fd.append('titolo', title);
            fd.append('descrizione', text);
            fd.append('idSurvey', lastSurvey);
            fd.append('idSection', idQuestion)
            fd.append('action', 'updateSection');
            fd.append('type', 'survey')

            $.ajax({
                type: 'POST',
                url: url,
                data: fd,
                processData: false,
                contentType: false,
                success: function(data) {
                    if(data){
                        const parsed = JSON.parse(data);
                        console.log(parsed);
                        if (parsed.error) {
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
            const lastSurvey = $('#last-survey-added').val();
            const order = $('#' + baseId + '-title').text().split('.')[0];
            const idQuestion = $('#' + baseId + '-order').val();
            const url = root + 'app/controllers/SectionController.php'

            const fd = new FormData(this);
            fd.append('numeroDomanda', order);
            fd.append('tipologia', questionType);
            fd.append('titolo', title);
            fd.append('descrizione', text);
            fd.append('idSurvey', lastSurvey);
            fd.append('idSection', idQuestion);
            fd.append('action', 'updateSection');
            fd.append('type', 'survey')

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
    const surveyBox = document.getElementById('new-survey-questions');

    const form = document.createElement('form');
    form.setAttribute('enctype', 'multipart/form-data');
    form.setAttribute('method', 'post');
    form.setAttribute('id', 'section-' + sectionNum);
    form.classList.add('accordion', 'w-75', 'my-8', 'mx-auto');

    const inputOrder = document.createElement('input');
    inputOrder.setAttribute('id', 'section-' + sectionNum + '-order');
    inputOrder.setAttribute('hidden', 'true');
    inputOrder.classList.add('value');
    form.append(inputOrder);

    const item = document.createElement('div');
    item.setAttribute('id', 'section-' + sectionNum + '-item');
    item.classList.add('accordion-item', 'p-7');
    const header = document.createElement('div');
    header.setAttribute('id', 'section-' + sectionNum + '-header');
    header.classList.add('accordion-header', 'd-flex', 'align-items-center', 'justify-content-between', 'px-7')
    const h3 = document.createElement('h3');
    h3.setAttribute('id', 'section-' + sectionNum + '-title');
    h3.classList.add('mb-0', 'px-7');
    h3.textContent = (document.querySelectorAll('form').length) + '. Nuova sezione';
    const icon = document.createElement('i');
    icon.setAttribute('id', 'section-' + sectionNum + '-showButton');
    icon.setAttribute('data-kt-drawer-show', 'true');
    icon.setAttribute('data-kt-drawer-target', '#question-types');
    icon.classList.add('ki-outline', 'fs-1', 'ki-plus-circle', 'fw-bold', 'text-auser', 'cursor-pointer', 'pe-7');

    header.append(h3);
    header.append(icon);
    item.append(header);
    form.append(item);
    surveyBox.append(form);
}
function updateOrder(action, redirect = false) {
    const title = $('#survey-title').val();
    if(title === "" || !title) {
        return;
    }
    const url = root + 'app/controllers/SurveyController.php'
    const lastSurvey = $('#last-survey-added').val();
    const answerOrder = [];

    $("#new-survey-questions form").each(function(i, elm) {
        $elm = $(elm);
        const y = $elm.find('.value');
        const id = y.val();
        if(id !== '') {
            const x = $elm.find('h3');
            const order = x.text().split('.')[0];
            answerOrder.push({id: id, order: order});
        }
    });
    console.log(answerOrder)

    const data = {
        'ordine': answerOrder,
        'idSurvey': lastSurvey,
        'action': action === 'save' ? 'saveSurvey' : 'publishSurvey'
    }

    $.ajax({
        type:'POST',
        url: url,
        data: data,
        success: function() {
            if(redirect) {
                Swal.fire({
                    icon: 'success',
                    text: 'Sondaggio creato con successo!',
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
    button.setAttribute('type', 'button');
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
            'type': 'survey'
        }

        console.log(data);

        $.ajax({
            type: 'POST',
            url: root + 'app/controllers/SectionController.php',
            data: data,
            success: function() {
                section.remove();
                $("#new-survey-questions form").each(function(i, elm) {
                    $elm = $(elm);
                    x = $elm.find('h3');
                    x.text(($elm.index("#new-survey-questions form")+1) + '. ' + x.text().split('.')[1].trim())
                });
                updateOrder('save');
            }
        })
    })
}