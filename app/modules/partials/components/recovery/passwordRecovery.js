const root = document.getElementById('root').getAttribute('value');
const form = document.getElementById('recovery-password-form');
const search = window.location.search;
const params = new URLSearchParams(search);
const id = params.get("id");

form.addEventListener("submit", function(e) {
    e.preventDefault();
    const password = $('#password-recovery').val();

    const validator = FormValidation.formValidation(
        form,
        {
            fields: {
                'password': {
                    validators: {
                        notEmpty: {
                            message: 'Inserisci una password.',
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
                    url: root + 'app/controllers/UserController.php',
                    data: {action: 'changePassword', password: password, id: id},
                    success: function () {
                        Swal.fire({
                            icon: 'success',
                            text:'La mail per il recupero password è stata inviata.',
                            showConfirmButton: false
                        })

                        const url = root + 'conferma-password'

                        setTimeout(() => window.location.assign(url), 2000)
                    }
                })
            }
        })
    }
})