const root = document.getElementById('root').getAttribute('value');
const lectureNoteInfo = document.getElementById('new-lecture-note-info');
const save = document.getElementById('lecture-note-saveButton');
const publish = document.getElementById('lecture-note-publishButton');

if(save) {
    save.addEventListener('click', function() {
        updateOrder('save', true);
    })
}
publish.addEventListener('click', function() {
    updateOrder('publish', true);
})
function addSectionBodyText() {
    const baseId = 'section-' + sectionNum;
    const section = document.getElementById(baseId + '-item');

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
    labelTitle.textContent = 'Inserisci il titolo della dispensa';
    const inputTitle = document.createElement('input');
    inputTitle.setAttribute('id', baseId + '-titleQuestion');
    inputTitle.classList.add('form-control', 'form-control-solid');
    innerBody.append(labelTitle);
    innerBody.append(inputTitle);

    const labelText = document.createElement('label');
    labelText.setAttribute('for', baseId + '-text');
    labelText.classList.add('form-label', 'mt-4');
    labelText.textContent = 'Inserisci il testo della dispensa';
    const textarea = document.createElement('textarea');
    textarea.setAttribute('id', baseId + '-text');
    textarea.classList.add('form-control', 'form-control-solid');
    innerBody.append(labelText);
    innerBody.append(textarea);

    const flexBox = document.createElement('div');
    flexBox.classList.add('d-flex', 'gap-8', 'mt-8');
    innerBody.append(flexBox);
    const imageBox = document.createElement('div');
    imageBox.setAttribute('id', baseId + '-imageBox');
    imageBox.classList.add('d-flex', 'flex-column', 'w-100');

    flexBox.append(imageBox)

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
    fileWrapper.classList.add('h-75');
    fileWrapper.setAttribute('id', baseId + '-file');
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
    return input;
}
function updateOrder(action, redirect = false) {
    const title = $('#lecture-note-title').val();
    if(title === "" || !title) {
        return;
    }
    const url = root + 'app/controllers/LectureNoteController.php'
    const lastLectureNote = $('#last-lecture-note-added').val();
    const answerOrder = [];

    $("#new-lecture-note-questions form").each(function(i, elm) {
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
        'idLectureNote': lastLectureNote,
        'action': action === 'save' ? 'saveLectureNote' : 'publishLectureNote'
    }

    $.ajax({
        type:'POST',
        url: url,
        data: data,
        success: function() {
            if(redirect) {
                Swal.fire({
                    icon: 'success',
                    text: 'Dispensa creata con successo!',
                    showConfirmButton: false
                })
                const redirectUrl = root + 'materiali?'

                setTimeout(() => window.location.assign(redirectUrl), 2000)
            }
        }
    })
}
function addSectionFooter() {
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

    section.addEventListener('submit', function(e) {
        e.preventDefault();
        const title = $('#' + baseId + '-titleQuestion').val();
        const text = $('#' + baseId + '-text').val();
        const idLectureNote = $('#last-lecture-note-added').val();
        const order = $('#' + baseId + '-title').text().split('.')[0];
        const idSection = $('#' + baseId + '-order').val();
        const url = !idSection || idSection === "" ? root + 'app/controllers/LectureNoteController.php' : root + 'app/controllers/SectionController.php'

        if($('#'+ baseId + '-fileInput')[0].files[0] === undefined || !$('#'+ baseId + '-fileInput')[0].files[0]) {
            return;
        }

        const fd = new FormData(this);
        fd.append('file', $('#'+ baseId + '-fileInput')[0].files[0]);
        fd.append('numeroSezione', order);
        fd.append('titolo', title);
        fd.append('descrizione', text);
        fd.append('idSection', idSection);
        fd.append('idLectureNote', idLectureNote)
        if(!idSection || idSection === "") {
            fd.append('action', 'createSectionLectureNote')
        } else {
            fd.append('action', 'updateSection');
            fd.append('type', 'lecture')
        }

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
                    if (!idSection || idSection === "") {
                        $('#' + baseId + '-order').val(parsed.lastRow);
                        const button = document.createElement('button')
                        button.setAttribute('id', baseId + '-removeButton');
                        button.setAttribute('type', 'click');
                        button.classList.add('btn', 'btn-secondary', 'me-4');
                        button.textContent = 'Rimuovi sezione';
                        footer.prepend(button);
                        newSection();

                        button.addEventListener('click', function (e) {
                            e.preventDefault();

                            const data = {
                                'idSection': $('#' + baseId + '-order').val(),
                                'type': 'lecture',
                                'action': 'deleteSection'
                            }

                            $.ajax({
                                type: 'POST',
                                url: root + 'app/controllers/SectionController.php',
                                data: data,
                                success: function () {
                                    section.remove();
                                    $("#new-lecture-note-questions form").each(function (i, elm) {
                                        $elm = $(elm);
                                        x = $elm.find('h3');
                                        x.text(($elm.index("#new-lecture-note-questions form") + 1) + '. ' + x.text().split('.')[1].trim())
                                    });
                                    updateOrder('save');
                                }
                            })
                        })
                    }
                }
            }
        })
    })
}
function newSection() {
    sectionNum++;
    const lectureNoteBox = document.getElementById('new-lecture-note-questions');

    const form = document.createElement('form');
    form.setAttribute('method', 'post');
    form.setAttribute('id', 'section-' + sectionNum);
    form.classList.add('accordion', 'w-75', 'my-8', 'mx-auto');

    const inputOrder = document.createElement('input');
    inputOrder.setAttribute('id', 'section-' + sectionNum + '-order');
    inputOrder.setAttribute('hidden', 'true');
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
    h3.classList.add('mb-0');
    h3.textContent = (document.querySelectorAll('form').length) + '. Sezione';
    const headerButton  = document.createElement('button');
    headerButton.classList.add('accordion-button');
    headerButton.setAttribute('type', 'button');
    headerButton.setAttribute('data-bs-toggle', 'collapse');
    headerButton.setAttribute('data-bs-target', '#section-' + sectionNum + '-body');
    headerButton.setAttribute('aria-controls', '#section-' + sectionNum + '-body');
    headerButton.setAttribute('aria-expanded', 'true');

    headerButton.append(h3);
    header.append(headerButton);
    item.append(header);
    form.append(item);
    lectureNoteBox.append(form);

    addSectionBodyText();
    addSectionFile();
    addSectionFooter();
}
function readURL(input, currentSection) {
    const baseId = 'section-' + currentSection;

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            $('#' + baseId + '-file').attr('src', root + 'app/assets/images/pdf.png');
            $('#' + baseId + '-fileName').text(input.files[0].name);
        }

        reader.readAsDataURL(input.files[0]);
    }
}