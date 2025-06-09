const certificateModal = document.getElementById('reward-user-modal');
const removeModal = document.getElementById('remove-user-modal');
const moveModal = document.getElementById('move-user-modal');
const messageModal = document.getElementById('message-user-modal');
certificateModal.addEventListener('show.bs.modal', function (event) {

    const button = event.relatedTarget;
    const course = button.getAttribute('data-bs-ids').split('-')[0];
    const user = button.getAttribute('data-bs-ids').split('-')[1];

    $.ajax({
        type: 'POST',
        data: {
            'idCourse': course,
            'idUser': user,
            'action': 'getSingleCourse'
        },
        url: root + 'app/controllers/UserController.php',
        success: function(data) {
            const parsed = JSON.parse(data);
            console.log(parsed)
            const courseText = parsed.data[0]['corso'].split(' ')[0].toLowerCase() === 'corso' ? parsed.data[0]['corso'] : 'corso di ' + parsed.data[0]['corso']
            $('#reward-course').text(courseText);
            $('#reward-user').text(parsed.data[0]['studente'])
            $('#reward-user-button').val(course + '-' + user);
        }
    })
})
certificateModal.addEventListener('submit', function(e) {
    e.preventDefault();
    const value = $('#reward-user-button').val()
    const course = value.split('-')[0];
    const user = value.split('-')[1];
    $.ajax({
        type: 'POST',
        data: {
            'idCourse': course,
            'idUser': user,
            'action': 'updateCertificate'
        },
        url: root + 'app/controllers/UserController.php',
        success: function() {
            Swal.fire({
                icon: 'success',
                text: 'L\'attestato del corso è stato rilasciato con successo!',
                showConfirmButton: true
            })
            const rewardButton = document.getElementById('reward-' + course + '-' + user);
            rewardButton.setAttribute('disabled', 'disabled')
        }
    })
})
moveModal.addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget;
    const course = button.getAttribute('data-bs-ids').split('-')[0];
    const user = button.getAttribute('data-bs-ids').split('-')[1];
    const courseClass = button.getAttribute('data-class');

    $('#course-selection').empty();
    const select = document.getElementById('course-selection');

        $.ajax({
            type: 'POST',
            data: {
                'idUser': user,
                'action': 'getAvailableCourses',
                'class': Number(courseClass)
            },
            url: root + 'app/controllers/UserController.php',
            success: function(data) {
                const parsed = JSON.parse(data);
                console.log(parsed);
                $('#move-user-button').val(course + '-' + user);
                $('#move-user').text(parsed.student['nome'] + ' ' + parsed.student['cognome'])
                parsed.data.forEach(el => {
                    const option = document.createElement('option');
                    option.setAttribute('value', el['id']);
                    option.textContent = el['nome'];
                    select.append(option);
                })
            }
        })

})
moveModal.addEventListener('submit', function(e) {
    e.preventDefault();
    const courseSelected = $('#course-selection').val();
    const value = $('#move-user-button').val()
    const courseRemoved = value.split('-')[0];
    const user = value.split('-')[1];
    $.ajax({
        type: 'POST',
        data: {
            'idCourseRemoved': courseRemoved,
            'idCourseSelected': courseSelected,
            'idUser': user,
            'action': 'moveUser'
        },
        url: root + 'app/controllers/UserController.php',
        success: function() {
                Swal.fire({
                    icon: 'success',
                    text: 'L\'utente è stato spostato con successo nella classe selezionata.',
                    showConfirmButton: true
                })
                const url = root + 'app/modules/register/single-register.php?id=' + courseRemoved

                setTimeout(() => window.location.assign(url), 2000)
            }
    })
})
removeModal.addEventListener('show.bs.modal', function (event) {

    const button = event.relatedTarget;
    const course = button.getAttribute('data-bs-ids').split('-')[0];
    const user = button.getAttribute('data-bs-ids').split('-')[1];

    $.ajax({
        type: 'POST',
        data: {
            'idCourse': course,
            'idUser': user,
            'action': 'getSingleCourse'
        },
        url: root + 'app/controllers/UserController.php',
        success: function(data) {
            const parsed = JSON.parse(data);
            console.log(parsed)
            const courseText = parsed.data[0]['corso'].split(' ')[0].toLowerCase() === 'corso' ? parsed.data[0]['corso'] : 'corso di ' + parsed.data[0]['corso']
            $('#remove-course').text(courseText);
            $('#remove-user').text(parsed.data[0]['studente'])
            $('#remove-user-button').val(course + '-' + user);
        }
    })
})
removeModal.addEventListener('submit', function(e) {
    e.preventDefault();
    const value = $('#remove-user-button').val()
    const course = value.split('-')[0];
    const user = value.split('-')[1];
    $.ajax({
        type: 'POST',
        data: {
            'idCourse': course,
            'idUser': user,
            'action': 'removeUserCourse'
        },
        url: root + 'app/controllers/UserController.php',
    })
})
messageModal.addEventListener('show.bs.modal', function (event) {

    const button = event.relatedTarget;
    const course = button.getAttribute('data-bs-ids').split('-')[0];
    const user = button.getAttribute('data-bs-ids').split('-')[1];
    $('#message-user-button').val(course + '-' + user);
})
messageModal.addEventListener('submit', function(e) {
    e.preventDefault();
    const value = $('#message-user-button').val();
    const user = value.split('-')[1];
    const message = $('#modal-message-user-post').val();
    $.ajax({
        type: 'POST',
        data: {
            'idUser': user,
            'message': message,
            'action': 'sendUserMessage'
        },
        url: root + 'app/controllers/UserController.php',
        success: function() {
            Swal.fire({
                icon: 'success',
                text: 'Messaggio inviato!',
                showConfirmButton: true
            })
        }
    })
})