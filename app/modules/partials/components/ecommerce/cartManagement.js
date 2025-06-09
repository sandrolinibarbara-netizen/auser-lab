const root = document.getElementById('root').getAttribute('value');
const removeButtons = document.querySelectorAll('.remove-button');

removeButtons.forEach(button => {
    button.addEventListener('click', function(e) {
        const idCourse = e.target.closest('button').value

        $.ajax({
            type: 'POST',
            data: {action: 'removeFromCart', id: idCourse},
            url: root + 'app/controllers/EcommerceController.php',
            success: function(data) {
                const parsed = JSON.parse(data);

                Swal.fire({
                    icon: 'success',
                    text: 'L\' elemento è stato rimosso dal carrello.',
                    showConfirmButton: false
                })
                const url = root + 'checkout?cart=' + parsed.user

                setTimeout(() => window.location.assign(url), 2000)
            }
        })
    })
})