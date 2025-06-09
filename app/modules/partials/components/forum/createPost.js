const root = document.getElementById('root').getAttribute('value');
const form = document.getElementById('post-modal');

form.addEventListener('submit', function(e) {
    e.preventDefault();
    e.submitter.disabled = true;

    const post = $('#modal-post-content').val();
    if(post === "" || !post) {
        e.submitter.disabled = false;
        return;
    }

    const search = window.location.search;
    const params = new URLSearchParams(search);
    const thread = params.get("id");

    if(Number(e.submitter.value) === 1) {
        $.ajax({
            type: 'POST',
            data: {'action': 'createPost', 'post': post, 'thread': thread},
            url: root + 'app/controllers/ForumController.php',
            success: function (data) {
                console.log(data)
                Swal.fire({
                    icon: 'success',
                    text: 'Post creato con successo!',
                    showConfirmButton: false
                })
                const url = root + 'forum/corso/thread?thread=single&id=' + thread;
                setTimeout(() => window.location.assign(url), 2000)
            }
        })
    } else {
        const recipient = e.submitter.value.split('-')[1];
        $.ajax({
            type: 'POST',
            data: {'action': 'createMessage', 'recipient': recipient, 'message': post, 'thread': thread},
            url: root + 'app/controllers/MessagesController.php',
            success: function (data) {
                console.log(data)
                Swal.fire({
                    icon: 'success',
                    text: 'Messaggio inviato!',
                    showConfirmButton: false
                })
                const url = root + 'messaggi/conversazione?chat=single&id=' + thread;
                setTimeout(() => window.location.assign(url), 2000)
            }
        })
    }
})