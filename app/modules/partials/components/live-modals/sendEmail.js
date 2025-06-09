const form = document.getElementById('question-modal');

form.addEventListener('submit', function(e) {
    e.preventDefault();
    e.submitter.disabled = true;

    const userName = $('#modal-question-name').val();
    const userEmail = $('#modal-question-address').val();
    const userMessage = $('#modal-question-message').val();
    const teacherEmail = $('#modal-question-teacher').val();

    const data = {
        'userName': userName,
        'userEmail': userEmail,
        'userMessage': userMessage,
        'teacherEmail': teacherEmail,
        'action': 'sendEmail'
    }

    $.ajax({
        type:'POST',
        data: data,
        url: root + 'app/controllers/LiveController.php',
        success: function() {
            e.submitter.disabled = false;
        }
    })
})