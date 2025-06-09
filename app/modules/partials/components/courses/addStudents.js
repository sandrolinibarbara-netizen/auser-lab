const addStudentsModal = document.getElementById('add-students-modal');
const select = document.getElementById('students-selection');
const type = document.getElementById('type').value;
const beet = document.getElementById('root').value;

let idCourseEvent;
let url;
let redirectUrl;
let getData;
let submitData;

addStudentsModal.addEventListener('show.bs.modal', function (event) {

    const button = event.relatedTarget;
    idCourseEvent = button.getAttribute('value');
    $('#students-selection').empty();

    switch(type) {
        case 'corso':
            url = root + 'app/controllers/CourseController.php';
            redirectUrl = root + 'corsi-eventi';
            getData = {action: 'getPrivateStudents', course: idCourseEvent};
            submitData = {action: 'addStudentsPrivate', course: idCourseEvent};
            document.getElementById('modal-add-students-title').textContent = 'Invita gli studenti a partecipare al corso privato';
            document.getElementById('modal-add-students-info').textContent = 'Gli studenti che selezionerai dalla lista verranno iscritti al corso. A questi studenti verrà inviata una mail con la notifica dell\'avvenuta iscrizione.';
            document.getElementById('modal-add-students-label').textContent = 'Scegli gli studenti che vuoi iscrivere al corso';
            break;
        case 'evento':
            url = beet + 'app/controllers/LessonController.php';
            redirectUrl = beet + 'corsi-eventi';
            getData = {action: 'getPrivateAttendants', lesson: idCourseEvent};
            submitData = {action: 'addAttendantsPrivate', lesson: idCourseEvent};
            document.getElementById('modal-add-students-title').textContent = 'Invita gli studenti a partecipare all\'evento privato';
            document.getElementById('modal-add-students-info').textContent = 'Gli studenti che selezionerai dalla lista verranno iscritti all\'evento. A questi studenti verrà inviata una mail con la notifica dell\'avvenuta iscrizione.';
            document.getElementById('modal-add-students-label').textContent = 'Scegli gli studenti che vuoi iscrivere all\'evento';
            break;
    }

    console.log(url)

    $.ajax({
        type: 'POST',
        url: url,
        data: getData,
        success: function(data) {
            const parsed = JSON.parse(data);
            parsed.forEach(el => {
                const option = document.createElement('option');
                option.setAttribute('value', el['id'] + '-' + el['email']);
                option.textContent = el['nome'] + ' ' + el['cognome'];
                select.append(option);
            })
        }
    })
})
addStudentsModal.addEventListener('submit', function(e) {
    e.preventDefault();
    const students = $('#students-selection').val();

    if(!students || students === "") {
        return;
    }

    submitData.students = students;

    $.ajax({
        type: 'POST',
        url: url,
        data: submitData,
        success: function(data) {
            console.log(JSON.parse(data))
            $('#clone-course-modal').modal('hide');
            Swal.fire({
                icon: 'success',
                text:'Gli studenti che selezionato sono stati iscritti al corso. L\'iscrizione gli è stata notificata via mail.',
                showConfirmButton: false
            })

            setTimeout(() => window.location.assign(redirectUrl), 2000)
        }
    })
})