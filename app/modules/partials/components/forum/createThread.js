const form = document.getElementById('thread-modal');

form.addEventListener('submit', function(e) {
    e.preventDefault();
    e.submitter.disabled = true;
    const title = $('#modal-thread-title').val();
    const subtitle = $('#modal-thread-subtitle').val();
    const post = $('#modal-thread-post').val();

    if(title === "" || !title || post === "" || !post) {
        e.submitter.disabled = false;
        return;
    }

    const search = window.location.search;
    const params = new URLSearchParams(search);
    const course = params.get("id");

    console.log({'action': 'createThread', 'course': course, 'title': title, 'subtitle': subtitle, 'post': post},)

    $.ajax({
        type: 'POST',
        data: {'action': 'createThread', 'course': course, 'title': title, 'subtitle': subtitle, 'post': post},
        url: root + 'app/controllers/CourseController.php',
        success: function(data) {
            console.log(data)
            Swal.fire({
                icon: 'success',
                text: 'Discussione creata con successo!',
                showConfirmButton: false
            })
            const url = root + 'forum/corso?id=' + course;
            setTimeout(() => window.location.assign(url), 2000)
        }
    })
})