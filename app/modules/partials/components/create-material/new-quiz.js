let sectionNum = 0;
pollInfo.addEventListener('submit', function(e) {
    e.preventDefault();
    const title = $('#poll-title').val();
    const idPoll = $('#last-poll-added').val();
    if(title === "" || !title) {
        return;
    }
    const description = $('#poll-description').val();
    const action = idPoll ? 'updatePoll' : 'createPoll'
    const url = idPoll ? 'app/controllers/PollController.php' : 'app/controllers/CreationController.php'
    const data = {'titolo': title, 'descrizione': description, 'action': action};
    if(idPoll) {
        data.idPoll = idPoll;
    }

    $.ajax({
        type: 'POST',
        url: root + url,
        data: data,
        success: function(data) {
            Swal.fire({
                icon: 'success',
                text: 'Le informazioni generali del quiz sono state salvate con successo!',
                showConfirmButton: true
            })
            if(!idPoll) {
                const parsed = JSON.parse(data);
                $('#last-poll-added').val(parsed.lastSurvey);
                newSection();
            }
        }
    })
})
function addSectionFile() {
    const [input, baseId] = addFile();

    input.addEventListener('change', function() {
        readURL(this, Number(baseId.split('-')[1]), 'file');
    })
}