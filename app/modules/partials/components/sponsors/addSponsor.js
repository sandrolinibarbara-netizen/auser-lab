const root = document.getElementById('root').getAttribute('value');
const form = document.getElementById('new-sponsor-form');
const input = document.getElementById('picInput');

form.querySelectorAll('input').forEach(el => {
    el.addEventListener('input', function() {
        document.getElementById('error-name-alert').classList.add('d-none')
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
    const sponsor = $('#nome').val();
    if(sponsor === "" || !sponsor) {
        document.getElementById('error-name-alert').classList.remove('d-none')
        e.submitter.disabled = false;
        return;
    }
    const search = window.location.search;
    const params = new URLSearchParams(search);
    const sponsorId = params.get("id");

    const video = $('#link-video').val();
    const website = $('#website').val();
    const phone = $('#phone').val();
    const email = $('#email').val();
    const descrizione = $('#descrizione').val();

    const fd = new FormData(this);
    if($('#picInput')[0].files[0] !== undefined) {
        fd.append('pic', $('#picInput')[0].files[0])
    }
    fd.append('path-link', video);
    fd.append('sponsor', sponsor);
    fd.append('website', website);
    fd.append('descrizione', descrizione);
    fd.append('phone', phone);
    fd.append('email', email);
    if(e.submitter.value === "1") {
        fd.append('action', 'createSponsor')
    }  else {
        fd.append('action', 'updateSponsor');
        fd.append('idSponsor', sponsorId);
    }
    console.log($('#picInput')[0].files[0])
    const postUrl = e.submitter.value === "1" ? root + 'app/controllers/CreationController.php' : root + 'app/controllers/SponsorController.php'

        $.ajax({
            type: 'POST',
            url: postUrl,
            data: fd,
            processData: false,
            contentType: false,
            success: function () {
                Swal.fire({
                    icon: 'success',
                    text: 'Partner creato con successo!',
                    showConfirmButton: false
                })
                const url = root + 'partner'

                setTimeout(() => window.location.assign(url), 2000)
            }
        })
});