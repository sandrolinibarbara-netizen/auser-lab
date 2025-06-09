const form = document.getElementById('forum-modal');
const select = document.getElementById('user-selection')
$('#single-course-selection').on('change', function() {
    const idCourse = $('#single-course-selection').val();
    $('#user-selection').empty();
    if(idCourse !== '-') {
        $.ajax({
            type: 'POST',
            data: {'course': idCourse, 'action': 'createForum'},
            url: root + 'app/controllers/CourseController.php',
            success: function(data) {
                const parsed = JSON.parse(data);
                console.log(parsed);
                const allOption = document.createElement('option');
                allOption.setAttribute('value', '0');
                allOption.textContent = 'Tutti';
                select.append(allOption);
                parsed.forEach(el => {
                    const option = document.createElement('option');
                    option.setAttribute('value', el['id']);
                    option.textContent = el['nome'] + ' ' + el['cognome'];
                    select.append(option);
                })
                const userSelection = document.getElementById('user-selection-div');
                userSelection.classList.remove('d-none')
                const intro = document.getElementById('brief-intro-div');
                intro.classList.remove('d-none');
                const answersChance = document.getElementById('answers');
                answersChance.classList.remove('d-none');
            }
        })
    }
})

form.addEventListener('submit', function(e) {
    e.preventDefault();
    e.submitter.disabled = true;
    const idCourse = $('#single-course-selection').val();
    let idUsers = $('#user-selection').val();
    const thread = $('#brief-intro').val();
    const answersChance = $('input[name="answers-poss"]:checked').val();

    if(idCourse === "" || !idCourse || idUsers === [] || !idUsers || thread === "" || !thread || answersChance === "" || !answersChance) {
        e.submitter.disabled = false;
        return;
    }

    if(idUsers.filter(el=> el === '0').length > 0) idUsers = ['0']
    console.log({'action': 'addUsersThread', 'course': idCourse, 'users': idUsers, 'firstThread': thread})
    $.ajax({
        type: 'POST',
        data: {'action': 'addUsersThread', 'course': idCourse, 'users': idUsers, 'firstThread': thread, 'answersChance': answersChance},
        url: root + 'app/controllers/CourseController.php',
        success: function() {
            $('#forum-modal').modal('hide');
            Swal.fire({
                icon: 'success',
                text: 'Forum creato con successo!',
                showConfirmButton: false
            })
            const url = root +'forum/corso?id=' + idCourse;
            setTimeout(() => window.location.assign(url), 2000)
        }
    })
})