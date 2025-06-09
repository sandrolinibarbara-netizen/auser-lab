const form = document.getElementById('modal-add-users');
const newUsers = document.getElementById('new-users');
const availUsers = document.getElementById('avail-users')
let idEventElement;
form.addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget;
    const idEvent = button.getAttribute('data-bs-id').split('-')[1];
    idEventElement = idEvent;

    $.ajax({
        type: 'POST',
        data: {action: 'getAvailability', idLesson: idEvent},
        url: root + 'app/controllers/LessonController.php',
        success: function(data) {
            const parsed = JSON.parse(data);
            availUsers.textContent = (parsed[0]['posti'] - parsed[0]['subbed']).toString();
            newUsers.setAttribute('max', (parsed[0]['posti'] - parsed[0]['subbed']).toString())
        }
    })
})
form.addEventListener('submit', function(e) {
    e.preventDefault();
    const max =  Number(newUsers.max);
    const value = Number(newUsers.value)

    if(value > max) {
        return false;
    } else {
        $.ajax({
            type: 'POST',
            data: {action: 'updateAvailability', idLesson: idEventElement, newAvail: value},
            url: root + 'app/controllers/LessonController.php',
            success: function(data) {
                const parsed = JSON.parse(data)
                if(parsed.success) {
                    $('#modal-add-users').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        text: 'Utenti iscritti con successo all\'evento!',
                        showConfirmButton: false
                    })

                    setTimeout(() => window.location.assign(root + 'corsi-eventi'), 2000)
                } else {
                    $('#modal-add-users').modal('hide');
                    Swal.fire({
                        icon: 'error',
                        text:'Qualcosa è andato storto...',
                        showConfirmButton: false
                    })

                    setTimeout(() => window.location.assign(root + 'corsi-eventi'), 2000)
                }
            }
        })
    }
})