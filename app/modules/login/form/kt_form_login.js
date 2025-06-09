var kt_form_login = function () {

    var _initGroup = function () {
        FormValidation.formValidation(
            document.getElementById('kt_sign_in_form'),
            {
                fields: {
                    kt_form_login_username: {
                        validators: {
                            notEmpty: {
                                message: $('#l_1').val()
                            }
                        }
                    },
                    kt_form_login_password: {
                        validators: {
                            notEmpty: {
                                message: $('#l_1').val()
                            }
                        }
                    },
                },

                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({rowSelector:".fv-row"})
                }
            }
        )
            .on('core.form.valid', function() {
                loginUser();
            });
    }

    return {
        init: function() {
            _initGroup();
        }
    };
}();

function loginUser() {

    $('#kt_sign_in_submit').attr("data-kt-indicator","on");

    const data = {
        'action': 'login',
        'username': $('#kt_form_login_username').val(),
        'password': $('#kt_form_login_password').val()
    };

    const root = $('#kt_root_login').val();

    $.ajax({
        type: "POST",
        url: root + "db-calls/login/function.php",
        data: data,
        success: function(response){

            const json = JSON.parse(response);
            setTimeout(function () {

                $('#kt_sign_in_submit').removeAttr("data-kt-indicator");

                if(json.return === true) {

                    Swal.fire({
                        icon: 'success',
                        title: $('#l_2').val(),
                        text: 'Login effettuato con successo. Benvenuto su Auser UniPop!',
                        showConfirmButton: false
                    })

                    const search = window.location.search;
                    const params = new URLSearchParams(search);
                    const poll = params.get("id");

                    let url;

                    if(poll) {
                        url = root + 'qr?live=fill-poll&id=' + poll;
                    } else {
                        url = root + 'dashboard';
                    }

                    setTimeout(() => window.location.assign(url), 2000)

                } else {

                    Swal.fire({
                        icon: 'error',
                        title: $('#l_3').val(),
                        showConfirmButton: false,
                        showCancelButton: true,
                        text: 'Le credenziali inserite non sono valide',
                        cancelButtonText: $('#l_4').val()
                    })
                }
            }, 500);
        }
    });
}

jQuery(document).ready(function() {
    kt_form_login.init();
});