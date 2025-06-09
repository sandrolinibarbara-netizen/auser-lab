const messageModal = document.getElementById('message-modal');
const select = document.getElementById('user-selection');
messageModal.addEventListener('show.bs.modal', function (event) {
    $('#user-selection').empty();
        $.ajax({
            type: 'POST',
            data: {'action': 'getOtherUsers'},
            url: root + 'app/controllers/UserController.php',
            success: function(data) {
                const parsed = JSON.parse(data);
                parsed.forEach(el => {
                    const option = document.createElement('option');
                    option.setAttribute('value', el['id']);
                    option.textContent = el['nome'] + ' ' + el['cognome'];
                    select.append(option);
                })
            }
        })
})
messageModal.addEventListener('submit', function(e) {
    e.preventDefault();
    const user = $('#user-selection').val()
    const message = $('#modal-message-post').val();
    if(user === "" || !user || message === "" || !message) {
        return;
    }
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
                showConfirmButton: false
            })
            const url = root + 'messaggi'

            setTimeout(() => window.location.assign(url), 2000)
        }
    })
})