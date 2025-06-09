const root = document.getElementById('root').getAttribute('value');
const form = document.getElementById('new-user-form');
const input = document.getElementById('picInput');
const newUser = document.getElementById('new-user-signup');
const emailAlert = document.getElementById('email-alert');
const usernameAlert = document.getElementById('username-alert');
const dateAlert = document.getElementById('date-alert');
const ageMismatchAlert = document.getElementById('age-mismatch-alert');
const button = document.getElementById('submit-button');

input.addEventListener('change', function() {
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            $('#pic').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
})

document.addEventListener('input', function() {
    if(button.disabled === true) {
        button.disabled = false;
    }
})

$("#birth").daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    minYear: 1900,
    maxYear: parseInt(moment().format("YYYY"),12),
    locale: {
        format: "DD/MM/YYYY",
        applyLabel: "Applica",
        cancelLabel: "Indietro",
        daysOfWeek: [
            "Dom",
            "Lun",
            "Mar",
            "Mer",
            "Gio",
            "Ven",
            "Sab"
        ],
        monthNames: [
            "Gennaio",
            "Febbraio",
            "Marzo",
            "Aprile",
            "Maggio",
            "Giugno",
            "Luglio",
            "Agosto",
            "Settembre",
            "Ottobre",
            "Novembre",
            "Dicembre"
        ],
        "firstDay": 1
    }
});

form.addEventListener("submit", function(e) {
    e.preventDefault();
    dateAlert.classList.add('d-none');
    ageMismatchAlert.classList.add('d-none');
    emailAlert.classList.add('d-none');
    usernameAlert.classList.add('d-none');

    const surname = $('#cognome').val();
    const user = $('#nome').val();
    const birthdate = $('#birth').val();
    const address = $('#address').val();
    const email = $('#email').val();
    const phone = $('#phone').val();
    const username = $('#username').val();
    const password = $('#password').val();
    const role = $('input[name="role"]:checked').val();
    const underage = $('input[name="underage"]:checked').val();
    const job = Number($('#job').val());
    const cardNumber = $('#card').val();

    const date = new Date();
    let day = date.getDate();
    if(day < 10) {
        day = '0' + day.toString();
    }
    let month = date.getMonth() + 1;
    if(month < 10) {
        month = '0' + month.toString();
    }
    const bArr = birthdate.split('/');
    const birthday = Date.parse(bArr[2] + '/' + bArr[1] + '/' + bArr[0]);
    const today = Date.parse(`${date.getFullYear()}/${month}/${day}`);

    const ageCheck = new Date(birthday);
    ageCheck.setFullYear(ageCheck.getFullYear() + 18);

    const validator = FormValidation.formValidation(
        form,
        {
            fields: {
                'nome': {
                    validators: {
                        notEmpty: {
                            message: 'Il nome è obbligatorio.',
                        }
                    }
                },
                'cognome': {
                    validators: {
                        notEmpty: {
                            message: 'Il cognome è obbligatorio.',
                        }
                    }
                },
                'birth': {
                    validators: {
                        notEmpty: {
                            message: 'La data di nascita è obbligatoria.',
                        }
                    }
                },
                'underage': {
                    validators: {
                        notEmpty: {
                            message: 'La dichiarazione del compimento o meno della maggiore età è obbligatoria.',
                        }
                    }
                },
                'email': {
                    validators: {
                        notEmpty: {
                            message: 'L\'indirizzo email è obbligatorio.',
                        },
                        emailAddress: {
                            message: 'L\'indirizzo email non è valido.',
                            requireGlobalDomain: true
                        },
                    }
                },
                'username': {
                    validators: {
                        notEmpty: {
                            message: 'Lo username è obbligatorio.',
                        }
                    }
                },
                'password': {
                    validators: {
                        notEmpty: {
                            message: 'La password è obbligatoria.',
                        }
                    }
                },
            },

            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: '.fv-row',
                    eleInvalidClass: '',
                    eleValidClass: '',
                    defaultMessageContainer: false,
                }),
                message: new FormValidation.plugins.Message({
                    container: '#name-error-message',
                }),
            }
        }
    );

    if((ageCheck > date && underage === '0') || (ageCheck <= date && underage === '1')) {
        ageMismatchAlert.classList.remove('d-none');
        return;
    }


    if(birthday >= today) {
        dateAlert.classList.remove('d-none');
        return;
    }

    if (!newUser && (role === "" || !role)) {
        return;
    }

    const fd = new FormData();
    if ($('#picInput')[0].files[0] !== undefined) {
        fd.append('pic', $('#picInput')[0].files[0])
    }
    fd.append('surname', surname);
    fd.append('user', user);
    fd.append('date', birthdate);
    fd.append('address', address);
    fd.append('phone', phone);
    fd.append('email', email);
    fd.append('username', username);
    fd.append('password', password);
    fd.append('underage', underage);
    fd.append('job', job);
    fd.append('cardNumber', cardNumber);
    if (!newUser) fd.append('role', role);
    if (newUser) fd.append('confirmation', '1');
    fd.append('action', 'createUser')

    const postUrl = root + 'app/controllers/CreationController.php'

    if (validator) {
        validator.validate().then(function (status) {

            if (status === 'Valid') {

                e.submitter.disabled = true;

                $.ajax({
                    type: 'POST',
                    url: postUrl,
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        emailAlert.classList.add('d-none')
                        usernameAlert.classList.add('d-none')
                        const parsed = JSON.parse(data);
                        if (parsed.user === 'email-taken') {
                            emailAlert.classList.remove('d-none')
                        } else if (parsed.user === 'username-taken') {
                            usernameAlert.classList.remove('d-none')
                        } else {
                            Swal.fire({
                                icon: 'success',
                                text: 'Utente creato con successo!',
                                showConfirmButton: false
                            })
                            let url;
                            if (newUser) {
                                url = root + 'conferma-registrazione'
                            } else {
                                url = root + 'utenti';
                            }

                            setTimeout(() => window.location.assign(url), 2000)
                        }
                    }
                })
            }
        })
    }
});