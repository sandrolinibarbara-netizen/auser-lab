const now = new Date();
const thisYear = now.getFullYear();
const nextYear = thisYear + 1;
console.log(thisYear, nextYear)


const root = document.getElementById('root').getAttribute('value');
const selectedPaymentOpts = [];
const payForm = document.getElementById('user-payments');
const subsForm = document.getElementById('subs-form-' + thisYear);
const upForm = document.getElementById('subs-form-' + nextYear);
const subButtons = document.querySelectorAll('.sub-button');
const upButtons = document.querySelectorAll('.up-button');
const input = document.getElementById('fileInput-' + thisYear);
const onput = document.getElementById('fileInput-' + nextYear);
const subloadFileForm = document.getElementById('fileBoxForm-' + thisYear);
const uploadFileForm = document.getElementById('fileBoxForm-' + nextYear);
const saveGenerate = document.getElementById('save-and-generate-' + thisYear);
const saveUpGenerate = document.getElementById('save-and-generate-' + nextYear);
const divThis = document.getElementById('container-' + thisYear);
const divNext = document.getElementById('container-' + nextYear);

$('#privacy-year').on('change', function() {
    const value = $('#privacy-year').val();
    if(value === nextYear.toString()) {
        divNext.classList.remove('d-none');
        divThis.classList.add('d-none');

    } else {
        divNext.classList.add('d-none');
        divThis.classList.remove('d-none');
    }
})

$('#sub-year').on('change', function() {
    const value = $('#sub-year').val();
    if(value === nextYear.toString()) {
        upForm.classList.remove('d-none');
        subsForm.classList.add('d-none');

    } else {
        upForm.classList.add('d-none');
        subsForm.classList.remove('d-none');
    }
})
subloadFileForm.addEventListener('submit', function(e) {
    e.preventDefault();
    e.submitter.disabled = true;
    const fd = new FormData(this);
    fd.append('file', $('#fileInput-' + thisYear)[0].files[0]);
    fd.append('user', user);
    fd.append('action', 'uploadPrivacy');
    fd.append('year', thisYear.toString())

    $.ajax({
        type: 'POST',
        url: root + 'app/controllers/UserController.php',
        data: fd,
        processData: false,
        contentType: false,
        success: function() {
            Swal.fire({
                icon: 'success',
                text: 'Documento caricato con successo!',
                showConfirmButton: false
            })
            const url = root + 'utente?utente=infos&id=' + user

            setTimeout(() => window.location.assign(url), 2000)
        }
    })
})
uploadFileForm.addEventListener('submit', function(e) {
    e.preventDefault();
    e.submitter.disabled = true;
    const fd = new FormData(this);
    fd.append('file', $('#fileInput-' + nextYear)[0].files[0]);
    fd.append('user', user);
    fd.append('action', 'uploadPrivacy');
    fd.append('year', nextYear.toString())

    $.ajax({
        type: 'POST',
        url: root + 'app/controllers/UserController.php',
        data: fd,
        processData: false,
        contentType: false,
        success: function() {
            Swal.fire({
                icon: 'success',
                text: 'Documento caricato con successo!',
                showConfirmButton: false
            })
            const url = root + 'utente?utente=infos&id=' + user

            setTimeout(() => window.location.assign(url), 2000)
        }
    })
})
if(subButtons){
    subButtons.forEach(el => {
        el.addEventListener('click', function (e) {
            const button = Number(e.currentTarget.value)
            const dataRec = Number(el.getAttribute('data-recorded'));
            subButtons.forEach(elm => {
                elm.classList.remove('btn-success', 'btn-danger', 'btn-warning', 'active');
                elm.classList.add('btn-color-muted', 'bg-surface')
            })

            if (button === 1) {
                el.classList.remove('btn-color-muted', 'bg-surface');
                el.classList.add('btn-success', 'active');
                if (dataRec !== 1) {
                    saveGenerate.removeAttribute('disabled');
                    saveGenerate.classList.remove('btn-bg-surface', 'text-gray-600');
                    saveGenerate.classList.add('btn-primary')
                    saveGenerate.textContent = 'Salva e genera licenza';
                } else {
                    saveGenerate.setAttribute('disabled', 'disabled');
                    saveGenerate.classList.add('btn-bg-surface', 'text-gray-600');
                    saveGenerate.classList.remove('btn-primary')
                    saveGenerate.textContent = 'Salva';
                }
            } else if (button === 0) {
                el.classList.remove('btn-color-muted', 'bg-surface');
                el.classList.add('btn-danger', 'active');
                saveGenerate.textContent = 'Salva';
                if (dataRec !== 0) {
                    saveGenerate.removeAttribute('disabled');
                    saveGenerate.classList.remove('btn-bg-surface', 'text-gray-600');
                    saveGenerate.classList.add('btn-primary')
                } else {
                    saveGenerate.setAttribute('disabled', 'disabled');
                    saveGenerate.classList.add('btn-bg-surface', 'text-gray-600');
                    saveGenerate.classList.remove('btn-primary')
                }
            } else {
                el.classList.remove('btn-color-muted', 'bg-surface');
                el.classList.add('btn-warning', 'active');
                saveGenerate.textContent = 'Salva';
                if (dataRec !== 2) {
                    saveGenerate.removeAttribute('disabled');
                    saveGenerate.classList.remove('btn-bg-surface', 'text-gray-600');
                    saveGenerate.classList.add('btn-primary')
                } else {
                    saveGenerate.setAttribute('disabled', 'disabled');
                    saveGenerate.classList.add('btn-bg-surface', 'text-gray-600');
                    saveGenerate.classList.remove('btn-primary')
                }
            }
        })
    })
}
if(upButtons){
    upButtons.forEach(el => {
        el.addEventListener('click', function (e) {
            const button = Number(e.currentTarget.value)
            const dataRec = Number(el.getAttribute('data-recorded'));
            upButtons.forEach(elm => {
                elm.classList.remove('btn-success', 'btn-danger', 'btn-warning', 'active');
                elm.classList.add('btn-color-muted', 'bg-surface')
            })

            if (button === 1) {
                el.classList.remove('btn-color-muted', 'bg-surface');
                el.classList.add('btn-success', 'active');
                if (dataRec !== 1) {
                    saveUpGenerate.removeAttribute('disabled');
                    saveUpGenerate.classList.remove('btn-bg-surface', 'text-gray-600');
                    saveUpGenerate.classList.add('btn-primary')
                    saveUpGenerate.textContent = 'Salva e genera licenza';
                } else {
                    saveUpGenerate.setAttribute('disabled', 'disabled');
                    saveUpGenerate.classList.add('btn-bg-surface', 'text-gray-600');
                    saveUpGenerate.classList.remove('btn-primary')
                    saveUpGenerate.textContent = 'Salva';
                }
            } else if (button === 0) {
                el.classList.remove('btn-color-muted', 'bg-surface');
                el.classList.add('btn-danger', 'active');
                saveUpGenerate.textContent = 'Salva';
                if (dataRec !== 0) {
                    saveUpGenerate.removeAttribute('disabled');
                    saveUpGenerate.classList.remove('btn-bg-surface', 'text-gray-600');
                    saveUpGenerate.classList.add('btn-primary')
                } else {
                    saveUpGenerate.setAttribute('disabled', 'disabled');
                    saveUpGenerate.classList.add('btn-bg-surface', 'text-gray-600');
                    saveUpGenerate.classList.remove('btn-primary')
                }
            } else {
                el.classList.remove('btn-color-muted', 'bg-surface');
                el.classList.add('btn-warning', 'active');
                saveUpGenerate.textContent = 'Salva';
                if (dataRec !== 2) {
                    saveUpGenerate.removeAttribute('disabled');
                    saveUpGenerate.classList.remove('btn-bg-surface', 'text-gray-600');
                    saveUpGenerate.classList.add('btn-primary')
                } else {
                    saveUpGenerate.setAttribute('disabled', 'disabled');
                    saveUpGenerate.classList.add('btn-bg-surface', 'text-gray-600');
                    saveUpGenerate.classList.remove('btn-primary')
                }
            }
        })
    })
}
payForm.addEventListener('submit', function(e){
    e.preventDefault();
    e.submitter.disabled = true;
    $('#user-payments-body input:checked').each(function() {
        const ids = $(this).attr('name').split('-');
        const value = $(this).val();
        selectedPaymentOpts.push({'id_user': ids[1], 'id_type': ids[2], 'id_event': ids[3], 'payValue': value});
    })
    console.log(selectedPaymentOpts)
    $.ajax({
        type: 'POST',
        data: {
            'payOpts': selectedPaymentOpts,
            'action': 'updatePayments',
            'user': user
        },
        url: root + 'app/controllers/UserController.php',
        success: function() {
            Swal.fire({
                icon: 'success',
                text: 'Il contributo è stato registrato con successo!',
                showConfirmButton: false
            })

            const url = root + 'utente?utente=infos&id=' + user

            setTimeout(() => window.location.assign(url), 2000)
        }
    })
})

if(subsForm){
    subsForm.addEventListener('submit', function (e) {
        e.preventDefault();
        e.submitter.disabled = true;
        const activeSubOpt = document.querySelector('.active');
        const value = activeSubOpt.getAttribute('value');
        const idTex = $('#idTesseramento-' + thisYear).val();
        const year = thisYear.toString();

        $.ajax({
            type: 'POST',
            data: {
                'action': 'updateSub',
                'idTesseramento': idTex,
                'user': user,
                'value': value,
                'year': year
            },
            url: root + 'app/controllers/UserController.php',
            success: function() {
                Swal.fire({
                    icon: 'success',
                    text: 'Il tesseramento è stato registrato con successo!',
                    showConfirmButton: false
                })

                const url = root + 'utente?utente=infos&id=' + user

                setTimeout(() => window.location.assign(url), 2000)
            }
        })
    })
}
if(upForm){
    upForm.addEventListener('submit', function (e) {
        e.preventDefault();
        e.submitter.disabled = true;
        const activeSubOpt = document.querySelector('.active');
        const value = activeSubOpt.getAttribute('value');
        const idTex = $('#idTesseramento-' + nextYear).val();
        const year = nextYear.toString();

        $.ajax({
            type: 'POST',
            data: {
                'action': 'updateSub',
                'idTesseramento': idTex,
                'user': user,
                'value': value,
                'year': year
            },
            url: root + 'app/controllers/UserController.php',
            success: function() {
                Swal.fire({
                    icon: 'success',
                    text: 'Il tesseramento è stato registrato con successo!',
                    showConfirmButton: false
                })

                const url = root + 'utente?utente=infos&id=' + user

                setTimeout(() => window.location.assign(url), 2000)
            }
        })
    })
}

input.addEventListener('change', function() {
    readURLthis(this);
})
function readURLthis(input) {
    const uploadButton = document.getElementById('file-upload-button-' + thisYear);
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            $('#file-' + thisYear).attr('src', root + 'app/assets/images/pdf.png');
            $('#fileName-' + thisYear).text(input.files[0].name);
            uploadButton.classList.remove('d-none')
        }

        reader.readAsDataURL(input.files[0]);
    }
}

onput.addEventListener('change', function() {
    readURL(this);
})
function readURL(input) {
    const uploadButton = document.getElementById('file-upload-button-' + nextYear);
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            $('#file-' + nextYear).attr('src', root + 'app/assets/images/pdf.png');
            $('#fileName-' + nextYear).text(input.files[0].name);
            uploadButton.classList.remove('d-none')
        }

        reader.readAsDataURL(input.files[0]);
    }
}