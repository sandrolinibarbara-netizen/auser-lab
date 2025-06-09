const resetForums = document.getElementById('reset-forums');

resetForums.addEventListener('click', function() {
    $('#all-forum-creation').val("");

    reloadTable("#kt_datatable_all_forums_tab");
})