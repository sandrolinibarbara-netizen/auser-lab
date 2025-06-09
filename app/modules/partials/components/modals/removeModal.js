const removeModal = document.getElementById('modal-remove');
const contentType = document.getElementById('element').value;
let idElement;
let action;
let redirect;
let controller;

switch(contentType) {
    case 'corso/evento':
        redirect = 'corsi-eventi'
        break;
    case 'materiale':
        redirect = 'materiali'
        break;
    case 'lezione':
        redirect = 'corsi-eventi'
        break;
    case 'categoria':
        redirect = 'categorie'
        break;
    case 'partner':
        redirect = 'partner'
        break;
    case 'relatore':
        redirect = 'relatori'
        break;
    case 'discussione':
        redirect = 'forum/corso?id='
        break;
    case 'post':
        redirect = 'forum/corso/thread?thread=single&id='
        break;
    default:
        redirect = 'dashboard'
}
removeModal.addEventListener('show.bs.modal', function (event) {

    const button = event.relatedTarget;
    idElement = button.getAttribute('data-bs-id').split('-')[1];
    const type = button.getAttribute('data-bs-id').split('-')[0];
    switch(type) {
        case 'course':
            action = 'deleteCourse'
            controller = 'app/controllers/CourseController.php';
            break;
        case 'event':
            action = 'deleteEvent'
            controller = 'app/controllers/LessonController.php';
            break;
        case 'poll':
            action = 'deletePoll'
            controller = 'app/controllers/PollController.php';
            break;
        case 'lecture':
            action = 'deleteLectureNote'
            controller = 'app/controllers/LectureNoteController.php';
            break;
        case 'survey':
            action = 'deleteSurvey'
            controller = 'app/controllers/SurveyController.php';
            break;
        case 'lesson':
            action = 'deleteLesson'
            controller = 'app/controllers/LessonController.php';
            break;
        case 'category':
            action = 'deleteCategory'
            controller = 'app/controllers/CategoryController.php';
            break;
        case 'speaker':
            action = 'deleteSpeaker'
            controller = 'app/controllers/SpeakerController.php';
            break;
        case 'sponsor':
            action = 'deleteSponsor'
            controller = 'app/controllers/SponsorController.php';
            break;
        case 'thread':
            action = 'deleteThread'
            controller = 'app/controllers/ForumController.php';
            redirect += button.getAttribute('data-bs-id').split('-')[2];
            break;
        case 'post':
            action = 'deletePost'
            controller = 'app/controllers/ForumController.php';
            const idThread = idElement.split('/')[0];
            redirect += idThread;
            break;
        default:
            action = ''
    }

})
removeModal.addEventListener('submit', function(e) {
    e.preventDefault();

    $.ajax({
        type: 'POST',
        data: {
            'id': idElement,
            'action': action
        },
        url: root + controller,
        success: function() {
            Swal.fire({
                icon: 'success',
                text: 'L\'elemento è stato rimosso con successo.',
                showConfirmButton: false
            })

            setTimeout(() => window.location.assign(root + redirect), 2000)
        }
    })
})