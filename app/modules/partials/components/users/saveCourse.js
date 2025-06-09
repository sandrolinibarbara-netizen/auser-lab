const form = document.getElementById('user-modal');
const select = document.getElementById('course-selection')
form.addEventListener('submit', function(e) {
    e.preventDefault();
    e.submitter.disabled = true;
    const selected = $('#course-selection').val();
    if(selected === "" || !selected ) {
        e.submitter.disabled = false;
        return;
    }
    const search = window.location.search;
    const params = new URLSearchParams(search);
    const user = params.get("id");

    $.ajax({
        type: 'POST',
        data: {'action': 'addUserCourse', 'selected': selected, 'user': user},
        url: root + 'app/controllers/UserController.php',
        success: function() {
            Swal.fire({
                icon: 'success',
                text: 'L\'utente è stato aggiunto al corso selezionato.',
                showConfirmButton: false
            })
            const url = root + 'utente?utente=infos&id=' + user

            setTimeout(() => window.location.assign(url), 2000)
        }
    })
})