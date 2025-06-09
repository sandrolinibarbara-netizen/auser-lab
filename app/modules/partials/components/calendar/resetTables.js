const resetNextLessons = document.getElementById('reset-next-lessons');
const resetEvents = document.getElementById('reset-events');
const warningLicenseButton = document.getElementById('close-warning')
resetNextLessons.addEventListener('click', function() {
    $('#courses-names').val("");
    $('#lessons-dates').val("");
    $('#lessons-hours').val("");
    $('#lessons-location').val("");

    reloadTable("#kt_datatable_lessons_tab");
})

resetEvents.addEventListener('click', function() {
    $('#events-dates').val("");
    $('#events-hours').val("");
    $('#events-location').val("");

    reloadTable("#kt_datatable_events_tab");
})

if(warningLicenseButton) {
    const warningLicense = document.getElementById('warning-license')
    warningLicenseButton.addEventListener('click', function() {
        warningLicense.classList.add('d-none');
        warningLicense.setAttribute('disabled', 'disabled');
    })
}
