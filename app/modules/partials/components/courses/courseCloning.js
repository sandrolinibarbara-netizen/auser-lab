const cloneCourseModal = document.getElementById('clone-course-modal');
let idCourse;
cloneCourseModal.addEventListener('show.bs.modal', function (event) {

    const button = event.relatedTarget;
    idCourse = button.getAttribute('value');
})
cloneCourseModal.addEventListener('submit', function(e) {
    e.preventDefault();
    const cloneType = $('input[name="classe"]:checked').val();

    if(!cloneType || cloneType === "") {
        return;
    }

    $.ajax({
        type: 'POST',
        url: root + 'app/controllers/CourseController.php',
        data: {action: 'duplicateCourse', cloneType: Number(cloneType), idCourse: idCourse},
        success: function() {
            $('#clone-course-modal').modal('hide');
            Swal.fire({
                icon: 'success',
                text: 'Corso duplicato con successo!',
                showConfirmButton: false
            })

            setTimeout(() => window.location.assign(root + 'corsi-eventi'), 2000)
        }
    })
})