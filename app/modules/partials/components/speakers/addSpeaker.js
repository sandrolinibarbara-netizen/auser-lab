const root = document.getElementById('root').getAttribute('value');
const form = document.getElementById('new-speaker-form');
const input = document.getElementById('picInput');

form.querySelectorAll('input').forEach(el => {
    el.addEventListener('input', function() {
        document.getElementById('error-name-alert').classList.add('d-none')
        document.getElementById('error-surname-alert').classList.add('d-none')
    })
})

input.addEventListener('change', function() {
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            $('#pic').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
})

form.addEventListener("submit", function(e) {
    e.preventDefault();
    e.submitter.disabled = true;
    const speaker = $('#nome').val();
    const surname = $('#cognome').val();
    if(speaker === "" || !speaker || surname === "" || !surname) {
        if(speaker === "" || !speaker){
            document.getElementById('error-name-alert').classList.remove('d-none')
        }
        if(surname === "" || !surname){
            document.getElementById('error-surname-alert').classList.remove('d-none')
        }
        e.submitter.disabled = false;
        return;
    }

    const search = window.location.search;
    const params = new URLSearchParams(search);
    const speakerId = params.get("id");

    const job = $('#professione').val();
    const website = $('#website').val();
    const email = $('#email').val();
    const descrizione = $('#descrizione').val();

    const fd = new FormData(this);
    if($('#picInput')[0].files[0] !== undefined) {
        fd.append('pic', $('#picInput')[0].files[0])
    }
    fd.append('cognome', surname);
    fd.append('speaker', speaker);
    fd.append('professione', job);
    fd.append('website', website);
    fd.append('descrizione', descrizione);
    fd.append('email', email);
    if(e.submitter.value === "1") {
        fd.append('action', 'createSpeaker')
    }  else {
        fd.append('action', 'updateSpeaker');
        fd.append('idSpeaker', speakerId);
    }
    console.log($('#picInput')[0].files[0])
    const postUrl = e.submitter.value === "1" ? root + 'app/controllers/CreationController.php' : root + 'app/controllers/SpeakerController.php'

        $.ajax({
            type: 'POST',
            url: postUrl,
            data: fd,
            processData: false,
            contentType: false,
            success: function () {
                Swal.fire({
                    icon: 'success',
                    text: 'Relatore creato con successo!',
                    showConfirmButton: false
                })
                const url = root + 'relatori'

                setTimeout(() => window.location.assign(url), 2000)
            }
        })
});