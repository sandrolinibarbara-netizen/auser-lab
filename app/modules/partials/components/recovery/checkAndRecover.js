const root = document.getElementById('root').getAttribute('value');
const form = document.getElementById('recovery-form');
const emailAlert = document.getElementById('email-alert')

form.addEventListener("submit", function(e) {
    e.preventDefault();
    const email = $('#email-recovery').val();

    const validator = FormValidation.formValidation(
        form,
        {
            fields: {
                'email': {
                    validators: {
                        notEmpty: {
                            message: 'Inserisci un indirizzo email.',
                        },
                        emailAddress: {
                            message: 'L\'indirizzo email non è valido.',
                            requireGlobalDomain: true
                        },
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

    if (validator) {
        validator.validate().then(function (status) {
            if (status === 'Valid') {
                $.ajax({
                    type: 'POST',
                    url: root + 'app/controllers/GeneralGetterController.php',
                    data: {action: 'checkUserEmail', email: email, type: e.submitter.value},
                    success: function (data) {
                        emailAlert.classList.add('d-none')
                        const parsed = JSON.parse(data);
                        if (parsed.email === 'email-null') {
                            emailAlert.classList.remove('d-none')
                        } else {
                            Swal.fire({
                                icon: 'success',
                                text:'La mail di conferma è stata inviata.',
                                showConfirmButton: false
                            })

                            let url;

                            if(Number(e.submitter.value) === 2) {
                                url = root + 'conferma-registrazione'
                            } else if(Number(e.submitter.value) === 1) {
                                url = root + 'mail-confermata'
                            }

                            setTimeout(() => window.location.assign(url), 2000)
                        }
                    }
                })
            }
        })
    }
})