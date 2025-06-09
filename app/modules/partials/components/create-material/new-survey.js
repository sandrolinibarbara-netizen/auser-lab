let sectionNum = 0;
surveyInfo.addEventListener('submit', function(e) {
    e.preventDefault();
    const title = $('#survey-title').val();
    const idSurvey = $('#last-survey-added').val();
    if(title === "" || !title) {
        return;
    }
    const description = $('#survey-description').val();
    const action = idSurvey ? 'updateSurvey' : 'createSurvey'
    const url = idSurvey ? 'app/controllers/SurveyController.php' : 'app/controllers/CreationController.php'
    const data = {'titolo': title, 'descrizione': description, 'action': action};

    if(idSurvey) {
        data.idSurvey = idSurvey;
    }

    $.ajax({
        type: 'POST',
        url: root + url,
        data: data,
        success: function(data) {
            Swal.fire({
                icon: 'success',
                text: 'Le informazioni generali del sondaggio sono state salvate con successo!',
                showConfirmButton: true
            })
            if(!idSurvey) {
                const parsed = JSON.parse(data);
                $('#last-survey-added').val(parsed.lastSurvey);
                newSection();
            }
        }
    })
})