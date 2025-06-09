const resetCourses = document.getElementById('reset-courses');
const resetEvents = document.getElementById('reset-events');

resetCourses.addEventListener('click', function() {
    $('#courses-creation').val("");
    $('#courses-start').val("");
    $('#courses-end').val("");
    $('#courses-teachers').val("");

    reloadTable("#kt_datatable_courses_tab");
})

resetEvents.addEventListener('click', function() {
    $('#my-events-dates').val("");
    $('#my-events-hours').val("");
    $('#my-events-location').val("");

    reloadTable("#kt_datatable_my_events_tab");
})