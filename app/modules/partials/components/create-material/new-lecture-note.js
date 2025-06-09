let sectionNum = 0;
lectureNoteInfo.addEventListener('submit', function(e) {
    e.preventDefault();
    const title = $('#lecture-note-title').val();
    const idLectureNote = $('#last-lecture-note-added').val();
    if(title === "" || !title) {
        return;
    }
    const description = $('#lecture-note-description').val();
    const action = idLectureNote ? 'updateLectureNote' : 'createLectureNote'
    const url = idLectureNote ? 'app/controllers/LectureNoteController.php' : 'app/controllers/CreationController.php'
    const data = {'titolo': title, 'descrizione': description, 'action': action};

    if(idLectureNote) {
        data.idLectureNote = idLectureNote;
    }

    $.ajax({
        type: 'POST',
        url: root + url,
        data: data,
        success: function(data) {
            Swal.fire({
                icon: 'success',
                text: 'Le informazioni generali della dispensa sono state salvate con successo!',
                showConfirmButton: true
            })
            if(!idLectureNote) {
                const parsed = JSON.parse(data);
                $('#last-lecture-note-added').val(parsed.lastSurvey);
                newSection();
            }
        }
    })
})
function addSectionFile() {
    const currentSection = sectionNum;
    const input = addFile();
    input.addEventListener('change', function() {
        readURL(this, currentSection);
    })
}

