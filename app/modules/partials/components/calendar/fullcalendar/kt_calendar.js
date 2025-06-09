"use strict";

// Class definition
var KTAppCalendar = function () {
    // Shared variables
    // Calendar variables
    var calendar;
    var data = {
        id: '',
        eventName: '',
        eventDescription: '',
        eventLocation: '',
        eventLiveStream: '',
        eventHalt: '',
        eventCourse: '',
        eventVideo: '',
        eventZoom: '',
        startDate: '',
        endDate: '',
        allDay: false
    };

    // View event variables
    var viewEventName;
    var viewAllDay;
    var viewEventDescription;
    var viewEventLocation;
    var viewStartDate;
    var viewEndDate;
    var viewModal;


    // Private functions
    var initCalendarApp = function () {
        // Define variables
        var initialLocaleCode = 'it';
        var calendarEl = document.getElementById('kt_calendar_app');
        var todayDate = moment().startOf('day');
        var TODAY = todayDate.format('YYYY-MM-DD');

        // Init calendar --- more info: https://fullcalendar.io/docs/initialize-globals
        calendar = new FullCalendar.Calendar(calendarEl, {
            locale: initialLocaleCode,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            initialDate: TODAY,
            navLinks: true, // can click day/week names to navigate views
            selectable: true,
            selectMirror: true,

            // Click event --- more info: https://fullcalendar.io/docs/eventClick
            eventClick: function (arg) {
                formatArgs({
                    id: arg.event.id,
                    title: arg.event.title,
                    description: arg.event.extendedProps.description,
                    location: arg.event.extendedProps.location,
                    liveStream: arg.event.extendedProps.liveStream,
                    idCourse: arg.event.extendedProps.idCourse,
                    course: arg.event.extendedProps.course,
                    halt: arg.event.extendedProps.halt,
                    video: arg.event.extendedProps.video,
                    zoom: arg.event.extendedProps.zoom,
                    startStr: arg.event.startStr,
                    endStr: arg.event.endStr,
                    allDay: arg.event.allDay
                })
                handleViewEvent();
            },

            dayMaxEvents: true, // allow "more" link when too many events
            events: function(info, successCallback, failureCallback) {
                $.LoadingOverlay("show");
                $.ajax({
                    type: "POST",
                    url: root + 'app/controllers/FutureEventsController.php',
                    data: {
                        start: info.start.valueOf(),
                        end: info.end.valueOf(),
                        type: $('input[name="calendar-events-types"]:checked').val(),
                        action: 'getCalendar'
                    },
                    success: function(data) {
                        var events = [];
                        if (data != null) {
                            $.each(JSON.parse(data), function(i, el) {
                                events.push({
                                    title: el.course ?? el.title,
                                    start: el.start,
                                    end: el.end,
                                    location: el.location,
                                    description: el.description,
                                    id: el.id,
                                    idCourse: el.idCourse,
                                    halt: el.halt,
                                    liveStream: el.liveStream,
                                    course: el.course ? el.title : undefined,
                                    video: el.video,
                                    zoom: el.zoom
                                })
                            })
                        }
                        console.log('events', events);
                        successCallback(events);
                    },
                    error: function () {
                        failureCallback();
                    },
                    complete: function () {
                        $.LoadingOverlay("hide");
                    }
                });
            },
            eventTimeFormat: {
                hour: "2-digit",
                minute: "2-digit",
                hour12: false
            },
        });

        calendar.render();

        $('input[name="calendar-events-types"]:radio').on('change', function() {
            console.log($('input[name="calendar-events-types"]:checked').val())
            calendar.refetchEvents();
        })
    }

    // Handle view event
    const handleViewEvent = () => {
        viewModal.show();

        // Detect all day event
        var eventNameMod;
        var startDateMod;
        var endDateMod;

        // Generate labels
        if (data.allDay) {
            eventNameMod = 'All Day';
            startDateMod = moment(data.startDate).format('D/MM/YYYY');
            endDateMod = moment(data.endDate).format('D/MM/YYYY');
        } else {
            eventNameMod = '';
            startDateMod = moment(data.startDate).format('D/MM/YYYY - H:mm');
            endDateMod = moment(data.endDate).format('D/MM/YYYY - H:mm');
        }

        // Populate view data
        viewEventName.innerText = data.eventName;
        viewAllDay.innerText = eventNameMod;
        viewEventDescription.innerText = data.eventDescription ? data.eventDescription : '--';
        viewEventLocation.innerText = data.eventLocation ? data.eventLocation : '--';
        viewStartDate.innerText = startDateMod;
        viewEndDate.innerText = endDateMod;

        const matches = document.getElementById('kt_modal_view_event').querySelectorAll("div.fs-6 > span.fw-bold");
        matches[0].innerText = 'Inizio:';
        matches[1].innerText = 'Fine:';
        if(data.eventCourse) {
            const courseSpan = document.createElement('span');
            const newLine = document.createElement('br');
            courseSpan.innerText = data.eventCourse;
            courseSpan.classList.add('fs-5')
            document.getElementById('kt_modal_view_event').querySelector('[data-kt-calendar="event_name"]').append(newLine)
            document.getElementById('kt_modal_view_event').querySelector('[data-kt-calendar="event_name"]').append(courseSpan)
        }
        document.getElementsByClassName('fc-popover').forEach(el => {
            el.classList.add('d-none')
        });
        const modal = document.querySelector('#kt_modal_view_event > div.modal-dialog > div.modal-content');
        const modalBody = document.querySelector('#kt_modal_view_event div.modal-body');

        // Remove edit/delete buttons
        if(document.getElementById('kt_modal_view_event_edit')) {
            document.getElementById('kt_modal_view_event_edit').remove();
        }

        if(document.getElementById('kt_modal_view_event_delete')) {
            document.getElementById('kt_modal_view_event_delete').remove()
        }

        if(document.getElementById('calendar-cta')) {
            modal.removeChild(modal.lastChild);
            modalBody.classList.add('pb-20');
        }

        if(data.eventHalt !== undefined || data.eventVideo !== null || data.eventLiveStream !== null || data.eventZoom !== null) {
            modalBody.classList.remove('pb-20');
            modalBody.classList.add('pb-8');
            modalBody.classList.add('pb-8');
            const div = document.createElement('div');
            div.classList.add('modal-footer', 'w-100', 'text-end');
            const button = document.createElement('a');
            button.classList.add('btn', 'btn-primary');
            button.setAttribute('id', 'calendar-cta')
            if(data.eventHalt !== undefined) {
                button.setAttribute('href', data.eventHalt)
                button.textContent = 'Vai allo shop';
            } else {
                if(data.eventCourse) {
                    button.setAttribute('href', root + 'watch?live=stream&id=' + data.id)
                } else {
                    button.setAttribute('href', root + 'watch?live=event&id=' + data.id)

                }
                button.textContent = 'Guarda la diretta';
            }
            div.append(button);
            modal.append(div);
        }
    }

    // Format FullCalendar responses
    const formatArgs = (res) => {
        data.id = res.id;
        data.eventName = res.title;
        data.eventDescription = res.description;
        data.eventCourse = res.course;
        data.eventLocation = res.location;
        data.eventLiveStream = res.liveStream;
        data.eventHalt = res.halt;
        data.eventVideo = res.video;
        data.eventZoom = res.zoom;
        data.startDate = res.startStr;
        data.endDate = res.endStr;
        data.allDay = res.allDay;
    }

    return {
        // Public Functions
        init: function () {
            // Define variables

            // View event modal
            const viewElement = document.getElementById('kt_modal_view_event');
            viewModal = new bootstrap.Modal(viewElement);
            viewEventName = viewElement.querySelector('[data-kt-calendar="event_name"]');
            viewAllDay = viewElement.querySelector('[data-kt-calendar="all_day"]');
            viewEventDescription = viewElement.querySelector('[data-kt-calendar="event_description"]');
            viewEventLocation = viewElement.querySelector('[data-kt-calendar="event_location"]');
            viewStartDate = viewElement.querySelector('[data-kt-calendar="event_start_date"]');
            viewEndDate = viewElement.querySelector('[data-kt-calendar="event_end_date"]');

            initCalendarApp();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTAppCalendar.init();
});
