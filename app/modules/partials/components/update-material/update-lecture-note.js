const questionsList = document.getElementById('new-lecture-note-questions');

//il numero attuale della sezione, basato sull'id dell'ultima sezione recuperata dal db
let sectionNum = Number(questionsList.lastElementChild.id.split('-')[1]);

//eventListener per il cambio delle info della dispensa
lectureNoteInfo.addEventListener('submit', function(e) {
    e.preventDefault();
    const title = $('#lecture-note-title').val();
    if(title === "" || !title) {
        return;
    }
    const description = $('#lecture-note-description').val();
    const idLectureNote = $('#last-lecture-note-added').val();
    const data = {'idLectureNote': idLectureNote, 'titolo': title, 'descrizione': description, 'action': 'updateLectureNote'};

    $.ajax({
        type: 'POST',
        url: root + 'app/controllers/LectureNoteController.php',
        data: data,
    })
})

//eventListener sul submit button di ogni sezione. Se la sezione ha già dei dati, il bottone è legato a un update,
// altrimenti vuol dire che è l'ultima sezione ('Nuova sezione'), e che quindi i dati che verranno inseriti andranno inseriti nel db
for(let i = 1; i <= sectionNum; i++) {
    const form = document.getElementById('section-' + i);
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const title = $('#section-' + i + '-titleQuestion').val();
        const text = $('#section-' + i + '-text').val();
        const idSection = $('#section-' + i + '-order').val();
        const idLectureNote = $('#last-lecture-note-added').val();
        const order = $('#section-' + i + '-title').text().split('.')[0];
        const url = !idSection || idSection === "" ? root + 'app/controllers/LectureNoteController.php' : root + 'app/controllers/SectionController.php'

        const fd = new FormData(this);
        fd.append('file', $('#section-'+ i + '-fileInput')[0].files[0]);
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
                                // se la sezione è una 'Nuova sezione', viene creato anche il bottone 'Rimuovi sezione' e gli viene associato l'eventListener per cancellare la sezione
                                if (!idSection || idSection === "") {
                                    $('#section-' + i + '-order').val(parsed.lastRow);
                                    $('#section-' + i + '-title').text(i + '. Sezione');
                                    const footer = document.getElementById('section-' + i + '-footer');
                                    const button = document.createElement('button')
                                    button.setAttribute('id', 'section-' + i + '-removeButton');
                                    button.setAttribute('type', 'click');
                                    button.classList.add('btn', 'btn-secondary', 'me-4');
                                    button.textContent = 'Rimuovi sezione';
                                    footer.prepend(button);
                                    newSection();
                                    button.addEventListener('click', function (e) {
                                        e.preventDefault();
                                        const idSection = $('#section-' + i + '-order').val();

                                        const data = {
                                            'idSection': idSection,
                                            'type': 'lecture',
                                            'action': 'deleteSection'
                                        }

                                        console.log(data);

                                        $.ajax({
                                            type: 'POST',
                                            url: root + 'app/controllers/SectionController.php',
                                            data: data,
                                            success: function () {
                                                form.remove();
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
                    }
                })
    })
    const input = document.getElementById('section-' + i + '-fileInput')
    input.addEventListener('change', function() {
        readURL(this, i);
        const wrapper = document.getElementById('section-' + i + '-file')
        console.log(wrapper.getAttribute('src'))
    })
}

//creazione dei bottoni 'Rimuovi sezione' per le sezioni che hanno già dei dati
for(let i = 1; i < sectionNum; i++) {
    const form = document.getElementById('section-' + i);
    const deleteSection = document.getElementById('section-' + i + '-removeButton');
    deleteSection.addEventListener('click', function(e) {
        e.preventDefault();
        const idSection = $('#section-' + i + '-order').val();

        const data = {
            'idSection': idSection,
            'type': 'lecture',
            'action': 'deleteSection'
        }

        console.log(data);

        $.ajax({
            type: 'POST',
            url: root + 'app/controllers/SectionController.php',
            data: data,
            success: function() {
                form.remove();
                $("#new-lecture-note-questions form").each(function(i, elm) {
                    $elm = $(elm);
                    x = $elm.find('h3');
                    x.text(($elm.index("#new-lecture-note-questions form")+1) + '. ' + x.text().split('.')[1].trim())
                });
                updateOrder('save');
            }
        })
    })

}

function addSectionFile() {
    const input = addFile();

    input.addEventListener('change', function() {
        readURL(this, sectionNum);
    })
}

//quando si lega l'eventListener del salvataggio al nuovo bottone 'Salva sezione' che viene creato,
// bisogna poi aggiungere a sua volta, una volta avvenuto il salvataggio, il bottone 'Rimuovi sezione'
// e associare anche a esso il suo eventListener per la cancellazione della sezione