const form = document.getElementById('new-category-form');
const input = document.getElementById('picInput');
const root = document.getElementById('root').getAttribute('value');

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
    const search = window.location.search;
    const params = new URLSearchParams(search);
    const categoryId = params.get("id");

    const category = $('#nome').val();
    const color = $('#colore').val();

    if(category === "" || !category || color === "" || !color) {
        if(category === "" || !category){
            document.getElementById('error-name-alert').classList.remove('d-none')
        }
        e.submitter.disabled = false;
        return;
    }

    const fd = new FormData(this);
    if($('#picInput')[0].files[0] !== undefined) {
        fd.append('pic', $('#picInput')[0].files[0])
    }
    fd.append('nome', category);
    fd.append('colore', color);
    if(e.submitter.value === "1") {
        fd.append('action', 'createCategory')
    }  else {
        fd.append('action', 'updateCategory');
        fd.append('idCategory', categoryId);
    }

    const postUrl = e.submitter.value === "1" ? root + 'app/controllers/CreationController.php' : root + 'app/controllers/CategoryController.php'

        $.ajax({
            type: 'POST',
            url: postUrl,
            data: fd,
            processData: false,
            contentType: false,
            success: function () {
                Swal.fire({
                    icon: 'success',
                    text: 'Categoria creata con successo!',
                    showConfirmButton: false
                })
                const url = root + 'categorie'

                setTimeout(() => window.location.assign(url), 2000)
            }
        })
});