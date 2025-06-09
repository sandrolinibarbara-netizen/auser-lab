const resetRegisters = document.getElementById('reset-reg');

resetRegisters.addEventListener('click', function() {
    $('#all-reg-creation').val("");
    $('#all-reg-start').val("");
    $('#all-reg-end').val("");

    reloadTable("#kt_datatable_all_reg_tab");
})