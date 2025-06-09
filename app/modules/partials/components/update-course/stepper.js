const course = params.get("id");
form.addEventListener("submit", function(e) {
    e.preventDefault();
    console.log($('#picInput')[0].files[0])
    $('#name-error-message').empty()
    const topic = $('input[name="argomento"]:checked').val() ?? '0';
    const corso = $('#nome').val();
    let insegnanti = $('#insegnanti').val();
    if(insegnanti.length === 0 || !insegnanti) {
        insegnanti = ['user'];
    }
    const lezioni = $('#lezioni').val();
    const ore = $('#ore').val();
    const inizio = $('#data-inizio').val();
    const fine = $('#data-fine').val();
    const descrizione = $('#descrizione').val();
    const importo = $('#contributo').val();
    const min = $('#min').val();
    const max = $('#max').val();
    const pathVideo = $('#path-video').val();

    if(!checkDate(inizio) || !checkDate(fine)) {
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
        case '3':
            remoto = '1';
            presenza = '1';
            break;
        default:
            remoto = '2';
            presenza = '2';
    }

    const tesseramento = $('input[name="tesseramento"]:checked').val();
    const privato = $('input[name="visibilità"]:checked').val();

    const fd = new FormData(this);
    if ($('#picInput')[0].files[0] !== undefined) {
        fd.append('file', $('#picInput')[0].files[0])
    }
    fd.append('topic', topic);
    fd.append('corso', corso);
    fd.append('lezioni', lezioni);
    fd.append('ore', ore);
    fd.append('inizio', inizio);
    fd.append('fine', fine);
    fd.append('descrizione', descrizione);
    fd.append('importo', importo)
    fd.append('min', min);
    fd.append('max', max);
    fd.append('insegnanti', JSON.stringify(insegnanti));
    fd.append('remoto', remoto);
    fd.append('presenza', presenza);
    fd.append('tesseramento', tesseramento);
    fd.append('privato', privato)
    fd.append('pathVideo', pathVideo)
    fd.append('idCorso', course);

    const validator = FormValidation.formValidation(
        form,
        {
            fields: {
                'nome': {
                    validators: {
                        notEmpty: {
                            message: 'Il nome del corso è obbligatorio.',
                        }
                    }
                },
                'lezioni': {
                    validators: {
                        notEmpty: {
                            message: 'Il numero di lezioni è obbligatorio.',
                        }
                    }
                },
            },

            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: '.fv-row',
                    eleInvalidClass: '',
                    eleValidClass: '',
                    defaultMessageContainer: false,
                }),
                message: new FormValidation.plugins.Message({
                    container: '#name-error-message',
                }),
            }
        }
    );

    if (validator) {
        validator.validate().then(function (status) {

            if (status === 'Valid') {

                if(saveButton) {
                    saveButton.setAttribute('data-kt-indicator', 'on');
                    saveButton.disabled = true;
                }
                publishButton.setAttribute('data-kt-indicator', 'on');
                publishButton.disabled = true;

                if (e.submitter.value === "2") {
                    fd.append('action', 'saveDraftCourse')
                    $.ajax({
                        type: 'POST',
                        url: root + 'app/controllers/CourseController.php',
                        data: fd,
                        processData: false,
                        contentType: false,
                        success: function () {
                            Swal.fire({
                                icon: 'success',
                                text: 'Corso salvato con successo!',
                                showConfirmButton: false
                            })
                            const url = root + 'corsi-eventi'

                            setTimeout(() => window.location.assign(url), 2000)
                        }
                    })
                }
                if (e.submitter.value === "1") {
                    if(topic && corso && lezioni && ore && inizio && fine && importo && min && max && remoto && presenza && tesseramento && privato) {
                        fd.append('action', 'publishDraftCourse');
                        $.ajax({
                            type: 'POST',
                            url: root + 'app/controllers/CourseController.php',
                            data: fd,
                            processData: false,
                            contentType: false,
                            success: function () {
                                Swal.fire({
                                    icon: 'success',
                                    text: 'Corso pubblicato con successo!',
                                    showConfirmButton: false
                                })
                                const url = root + 'corsi-eventi'

                                setTimeout(() => window.location.assign(url), 2000)
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    text: 'Per pubblicare un corso devi indicare gli insegnanti.',
                                    showConfirmButton: true
                                })
                                document.getElementById('publish-error-message').classList.remove('d-none');
                                if(saveButton) {
                                    saveButton.setAttribute('data-kt-indicator', 'off');
                                    saveButton.disabled = false;
                                }
                                publishButton.setAttribute('data-kt-indicator', 'off');
                                publishButton.disabled = false;
                            }
                        })
                    } else {

                        document.getElementById('publish-error-message').classList.remove('d-none');
                        if(saveButton) {
                            saveButton.setAttribute('data-kt-indicator', 'off');
                            saveButton.disabled = false;
                        }
                        publishButton.setAttribute('data-kt-indicator', 'off');
                        publishButton.disabled = false;
                    }
                }
            }
        })
    }
});
