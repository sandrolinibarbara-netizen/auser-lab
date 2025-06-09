const qrModal = document.getElementById('qr-modal');
qrModal.addEventListener('show.bs.modal', function (event) {

    const button = event.relatedTarget;
    const idPoll = button.getAttribute('data-bs-id').split('-')[1];

    $.ajax({
        type: 'POST',
        data: {action: 'getQR', idPoll: idPoll},
        url: root + 'app/controllers/PollController.php',
        success: function(data) {
            const parsed = JSON.parse(data);
            document.getElementById('qr-code-pic').setAttribute('src', root + 'app/assets/qr-codes/' + parsed.data[0]['qrcode'])
        }
    })
})