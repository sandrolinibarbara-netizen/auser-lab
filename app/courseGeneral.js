let numLesson = 1;
const root = document.getElementById('root').getAttribute('value');

const element = document.querySelector("#kt_stepper_course");
const form = document.getElementById('kt_stepper_course_form');

const search = window.location.search;
const params = new URLSearchParams(search);

const stepper = new KTStepper(element);
const input = document.getElementById('picInput');
const saveButton = document.getElementById('save-button');
const publishButton = document.getElementById('publish-button')
const radioButtons = document.querySelectorAll('.radio-check');

radioButtons.forEach(el => {
    el.addEventListener('change', function() {
        document.getElementById('error-message').classList.add('d-none')
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

stepper.on("kt.stepper.next", function (stepper) {
    const step = stepper.getCurrentStepIndex();
    if(step === 1) {
        if($('input[name="argomento"]:checked').val()) {
            stepper.goNext();
        } else {
            document.getElementById('error-message').classList.remove('d-none')
        }
    }
});
stepper.on("kt.stepper.previous", function (stepper) {
    document.getElementById('publish-error-message').classList.add('d-none')
    stepper.goPrevious(); // go previous step
});

$("#data-inizio").daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    minYear: 2020,
    maxYear: parseInt(moment().format("YYYY"),12),
    locale: {
        format: "DD/MM/YYYY",
        applyLabel: "Applica",
        cancelLabel: "Indietro",
        daysOfWeek: [
            "Dom",
            "Lun",
            "Mar",
            "Mer",
            "Gio",
            "Ven",
            "Sab"
        ],
        monthNames: [
            "Gennaio",
            "Febbraio",
            "Marzo",
            "Aprile",
            "Maggio",
            "Giugno",
            "Luglio",
            "Agosto",
            "Settembre",
            "Ottobre",
            "Novembre",
            "Dicembre"
        ],
        "firstDay": 1
    }
});

$("#data-fine").daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    minYear: 2020,
    maxYear: parseInt(moment().format("YYYY"),12),
    locale: {
        format: "DD/MM/YYYY",
        applyLabel: "Applica",
        cancelLabel: "Indietro",
        daysOfWeek: [
            "Dom",
            "Lun",
            "Mar",
            "Mer",
            "Gio",
            "Ven",
            "Sab"
        ],
        monthNames: [
            "Gennaio",
            "Febbraio",
            "Marzo",
            "Aprile",
            "Maggio",
            "Giugno",
            "Luglio",
            "Agosto",
            "Settembre",
            "Ottobre",
            "Novembre",
            "Dicembre"
        ],
        "firstDay": 1
    }
});

function checkDate(date) {
    document.getElementById('another-date-message').classList.add('d-none');
    if(date === '01/01/3000') {
        return true;
    }
    const now = new Date();
    let day = now.getDate();
    if(day < 10) {
        day = '0' + day.toString();
    }
    let month = now.getMonth() + 1;
    if(month < 10) {
        month = '0' + month.toString();
    }
    const bArr = date.split('/');
    const lessonDate = Date.parse(bArr[2] + '/' + bArr[1] + '/' + bArr[0]);
    const today = Date.parse(`${now.getFullYear()}/${month}/${day}`);

    if(lessonDate < today) {
        document.getElementById('another-date-message').classList.remove('d-none');
        return false;
    }
    return true;
}