const questionsList = document.getElementById('new-survey-questions');
let sectionNum = Number(questionsList.lastElementChild.id.split('-')[1]);
surveyInfo.addEventListener('submit', function(e) {
    e.preventDefault();
    const title = $('#survey-title').val();
    if(title === "" || !title) {
        return;
    }
    const description = $('#survey-description').val();
    const idSurvey = $('#last-survey-added').val();
    const data = {'idSurvey': idSurvey, 'titolo': title, 'descrizione': description, 'action': 'updateSurvey'};

    $.ajax({
        type: 'POST',
        url: root + 'app/controllers/SurveyController.php',
        data: data,
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
        const title = $('#section-' + i + '-titleQuestion').val();
        const text = $('#section-' + i + '-text').val();
        const lastSurvey = $('#last-survey-added').val();
        const idQuestion = $('#section-' + i + '-order').val();
        const order = $('#section-' + i + '-title').text().split('.')[0];
        const url = root + 'app/controllers/SectionController.php'

            const fd = new FormData(this);
            fd.append('numeroDomanda', order);
            fd.append('tipologia', questionType);
            fd.append('titolo', title);
            fd.append('descrizione', text);
            fd.append('idSurvey', lastSurvey);
            fd.append('idSection', idQuestion);
            fd.append('type', 'survey');
            fd.append('action', 'updateSection');

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

    const deleteSection = document.getElementById('section-' + i + '-removeButton');
    deleteSection.addEventListener('click', function(e) {
        e.preventDefault();
        const idQuestion = $('#section-' + i + '-order').val();

        const data = {
            'idSection': idQuestion,
            'action': 'deleteSection',
            'type': 'survey'
        }

        $.ajax({
            type: 'POST',
            url: root + 'app/controllers/SectionController.php',
            data: data,
            success: function(data) {
                form.remove();
                $("#new-survey-questions form").each(function(i, elm) {
                    $elm = $(elm);
                    x = $elm.find('h3');
                    if(x.text().split('.')[1]) {
                        x.text(($elm.index("#new-survey-questions form")+1) + '. ' + x.text().split('.')[1].trim())
                    }
                });
                updateOrder('save');
            }
        })
    })
}