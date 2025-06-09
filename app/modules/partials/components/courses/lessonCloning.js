const anotherCourse = document.getElementById('another-course');
const sameCourse = document.getElementById('same-course');
const courseSelection = document.getElementById('course-selection');
const cloneModal = document.getElementById('clone-lesson-modal');
const selectBox = document.getElementById('select-box')

cloneModal.addEventListener('show.bs.modal', function (event) {

    const button = event.relatedTarget;
    const value = button.getAttribute('value');
    const course = button.getAttribute('value').split('-')[0];
    const lesson = button.getAttribute('value').split('-')[1];

    $('#course-selection').empty();

    $.ajax({
        type: 'POST',
        data: {
            'idCourse': course,
            'action': 'getOtherCourses'
        },
        url: root + 'app/controllers/CourseController.php',
        success: function(data) {
            const parsed = JSON.parse(data);
            console.log(parsed);
            sameCourse.setAttribute('value', value)
            parsed.data.forEach(el => {
                const option = document.createElement('option');
                option.setAttribute('value', el['id'] + '-' + lesson);
                option.textContent = el['nome'];
                courseSelection.append(option);
            })
        }
    })
})

cloneModal.addEventListener('submit', function(e) {
    e.preventDefault();
    e.submitter.disabled = true;
    const radio = $('input[name="course"]:checked').val();
    let value;

    if(!radio || radio === "") {
        e.submitter.disabled = false;
        return;
    }

    if(Number(radio) === 0) {
        value = $('#course-selection').val();
    } else {
        value = sameCourse.getAttribute('value')
    }

    const course = value.split('-')[0];
    const lesson = value.split('-')[1];

    $.ajax({
        type: 'POST',
        data: {
            'idCourse': course,
            'idLesson': lesson,
            'action': 'cloneLesson'
        },
        url: root + 'app/controllers/LessonController.php',
        success: function() {
            $('#clone-lesson-modal').modal('hide');
            Swal.fire({
                icon: 'success',
                text: 'Lezione duplicata con successo!',
                showConfirmButton: false
            })


            setTimeout(() => window.location.assign(root + 'corso?get=course&id=' + course), 2000)
        }
    })
})

anotherCourse.addEventListener('change', function() {
    selectBox.classList.remove('d-none');
})

sameCourse.addEventListener('change', function() {
    selectBox.classList.add('d-none');
})