const resetPolls = document.getElementById('reset-polls');
const resetLectures = document.getElementById('reset-lectures');
const resetSurveys = document.getElementById('reset-surveys');

resetPolls.addEventListener('click', function() {

    $('#polls-lesson-names').val("");
    $('#polls-course-names').val("");
    $('#polls-teachers').val("");

    reloadTable("#kt_datatable_polls_tab");
})

resetLectures.addEventListener('click', function() {

    $('#lecture-notes-teachers').val('');
    $('#lecture-notes-lesson-names').val('');
    $('#lecture-notes-course-names').val('');

    reloadTable("#kt_datatable_lecture_notes_tab");
})

resetSurveys.addEventListener('click', function() {

    $('#surveys-teachers').val('');
    $('#surveys-lesson-names').val('');
    $('#surveys-course-names').val('');

    reloadTable("#kt_datatable_surveys_tab");
})