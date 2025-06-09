const downloadButton = document.getElementById('download-reg');

if(downloadButton) {
    downloadButton.addEventListener('click', function() {
        const search = window.location.search;
        const params = new URLSearchParams(search);
        const course = params.get("id");
            $.ajax({
                type: 'POST',
                data: {
                    'idCourse': course,
                    'action': 'downloadRegister'
                },
                url: root + 'app/controllers/CourseController.php',
                success: function(data) {
                    const parsed = JSON.parse(data);
                    console.log(parsed)
                    $('#poll-name').empty();
                    $('#download-modal').modal('show');
                    const linkBox = document.getElementById('poll-name');
                    const link = document.createElement('a');
                    const fileName = parsed.split('/');
                    link.textContent = fileName[fileName.length - 1];
                    link.classList.add('d-inline-block', 'fs-3')
                    link.setAttribute('href', parsed);
                    link.setAttribute('download', 'download');
                    linkBox.append(link);
                }
            })
    })
}

