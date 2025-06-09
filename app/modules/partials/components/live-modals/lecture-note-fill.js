const lectureModal = document.getElementById('lecture-note-modal');
lectureModal.addEventListener('show.bs.modal', function(e) {
    const button = e.relatedTarget;
    const idDispensa = button.getAttribute('data-bs-idDispensa');
    $.ajax({
        type: 'POST',
        url: root + 'app/controllers/LiveController.php',
        data: {'idDispensa': idDispensa, 'action': 'getLectureNoteLive'},
        success: function(data) {
            const parsed = JSON.parse(data);
            console.log(parsed);
            $('#modal-lecture-note-title').text(parsed.data[0]['nomeLectureNote']);
            $('#modal-lecture-note-description').text(parsed.data[0]['descrizioneLectureNote']);
            const body = document.getElementById('modal-lecture-note-body');
            $('#modal-poll-body').empty();
            parsed.data.forEach(el => {
                const div = document.createElement('div');
                const bg = el['ordine'] % 2 === 0 ? 'bg-gray-200' : 'bg-light-bg'
                div.classList.add('mb-8', bg, 'p-7', 'rounded');
                const sectionTitle = document.createElement('h4');
                sectionTitle.textContent = el['titoloSezione'];
                const sectionDescription = document.createElement('p');
                sectionDescription.textContent = el['descrizioneSezione'];
                sectionDescription.classList.add('px-4', 'py-2', 'fs-6');
                const separator = document.createElement('div');
                separator.classList.add('separator', 'my-4', 'border-auser');

                const downloadBox = document.createElement('div');
                downloadBox.classList.add('w-100', 'd-flex', 'flex-column', 'gap-4', 'align-items-center')

                const img = document.createElement('img');
                img.setAttribute('src', root + 'app/assets/images/pdf.png');
                img.setAttribute('alt', 'pdf placeholder');
                img.classList.add('h-100px', 'rounded');
                const download = document.createElement('a');
                download.setAttribute('download', parsed.data[0]['file']);
                download.setAttribute('href', root + 'app/assets/uploaded-files/lecture-notes-pdfs/' + parsed.data[0]['file']);
                download.textContent = 'Scarica ' + parsed.data[0]['file'];

                div.append(sectionTitle);
                div.append(separator);
                div.append(sectionDescription);
                downloadBox.append(img);
                downloadBox.append(download);
                div.append(downloadBox);
                body.append(div);
            })
        }
    })
})